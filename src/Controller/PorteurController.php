<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\Porteur;
use App\Form\PorteurType;
use App\Form\UtilisateurProfileType;
use App\Notification\ContactNotification;
use App\Repository\CategorieRepository;
use App\Repository\PorteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Exception\Config\Filter\NotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class PorteurController extends AbstractController
{
    /**
     * Cette function permet aux users de s'insrire
     *
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/inscription', name: 'app_porteur_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $porteur = new Porteur();
        $form = $this->createForm(PorteurType::class, $porteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $porteur->setPassword(
                $userPasswordHasher->hashPassword(
                    $porteur,
                    $form->get('password')->getData()
                )
            );
            $porteur->setRoles(['ROLE_PORTEUR']);
            $entityManager->persist($porteur);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * cette function permet au porteur de modifier son profile
     */
    #[Route('porteur/editer_profile/{id}', name: 'porteur_edit_profile', methods: ['GET', 'POST'])]
    public function editerProfile(Porteur $user, Request $request, EntityManagerInterface $entityManager, CategorieRepository $categorieRepository): Response
    {
        //si le user n'est pas connecté
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        //le user en param # du user courant 
        if (!$this->getUser() === $user) {
            return $this->redirectToRoute('admin_login');
        }
        $form = $this->createForm(UtilisateurProfileType::class, $user, ['is_edit' => true]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Les informations de votre compte ont été modifiées avec success');
            return $this->redirectToRoute('app_accueil');
        }
        return $this->render('pages/profile/profile.html.twig', [
            'form' => $form->createView(),
            'categories' => $categorieRepository->findAll()
        ]);
    }
    /**
     * Cette funcion permet de mettre en favoris un bien 
     */
    #[Route('/favoris/ajout/{id}', name: 'ajout_favoris', methods: ['GET'])]
    public function ajoutFavoris(Bien $bien, ContactNotification $notification, EntityManagerInterface $entityManager): Response
    {
        if (!$bien) {
            throw new NotFoundHttpException('Pas de bien trouver');
        }
        $bien->addFavori($this->getUser());
        $bien->setIsFavoris(true);
        $entityManager->persist($bien);
        $entityManager->flush();
        $this->addFlash('favoris', "Vous avez mis en favoris ce bien:" . $bien->getTitre() . "");
        $notification->notifierSaferPourBienMisEnFavoris($bien);
        return $this->redirectToRoute('app_accueil');
    }
    /**
     * cette function permet de retirer des favoris un bien
     */
    #[Route('/favoris/retirer/{id}', name: 'retirer_favoris', methods: ['GET'])]
    public function retirerFavoris(Bien $bien, EntityManagerInterface $entityManager): Response
    {
        if (!$bien) {
            throw new NotFoundHttpException('Pas de bien trouver');
        }
        $bien->removeFavori($this->getUser());
        $bien->setIsFavoris(false);
        $entityManager->persist($bien);
        $entityManager->flush();
        $this->addFlash('favoris', "Vous avez  retirer de vos favoris ce bien:" . $bien->getTitre() . "");
        return $this->redirectToRoute('app_accueil');
    }
    /**
     * Cette  function permet de visualiser mes favoris
     */
    #[Route('/favoris/mes_favoris/{id}', name: 'mes_favoris', methods: ['GET'])]
    public function mesFavoris(int $id, PorteurRepository $rep, CategorieRepository $categorieRepository): Response
    {
        $porteur = $rep->findOneBy(["id" => $id]);
        if (!$porteur) {
            throw new NotFoundHttpException('Pas de bien trouver');
        }
        $favoris = $porteur->getFavoris();
        return $this->render('pages/porteur/favoris.html.twig', [
            'porteur' => $porteur,
            'favoris' => $favoris,
            'categories' => $categorieRepository->findAll()
        ]);
    }
}
