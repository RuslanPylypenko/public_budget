<?php

namespace App\City\Api\Get;

use App\City\CityEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Handler extends AbstractController
{
    #[Route('/city/', methods: ['GET'])]
    public function process(CityEntity $city): Response
    {
        return $this->json([
            'city' => $city->toArray(),
        ]);
    }
}