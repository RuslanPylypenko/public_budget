<?php

namespace App\Project\Console;

use App\Project\ProjectEntity as Project;
use App\Project\ProjectStatus;
use App\Session\SessionEntity;
use App\Session\StageEntity as Stage;
use App\Utils\DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name       : 'app:project:status-change',
    description: 'Updates project statuses according to session stage changes and other conditions'
)]
class UpdateProjectStatus extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $qb = $this->em->getRepository(SessionEntity::class)->createQueryBuilder('s');
        $qb
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->isNull('s.projectStatusUpdateLastRunDate'),
                    $qb->expr()->eq('s.projectStatusUpdateLastRunDate', ':border_date')
                )
            )
            ->setParameter('border_date', DateTime::fromString('-10 minutes'));

        /** @var SessionEntity $session */
        foreach ($qb->getQuery()->getResult() as $session) {
            switch ($session->getStage()?->getName()) {
                case Stage::STAGE_VOTING:
                    $this->voting($session);
                    break;
                case Stage::STAGE_DECISION:
                    $this->decision($session);
                    break;
                case Stage::STAGE_IMPLEMENTATION:
                    $this->implementation($session);
            }

            $session->setProjectStatusUpdateLastRunDate(DateTime::current());
            $this->em->persist($session);
        }

        $this->em->flush();

        return Command::SUCCESS;
    }

    private function voting(SessionEntity $session): void
    {
        $projects = $this->em->getRepository(Project::class)->findBy([
            'status' => [ProjectStatus::AUTHOR_EDIT, ProjectStatus::REVIEW, ProjectStatus::APPROVED],
            'session' => $session,
        ]);

        foreach ($projects as $project) {
            $project->isApprovedStatus()
                ? $project->setStatus(ProjectStatus::VOTING)
                : $project->setStatus(ProjectStatus::REJECTED_FINAL);
            $this->em->persist($project);
        }

        $this->em->flush();
    }

    private function decision(SessionEntity $session): void
    {
        $projects = $this->em->getRepository(Project::class)->findBy([
            'status' => [ProjectStatus::VOTING],
            'session' => $session,
        ]);

        foreach ($projects as $project) {
            $project->setStatus(ProjectStatus::AWAIT);
            $this->em->persist($project);
        }

        $this->em->flush();
    }

    private function implementation(SessionEntity $session): void
    {
        $projects = $this->em->getRepository(Project::class)->findBy([
            'status' => [ProjectStatus::AWAIT, ProjectStatus::WINNER],
            'session' => $session,
        ]);

        foreach ($projects as $project) {
            $project->isAwaitStatus()
                ? $project->setStatus(ProjectStatus::AWAIT)
                : $project->setStatus(ProjectStatus::IMPLEMENTATION);

            $this->em->persist($project);
        }

        $this->em->flush();
    }
}
