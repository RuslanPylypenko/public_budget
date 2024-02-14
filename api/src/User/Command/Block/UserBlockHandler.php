<?php

declare(strict_types=1);

namespace App\User\Command\Block;

use App\User\UserEntity;
use Doctrine\ORM\EntityManagerInterface;

readonly class UserBlockHandler
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function handle(UserBlockCommand $command): void
    {
        /** @var UserEntity $user */
        $user = $this->em->getRepository(UserEntity::class)->getById($command->userId);

        $user->moveToBanned($command->reason);

        $this->em->persist($user);
        $this->em->flush();
    }
}
