<?php
// src/EventListener/JWTCreatedListener.php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class JWTCreatedListener
{
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        // Get the user ID
        $userId = $user->getId();

        // Add the user ID to the payload
        $payload = $event->getData();
        $payload['user_id'] = $userId;

        // Update the payload in the event
        $event->setData($payload);
    }
}
