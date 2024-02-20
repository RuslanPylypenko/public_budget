<?php

declare(strict_types=1);

namespace App\Project\Command\Approve;

use App\Common\CQRS\CommandHandler;
use App\Project\ProjectEntity;
use Doctrine\ORM\EntityManagerInterface;

readonly class ProjectApproveHandler implements CommandHandler
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function __invoke(ProjectApproveCommand $command): void
    {
        /** @var ProjectEntity $project */
        $project = $this->em
            ->getRepository(ProjectEntity::class)
            ->getByProjectNumber($command->session, $command->projectNumber);

        $project->approve();

        $this->em->persist($project);
        $this->em->flush();
    }
}
