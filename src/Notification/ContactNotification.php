<?php

namespace App\Notification;

use App\Entity\Bien;
use App\Entity\Categorie;
use App\Entity\Contact;
use App\Entity\Porteur;
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
    /**
     * Cette function permet d'envoyer un email lorsqu'un bien est mis en favoris par un porteur de projet
     *
     * @param Bien $bien
     * @return void
     */
    public function notifierSaferPourBienMisEnFavoris(Bien $bien)
    {
        $favoris = $bien->getFavoris();
        foreach ($favoris as $key => $porteur)
            $email = $porteur->getEmail();
        $message  = (new TemplatedEmail())
            ->from($email)
            ->subject("Bien ajouter aux  favoris")
            ->to('safer.support@gmail.com')
            ->htmlTemplate(
                'email/ajouter_favoris.html.twig',
            )
            ->context([
                'bien' => $bien,
                'porteur' => $porteur
            ]);
        $this->mailer->send($message);
    }

    /**
     * Cette function permet d'envoyer un email au porteur de projet pour des biens similaires
     *
     * @param Bien $bien
     * @return void
     */
    public function notifierPorteurPourBienMisEnFavorisSimilaire(Bien $bien)
    {

        $favoris = $bien->getFavoris();
        foreach ($favoris as $key => $porteur)
            $email = $porteur->getEmail();
        $categorie = $bien->getCategorie();
        $message  = (new TemplatedEmail())
            ->from('safer.support@gmail.com')
            ->subject("Ces biens pouraient vous intérésser")
            ->to($email)
            ->htmlTemplate(
                'email/similaire_favoris.html.twig',
            )
            ->context([
                'categorie' => $categorie,
                'porteur' => $porteur
            ]);
        $this->mailer->send($message);
    }
}
