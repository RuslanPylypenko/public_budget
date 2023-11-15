<?php

namespace App\Api\Find;

use Doctrine\ORM\QueryBuilder;

class Repository
{
    private bool $decorated = false;

    private ?int $offset = null;
    private ?int $count  = null;

    /** @var array<string, string> */
    private array $sorts = [];

    /** @var array<DecoratorInterface>  */
    private array $decorators = [];

    // ----------------------------------------

    public function __construct(
        private readonly QueryBuilder $qb
    ) {
    }

    // ----------------------------------------

    /**
     * @param array<DecoratorInterface>|DecoratorInterface $decorator
     */
    public function addDecorators(array | DecoratorInterface $decorator): self
    {
        is_array($decorator) ? $this->decorators += $decorator : $this->decorators[] = $decorator;
        $this->decorated  = false;

        return $this;
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
        $this->decorate();
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
        $this->decorate();

        foreach ($this->sorts as $column => $direction) {
            $this->qb->addOrderBy($column, $direction);
        }

        $this->qb->setFirstResult($this->offset);
        $this->qb->setMaxResults($this->count);

        return $this->qb;
    }

    // ----------------------------------------

    private function decorate(): void
    {
        if ($this->decorated) {
            return;
        }

        foreach ($this->decorators as $decorator) {
            $decorator->apply($this->qb);
        }
        $this->decorated = true;
    }

}