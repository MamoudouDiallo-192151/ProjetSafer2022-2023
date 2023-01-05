<?php

namespace App\Controller\admin;

use App\Entity\Bien;
use App\Form\BienType;
use App\Repository\BienRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/bien')]
class BienController extends AbstractController
{
    #[Route('/', name: 'admin_bien_index', methods: ['GET'])]
    public function index(BienRepository $bienRepository): Response
    {
        return $this->render('admin/bien/index.html.twig', [
            'biens' => $bienRepository->findAll(),
        ]);
    }

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

    #[Route('/{id}', name: 'admin_bien_show', methods: ['GET'])]
    public function show(Bien $bien): Response
    {
        return $this->render('admin/bien/show.html.twig', [
            'bien' => $bien,
        ]);
    }

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

    #[Route('/{id}/delete', name: 'admin_bien_delete', methods: ['POST'])]
    public function delete(Request $request, Bien $bien, BienRepository $bienRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $bien->getId(), $request->request->get('_token'))) {
            $bienRepository->remove($bien, true);
        }

        return $this->redirectToRoute('admin_bien_index', [], Response::HTTP_SEE_OTHER);
    }
}
