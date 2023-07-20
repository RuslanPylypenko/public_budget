<?php

namespace App\Project\Api\Find\Filter;

use App\Api\Find\DecoratorInterface;
use Doctrine\ORM\QueryBuilder;

class Search implements DecoratorInterface
{
    public function __construct(
        private readonly string $search,
    ) {
    }

    public function apply(QueryBuilder $queryBuilder): void
    {
       $conditions = [
           $queryBuilder->expr()->like('p.name', ":search"),
           $queryBuilder->expr()->like('p.short', ":search"),
           $queryBuilder->expr()->like('p.description', ":search"),
       ];

       $queryBuilder->andWhere($queryBuilder->expr()->orX(...$conditions));
       $queryBuilder->setParameter('search', "%{$this->search}%");
    }
}