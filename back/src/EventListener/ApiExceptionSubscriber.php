<?php

namespace App\EventListener;

use App\Entity\Manager;
use App\Exception\TwitchException;
use App\Service\Twitch\RefreshAccessTokenService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly RefreshAccessTokenService $refreshAccessTokenService,
        private readonly TokenStorageInterface $tokenStorage,
    ) {
    }

    private function handleTwitchException(
        ExceptionEvent $event,
    ): void {
        $event->stopPropagation();

        /** @var Manager $currentManager */
        $currentManager = $this->tokenStorage?->getToken()?->getUser() ?? null;

        if (null === $currentManager) {
            return;
        }

        $this->refreshAccessTokenService->handleExpiredAccessToken($currentManager);
    }

    public function onKernelException(
        ExceptionEvent $event,
    ): void {
        $e = $event->getThrowable();

        match (get_class($e)) {
            TwitchException::class => $this->handleTwitchException($event),
        };
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
