<?php

namespace App\Project\Api\Find;

use App\City\CityEntity as City;
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

    public function fromInput(City $city, Command $command): Repository
    {
        $decorators = [];

        if($search = $command->search){
            $decorators[] = new Search(trim($search));
        }

        $qb = $this->em->getRepository(ProjectEntity::class)->createQueryBuilder('p');

        $qb->join('p.session', 's')
            ->where($qb->expr()->eq('s.city', ':city'))
            ->setParameter('city', $city);

        return (new Repository($qb))
            ->setPagination($command->result['offset'], $command->result['limit'])
            ->addDecorators($decorators);
    }
}