<?php

declare(strict_types=1);

namespace App\Project\Query\Find;

use App\Common\CQRS\QueryHandler;
use App\Project\Query\DataBuilder;

readonly class Handler implements QueryHandler
{
    public function __construct(
        private RepositoryFactory $repositoryFactory,
        private DataBuilder $dataBuilder,
    ) {
    }

    public function __invoke(Query $query): array
    {
        $repository = $this->repositoryFactory->fromInput($query);
        return [
            'list'  => $this->dataBuilder->projects($repository->result()),
            'total' => $repository->total()
        ];
    }
}
