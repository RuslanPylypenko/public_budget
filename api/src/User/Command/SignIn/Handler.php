<?php

namespace App\User\Api\Login;

use App\Api\Exception\ApiException;
use App\Api\Exception\ValidationException;
use App\Api\Validator\Validator;
use App\Common\CQRS\CommandHandler;
use App\Http\Annotation\Authenticate\TokenManager;
use App\User\PasswordHasher;
use App\User\UserEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class Handler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly PasswordHasher $passwordHasher,
        private readonly TokenManager $tokenManager,
    ) {
    }

    #[Route('/user/login/', methods: ['POST'])]
    public function process(Request $request, Command $command): Response
    {
        /** @var UserEntity $user */
        if (null === $user = $this->em->getRepository(UserEntity::class)->findByEmail($command->email)) {
            throw new ApiException('Invalid email or password.');
        }

        if (!$this->passwordHasher->validate($command->password, $user->getPassword())) {
            throw new ApiException('Invalid email or password.');
        }

        return $this->json([
            'access_token' => $this->tokenManager->build($user, $request)->toString(),
        ]);
    }
}