<?php
// src/Controller/ProfileController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="user_profile", methods={"GET"})
     */
    #[Route('/profile', name: 'profile')]
    public function profile(UserInterface $user): JsonResponse
    {
        // Retrieve user information
        $userData = [
            'id' => $user->getId(),
            'email' => $user->getEmail(), // If available
            // Add more user information as needed
        ];

        // Return user profile data
        return $this->json($userData);
    }
}
