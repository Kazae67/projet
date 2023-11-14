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
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $wishlistItems = $wishlistRepository->findBy(['user' => $user]);

        return $this->render('wishlist/index.html.twig', [
            'wishlistItems' => $wishlistItems,
        ]);
    }

    #[Route('/wishlist/add/{productId}', name: 'wishlist_add')]
    public function add(int $productId, ProductRepository $productRepository, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $product = $productRepository->find($productId);
        if (!$product) {
            throw $this->createNotFoundException('Product not found.');
        }

        $wishlist = new Wishlist();
        $wishlist->setUser($user);
        $wishlist->setProduct($product);

        $em->persist($wishlist);
        $em->flush();

        $this->addFlash('success', 'Product added to your wishlist.');
        return $this->redirectToRoute('wishlist_index');
    }

    #[Route('/wishlist/remove/{id}', name: 'wishlist_remove')]
    public function remove(Wishlist $wishlist, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if ($wishlist->getUser() !== $user) {
            throw $this->createAccessDeniedException('You cannot remove this item.');
        }

        $em->remove($wishlist);
        $em->flush();

        $this->addFlash('success', 'Product removed from your wishlist.');
        return $this->redirectToRoute('wishlist_index');
    }

    #[Route('/wishlist/move-to-cart/{id}', name: 'wishlist_move_to_cart')]
    public function moveToCart($id, WishlistService $wishlistService, CartService $cartService): Response
    {
        $user = $this->getUser();
        $wishlistService->moveToCart($id, $cartService, $user);
        $this->addFlash('success', 'Product moved to cart.');
        return $this->redirectToRoute('cart_index');
    }
}
