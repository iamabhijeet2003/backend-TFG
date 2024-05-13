<?php
// src/EventSubscriber/OrderMailSubscriber.php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Order;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class OrderStatusSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $logger;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['sendOrderPreparedEmail', EventPriorities::POST_WRITE],
        ];
    }

    public function sendOrderPreparedEmail(ViewEvent $event)
    {
        $order = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        // Check if the controller result is an Order entity and if the request method is PATCH
        if (!$order instanceof Order || Request::METHOD_PATCH !== $method) {
            return;
        }

        // Check if the order status is changed to "prepared"
        if ($order->isPrepared() !== true) {
            return;
        }

        $user = $order->getUserId();

        // Construct the email message
        $email = (new Email())
        ->from('iamabhijeet.dev@gmail.com')
            ->to($user->getEmail())
            ->subject('Order Prepared')
            ->html($this->generateOrderPreparedHtml($order));

        // Log the email sending process
        $this->logger->info('Sending order prepared email for order ID ' . $order->getId());

        // Send the email
        $this->mailer->send($email);
    }


    private function generateOrderPreparedHtml(Order $order): string
    {
        // Generate the HTML content for the order prepared email
        $html = '<h1>Hello ' . $order->getUserId()->getFirstName() . '</h1>';
        $html .= '<p>Your order # ' . $order->getId() . ' has been prepared and will arrive in 2-3 days.</p>';
        // You can include more details about the order if needed
        return $html;
    }
}
