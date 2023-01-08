<?php

namespace App\Controller\admin;

use App\Entity\Administrateur;
use App\Entity\Utilisateur;
use App\Entity\UtilisateurSearch;
use App\Form\AdministrateurType;
use App\Form\UtilisateurProfileType;
use App\Form\UtilisateurRoleType;
use App\Form\UtilisateurSearchType;
use App\Repository\BienRepository;
use App\Repository\CategorieRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin')]
class AdminController extends AbstractController
{
    /**
     * Cette methode de recuperer toutes les statistiques du site safer et l'envoie à la vue twig
     *
     * @param BienRepository $brep
     * @param CategorieRepository $catrep
     * @param UtilisateurRepository $utilisateurRepository
     * @return Response
     */
    #[Route('/statistiques', name: 'admin_home')]
    public function home(BienRepository $brep, CategorieRepository $catrep, UtilisateurRepository $utilisateurRepository): Response
    {
        $nbreBiens = $brep->getNbreBien();
        $nbreBienEnFavoris = $brep->getNbreBienEnFavoris();
        $nbreBienNonFavoris = $brep->getNbreBienNonFavoris();
        $categories = $catrep->findAll();
        return $this->render('admin/dashboard/statistiques.html.twig', [
            'nbreBiens' => $nbreBiens,
            'nbreBienNonFavoris' => $nbreBienNonFavoris,
            'nbreBienEnFavoris' => $nbreBienEnFavoris,
            'categories' => $categories,
            'nbreUsers' => $utilisateurRepository->getNbreUser()

        ]);
    }

    /**
     * Cette methode affiche la liste des users avec pagination et une barre de rechercher afin
     * de supprimer ounnon un utilisateur
     *
     * @param UtilisateurRepository $userRepository
     * @param Request $request
     * @return Response
     */
    #[Route('/utilisateurs', name: 'admin_users')]
    public function findAllUsers(UtilisateurRepository $userRepository, Request $request): Response
    {
        $nbUsers = $userRepository->getNbreUser();
        $nbToTalPages = ceil(($nbUsers) / 3);
        $search = new UtilisateurSearch();
        $form = $this->createForm(UtilisateurSearchType::class, $search);
        $form->handleRequest($request);
        return $this->render('admin/user/user.html.twig', [
            'users' => $userRepository->paginateAllUtilisateurs($search, $request->query->getInt('page', 1)),
            'formSearch' => $form->createView(),
            'nbPage' => $request->query->getInt('page', 1),
            'nbTotalPage' => $nbToTalPages,
        ]);
    }
    
    /**
     * Cette methode permet d'incrire des admin de role admin 
     *
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/inscrire', name: 'admin_register_form')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Administrateur;
        $form = $this->createForm(AdministrateurType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $user->setRoles(['ROLE_ADMIN']);
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
        }

        return $this->render('admin/register/admin_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     *      * Cette methode permet aux admin de modifier leurs roles
     */
    #[Route('/profile/{id}/edit', name: 'admin_edit_profile', methods: ['GET', 'POST'])]
    public function editerProfile(Administrateur $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        //si le user n'est pas connecté
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        //le user en param # du user courant 
        if (!$this->getUser() === $user) {
            return $this->redirectToRoute('admin_bien_index');
        }
        $form = $this->createForm(UtilisateurProfileType::class, $user, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Les informations de votre compte ont été modifiées avec success');
            return $this->redirectToRoute('admin_bien_index');
        }
        return $this->render('admin/profile/profile.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
    /**
     * Cette méthode permet  à l'admin ayant le role superAdmin de modifier le role des users
     */

    #[Route('/role_utilisateur/{id}/editer', name: 'admin_edit_user_role', methods: ['GET', 'POST'])]
    public function editUserRole(Request $request, Utilisateur $user, UtilisateurRepository $userRepository): Response
    {
        $form = $this->createForm(UtilisateurRoleType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);
            $this->addFlash("success", "Le rôle de l'utilisateur à été modifié avec success");
            return $this->redirectToRoute('admin_users', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/user/edit_role.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/user_delete', name: 'admin_user_delete')]
    public function delete(Utilisateur $user, UtilisateurRepository $utilisateurRepository): Response
    {
        //if ($this->isCsrfTokenValid('delete' . $service->getId(), $request->request->get('_token'))) {
        $utilisateurRepository->remove($user, true);
        $this->addFlash('success', 'Utilisateur supprimé avec succes');
        //}

        return $this->redirectToRoute('admin_users', [], Response::HTTP_SEE_OTHER);
    }
}
