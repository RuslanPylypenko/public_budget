<?php

declare(strict_types=1);

namespace App\Http\Controller\Admin;

use App\Admin\AdminEntity;
use App\Api\Exception\ApiException;
use App\Http\Annotation\Authenticate;
use App\Http\Annotation\Authenticate\TokenManager;
use App\User\Command\SignIn\Command as SignInCommand;
use App\User\PasswordHasher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly PasswordHasher $passwordHasher,
        private readonly TokenManager $tokenManager,
    ) {
    }

    #[Route('/admin/login/', methods: ['POST'])]
    public function login(SignInCommand $command): Response
    {
        /** @var AdminEntity $admin */
        if (null === $admin = $this->em->getRepository(AdminEntity::class)->findOneBy(['email' => $command->email])) {
            throw new ApiException('Invalid email or password.');
        }

        if (!$this->passwordHasher->validate($command->password, $admin->getPassword())) {
            throw new ApiException('Invalid email or password.');
        }

        return $this->json([
            'access_token' => $this->tokenManager->build($admin)->toString(),
        ]);
    }

    #[Route('/admin/', methods: ['GET']), Authenticate]
    public function loadUser(AdminEntity $Admin): Response
    {
        return $this->json([
            'admin' => $Admin->toArray()
        ]);
    }
}
