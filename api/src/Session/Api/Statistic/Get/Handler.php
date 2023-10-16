<?php

declare(strict_types=1);

namespace App\Session\Api\Statistic\Get;

use App\City\CityEntity;
use App\Project\ProjectEntity;
use App\Session\SessionEntity;
use App\Session\StageEntity;
use App\User\UserEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Handler extends AbstractController
{
    private readonly EntityRepository $projects;
    private readonly EntityRepository $users;

    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
        $this->projects = $this->em->getRepository(ProjectEntity::class);
        $this->users = $this->em->getRepository(UserEntity::class);
    }

    #[Route(
        path: '/sessions/statistic/{stage}/',
        requirements: [
            'stage' => 'submission|review|voting|decision|implementation'
        ],
        methods: ['GET']
    )]
    public function handle(CityEntity $city, string $stage): Response
    {
        $session = $city->getCurrentSession();
        $statistic = match ($stage) {
            StageEntity::STAGE_SUBMISSION => $this->submission($session),
            StageEntity::STAGE_REVIEW => $this->review($session),
            StageEntity::STAGE_VOTING => $this->voting($session),
            StageEntity::STAGE_IMPLEMENTATION => $this->implementation($session),
        };

        return $this->json($statistic);
    }

    private function submission(SessionEntity $session): array
    {
        $projects = $this->projects->findActiveProjects($session);

        return [
            'projects_count' => count($projects),
            'budget_sum' => round(array_reduce($projects, static fn($carry, ProjectEntity $project) => $carry + $project->getBudget(), 0), 2),
        ];
    }

    private function review(SessionEntity $session): array
    {
        $projects = $this->projects->findModeratedProjects($session);

        return [
            'projects_count' => count($projects),
            'budget_sum' => round(array_reduce($projects, static fn($carry, ProjectEntity $project) => $carry + $project->getBudget(), 0), 2),
        ];
    }

    private function voting(SessionEntity $session): array
    {
        $users = $this->users->findVoters($session);

        return [
            'voters_total' => count($users),
        ];
    }

    private function decision(SessionEntity $session): array
    {
        $projects = $this->projects->findWonProjects($session);

        return [
            'projects_count' => count($projects),
        ];
    }

    private function implementation(SessionEntity $session): array
    {
        $projects = $this->projects->findImplementationProjects($session);

        return [
            'projects_count' => count($projects),
        ];
    }
}