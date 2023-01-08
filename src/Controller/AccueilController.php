<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Utilisateur;
use App\Repository\BienRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Repository\CategorieRepository;
use App\Repository\UtilisateurRepository;

class AccueilController extends AbstractController
{
    /**
     * Cette function renvoye vers la  page d'accueil  et les 3 biens afficher aleatoirement
     *
     * @param BienRepository $bienRepository
     * @param CategorieRepository $rep
     * @return Response
     */
    #[Route('/', name: 'app_accueil')]
    public function index(BienRepository $bienRepository, CategorieRepository $rep, UtilisateurRepository $utilisateurRepository): Response
    {
        $categories = $rep->findAll();
        $biens = $bienRepository->findLatestBien();
        return $this->render('pages/accueil/accueil.html.twig', [
            'biens' => $biens,
            'categories' => $categories,
            'utilisateurs' => $utilisateurRepository->findAll()

        ]);
    }
    /**
     * Cette methode permet de voir les détails d'une categorie c'està dire à tous les biens de cette categorie
     */
    #[Route('/categorie/detail/{id}', name: 'categorie_show')]
    public function show(Categorie $categorie, CategorieRepository $rep): Response
    {
        $categories = $rep->findAll();

        return $this->render('pages/categorie/show.html.twig', [
            'categorie' => $categorie,
            'categories' => $categories,


        ]);
    }

    /**
     * Cette methode ne marche pas pourr le momoment le but etait de remplir la bd avec le fichier excel
     * @param Request $request
     * @throws \Exception
     */
    #[Route('/upload-excel', name: 'xlsx')]
    public function xslx(Request $request)
    {
        $file = $request->files->get('file'); // get the file from the sent request
        $fileFolder = "";
        $filePathName = "";
        if ($request->files->get('file')) {
            $file = $request->files->get('file'); // get the file from the sent request
            $fileFolder = __DIR__ . '/../../public/data/data_safer.xlsx';  //choose the folder in which the uploaded file will be stored
            $filePathName = md5(uniqid()) . $file->getClientOriginalName();
        }
        // apply md5 function to generate an unique identifier for the file and concat it with the file extension  
        try {
            if ($request->files->get('file')) {
                $file->move($fileFolder, $filePathName);
            }
        } catch (FileException $e) {
            dd($e);
        }
        $spreadsheet = IOFactory::load($fileFolder . $filePathName); // Here we are able to read from the excel file 
        $row = $spreadsheet->getActiveSheet()->removeRow(1); // I added this to be able to remove the first file line 
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true); // here, the read data is turned into an array
        dd($sheetData);
    }
}
