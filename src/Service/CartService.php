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
