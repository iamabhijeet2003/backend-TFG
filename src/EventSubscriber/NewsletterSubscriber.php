<?php

// src/EventSubscriber/NewsletterSubscriber.php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Newsletter;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NewsletterSubscriber implements EventSubscriberInterface
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
        $newsletter = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        // Check if the controller result is a Newsletter entity and if the request method is POST
        if (!$newsletter instanceof Newsletter || Request::METHOD_POST !== $method) {
            return;
        }

        // Construct the email message
        $email = (new Email())
            ->from('iamabhijeet.dev@gmail.com')
            ->to($newsletter->getEmail())
            ->subject('Welcome to Our Newsletter!')
            ->html($this->generateWelcomeEmailHtml($newsletter->getName()));

        // Log the email sending process
        $this->logger->info('Sending welcome email to ' . $newsletter->getEmail());

        // Send the email
        $this->mailer->send($email);
    }

    private function generateWelcomeEmailHtml(string $name): string
    {
        // Generate the HTML content for the welcome email
        $html = '<h1>Hello ' . $name . ',</h1>';
        $html .= '<h1>Welcome to Our Newsletter!</h1>';
        $html .= '<p>Thank you for subscribing to our newsletter. You will now receive the latest updates, news, and promotions straight to your inbox.</p>';
        $html .= '<p>Stay tuned for exciting content!</p>';

        return $html;
    }
}
