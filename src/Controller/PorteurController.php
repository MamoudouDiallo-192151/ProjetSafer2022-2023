<?php

namespace App\Controller;

use App\Entity\Porteur;
use App\Form\PorteurType;
use App\Form\UtilisateurProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class PorteurController extends AbstractController
{
    /**
     * Cette function permet
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
     * cette function permet au proteur de modifier son profile
     */
    #[Route('/editer_profile/{id}', name: 'porteur_edit_profile', methods: ['GET', 'POST'])]
    public function editerProfile(Porteur $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        //si le user n'est pas connecté
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        //le user en param # du user courant 
        if (!$this->getUser() === $user) {
            return $this->redirectToRoute('admin_home');
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
            'form' => $form->createView()
        ]);
    }
}
