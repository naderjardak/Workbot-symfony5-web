<?php

namespace App\Service;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class mailerService
{
    public function __construct(private MailerInterface $mailer)
    {
    }


    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmail($to = 'ilyesbettaieb@gmail.com',
                              $content = '<p>See Twig integration for better HTML integration!</p>',
                              $subject = 'Time for Symfony Mailer!'): void
    {
        $email = (new Email())
            ->from('houssem.bribech@esprit.tn')
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            ->text('Sending emails is fun again!')
            ->html($content);

        $this->mailer->send($email);

        // ...
    }

}