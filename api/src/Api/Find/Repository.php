<?php

namespace App\Api\Find;

use Doctrine\ORM\QueryBuilder;

class Repository
{
    private ?int $offset = null;
    private ?int $count  = null;

    /** @var array<string, string> */
    private array $sorts = [];

    // ----------------------------------------

    public function __construct(
        private readonly QueryBuilder $qb
    ) {
    }

    // ----------------------------------------

    public function setPagination(?int $offset, ?int $count): self
    {
        $this->offset = $offset;
        $this->count  = $count;

        return $this;
    }

    public function addSorts(array $sorts): self
    {
        $this->sorts += $sorts;

        return $this;
    }

    // ----------------------------------------

    public function result(): array
    {
        return $this->queryBuilder()->getQuery()->getResult();
    }

    public function total()
    {
        $query = clone $this->qb;

        $query->setFirstResult(null);
        $query->setMaxResults(null);
        $query->resetDQLPart('orderBy');

        return $query->select(sprintf('COUNT(%s)', $query->getRootAliases()[0]))
            ->getQuery()
            ->getSingleScalarResult();
    }

    // ----------------------------------------

    public function queryBuilder(): QueryBuilder
    {
        foreach ($this->sorts as $column => $direction) {
            $this->qb->addOrderBy($column, $direction);
        }

        $this->qb->setFirstResult($this->offset);
        $this->qb->setMaxResults($this->count);

        return $this->qb;
    }

    // ----------------------------------------

}