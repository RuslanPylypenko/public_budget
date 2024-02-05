<?php

declare(strict_types=1);

namespace App\City\Api\Get;

use App\City\Api\DataBuilder;
use App\City\CityEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Handler extends AbstractController
{
    public function __construct(
        private readonly DataBuilder $dataBuilder,
    ) {
    }

    #[Route('/city/', methods: ['GET'])]
    public function process(CityEntity $city): Response
    {
        return $this->json([
            'city' => $this->dataBuilder->city($city)
        ]);
    }
}