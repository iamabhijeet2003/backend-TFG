<?php
// src/EventSubscriber/LoginMailSubscriber.php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class LoginMailSubscriber implements EventSubscriberInterface
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
            SecurityEvents::INTERACTIVE_LOGIN => 'sendLoginConfirmationEmail',
        ];
    }

    public function sendLoginConfirmationEmail(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        $request = $event->getRequest();

        // Get device information
        $ipAddress = $request->getClientIp();
        $userAgent = $request->headers->get('User-Agent');

        // Construct the email message
        $email = (new Email())
            ->from('iamabhijeet.dev@gmail.com')
            ->to($user->getEmail())
            ->subject('Login Confirmation')
            ->html($this->generateLoginConfirmationHtml($user, $ipAddress, $userAgent));

        // Log the email sending process
        $this->logger->info('Sending login confirmation email for user ' . $user->getEmail());

        // Send the email
        $this->mailer->send($email);
    }

    private function generateLoginConfirmationHtml($user, $ipAddress, $userAgent): string
    {
        // Generate the HTML content for the login confirmation email
        $html = '<h1 style="color: #333; font-family: Arial, sans-serif; text-align:center;"> Hello '. $user->getFirstName() .'</h1>';
        $html .= '<h1 style="color: #333; font-family: Arial, sans-serif; text-align:center;">Welcome back!</h1>';
        $html .= '<p style="font-size: 16px; text-align:center;">';
        $html .= 'You have successfully logged in to your account.';
        $html .= '</p>';

        // Include device information
        $html .= '<p style="font-size: 14px; color: #666; text-align:center;">';
        $html .= 'IP Address: ' . $ipAddress . '<br>';
        $html .= 'User Agent: ' . $userAgent . '<br>';
        $html .= '</p>';

         // Add additional text
        $html .= '<p style="font-size: 14px; text-align:center;">';
        $html .= 'If this login was not done by you, please contact us immediately and consider changing your password for security.';
        $html .= '</p>';
        return $html;
    }
}
