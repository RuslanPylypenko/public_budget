<?php

declare(strict_types=1);

namespace App\Project\Query\Find;

use App\Api\Find\Repository;
use App\Project\ProjectEntity;
use App\Project\Query\Find\Filter\Search;
use Doctrine\ORM\EntityManagerInterface;

readonly class RepositoryFactory
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function fromInput(Query $query): Repository
    {
        $decorators = [];

        if($search = $query->search){
            $decorators[] = new Search(trim($search));
        }

        $qb = $this->em->getRepository(ProjectEntity::class)->createQueryBuilder('p');

        $qb->join('p.session', 's')
            ->where($qb->expr()->eq('s.city', ':city'))
            ->setParameter('city', $query->cityId);

        return (new Repository($qb))
            ->setPagination($query->result['offset'], $query->result['limit'])
            ->addDecorators($decorators);
    }
}