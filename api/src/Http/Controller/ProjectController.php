<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\City\CityEntity;
use App\Common\CQRS\QueryBus;
use App\Project\Query\Find\Query as ProjectFindQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
    ) {
    }

    #[Route('/projects/find/', methods: ['POST'])]
    public function index(CityEntity $city, ProjectFindQuery $query): Response
    {
        $query->cityId = $city->getId();

        return $this->json($this->queryBus->handle($query));
    }

    public function create(): Response
    {
        return $this->json([]);
    }

    public function get(): Response
    {
        return $this->json([]);
    }

    public function update(): Response
    {
        return $this->json([]);
    }
}
