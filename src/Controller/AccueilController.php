<?php

namespace App\Controller;

use App\Repository\BienRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(BienRepository $bienRepository): Response
    {
        $biens = $bienRepository->findLatestBien();
        return $this->render('pages/accueil.html.twig', [
            'biens' => $biens,
        ]);
    }
}
