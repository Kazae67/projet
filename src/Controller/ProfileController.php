<?php

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use App\DTO\ChangePasswordModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $user = $this->getUser(); // Récupère l'utilisateur actuellement connecté
        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profile/change-password', name: 'app_profile_change_password')]
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); // S'assurer que l'utilisateur est connecté
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $changePasswordModel = new ChangePasswordModel(); // Créer une instance du DTO
        $form = $this->createForm(ChangePasswordFormType::class, $changePasswordModel); // Passer le DTO au formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hasher le nouveau mot de passe et l'assigner à l'utilisateur
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $changePasswordModel->getNewPassword() // Utiliser les données du DTO
            );
            $user->setPassword($hashedPassword);

            // Utiliser l'EntityManagerInterface injecté pour persister les changements
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Your password has been changed.');

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/changePassword.html.twig', [
            'changePasswordForm' => $form->createView(),
        ]);
    }
}
