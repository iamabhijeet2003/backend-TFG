<?php
// src/EventListener/AuthenticationSuccessListener.php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccessListener
{
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        // Get the user ID
        $userId = $user->getId();

        // Modify the response data to include the token and user ID
        $responseData = [
            'token' => $data['token'],
            'user_id' => $userId,
        ];

        // Set the modified response data
        $event->setData($responseData);
    }
}
