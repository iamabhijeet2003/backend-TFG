<?php
 
namespace App\Controller;
 


use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Checkout\Session;


class StripeController extends AbstractController
{
    #[Route('/stripe', name: 'app_stripe')]
    public function index(): Response
    {
        return $this->render('stripe/index.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
        ]);
    }
 
 
    #[Route('/stripe/create-charge', name: 'app_stripe_charge', methods: ['POST'])]
    public function createCharge(Request $request)
    {
        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        Stripe\Charge::create ([
                "amount" => 5 * 100,
                "currency" => "eur",
                "source" => $request->request->get('stripeToken'),
                "description" => "Abhijeet Payment Test"
        ]);
        $this->addFlash(
            'success',
            'Payment Successful!'
        );
        return $this->redirectToRoute('app_stripe', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/stripe/create-checkout-session', name: 'app_stripe_create_checkout_session', methods: ['POST'])]
    public function createCheckoutSession(Request $request): JsonResponse
    {
        // Decode JSON request data
        $data = json_decode($request->getContent(), true);

        // Extract data from the request
        $totalAmount = $data['amount_total'];
        $userName = $data['created'];
        $successUrl = $data['successUrl'];
        $cancelUrl = $data['cancelUrl'];

        // Create a new Checkout Session
        Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd', // Replace with the appropriate currency code
                            'unit_amount' => $totalAmount * 100, // Convert to cents
                            'product_data' => [
                                'name' => 'Purchase', // Example item name
                            ],
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'metadata' => [
                    'user_name' => $userName,
                ],
            ]);

            // Return the session URL
            return $this->json(['url' => $session->url]);
        } catch (\Exception $e) {
            // Handle exceptions
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}