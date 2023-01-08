<?php

namespace App\Controller\admin;

use App\Entity\Bien;
use App\Form\BienType;
use App\Notification\ContactNotification;
use App\Repository\BienRepository;
use App\Repository\CategorieRepository;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Ce controleur gere le crud des biens et est accessible uniquement par les users qui ont le role admin ou superAdmin
 */
#[Route('admin/bien')]
class BienController extends AbstractController
{
    /**
     * Cette methodes sert à afficher tous les biens
     *
     * @param BienRepository $bienRepository
     * @return Response
     */
    #[Route('/', name: 'admin_bien_index', methods: ['GET'])]
    public function index(BienRepository $bienRepository): Response
    {
        return $this->render('admin/bien/index.html.twig', [
            'biens' => $bienRepository->findAll(),
        ]);
    }

    /**
     * Cette methodes sert à afficher tous les biens en favoris
     *
     * @param BienRepository $bienRepository
     * @return Response
     */
    #[Route('/favoris', name: 'admin_bien_en_favoris', methods: ['GET'])]
    public function bienFavoris(BienRepository $bienRepository): Response
    {
        $biens = $bienRepository->getBienEnFavoris();
        //dd($biens);
        return $this->render('admin/bien/favoris/favoris.html.twig', [
            'biens' => $biens,
        ]);
    }

    /**
     * Cette methode sert à envoyer tous les biens similaires au bien mis  en favoris par un porteur
     *
     * @param BienRepository $bienRepository
     * @return Response
     */
    #[Route('/favoris/similaire/{id}', name: 'admin_envoie_favoris_similaire', methods: ['GET'])]
    public function bienFavorisSimilaire(Bien $bien, ContactNotification $contactNotification, EntityManagerInterface $entityManager, BienRepository $bienRepository): Response
    {
        $biens = $bienRepository->getBienEnFavoris();
        $contactNotification->notifierPorteurPourBienMisEnFavorisSimilaire($bien);
        $bien->setIsEnvoyer(true);
        $entityManager->persist($bien);
        $entityManager->flush();
        return $this->redirectToRoute('admin_bien_en_favoris', [], Response::HTTP_SEE_OTHER);
    }




    /**
     * Cette methode  permet de creer un bien
     *
     * @param Request $request
     * @param BienRepository $bienRepository
     * @return Response
     */
    #[Route('/new', name: 'admin_bien_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BienRepository $bienRepository): Response
    {
        $bien = new Bien();
        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bien->setIsFavoris(false);
            $bienRepository->add($bien, true);
            return $this->redirectToRoute('admin_bien_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/bien/new.html.twig', [
            'bien' => $bien,
            'form' => $form->createView(),
        ]);
    }
    /**
     * Cette methode permet de voir les détals d'un bien
     */
    #[Route('/{id}', name: 'admin_bien_show', methods: ['GET'])]
    public function show(Bien $bien): Response
    {
        return $this->render('admin/bien/show.html.twig', [
            'bien' => $bien,
        ]);
    }
    /**
     * Cette methode permet à l'admin de modifier un bien
     */
    #[Route('/{id}/edit', name: 'admin_bien_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bien $bien, BienRepository $bienRepository): Response
    {
        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bienRepository->add($bien, true);

            return $this->redirectToRoute('admin_bien_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/bien/edit.html.twig', [
            'bien' => $bien,
            'form' => $form->createView(),
        ]);
    }
    /**
     * Cette methode permet à l'admin de supppimer un bien
     */
    #[Route('/{id}/delete', name: 'admin_bien_delete', methods: ['POST'])]
    public function delete(Request $request, Bien $bien, BienRepository $bienRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $bien->getId(), $request->request->get('_token'))) {
            $bienRepository->remove($bien, true);
        }

        return $this->redirectToRoute('admin_bien_index', [], Response::HTTP_SEE_OTHER);
    }
}
