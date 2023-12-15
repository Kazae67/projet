<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Entity\Order;
use App\Entity\Review;
use App\Repository\ProductRepository;
use App\Repository\OrderRepository;
use App\Repository\OrderDetailRepository;
use App\Repository\ReviewRepository;
use App\Repository\AdressRepository;
use App\Form\PasswordConfirmationFormType;
use App\DTO\PasswordConfirmationModel;
use App\Entity\OrderDetail;
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
            //  $em->remove($product);
            $product->setPrice(0);
            $product->setStockQuantity(0);
            $product->setActive(false);

         }

        // Anonymiser les adresses liées à l'utilisateur
        $addresses = $addressRepository->findBy(['user' => $user]);
        foreach ($addresses as $address) {
            $address->setStreet('Anonymized');
            $address->setCity('Anonymized');
            $address->setState('Anonymized');
            $address->setPostalCode('XX-000');
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
        // $reviews = $reviewRepository->findBy(['user' => $user]);
        // foreach ($reviews as $review) {
        //     $review->setTitle('Anonymized Review');
        //     $review->setComment('This review has been anonymized.');
        //     $em->persist($review);
        // }

        // Remplacer les données personnelles de l'utilisateur par des valeurs génériques
        $user->setUsername('DeletedUser' . rand(1000, 9999));
        $user->setEmail('anonyme' . rand(1000, 9999) . '@xxx.x');
        $user->setIsActivated(0);
        $user->setProfilePicture('anonyme.png');
        // Mettre à jour l'utilisateur dans la base de données
        $em->persist($user);
        $em->flush();

        // Déconnecter l'utilisateur après l'anonymisation
        return new JsonResponse(['success' => true, 'redirectUrl' => $this->generateUrl('app_logout')]);
    }
}