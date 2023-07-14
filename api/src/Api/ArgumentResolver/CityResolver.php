<?php

namespace App\Api\ArgumentResolver;

use App\City\CityEntity;
use App\Http\Annotation\Authenticate;
use App\User\UserEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class CityResolver implements ValueResolverInterface
{
    public function __construct(
       private readonly EntityManagerInterface $em,
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getType() === CityEntity::class) {
            yield $this->em->getRepository(CityEntity::class)->findOneBy(['techName' => 'lviv']);
        }
    }
}