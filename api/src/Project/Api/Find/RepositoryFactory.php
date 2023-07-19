<?php

namespace App\Project\Api\Find;

use App\Project\ProjectEntity;
use App\Api\Find\Repository;
use Doctrine\ORM\EntityManagerInterface;

class RepositoryFactory
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function fromInput(Command $command): Repository
    {
        $qb = $this->em->getRepository(ProjectEntity::class)->createQueryBuilder('p');
        $repository = new Repository($qb);

        return $repository;
    }
}