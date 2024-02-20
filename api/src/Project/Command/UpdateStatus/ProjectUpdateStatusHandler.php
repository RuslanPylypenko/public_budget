<?php

declare(strict_types=1);

namespace App\Project\Command\UpdateStatus;

use App\Common\CQRS\CommandHandler;
use App\Project\Command\Reject\ProjectRejectCommand;
use App\Project\ProjectEntity;
use Doctrine\ORM\EntityManagerInterface;

readonly class ProjectUpdateStatusHandler implements CommandHandler
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function __invoke(ProjectUpdateStatusCommand $command): void
    {
        /** @var ProjectEntity $project */
        $project = $this->em
            ->getRepository(ProjectEntity::class)
            ->getByProjectNumber($command->session, $command->projectNumber);

        $project->setStatus($command->status);

        $this->em->persist($project);
        $this->em->flush();
    }
}
