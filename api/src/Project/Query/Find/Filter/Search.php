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
        if ($this->search) {
            $queryBuilder
                ->addSelect("MATCH_AGAINST (p.description, p.name, p.short, :search 'IN NATURAL MODE') as HIDDEN score")
                ->andWhere("MATCH_AGAINST (p.description, p.name, p.short, :search) > 0.0")
                ->orderBy('score', 'desc')
                ->setParameter('search', "$this->search");

        }
    }
}