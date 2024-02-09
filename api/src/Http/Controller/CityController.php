<?php

namespace App\Http\Controller;

use App\City\Api\DataBuilder;
use App\City\CityEntity;
use App\City\Query\GetWithProjects\Query as GetWithProjectsQuery;
use App\Common\CQRS\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends AbstractController
{
    public function __construct(
        private readonly DataBuilder $dataBuilder,
        private readonly QueryBus $queryBus,
    ) {
    }

    #[Route('/city/', methods: ['GET'])]
    public function process(CityEntity $city): Response
    {
        $query = new GetWithProjectsQuery(
            $city->getId()
        );
        return $this->json([
            'city' => $this->queryBus->handle($query)
        ]);
    }
}