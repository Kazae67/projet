<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Entity\Order;
use App\Entity\Review;
use App\Repository\ProductRepository;
use App\Repository\OrderRepository;
use App\Repository\ReviewRepository;
use App\Repository\AdressRepository;
use App\Form\PasswordConfirmationFormType;
use App\DTO\PasswordConfirmationModel;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;




class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà connecté, rediriger vers l'accueil
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // Récupérer l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // Récupérer le dernier nom d'utilisateur saisi
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
        // La logique de déconnexion sera gérée automatiquement par Symfony donc je laisse vide
    }

    #[Route('/user/anonymize', name: 'user_anonymize', methods: ['POST'])]
    public function anonymizeUser(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to anonymize your account.');
        }

        if ($this->isCsrfTokenValid('anonymize_user', $request->request->get('_token'))) {
            // Anonymiser les adresses liées à l'utilisateur
            $addresses = $em->getRepository(Adress::class)->findBy(['user' => $user]);
            foreach ($addresses as $address) {
                $address->setStreet('Anonymized');
                $address->setCity('Anonymized');
                $address->setState('Anonymized');
                $address->setPostalCode('00000');
                $address->setCountry('Anonymized');
                $em->persist($address);
            }

            // Anonymiser les commandes liées à l'utilisateur
            $orders = $em->getRepository(Order::class)->findBy(['user' => $user]);
            foreach ($orders as $order) {
                $order->setFirstName('Anonymized');
                $order->setLastName('Anonymized');
                $em->persist($order);
            }

            // Anonymiser les avis laissés par l'utilisateur
            $reviews = $em->getRepository(Review::class)->findBy(['user' => $user]);
            foreach ($reviews as $review) {
                $review->setTitle('Anonymized Review');
                $review->setComment('This review has been anonymized.');
                $em->persist($review);
            }

            // Remplacer les données personnelles de l'utilisateur par des valeurs génériques
            $user->setUsername('DeletedUser' . rand(1000, 9999));
            $user->setEmail('anonyme' . rand(1000, 9999) . '@example.com');

            // Mettre à jour l'utilisateur dans la base de données
            $em->persist($user);
            $em->flush();

            // Déconnecter l'utilisateur après l'anonymisation
            return $this->redirectToRoute('app_logout');
        }

        return $this->redirectToRoute('app_profile');
    }

    #[Route('/user/confirm-password', name: 'user_confirm_password', methods: ['POST'])]
    public function confirmPassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em, ProductRepository $productRepository, AdressRepository $addressRepository, OrderRepository $orderRepository, ReviewRepository $reviewRepository): JsonResponse
    {
        $user = $this->getUser();
        if (!$user || !$user instanceof User) {
            return new JsonResponse(['success' => false, 'message' => 'You must be logged in.']);
        }
        $data = json_decode($request->getContent(), true);

        // Vérifier si le mot de passe est correct
        if (!$passwordHasher->isPasswordValid($user, $data['password'])) {
            return new JsonResponse(['success' => false, 'message' => 'Incorrect password.']);
        }

        // Supprimer tous les produits associés à l'utilisateur
        $products = $productRepository->findBy(['user' => $user]);
        foreach ($products as $product) {
            $em->remove($product);
        }

        // Anonymiser les adresses liées à l'utilisateur
        $addresses = $addressRepository->findBy(['user' => $user]);
        foreach ($addresses as $address) {
            $address->setStreet('Anonymized');
            $address->setCity('Anonymized');
            $address->setState('Anonymized');
            $address->setPostalCode('00000');
            $address->setCountry('Anonymized');
            $em->persist($address);
        }

        // Anonymiser les commandes liées à l'utilisateur
        $orders = $orderRepository->findBy(['user' => $user]);
        foreach ($orders as $order) {
            $order->setFirstName('Anonymized');
            $order->setLastName('Anonymized');
            $em->persist($order);
        }

        // Anonymiser les avis laissés par l'utilisateur
        $reviews = $reviewRepository->findBy(['user' => $user]);
        foreach ($reviews as $review) {
            $review->setTitle('Anonymized Review');
            $review->setComment('This review has been anonymized.');
            $em->persist($review);
        }

        // Remplacer les données personnelles de l'utilisateur par des valeurs génériques
        $user->setUsername('DeletedUser' . rand(1000, 9999));
        $user->setEmail('anonyme' . rand(1000, 9999) . '@example.com');

        // Mettre à jour l'utilisateur dans la base de données
        $em->persist($user);
        $em->flush();

        // Déconnecter l'utilisateur après l'anonymisation
        return new JsonResponse(['success' => true, 'redirectUrl' => $this->generateUrl('app_logout')]);
    }
}