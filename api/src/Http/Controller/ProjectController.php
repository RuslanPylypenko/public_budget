<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Api\Exception\ApiException;
use App\City\CityEntity;
use App\Common\CQRS\CommandBus;
use App\Common\CQRS\QueryBus;
use App\Http\Annotation\Authenticate;
use App\Project\Command\Create\CreateProjectCommand;
use App\Project\Command\Create\CreateProjectHandler;
use App\Project\Command\Update\{UpdateProjectCommand, UpdateProjectHandler};
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
        private readonly QueryBus             $queryBus,
        private readonly CommandBus           $commandBus,
        private readonly DataBuilder          $dataBuilder,
        private readonly UpdateProjectHandler $updateProjectHandler,
        private readonly CreateProjectHandler $createProjectHandler,
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
        $project = $this->createProjectHandler->handle($command);

        return $this->json([
            'data' => $this->dataBuilder->project($project, true),
        ], Response::HTTP_CREATED);
    }

    #[Route('/projects/{number}/update/', methods: ['POST']), Authenticate]
    public function update(UpdateProjectCommand $command, CityEntity $city, UserEntity $user, int $number): Response
    {
        $command->setCity($city);
        $command->setNumber($number);
        $command->setUser($user);

        $project = $this->updateProjectHandler->handle($command);

        return $this->json([
            'data' => $this->dataBuilder->project($project, true)
        ], Response::HTTP_OK);
    }
}
