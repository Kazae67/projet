<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Adress;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use App\Repository\ReviewRepository;
use App\DTO\ChangePasswordModel;
use App\Form\ChangePasswordFormType;
use App\Form\ProfilePictureFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupère l'utilisateur actuellement connecté
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new AccessDeniedException('You must be logged in.');
        }
    
        // Création du formulaire pour la photo de profil
        $profilePictureForm = $this->createForm(ProfilePictureFormType::class);
        $profilePictureForm->handleRequest($request);
    
        if ($profilePictureForm->isSubmitted() && $profilePictureForm->isValid()) {
            /** @var UploadedFile $file */
            $file = $profilePictureForm['image']->getData();
    
            if ($file) {
                $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
    
                try {
                    $file->move($this->getParameter('profile_pictures_directory'), $fileName);
                    $user->setProfilePicture($fileName);
                    $entityManager->persist($user);
                    $entityManager->flush();
    
                    $this->addFlash('success', 'Profile picture updated successfully.');
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload profile picture.');
                }
            }
    
            return $this->redirectToRoute('app_profile');
        }
    
        // Récupérer uniquement les adresses actives de l'utilisateur
        $activeAdresses = $entityManager->getRepository(Adress::class)->findBy([
            'user' => $user,
            'isActive' => true
        ]);
    
        // Rendre la vue 'profile/index.html.twig'
        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'adresses' => $activeAdresses,
            'profilePictureForm' => $profilePictureForm->createView(),
        ]);
    }
    
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }

    #[Route('/profile/delete-picture', name: 'app_profile_delete_picture')]
    public function deleteProfilePicture(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new AccessDeniedException('You must be logged in.');
        }

        $user->setProfilePicture(null);
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Profile picture deleted successfully.');
        return $this->redirectToRoute('app_profile');
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

    #[Route('/user/{id}', name: 'other_profile', requirements: ['id' => '\d+'])]
    public function viewUserProfile($id, UserRepository $userRepository, ProductRepository $productRepository, ReviewRepository $reviewRepository, EntityManagerInterface $entityManager): Response
    {
        if (!is_numeric($id)) {
            error_log("Invalid ID: " . $id); 
            throw $this->createNotFoundException('Invalid user ID');
        }
    
        $id = (int) $id; 
        $user = $userRepository->find($id);
    
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
    
        $products = $productRepository->findBy(['user' => $user]);
        $reviewCount = $reviewRepository->countReviewsByUser($user);
        $lastLogin = $user->getLastLogin();
    
        return $this->render('profile/otherProfile.html.twig', [
            'user' => $user,
            'products' => $products,
            'reviewCount' => $reviewCount,
            'lastLogin' => $lastLogin,
        ]);
    }
}
