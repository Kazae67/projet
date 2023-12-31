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
            // Vérifiez si l'utilisateur a accepté les termes et conditions
            if ($form->get('agreeTerms')->getData() !== true) {
                $this->addFlash('error', 'You must agree to the terms and conditions to register.');
                return $this->redirectToRoute('app_register');
            }

            // Hash le mot de passe de l'utilisateur
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Générer un jeton de confirmation et sa date d'expiration
            $confirmationToken = bin2hex(random_bytes(32));
            $user->setConfirmationToken($confirmationToken);
            $expirationDate = new \DateTimeImmutable('+1 minute');
            $user->setConfirmationTokenExpiresAt($expirationDate);

            // Persister l'utilisateur
            $entityManager->persist($user);
            $entityManager->flush();

            // Création de l'email de confirmation
            $confirmationUrl = $this->generateUrl('app_confirm', ['token' => $confirmationToken], UrlGeneratorInterface::ABSOLUTE_URL);
            $email = (new Email())
                ->from('noreply@yourdomain.com')
                ->to($user->getEmail())
                ->subject('Confirmation of your registration.')
                ->text('Please confirm your registration by clicking on this link: ' . $confirmationUrl);

            $mailer->send($email);

            // Ajouter un message flash
            $this->addFlash('notice', 'A confirmation email has been sent. You have 1 minute to activate your account.');

            // Redirect sur login après l'enregistrement
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    // Route pour la confirmation de l'utilisateur avec un token.
    #[Route('/confirm/{token}', name: 'app_confirm')]
    public function confirmUser(string $token, EntityManagerInterface $entityManager): Response
    {
        // Recherche de l'utilisateur par le token de confirmation.
        $user = $entityManager->getRepository(User::class)->findOneBy(['confirmationToken' => $token]);

        // Vérifie si l'utilisateur existe et si le token est valide.
        if ($user !== null) {
            $now = new \DateTimeImmutable();
            // Vérification de la validité du token.
            if ($user->getConfirmationTokenExpiresAt() <= $now) {
                // Si le token a expiré, informer l'utilisateur et rediriger vers la page de connexion.
                $this->addFlash('error', 'The confirmation link has expired.');
                return $this->redirectToRoute('app_login');
            }

            // Activation du compte si le token est toujours valide et si le compte n'est pas déjà activé.
            if (!$user->getIsActivated()) {
                $user->setIsActivated(true);
                $user->setConfirmationToken(null);
                $user->setConfirmationTokenExpiresAt(null);
                $entityManager->flush(); // Sauvegarde des modifications dans la base de données.

                // Informer l'utilisateur que son compte a été activé et rediriger vers la page de connexion.
                $this->addFlash('success', 'Your account has been successfully activated.');
                return $this->redirectToRoute('app_login');
            }
        }

        // Si le token n'est pas valide ou déjà utilisé, informer l'utilisateur et rediriger vers la page d'accueil.
        $this->addFlash('error', 'The confirmation link is invalid or already used.');
        return $this->redirectToRoute('app_home');
    }
    #[Route('/resend-confirmation', name: 'app_resend_confirmation', methods: ['POST'])]
    public function resendConfirmation(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, UrlGeneratorInterface $urlGenerator): Response
    {
        $email = $request->request->get('email');
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($user && !$user->getIsActivated()) {
            // Générer un nouveau jeton de confirmation et sa date d'expiration
            $confirmationToken = bin2hex(random_bytes(32));
            $user->setConfirmationToken($confirmationToken);
            $expirationDate = new \DateTimeImmutable('+1 minute');
            $user->setConfirmationTokenExpiresAt($expirationDate);

            $entityManager->flush();

            // Renvoyer l'e-mail de confirmation
            $confirmationUrl = $urlGenerator->generate('app_confirm', ['token' => $confirmationToken], UrlGeneratorInterface::ABSOLUTE_URL);
            $email = (new Email())
                ->from('noreply@yourdomain.com')
                ->to($user->getEmail())
                ->subject('Confirmation of your registration.')
                ->text('Please confirm your registration by clicking on this link: ' . $confirmationUrl);

            $mailer->send($email);

            $this->addFlash('notice', 'A new confirmation email has been sent.');
        } else {
            $this->addFlash('error', 'Account already activated or email not found.');
        }

        return $this->redirectToRoute('app_login');
    }
}