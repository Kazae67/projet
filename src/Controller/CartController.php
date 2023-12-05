<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CartService;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart_index')]
    public function index(CartService $cartService): Response
    {
        // Charger le panier à partir du cookie si disponible
        $cartService->loadCartFromCookie();

        // Utiliser le service CartService pour obtenir le contenu complet du panier
        $cart = $cartService->getFullCart();

        // Utiliser le service CartService pour obtenir le montant total du panier
        $total = $cartService->getTotal();

        // Rendre la vue 'cart/index.html.twig' en passant les données du panier et le montant total
        return $this->render('cart/index.html.twig', [
            'items' => $cart,
            'total' => $total,
        ]);
    }

    #[Route('/cart/add/{productId}', name: 'cart_add')]
    public function add(int $productId, CartService $cartService): Response
    {
        // Ajouter le produit au panier
        $cartService->add($productId);

        // Sauvegarder le panier dans un cookie (voir tarte au citron avant)
        $cartService->saveCartToCookie();

        $this->addFlash('success', 'Product added to your cart.');
        // Rediriger vers la page du panier
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/remove/{productId}', name: 'cart_remove')]
    public function remove(int $productId, CartService $cartService): Response
    {
        // Supprimer le produit du panier
        $cartService->remove($productId);

        // Sauvegarder le panier dans un cookie (j'vais devoir ajouter tarte au citron avant de mieux gérer ça)
        $cartService->saveCartToCookie();

        $this->addFlash('success', 'Product removed from your cart.');

        // Rediriger vers la page du panier
        return $this->redirectToRoute('cart_index');
    }
    #[Route('/cart/increment/{productId}', name: 'cart_increment')]
    public function increment(int $productId, CartService $cartService): Response
    {
        $cartService->increment($productId);
        $cartService->saveCartToCookie();

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/decrement/{productId}', name: 'cart_decrement')]
    public function decrement(int $productId, CartService $cartService): Response
    {
        $cartService->decrement($productId);
        $cartService->saveCartToCookie();

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/clear', name: 'cart_clear')]
    public function clearCart(CartService $cartService): Response
    {
        $cartService->emptyCart();
        $this->addFlash('success', 'Your cart has been cleared.');
        return $this->redirectToRoute('cart_index');
    }
}
