<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash le mot de passe de l'utilisateur
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Générer un jeton de confirmation
            $confirmationToken = bin2hex(random_bytes(32));
            $user->setConfirmationToken($confirmationToken);

            // Persister l'utilisateur
            $entityManager->persist($user);
            $entityManager->flush();

            // Création de l'email de confirmation
            $confirmationUrl = $this->generateUrl('app_confirm', ['token' => $confirmationToken], UrlGeneratorInterface::ABSOLUTE_URL);
            $email = (new Email())
                ->from('noreply@yourdomain.com')
                ->to($user->getEmail()) // Utilise l'adresse e-mail de l'utilisateur enregistré
                ->subject('Confirmation de votre inscription')
                ->text('Veuillez confirmer votre inscription en cliquant sur ce lien: ' . $confirmationUrl);

            $mailer->send($email);

            // Redirect sur login après l'enregistrement
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/confirm/{token}', name: 'app_confirm')]
    public function confirmUser(string $token, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(['confirmationToken' => $token]);

        if ($user !== null && !$user->getIsActivated()) {
            $user->setIsActivated(true);
            $user->setConfirmationToken(null);
            $entityManager->flush();

            // Ajouter un message de succès ou rediriger l'utilisateur
            $this->addFlash('success', 'Votre compte a été activé avec succès.');
            return $this->redirectToRoute('app_login');
        }

        // Gérer le cas où le jeton n'est pas valide ou l'utilisateur est déjà activé
        $this->addFlash('error', 'Le lien de confirmation est invalide ou expiré.');
        return $this->redirectToRoute('app_home');
    }
}
