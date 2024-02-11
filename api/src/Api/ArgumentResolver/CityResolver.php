<?php

namespace App\Api\ArgumentResolver;

use App\City\CityEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class CityResolver implements ValueResolverInterface
{
    public function __construct(
       private EntityManagerInterface $em,
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getType() === CityEntity::class) {
            $scheme     = $request->getScheme();
            $httpOrigin = $request->headers->get('X-HOST') ?? $request->server->get('HTTP_ORIGIN') ?? sprintf('%s://%s', $scheme, $request->server->get('HTTP_HOST'));
            $subdomain  = null;

            if (preg_match("#$scheme://([a-z]+)\.#", $httpOrigin, $matches)) {
                $subdomain = $matches[1];
            }
            if ($subdomain && $city = $this->em->getRepository(CityEntity::class)->findOneBy(['techName' => $subdomain])){
                return [$city];
            }

            throw new NotFoundHttpException('City not Found');
        }
        return [null];
    }
}