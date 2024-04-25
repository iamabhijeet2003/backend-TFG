<?php
// src/EventSubscriber/ContactFormSubscriber.php

namespace App\EventSubscriber;


use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Contact;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactFormSubscriber implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['sendConfirmationEmail', EventPriorities::POST_WRITE],
        ];
    }

    public function sendConfirmationEmail(ViewEvent $event)
    {
        $contact = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$contact instanceof Contact || Request::METHOD_POST !== $method) {
            return;
        }

        // Construct the email message
        $email = (new Email())
            ->from('iamabhijeet.dev@gmail.com')
            ->to($contact->getEmail())
            ->subject('Message Submitted')
            ->html($this->generateConfirmationHtml($contact));

        // Send the email
        $this->mailer->send($email);
    }

    private function generateConfirmationHtml(Contact $contact): string
    {
        // Generate the HTML content for the confirmation email
        $html = '<h1>Hello ' . $contact->getName() . ',</h1>';
        $html .= '<p>Your message has been submitted successfully. We will get in touch with you soon.</p>';
        return $html;
    }
}
