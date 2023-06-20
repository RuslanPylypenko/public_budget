<?php

namespace App\User\Api\Login;

use App\Api\Exception\ApiException;
use App\Api\Exception\ValidationException;
use App\Api\Validator\Validator;
use App\Http\Annotation\Authenticate\TokenManager;
use App\User\PasswordHasher;
use App\User\UserEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class Handler extends AbstractController
{
    public function __construct(
        private readonly Validator $validator,
        private readonly EntityManagerInterface $em,
        private readonly PasswordHasher $passwordHasher,
        private readonly TokenManager $tokenManager,
    ) {
    }

    #[Route('/user/login/', methods: ['POST'])]
    public function process(Request $request): Response
    {
        $command = $this->deserialize($request);

        if ($errors = $this->validator->validate($command)) {
            throw new ValidationException($errors);
        }

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

    private function deserialize(Request $request): Command
    {
        $body    = $request->toArray();
        $command = new Command();

        $command->email = $body['email'] ?? '';
        $command->password = $body['password'] ?? '';

        return $command;
    }
}