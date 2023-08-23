<?php

declare(strict_types=1);

namespace App\Api;

use App\Api\Exception\ApiException;
use App\Api\Exception\ValidationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class HttpKernelListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 255],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();
        if ($e instanceof ApiException) {
            $event->allowCustomResponseCode();
            $event->setResponse(
                new JsonResponse([
                    'text' => $e->getMessage(),
                    'code' => $e->getCode()
                ]));
        }

        if ($e instanceof \DomainException) {
            $event->allowCustomResponseCode();
            $event->setResponse(
                new JsonResponse([
                    'text' => $e->getMessage(),
                    'code' => $e->getCode()
                ],
                    400
                ));
        }

        if ($e instanceof ValidationException) {
            $event->allowCustomResponseCode();
            $event->setResponse(
                new JsonResponse([
                    'message' => $e->getMessage(),
                    'data' => $e->getErrors()->toArray()
                ],
                    422
                ));
        }
    }

}