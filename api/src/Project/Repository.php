<?php

namespace App\Project;

use App\Session\SessionEntity;
use Doctrine\ORM\EntityRepository;
use App\Project\ProjectEntity as Project;
use DomainException;
use InvalidArgumentException;

/**
 * @template-extends EntityRepository<Project>
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
            ->setParameter('statuses', [ProjectStatus::DELETED->value]);

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
                ProjectStatus::REJECTED->value,
                ProjectStatus::REJECTED_FINAL->value,
                ProjectStatus::DELETED->value
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
                ProjectStatus::WINNER,
                ProjectStatus::FINISHED,
                ProjectStatus::IMPLEMENTATION_FAILED,
                ProjectStatus::IMPLEMENTATION
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
                ProjectStatus::IMPLEMENTATION,
            ]);

        return $qb->getQuery()->getResult();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function get(SessionEntity $session, int $projectNumber): Project
    {
        if (!$project = $this->findOneBy([

        ])) {

        }
        return $project;
    }

    public function getByProjectNumber(SessionEntity $session, int $projectNumber): Project
    {
        $project = $this->findOneBy([
            'session' => $session,
            'number' => $projectNumber,
        ]);

        if (!$project) {
            throw new InvalidArgumentException('Project not found');
        }

        return $project;
    }
}