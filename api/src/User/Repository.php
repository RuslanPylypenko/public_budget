<?php

namespace App\User;

use App\Project\ProjectEntity as Project;
use App\Session\SessionEntity as Session;
use Doctrine\ORM\EntityRepository;

class Repository extends EntityRepository
{
    public function getById(int $id): UserEntity
    {
        if (null === $user = $this->find($id)) {
            throw new \DomainException('User not found');
        }

        return $user;
    }

    public function findByEmail(string $email): ?UserEntity
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function findByConfirmToken(string $token): ?UserEntity
    {
        return $this->findOneBy(['confirmToken.token' => $token]);
    }

    /**
     * @param Session $session
     * @return UserEntity[]
     */
    public function findVoters(Session $session): array
    {
        $qb = $this->createQueryBuilder('u');
        $qb
            ->join('u.votes', 'v')
            ->join('v.project', 'p')
            ->where($qb->expr()->eq('p.session', ':session'))
            ->andWhere($qb->expr()->notIn('p.status', ':statuses'))
            ->setParameter('session', $session)
            ->setParameter('statuses', [Project::STATUS_DELETED]);

        return $qb->getQuery()->getResult();
    }
}