<?php

namespace App\User\Observer;

use App\User\Events\UserRegisteredEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class User implements EventSubscriberInterface
{
    public function __construct(
        private MailerInterface $mailer,
        private string $appUrl,
        private string $adminEmail,
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            UserRegisteredEvent::NAME => 'onUserRegister',
        ];
    }

    // -----------------------------------

    public function onUserRegister(UserRegisteredEvent $event): void
    {
        $endpoint = sprintf($this->appUrl . '/user/confirm/%s', $event->getUser()->getConfirmToken()->getToken());

        $email = (new Email())
            ->from($this->adminEmail)
            ->to($event->getUser()->getEmail())
            ->subject('Welcome!!!')
            ->html(sprintf('Welcome, you are registered! go to <a href="%s">Here to confirmation</a>', $endpoint));

        $this->mailer->send($email);
    }
}