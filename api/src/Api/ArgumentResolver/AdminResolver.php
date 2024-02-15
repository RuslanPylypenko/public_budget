<?php

namespace App\Api\ArgumentResolver;

use App\Admin\AdminEntity;
use App\Http\Annotation\Authenticate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class AdminResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getType() === AdminEntity::class) {
            yield $request->attributes->get(Authenticate::USER);
        }
    }
}