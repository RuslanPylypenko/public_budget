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
        if (is_subclass_of($argument->getType(), InputInterface::class)) {
            if (empty($request->getContent())) {
                throw new ApiException('Request can`t be empty', 400);
            }

            $input  = $this->serializer->deserialize($request->getContent(), $argument->getType(), 'json');

            if ($errors = $this->validator->validate($input)) {
                throw new ValidationException($errors);
            }

            yield $input;
        }
    }
}