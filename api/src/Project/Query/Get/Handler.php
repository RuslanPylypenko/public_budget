<?php

declare(strict_types=1);

namespace App\Project\Query\Get;

use App\Common\CQRS\QueryHandler;
use App\Project\ProjectEntity;
use Doctrine\ORM\EntityManagerInterface;

readonly class Handler implements QueryHandler
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function __invoke(Query $query): ?ProjectEntity
    {
        $project = $this->em->getRepository(ProjectEntity::class)
            ->findOneBy([
                'session' => $query->sessionId,
                'number' => $query->number,
            ]);


        return $project;
    }
}
