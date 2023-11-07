<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private $session;
    private $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    public function add(int $productId)
    {
        // Récupération du panier dans la session
        $cart = $this->session->get('cart', []);

        // incrémenter le produit dans le panier
        if (!empty($cart[$productId])) {
            $cart[$productId]++;
        } else {
            $cart[$productId] = 1;
        }

        // Sauvegarde du panier dans la session
        $this->session->set('cart', $cart);
    }

    public function remove(int $productId)
    {
        // Récupération du panier dans la session
        $cart = $this->session->get('cart', []);

        // Supprimer le produit du panier
        if (!empty($cart[$productId])) {
            if ($cart[$productId] > 1) {
                $cart[$productId]--;
            } else {
                unset($cart[$productId]);
            }
        }

        // Sauvegarde du panier dans la session
        $this->session->set('cart', $cart);
    }

    public function getFullCart(): array
    {
        // Initialise un tableau pour le panier complet
        $fullCart = [];

        // Récupère le panier de la session
        $cart = $this->session->get('cart', []);

        // Pour chaque produit dans le panier, récupère les détails du produit
        foreach ($cart as $id => $quantity) {
            $productDetails = $this->getProductDetails($id);
            if ($productDetails) {
                $fullCart[] = [
                    'product' => $productDetails,
                    'quantity' => $quantity
                ];
            }
        }

        return $fullCart;
    }

    public function getTotal(): float
    {
        $total = 0.0;
        $fullCart = $this->getFullCart();

        foreach ($fullCart as $item) {
            if ($item['product'] !== null) {
                $total += $item['product']->getPrice() * $item['quantity'];
            }
        }

        return $total;
    }

    private function getProductDetails(int $productId)
    {
        return $this->productRepository->find($productId);
    }

}
