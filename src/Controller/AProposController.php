<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AProposController extends AbstractController
{
    #[Route('/apropos', name: 'app_apropos')]
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->render('pages/apropos/apropos.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }
}
