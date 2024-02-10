<?php

declare(strict_types=1);

namespace App\Project\Query\Find\Filter;

use App\Api\Find\DecoratorInterface;
use Doctrine\ORM\QueryBuilder;

readonly class Search implements DecoratorInterface
{
    public function __construct(
        private string $search,
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