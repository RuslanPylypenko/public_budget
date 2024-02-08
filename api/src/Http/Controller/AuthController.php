<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Api\Exception\ApiException;
use App\Common\CQRS\CommandBus;
use App\Common\CQRS\QueryBus;
use App\Http\Annotation\Authenticate;
use App\Http\Annotation\Authenticate\TokenManager;
use App\User\Command\Registration\Confirm\Command as SignUpConfirmCommand;
use App\User\Command\Registration\Request\Command as SignUpRequestCommand;
use App\User\Command\SignIn\Command as SignInCommand;
use App\User\PasswordHasher;
use App\User\Query\ExistsByEmail\Query as ExistsByEmailQuery;
use App\User\Query\ExistsByConfirmToken\Query as ExistsByConfirmTokenQuery;
use App\User\UserEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly QueryBus $queryBus,
        private readonly EntityManagerInterface $em,
        private readonly PasswordHasher $passwordHasher,
        private readonly TokenManager $tokenManager,
    ) {
    }

    #[Route('/user/registration/request/', methods: ['POST'])]
    public function signUpRequest(SignUpRequestCommand $command): Response
    {
        if ($this->existsByEmail($command->email)) {
            throw new ApiException(
                sprintf('User with email: %s is already exists', $command->email),
                Response::HTTP_BAD_REQUEST
            );
        }

        $this->commandBus->dispatch($command);

        return $this->json([
            'message' => 'Check your email'
        ]);
    }

    #[Route('/user/registration/confirm/{token}', methods: ['GET'])]
    public function signUpConfirm(string $token): Response
    {
        if (!$this->existsByConfirmToken($token)) {
            throw new ApiException('Entity not found', Response::HTTP_NOT_FOUND);
        }

        /** @var UserEntity $user */
        if (null === $user = $this->em->getRepository(UserEntity::class)->findByConfirmToken($token)) {
            throw new ApiException('Invalid email or password.', Response::HTTP_BAD_REQUEST);
        }

        $this->commandBus->dispatch(new SignUpConfirmCommand($token));

        return $this->json([
            'access_token' => $this->tokenManager->build($user)->toString(),
        ]);
    }

    #[Route('/user/login/', methods: ['POST'])]
    public function login(SignInCommand $command): Response
    {
        /** @var UserEntity $user */
        if (null === $user = $this->em->getRepository(UserEntity::class)->findByEmail($command->email)) {
            throw new ApiException('Invalid email or password.');
        }

        if (!$this->passwordHasher->validate($command->password, $user->getPassword())) {
            throw new ApiException('Invalid email or password.');
        }

        return $this->json([
            'access_token' => $this->tokenManager->build($user)->toString(),
        ]);
    }

    #[Route('/user/', methods: ['GET']), Authenticate]
    public function loadUser(UserEntity $user): Response
    {
        return $this->json([
            'user' => $user->toArray()
        ]);
    }

    private function existsByEmail(string $email): bool
    {
        return $this->queryBus->handle(new ExistsByEmailQuery($email));
    }

    private function existsByConfirmToken(string $token): bool
    {
        return $this->queryBus->handle(new ExistsByConfirmTokenQuery($token));
    }
}
