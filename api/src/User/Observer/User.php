<?php

namespace App\User\Observer;

use App\User\Events\UserRegisteredEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class User implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            UserRegisteredEvent::NAME => 'onUserRegister',
        ];
    }

    // -----------------------------------

    public function onUserRegister(UserRegisteredEvent $event): void
    {

    }
}