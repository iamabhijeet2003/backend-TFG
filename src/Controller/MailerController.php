<?php
// src/Controller/MailerController.php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerController extends AbstractController
{
    #[Route('/mailer', name: 'app_mailer')]
    public function sendEmail(MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('mailtrap@example.com')
            ->to('abhijeet3016@gmail.com') // Primary recipient
            ->cc('mailtrapqa@example.com') // CC recipient
            ->bcc('mailtrapdev@example.com') // BCC recipient
            ->replyTo('mailtrap@example.com') // Reply-To address
            ->subject('Your subject here')
            ->text('This is the text version of the email.')
            ->html('<p>This is the HTML version of the email.</p>');

        $mailer->send($email);

        return new Response('Email was sent successfully.');
    }
}