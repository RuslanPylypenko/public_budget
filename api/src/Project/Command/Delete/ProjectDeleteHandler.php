<?php

declare(strict_types=1);

namespace App\Project\Command\Delete;

use App\Common\CQRS\CommandHandler;
use App\Project\ProjectEntity;
use Doctrine\ORM\EntityManagerInterface;

readonly class ProjectDeleteHandler implements CommandHandler
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function __invoke(ProjectDeleteCommand $command): void
    {
        /** @var ProjectEntity $project */
        $project = $this->em
            ->getRepository(ProjectEntity::class)
            ->getByProjectNumber($command->session, $command->projectNumber);

        $project->delete();

        $this->em->persist($project);
        $this->em->flush();
    }
}
