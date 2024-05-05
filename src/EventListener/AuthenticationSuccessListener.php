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
        $userName = $user->getFirstName();
        $userRole = $user->getRoles();

        // Modify the response data to include the token and user ID
        $responseData = [
            'token' => $data['token'],
            'user_id' => $userId,
            'username' => $userName,
            'role'=> $userRole
        ];

        // Set the modified response data
        $event->setData($responseData);
    }
}
