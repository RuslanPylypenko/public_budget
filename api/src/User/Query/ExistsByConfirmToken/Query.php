<?php

declare(strict_types=1);

namespace App\User\Query\ExistsByConfirmToken;

use App\Common\CQRS\IQuery;

readonly class Query implements IQuery
{
    public function __construct(
        public string $token,
    ) {
    }
}
