<?php
// src/EventSubscriber/WelcomeEmailSubscriber.php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class WelcomeEmailSubscriber implements EventSubscriberInterface
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
            KernelEvents::VIEW => ['sendWelcomeEmail', EventPriorities::POST_WRITE],
        ];
    }

    public function sendWelcomeEmail(ViewEvent $event)
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        // Check if the controller result is a User entity and if the request method is POST
        if (!$user instanceof User || Request::METHOD_POST !== $method) {
            return;
        }

        // Construct the email message
        $email = (new Email())
            ->from('iamabhijeet.dev@gmail.com')
            ->to($user->getEmail())
            ->subject('Welcome to Our Website')
            ->html($this->generateWelcomeEmailHtml($user));

        // Log the email sending process
        $this->logger->info('Sending welcome email to ' . $user->getEmail());

        // Send the email
        $this->mailer->send($email);
    }

    private function generateWelcomeEmailHtml(User $user): string
    {
        // Generate the HTML content for the welcome email
        $html = '<h1>Hello ' . $user->getFirstName() . ',</h1>';
        $html .= '<p>Welcome to our website! We are glad to have you on board.</p>';
        $html .= '<p>Thank you for registering with us.</p>';
        $html .= '<p>If you have any questions or need assistance, feel free to contact us.</p>';
        $html .= '<p>Best regards,</p>';
        $html .= '<p>Your Website Team</p>';

        return $html;
    }
}
