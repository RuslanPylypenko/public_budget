<?php

declare(strict_types=1);

namespace App\User\Command\SignUp\Request;

use App\Api\Exception\AlreadyExistsApiException;
use App\Common\CQRS\CommandHandler;
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

readonly class Handler implements CommandHandler
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $em,
        private EventDispatcherInterface $dispatcher,
    ) {
    }

    public function __invoke(Command $command): void
    {
        if ($this->em->getRepository(UserEntity::class)->findByEmail($command->email) !== null) {
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
    }
}
