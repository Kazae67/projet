<?php

namespace App\Controller;

use App\Entity\Wishlist;
use App\Repository\ProductRepository;
use App\Repository\WishlistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\WishlistService;
use App\Service\CartService;

class WishlistController extends AbstractController
{
    #[Route('/wishlist', name: 'wishlist_index')]
    public function index(WishlistRepository $wishlistRepository): Response
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer les éléments de la liste de souhaits de l'utilisateur
        $wishlistItems = $wishlistRepository->findBy(['user' => $user]);

        // Rendre la vue 'wishlist/index.html.twig' avec les éléments de la liste de souhaits
        return $this->render('wishlist/index.html.twig', [
            'wishlistItems' => $wishlistItems,
        ]);
    }

    #[Route('/wishlist/add/{productId}', name: 'wishlist_add')]
    public function add(int $productId, ProductRepository $productRepository, EntityManagerInterface $em): Response
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer le produit par son ID
        $product = $productRepository->find($productId);
        if (!$product) {
            throw $this->createNotFoundException('Product not found.');
        }

        // Créer une nouvelle entrée dans la liste de souhaits et l'associer à l'utilisateur et au produit
        $wishlist = new Wishlist();
        $wishlist->setUser($user);
        $wishlist->setProduct($product);

        // Persister l'entrée dans la liste de souhaits en base de données
        $em->persist($wishlist);
        $em->flush();

        // Afficher un message de succès et rediriger vers la liste de souhaits
        $this->addFlash('success', 'Product added to your wishlist.');
        return $this->redirectToRoute('wishlist_index');
    }

    #[Route('/wishlist/remove/{id}', name: 'wishlist_remove')]
    public function remove(Wishlist $wishlist, EntityManagerInterface $em): Response
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = $this->getUser();

        // Vérifier que l'utilisateur a le droit de supprimer cet élément de la liste de souhaits
        if ($wishlist->getUser() !== $user) {
            throw $this->createAccessDeniedException('You cannot remove this item.');
        }

        // Supprimer l'élément de la liste de souhaits
        $em->remove($wishlist);
        $em->flush();

        // Afficher un message de succès et rediriger vers la liste de souhaits
        $this->addFlash('success', 'Product removed from your wishlist.');
        return $this->redirectToRoute('wishlist_index');
    }

    #[Route('/wishlist/move-to-cart/{id}', name: 'wishlist_move_to_cart')]
    public function moveToCart($id, WishlistService $wishlistService, CartService $cartService): Response
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = $this->getUser();

        // Déplacer l'élément de la liste de souhaits vers le panier en utilisant le service approprié
        $wishlistService->moveToCart($id, $cartService, $user);

        // Afficher un message de succès et rediriger vers le panier
        $this->addFlash('success', 'Product moved to cart.');
        return $this->redirectToRoute('cart_index');
    }
}
