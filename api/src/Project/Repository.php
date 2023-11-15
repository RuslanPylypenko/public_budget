<?php

namespace App\Project;

use App\Session\SessionEntity;
use Doctrine\ORM\EntityRepository;
use App\Project\ProjectEntity as Project;

/**
 * @template-extends EntityRepository<ProjectEntity>
 */
class Repository extends EntityRepository
{
    /**
     * @return Project[]
     */
    public function findActiveProjects(SessionEntity $session): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->where($qb->expr()->eq('p.session', ':session'))
            ->andWhere($qb->expr()->notIn('p.status', ':statuses'))
            ->setParameter('session', $session)
            ->setParameter('statuses', [Project::STATUS_DELETED]);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Project[]
     */
    public function findModeratedProjects(SessionEntity $session): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->where($qb->expr()->eq('p.session', ':session'))
            ->andWhere($qb->expr()->notIn('p.status', ':statuses'))
            ->setParameter('session', $session)
            ->setParameter('statuses', [
                Project::STATUS_MODERATION,
                Project::STATUS_REJECTED,
                Project::STATUS_REJECTED_FINAL,
                Project::STATUS_DELETED
            ]);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Project[]
     */
    public function findWonProjects(SessionEntity $session): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->where($qb->expr()->eq('p.session', ':session'))
            ->andWhere($qb->expr()->in('p.status', ':statuses'))
            ->setParameter('session', $session)
            ->setParameter('statuses', [
                Project::STATUS_WINNER,
                Project::STATUS_FINISHED,
                Project::STATUS_IMPLEMENTATION_FAILED,
                Project::STATUS_IMPLEMENTATION
            ]);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Project[]
     */
    public function findImplementationProjects(SessionEntity $session): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->where($qb->expr()->eq('p.session', ':session'))
            ->andWhere($qb->expr()->in('p.status', ':statuses'))
            ->setParameter('session', $session)
            ->setParameter('statuses', [
                Project::STATUS_IMPLEMENTATION,
            ]);

        return $qb->getQuery()->getResult();
    }
}