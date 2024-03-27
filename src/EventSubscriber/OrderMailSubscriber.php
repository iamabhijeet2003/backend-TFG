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

class OrderMailSubscriber implements EventSubscriberInterface
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
            KernelEvents::VIEW => ['sendOrderConfirmationEmail', EventPriorities::POST_WRITE],
        ];
    }

    public function sendOrderConfirmationEmail(ViewEvent $event)
    {
        $order = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        // Check if the controller result is an Order entity and if the request method is POST
        if (!$order instanceof Order || Request::METHOD_POST !== $method) {
            return;
        }

        $user = $order->getUserId();

        // Construct the email message
        $email = (new Email())
            ->from('iamabhijeet.dev@gmail.com')
            //->to('abhijeet3016@gmail.com') // 
            ->to($user->getEmail())
            ->subject('Order Confirmation')
            ->text(sprintf('Your order #%d has been confirmed.', $order->getId()));

        // Log the email sending process
        $this->logger->info('Sending order confirmation email for order ID ' . $order->getId());

        // Send the email
        $this->mailer->send($email);
    }
}
