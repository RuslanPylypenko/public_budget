<?php

declare(strict_types=1);

namespace App\User\Api\Registration\Confirm;

use App\Api\Exception\ApiException;
use App\Http\Annotation\Authenticate\TokenManager;
use App\User\UserEntity;
use App\Utils\DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Handler extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly TokenManager $tokenManager,
    ) {
    }

    #[Route('/user/registration/confirm/{token}', methods: ['GET'])]
    public function process(Request $request, string $token): Response
    {
        /** @var null|UserEntity $user */
        if (null === $user = $this->em->getRepository(UserEntity::class)->findByConfirmToken($token)) {
            throw new ApiException('Entity not found');
        }

        $user->getConfirmToken()->validate($token, DateTime::current());

        $user->setConfirmToken(null);
        $user->setStatus($user::STATUS_ACTIVE);

        $this->em->persist($user);
        $this->em->flush();

        return $this->json([
            'access_token' => $this->tokenManager->build($user, $request)->toString()
        ]);
    }
}