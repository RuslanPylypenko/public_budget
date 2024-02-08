<?php

declare(strict_types=1);

namespace App\Api\ArgumentResolver;

use App\Api\Exception\ApiException;
use App\Api\Exception\ValidationException;
use App\Api\InputInterface;
use App\Api\Validator\Errors;
use App\Api\Validator\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

readonly class DtoResolver implements ValueResolverInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private Validator           $validator,
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();
        if (!$argumentType || !is_subclass_of($argumentType, InputInterface::class)) {
            return [];
        }

        $payload = array_merge($request->getPayload()->all());

        if (empty($payload)) {
            throw new ApiException('CreateRequest can`t be empty', 400);
        }

        try {
            $input = $this->serializer->deserialize(json_encode($payload), $argument->getType(), 'json', [
                DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS => true,
            ]);
        } catch (PartialDenormalizationException $e) {
            $violations = new ConstraintViolationList();
            /** @var NotNormalizableValueException $exception */
            foreach ($e->getErrors() as $exception) {
                $message = sprintf('The type must be one of "%s" ("%s" given).', implode(', ', $exception->getExpectedTypes()), $exception->getCurrentType());
                $parameters = [];
                if ($exception->canUseMessageForUser()) {
                    $parameters['hint'] = $exception->getMessage();
                }
                $violations->add(new ConstraintViolation($message, '', $parameters, null, $exception->getPath(), null));
            }

            throw new ValidationException(new Errors($violations));
        }

        foreach ($request->files->all() as $property => $files){
            if(property_exists($input, $property)){
                $input->{$property} = $request->files->get($property);
            }
        }

        if ($errors = $this->validator->validate($input)) {
            throw new ValidationException($errors);
        }

        yield $input;
    }
}