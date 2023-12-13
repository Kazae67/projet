<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CartService
{
    private $session;
    private $productRepository;
    private $tokenStorage;
    private $authorizationChecker;

    public function __construct(SessionInterface $session, ProductRepository $productRepository, TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
    }

    // Méthode pour ajouter un produit au panier
    public function add(int $productId)
    {
        // Récupérer le produit depuis le référentiel
        $product = $this->productRepository->find($productId);

        if (!$product) {
            throw new \Exception('Product not found.');
        }

        // Vérifier si l'utilisateur est connecté
        if (!$this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw new \Exception('You must be logged in to add products to the cart.');
        }

        // Vérifier si l'utilisateur est le propriétaire du produit
        if ($product->getUser() === $this->tokenStorage->getToken()->getUser()) {
            throw new \Exception('You cannot add your own product to the cart.');
        }

        // Récupérer le panier de la session
        $cart = $this->session->get('cart', []);

        // Augmenter la quantité du produit ou l'ajouter au panier
        if (!empty($cart[$productId])) {
            $cart[$productId]++;
        } else {
            $cart[$productId] = 1;
        }

        // Mettre à jour le panier dans la session
        $this->session->set('cart', $cart);
    }

    // Méthode pour supprimer un produit du panier
    public function remove(int $productId)
    {
        $cart = $this->session->get('cart', []);
        if (!empty($cart[$productId])) {
                unset($cart[$productId]);
            }
        $this->session->set('cart', $cart);
    }

    // Méthode pour obtenir le panier complet
    public function getFullCart(): array
    {
        $fullCart = [];
        $cart = $this->session->get('cart', []);

        foreach ($cart as $id => $quantity) {
            $product = $this->getProductDetails($id);
            if ($product) {
                $fullCart[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
            }
        }

        return $fullCart;
    }

    // Méthode pour obtenir le total du panier
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

    // Méthode pour vider le panier
    public function emptyCart()
    {
        $this->session->set('cart', []);
    }

    // Méthode privée pour obtenir les détails d'un produit par son ID
    private function getProductDetails(int $productId)
    {
        return $this->productRepository->find($productId);
    }

    // Méthode pour définir l'ID de commande dans la session
    public function setOrderIdInSession($orderId)
    {
        $this->session->set('orderId', $orderId);
    }

    public function saveCartToCookie()
    {
        $cart = $this->session->get('cart', []);
        $cookieValue = json_encode($cart);
        // Set cookie with a specific expiration, e.g., 30 days
        setcookie('cart', $cookieValue, time() + (86400 * 30), "/");
    }

    // Méthode pour augmenter la quantité d'un produit dans le panier
    public function increment(int $productId)
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$productId])) {
            $cart[$productId]++;
        } else {
            $cart[$productId] = 1; // Si le produit n'est pas déjà dans le panier, l'ajouter
        }

        $this->session->set('cart', $cart);
    }

    // Méthode pour diminuer la quantité d'un produit dans le panier
    public function decrement(int $productId)
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$productId])) {
            if ($cart[$productId] > 1) {
                $cart[$productId]--;
            } else {
                unset($cart[$productId]); // Si la quantité devient 0, supprimer le produit du panier
            }
        }

        $this->session->set('cart', $cart);
    }

    public function loadCartFromCookie()
    {
        if (isset($_COOKIE['cart'])) {
            $cart = json_decode($_COOKIE['cart'], true);
            $this->session->set('cart', $cart);
        }
    }
}
