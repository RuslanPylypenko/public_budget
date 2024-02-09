<?php

declare(strict_types=1);

namespace App\City\Query\GetWithProjects;

use App\Common\CQRS\IQuery;

readonly class Query implements IQuery
{
    public function __construct(
        public int $cityId,
    ) {
    }
}
