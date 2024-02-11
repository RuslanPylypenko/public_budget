<?php

declare(strict_types=1);

namespace App\User\Command\SignUp\Confirm;

use App\Api\Exception\ApiException;
use App\Common\CQRS\CommandHandler;
use App\Http\Annotation\Authenticate\TokenManager;
use App\User\UserEntity;
use App\Utils\DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

readonly class Handler implements CommandHandler
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function __invoke(Command $command): void
    {
        /** @var null|UserEntity $user */
        if (null === $user = $this->em->getRepository(UserEntity::class)->findByConfirmToken($command->token)) {
            throw new ApiException('Entity not found');
        }

        $user->getConfirmToken()->validate($command->token, DateTime::current());

        $user->confirmSignUp();

        $this->em->persist($user);
        $this->em->flush();
    }
}