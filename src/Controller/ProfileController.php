<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Adress;
use App\DTO\ChangePasswordModel;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupère l'utilisateur actuellement connecté
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new AccessDeniedException('You must be logged in.');
        }

        // Récupérer uniquement les adresses actives de l'utilisateur
        $activeAdresses = $entityManager->getRepository(Adress::class)->findBy([
            'user' => $user,
            'isActive' => true
        ]);

        // Rendre la vue 'profile/index.html.twig' avec les données de l'utilisateur et ses adresses actives
        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'adresses' => $activeAdresses, // Passer les adresses actives à la vue
        ]);
    }

    #[Route('/profile/change-password', name: 'app_profile_change_password')]
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        // S'assurer que l'utilisateur est connecté
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        // Créer une instance du DTO (Data Transfer Object) pour gérer le changement de mot de passe
        $changePasswordModel = new ChangePasswordModel();

        // Créer un formulaire en utilisant le DTO pour la gestion du changement de mot de passe
        $form = $this->createForm(ChangePasswordFormType::class, $changePasswordModel);
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

            // Rediriger vers la page de profil et afficher un message de succès
            $this->addFlash('success', 'Your password has been changed.');
            return $this->redirectToRoute('app_profile');
        }

        // Rendre la vue 'profile/changePassword.html.twig' avec le formulaire de changement de mot de passe
        return $this->render('profile/changePassword.html.twig', [
            'changePasswordForm' => $form->createView(),
        ]);
    }
}
