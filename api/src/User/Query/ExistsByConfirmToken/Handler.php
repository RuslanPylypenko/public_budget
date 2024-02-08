<?php

declare(strict_types=1);

namespace App\User\Query\ExistsByConfirmToken;

use App\Common\CQRS\QueryHandler;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Result;

readonly class Handler implements QueryHandler
{
    public function __construct(
        private Connection $connection
    ) {
    }

    public function __invoke(Query $query): bool
    {
        /** @var Result $statement */
        $statement = $this->connection
            ->createQueryBuilder()
            ->select(['1'])
            ->from('user', 'u')
            ->where('u.confirm_token_token = :token')
            ->setParameter('token', $query->token)
            ->executeQuery();

        return !empty($statement->fetchAssociative());
    }
}
