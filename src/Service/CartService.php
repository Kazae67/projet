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
    public function add(int $productId)
    {
        $product = $this->productRepository->find($productId);

        if (!$product) {
            throw new \Exception('Product not found.');
        }

        // Vérifier si l'utilisateur actuel est le propriétaire du produit
        if ($product->getUser() === $this->tokenStorage->getToken()->getUser()) {
            throw new \Exception('You cannot add your own product to the cart.');
        }

        $cart = $this->session->get('cart', []);

        if (!empty($cart[$productId])) {
            $cart[$productId]++;
        } else {
            $cart[$productId] = 1;
        }

        $this->session->set('cart', $cart);
    }

    public function remove(int $productId)
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$productId])) {
            if ($cart[$productId] > 1) {
                $cart[$productId]--;
            } else {
                unset($cart[$productId]);
            }
        }

        $this->session->set('cart', $cart);
    }

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

    public function emptyCart()
    {
        $this->session->set('cart', []);
    }

    private function getProductDetails(int $productId)
    {
        return $this->productRepository->find($productId);
    }

}
