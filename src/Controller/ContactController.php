<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use App\Repository\CategorieRepository;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/porteur')]
class ContactController extends AbstractController
{
    /**
     * Cettte methode permet aux users d'envoyer des demandes de contact à la safer
     * @param Request $request
     * @param ContactRepository $contactRepository
     * @param ContactNotification $notification
     * @param CategorieRepository $rep
     * @return Response
     */
    #[Route('/contacter', name: 'app_contact', methods: ['GET', 'POST'])]
    public function new(Request $request, ContactRepository $contactRepository, ContactNotification $notification, CategorieRepository $rep): Response
    {
        $contact = new Contact();
        $categories = $rep->findAll();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notification->notifyContact($contact);
            $contactRepository->add($contact);
            $this->addFlash('success', 'Votre email a bien été envoyé');
            return $this->redirectToRoute('app_contact', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/contact/contact.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
            'categories' => $categories,

        ]);
    }
}
