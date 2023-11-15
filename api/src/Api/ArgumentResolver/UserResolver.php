<?php

namespace App\Api\ArgumentResolver;

use App\Http\Annotation\Authenticate;
use App\User\UserEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class UserResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getType() === UserEntity::class) {
            yield $request->attributes->get(Authenticate::USER);
        }
    }
}