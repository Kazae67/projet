<?php
namespace App\Service;

use App\Entity\Wishlist;
use App\Repository\ProductRepository;
use App\Repository\WishlistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class WishlistService
{
    private $wishlistRepository;
    private $entityManager;
    private $productRepository;

    public function __construct(WishlistRepository $wishlistRepository, EntityManagerInterface $entityManager, ProductRepository $productRepository)
    {
        $this->wishlistRepository = $wishlistRepository;
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
    }

    // Méthode pour ajouter un produit à la liste de souhaits de l'utilisateur
    public function addProductToWishlist($productId, UserInterface $user)
    {
        $product = $this->productRepository->find($productId);

        if (!$product) {
            throw new \Exception('Product not found.');
        }

        $wishlist = new Wishlist();
        $wishlist->setUser($user);
        $wishlist->setProduct($product);

        $this->entityManager->persist($wishlist);
        $this->entityManager->flush();
    }

    // Méthode pour supprimer un produit de la liste de souhaits de l'utilisateur
    public function removeProductFromWishlist($wishlistId, UserInterface $user)
    {
        $wishlistItem = $this->wishlistRepository->find($wishlistId);

        if (!$wishlistItem || $wishlistItem->getUser() !== $user) {
            throw new \Exception('Wishlist item not found or access denied.');
        }

        $this->entityManager->remove($wishlistItem);
        $this->entityManager->flush();
    }

    // Méthode pour déplacer un produit de la liste de souhaits vers le panier
    public function moveToCart($wishlistId, CartService $cartService, UserInterface $user)
    {
        // Recherche de l'élément de la liste de souhaits par son identifiant
        $wishlistItem = $this->wishlistRepository->find($wishlistId);

        // Vérification que l'élément de la liste de souhaits existe et appartient à l'utilisateur connecté
        if ($wishlistItem && $wishlistItem->getUser() === $user) {
            // Ajout du produit au panier en utilisant le service CartService
            $cartService->add($wishlistItem->getProduct()->getId());

            // Suppression de l'élément de la liste de souhaits de la base de données
            $this->entityManager->remove($wishlistItem);
            $this->entityManager->flush();
        } else {
            // Lancement d'une exception si l'élément n'est pas trouvé ou si l'accès est refusé
            throw new \Exception('Wishlist item not found or access denied.');
        }
    }
    public function clearWishlist(UserInterface $user)
    {
        $wishlistItems = $this->wishlistRepository->findBy(['user' => $user]);

        foreach ($wishlistItems as $item) {
            $this->entityManager->remove($item);
        }

        $this->entityManager->flush();
    }

    public function moveAllToCart(CartService $cartService, UserInterface $user)
    {
        $wishlistItems = $this->wishlistRepository->findBy(['user' => $user]);

        foreach ($wishlistItems as $item) {
            $cartService->add($item->getProduct()->getId());
            $this->entityManager->remove($item);
        }

        $this->entityManager->flush();
    }


}
