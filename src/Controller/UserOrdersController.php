<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface; 
use App\Entity\User; 

class UserOrdersController extends AbstractController
{
    #[Route('/user/{userId}/orders', name: 'app_user_orders')]
public function index(EntityManagerInterface $entityManager, int $userId): JsonResponse
{
    // Fetch the user by ID
    $user = $entityManager->getRepository(User::class)->find($userId);

    // Check if user exists
    if (!$user) {
        // Handle scenario where user is not found
        return new JsonResponse(['error' => 'User not found.'], 404);
    }

    // Fetch all orders associated with the user
    $orders = $user->getOrders();

    // Check if user has orders
    if (!$orders->isEmpty()) {
        // Convert orders data to array
        $ordersData = [];
        foreach ($orders as $order) {
            $ordersData[] = [
                'id' => $order->getId(),
                'total' => $order->getTotal(),
                'prepared' => $order->isPrepared(),
                'created_at' => $order->getCreatedAt()
            ];
        }

        // Return orders data as JSON response
        return new JsonResponse($ordersData);
    }

    // Handle scenario where user has no orders
    return new JsonResponse(['message' => 'User has no orders.'], 200);
}

}
