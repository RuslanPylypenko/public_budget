<?php

declare(strict_types=1);

namespace App\User\Api\Registration\Request;

use App\Api\Exception\AlreadyExistsApiException;
use App\Api\Exception\ValidationException;
use App\Api\Validator\Validator;
use App\User\ConfirmToken;
use App\User\Events\UserRegisteredEvent;
use App\User\PasswordHasher;
use App\User\UserEntity;
use App\Utils\DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

class Handler extends AbstractController
{
    public function __construct(
        private readonly Validator $validator,
        private readonly PasswordHasher $passwordHasher,
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
            $command->email,
            DateTime::fromString($command->birthday),
            $this->passwordHasher->hash($command->password),
            new ConfirmToken(Uuid::uuid4()->toString(), DateTime::current()->modify('+ 1 day'))
        );

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

        $command = new Command();

        $command->name = $body['name'] ?? '';
        $command->email = $body['email'] ?? '';
        $command->password = $body['password'] ?? '';
        $command->rePassword = $body['re_password'] ?? '';
        $command->birthday = $body['birthday'] ?? '';

        return $command;
    }
}