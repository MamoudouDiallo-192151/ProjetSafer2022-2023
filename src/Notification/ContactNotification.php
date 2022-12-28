<?php

namespace App\Notification;

use App\Entity\Contact;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class ContactNotification
{

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    public function notifyContact(Contact $contact)
    {
        $message  = (new TemplatedEmail())
            ->from($contact->getEmail())
            ->subject('Demande de contact')
            ->to('safer.support@gmail.com')
            ->replyTo($contact->getEmail())
            ->htmlTemplate(
                'email/contact.html.twig',
            )
            ->context([
                'contact' => $contact,
            ]);
        $this->mailer->send($message);
    }
}
