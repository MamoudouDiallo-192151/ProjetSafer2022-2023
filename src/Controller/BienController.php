<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\BienSearch;
use App\Entity\Contact;
use App\Form\BienSearchType;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use App\Repository\BienRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/biens')]
class BienController extends AbstractController
{
    /**
     * @var BienRepository
     */
    private $repository;
    public function __construct(BienRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }
    #[Route('/', name: 'bien_index')]
    public function index(BienRepository $rep, Request $request): Response
    {
        $search = new BienSearch();
        $form = $this->createForm(BienSearchType::class, $search);
        $form->handleRequest($request);
        return $this->render('pages/bien/index.html.twig', [
            'biens' => $rep->paginateAllVisibleQuery($search, $request->query->getInt('page', 1)),
            'formSearch' => $form->createView(),
            'current_menu' => 'biens'
        ]);
    }
    /**
     * @param   $bien
     */
    #[Route('/detail/{id}', name: 'bien_show')]
    public function show(Bien $bien, Request $request, ContactNotification $notification, CategorieRepository $rep): Response
    {
        $categories = $rep->findAll();
        $contact = new Contact();
        $contact->setBien($bien);
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $notification->notifyContactBien($contact);
            $this->addFlash('success', 'Votre email a bien été envoyé');
            return $this->redirectToRoute('bien_show', [
                'id' => $bien->getId(),
            ]);
        }
        return $this->render('pages/bien/show.html.twig', [
            'bien' => $bien,
            'categories' => $categories,
            'current_menu' => 'biens',
            'form' => $form->createView(),

        ]);
    }
}
