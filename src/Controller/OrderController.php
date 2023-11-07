<?php

// src/Controller/OrderController.php

namespace App\Controller;

use App\Form\OrderConfirmationFormType;
use App\Service\CartService;
use App\Entity\Order;
use App\Entity\OrderDetail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order/confirm', name: 'order_confirm')]
    public function confirmOrder(Request $request, CartService $cartService, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('User not found or not logged in.');
        }

        $cart = $cartService->getFullCart();
        if (empty($cart)) {
            $this->addFlash('error', 'Your cart is empty.');
            return $this->redirectToRoute('cart_index');
        }

        $form = $this->createForm(OrderConfirmationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = new Order();
            $order->setUser($user);
            $order->setStatus('pending');

            $total = 0;
            foreach ($cart as $item) {
                $orderDetail = new OrderDetail();
                $orderDetail->setOrder($order);
                $orderDetail->setProduct($item['product']);
                $orderDetail->setQuantity($item['quantity']);
                $orderDetail->setPrice($item['product']->getPrice());
                $em->persist($orderDetail);

                $total += $item['product']->getPrice() * $item['quantity'];
            }

            $order->setTotalPrice((string) $total);
            $em->persist($order);

            $cartService->emptyCart();
            $em->flush();

            $this->addFlash('success', 'Your order has been successfully confirmed.');
            return $this->redirectToRoute('order_thank_you');
        }

        return $this->render('order/confirm.html.twig', [
            'form' => $form->createView(),
            'items' => $cart,
            'total' => $cartService->getTotal(),
        ]);
    }

    #[Route('/order/thank-you', name: 'order_thank_you')]
    public function thankYou(): Response
    {
        return $this->render('order/thankYou.html.twig');
    }
}
