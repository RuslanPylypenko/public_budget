<?php

namespace App\User\Api\Get;

use App\Http\Annotation\Authenticate;
use App\User\UserEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Handler extends AbstractController
{

    #[Route('/user/', methods: ['GET']), Authenticate]
    public function process(UserEntity $user): Response
    {
        return $this->json([
            'user' => $user->toArray()
        ]);
    }
}