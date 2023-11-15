<?php

namespace App\Api\Find;

use Doctrine\ORM\QueryBuilder;

interface DecoratorInterface
{
    public function apply(QueryBuilder $queryBuilder): void;
}