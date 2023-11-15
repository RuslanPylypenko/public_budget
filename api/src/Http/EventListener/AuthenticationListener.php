<?php

namespace App\Http\EventListener;

use App\Http\Annotation\Authenticate;
use App\Http\Annotation\Authenticate\TokenManager;
use App\Http\Annotation\Authenticate\UserAuthenticatorInterface;
use App\Utils\Context;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Token\InvalidTokenStructure;

class AuthenticationListener implements EventSubscriberInterface
{

    public function __construct(
        private readonly TokenManager $tokenManager,
        private readonly ParameterBagInterface $parameterBag,
        private readonly UserAuthenticatorInterface $userAuthentication,
        private readonly Context $context,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
          KernelEvents::CONTROLLER => ['onKernelController', 200],
        ];
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $request = $event->getRequest();

        /** @var Authenticate $annotation */
        if (null === ($annotation = $request->attributes->get('_authenticate') ?? $event->getAttributes()[Authenticate::class][0] ?? null)) {
            return;
        }

        $request->attributes->set('_authenticate', $annotation);

        if (!$request->headers->has('access-token')) {
            throw new UnauthorizedHttpException('Bearer error="invalid_token"', 'Access Denied: NoToken');
        }

        try {
            $token = $this->tokenManager->parse($request->headers->get('access-token'));
        } catch (InvalidTokenStructure | CannotDecodeContent) {
            throw new UnauthorizedHttpException('Bearer error="invalid_token"', 'Access Denied: InvalidToken');
        }

        $issList = !empty($annotation->issuers) ? $annotation->issuers : [(string)$this->parameterBag->get('app.name')];
        try {
            $this->tokenManager->validate($token, $request, $issList);
            $request->attributes->set(Authenticate::TOKEN, $token);
        } catch (RequiredConstraintsViolated) {
            throw new UnauthorizedHttpException('Bearer error="invalid_token"', 'Access Denied: ConstraintsViolated');
        }

        if ($annotation->authUser) {
            if (null === $user = $this->userAuthentication->authenticate($token)) {
                throw new UnauthorizedHttpException('Bearer error="invalid_token"', 'Access Denied: Unauthorized');
            }

            $this->context->add('user', $user->getEmail());
            $request->attributes->set(Authenticate::USER, $user);
        }

    }
}