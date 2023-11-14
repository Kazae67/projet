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
}
