<?php

namespace App\Notification;

use App\Entity\Contact;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class ContactNotification
{
    /**
     * Cette methode permet de prendre contact avec l'agence
     *
     * @param MailerInterface $mailer
     */
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
    /**
     * Cette methode permet de prendre contact avec l'agence pour un bien rechercher en notifier l'agence par email
     *
     * @param Contact $contact
     * @return void
     */
    public function notifyContactBien(Contact $contact)
    {
        $message  = (new TemplatedEmail())
            ->from($contact->getEmail())
            ->subject('Demande de contact pour un bien:' . $contact->getBien()->getTitre())
            ->to('safer.support@gmail.com')
            ->replyTo($contact->getEmail())
            ->htmlTemplate(
                'email/contact_bien.html.twig',
            )
            ->context([
                'contact' => $contact,
            ]);
        $this->mailer->send($message);
    }
}
