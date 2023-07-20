<?php

namespace App\Project\Api\Find;

use App\Project\Api\Find\Filter\Search;
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
        $decorators = [];

        if($search = $command->search){
            $decorators[] = new Search(trim($search));
        }

        $qb = $this->em->getRepository(ProjectEntity::class)->createQueryBuilder('p');
        $repository = (new Repository($qb))
            ->setPagination($command->result['offset'], $command->result['limit'])
            ->addDecorators($decorators);

        return $repository;
    }
}