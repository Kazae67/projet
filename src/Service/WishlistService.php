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

    public function removeProductFromWishlist($wishlistId, UserInterface $user)
    {
        $wishlistItem = $this->wishlistRepository->find($wishlistId);

        if (!$wishlistItem || $wishlistItem->getUser() !== $user) {
            throw new \Exception('Wishlist item not found or access denied.');
        }

        $this->entityManager->remove($wishlistItem);
        $this->entityManager->flush();
    }

    public function moveToCart($wishlistId, CartService $cartService, UserInterface $user)
    {
        $wishlistItem = $this->wishlistRepository->find($wishlistId);

        if ($wishlistItem && $wishlistItem->getUser() === $user) {
            $cartService->add($wishlistItem->getProduct()->getId());

            $this->entityManager->remove($wishlistItem);
            $this->entityManager->flush();
        } else {
            throw new \Exception('Wishlist item not found or access denied.');
        }
    }
}
