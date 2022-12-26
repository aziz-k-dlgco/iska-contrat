<?php

namespace App\EventSubscriber;

use Gedmo\Loggable\LoggableListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DoctrineExtensionSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private LoggableListener $loggableListener
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
            KernelEvents::FINISH_REQUEST => 'onLateKernelRequest'
        ];
    }

    public function onKernelRequest(): void
    {


    }

    public function onLateKernelRequest(FinishRequestEvent $event): void
    {

    }
}