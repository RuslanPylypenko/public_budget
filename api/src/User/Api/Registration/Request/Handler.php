<?php

declare(strict_types=1);

namespace App\User\Api\Registration\Request;

use App\Api\Exception\ValidationException;
use App\Api\Validator\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Handler extends AbstractController
{
    public function __construct(
        private readonly Validator $validator
    ) {
    }

    #[Route('/user/registration/request/', methods: ['POST'])]
    public function process(Request $request): Response
    {
        $command = $this->deserialize($request);

        if ($errors = $this->validator->validate($command)) {
            throw new ValidationException($errors);
        }
        return $this->json([
            'access_token' => '123'
        ]);
    }

    private function deserialize(Request $request): Command
    {
        $body = $request->toArray();

        $command = new Command();

        $command->name = $body['name'] ?? '';
        $command->email = $body['email'] ?? '';
        $command->password = $body['password'] ?? '';
        $command->rePassword = $body['re_password'] ?? '';
        $command->birthday = $body['birthday'] ?? '';

        return $command;
    }
}