<?php

namespace App\Controller\admin;

use App\Entity\Administrateur;
use App\Form\AdministrateurType;
use App\Form\UtilisateurProfileType;
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

    #[Route('/profile_admin/{id}', name: 'admin_edit_profile', methods: ['GET', 'POST'])]
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
}
