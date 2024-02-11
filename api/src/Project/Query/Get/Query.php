<?php

declare(strict_types=1);

namespace App\Project\Query\Get;

use App\Api\InputInterface;
use App\Common\CQRS\IQuery;
use Symfony\Component\Validator\Constraints as Assert;

readonly class Query implements IQuery
{
    public function __construct(
        public int $sessionId,
        public int $number,
    ) {
    }
}
