<?php

namespace App\EventSubscriber;

use App\Entity\Account\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class JWTCreatedListenerSubscriber implements EventSubscriberInterface
{
    public function onLexikJwtAuthenticationOnJwtCreated(JWTCreatedEvent $event): void
    {
        /** @var User $user */
        $user = $event->getUser();
        $payload = $event->getData();
        $payload['role'] = $user->getRoles()[0];
        $payload['user_lib'] = $user->getLib();
        $payload['departement'] = $user->getDepartement()->getNom();
        $event->setData($payload);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'lexik_jwt_authentication.on_jwt_created' => 'onLexikJwtAuthenticationOnJwtCreated',
        ];
    }
}
