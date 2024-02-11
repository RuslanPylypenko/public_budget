<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Api\Exception\ApiException;
use App\City\CityEntity;
use App\Common\CQRS\CommandBus;
use App\Common\CQRS\QueryBus;
use App\Http\Annotation\Authenticate;
use App\Project\Command\Create\Command as CreateProjectCommand;
use App\Project\Command\Update\Command;
use App\Project\Query\DataBuilder;
use App\Project\Query\Find\Query as ProjectFindQuery;
use App\Project\Query\Get\Query as ProjectGetQuery;
use App\User\UserEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly CommandBus $commandBus,
        private readonly DataBuilder $dataBuilder,
    ) {
    }

    #[Route('/projects/find/', methods: ['POST'])]
    public function index(CityEntity $city, ProjectFindQuery $query): Response
    {
        $query->cityId = $city->getId();
        return $this->json($this->queryBus->handle($query));
    }

    #[Route('/projects/{number}/', methods: ['GET'])]
    public function get(CityEntity $city, int $number): Response
    {
        $query = new ProjectGetQuery($city->getCurrentSession()->getId(), $number);
        if (null === $project = $this->queryBus->handle($query)) {
            throw new ApiException(sprintf('Project with number %s is not found', $number), Response::HTTP_NOT_FOUND);
        }

        return $this->json($this->dataBuilder->project($project, true));
    }

    #[Route('/projects/', methods: ['POST']), Authenticate]
    public function create(CreateProjectCommand $command, UserEntity $user, CityEntity $city): Response
    {
        $command->setUser($user);
        $command->setCity($city);
        $this->commandBus->dispatch($command);

        return $this->json([], Response::HTTP_CREATED);
    }

    #[Route('/projects/{number}', methods: ['PUT']), Authenticate]
    public function update(Command $command, CityEntity $city, int $number): Response
    {
        $command->setCity($city);
        $command->setNumber($number);
        $this->commandBus->dispatch($command);

        return $this->json([], Response::HTTP_OK);
    }
}
