<?php

declare(strict_types=1);

namespace App\Project\Command\Reject;

use App\Common\CQRS\CommandHandler;
use App\Project\ProjectEntity;
use Doctrine\ORM\EntityManagerInterface;

readonly class ProjectRejectHandler implements CommandHandler
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function __invoke(ProjectRejectCommand $command): void
    {
        /** @var ProjectEntity $project */
        $project = $this->em
            ->getRepository(ProjectEntity::class)
            ->getByProjectNumber($command->session, $command->projectNumber);

        $project->reject($command->reason);

        $this->em->persist($project);
        $this->em->flush();
    }
}
