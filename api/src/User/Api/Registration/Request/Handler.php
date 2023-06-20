<?php

declare(strict_types=1);

namespace App\User\Api\Registration\Request;

use App\Api\Exception\AlreadyExistsApiException;
use App\Api\Exception\ValidationException;
use App\Api\Validator\Validator;
use App\User\ConfirmToken;
use App\User\Events\UserRegisteredEvent;
use App\User\UserEntity;
use App\Utils\DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

class Handler extends AbstractController
{
    public function __construct(
        private readonly Validator $validator,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface $em,
        private readonly EventDispatcherInterface $dispatcher,
    ) {
    }

    #[Route('/user/registration/request/', methods: ['POST'])]
    public function process(Request $request): Response
    {
        $command = $this->deserialize($request);

        if ($errors = $this->validator->validate($command)) {
            throw new ValidationException($errors);
        }

        if($this->em->getRepository(UserEntity::class)->findByEmail($command->email) !== null){
            throw new AlreadyExistsApiException();
        }

        $user = new UserEntity(
            $command->name,
            $command->surname,
            $command->patronymic,
            $command->email,
            DateTime::fromString($command->birthday),
            new ConfirmToken(Uuid::uuid4()->toString(), DateTime::current()->modify('+ 1 day')),
            $command->passport,
            $command->phone

        );

        $user->setPassword($this->passwordHasher->hashPassword($user, $command->password));

        $this->em->persist($user);
        $this->em->flush();

        $this->dispatcher->dispatch(new UserRegisteredEvent($user), UserRegisteredEvent::NAME);

        return $this->json([
            'message' => 'Check your email'
        ]);
    }

    private function deserialize(Request $request): Command
    {
        $body = $request->toArray();

        $command             = new Command();
        $command->name       = $body['name'] ?? '';
        $command->surname    = $body['password'] ?? '';
        $command->patronymic = $body['patronymic'] ?? '';
        $command->passport   = $body['passport'] ?? null;
        $command->phone      = $body['phone'] ?? null;
        $command->email      = $body['email'] ?? '';
        $command->password   = $body['password'] ?? '';
        $command->rePassword = $body['re_password'] ?? '';
        $command->birthday   = $body['birthday'] ?? '';

        return $command;
    }
}