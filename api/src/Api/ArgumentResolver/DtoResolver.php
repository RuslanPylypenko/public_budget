<?php

namespace App\Api\ArgumentResolver;

use App\Api\Exception\ApiException;
use App\Api\Exception\ValidationException;
use App\Api\InputInterface;
use App\Api\Validator\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;

class DtoResolver implements ValueResolverInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly Validator $validator,
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();
        if (
            !$argumentType
            || !is_subclass_of($argumentType, InputInterface::class, true)
        ) {
            return [];
        }


        $payload = array_merge($request->getPayload()->all());
        if (!empty($payload)) {
            $input = $this->serializer->deserialize(json_encode($payload), $argument->getType(), 'json');
        } elseif (!empty($request->getContent())) {
            $input = $this->serializer->deserialize($request->getContent(), $argument->getType(), 'json');
        } else {
            throw new ApiException('Request can`t be empty', 400);
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