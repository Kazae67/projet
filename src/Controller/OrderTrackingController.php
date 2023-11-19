<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Repository\OrderTrackingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderTrackingController extends AbstractController
{
    #[Route('/order/tracking/{orderId}', name: 'order_tracking')]
    public function trackOrder(int $orderId, OrderRepository $orderRepository, OrderTrackingRepository $orderTrackingRepository): Response
    {
        // Récupérer la commande par son ID
        $order = $orderRepository->find($orderId);

        // Vérifier si l'utilisateur a le droit de suivre cette commande
        if (!$order || $order->getUser() !== $this->getUser()) {
            // Si la commande n'existe pas ou si l'utilisateur actuel n'est pas le propriétaire de la commande
            $this->addFlash('error', 'Order not found or access denied.');
            return $this->redirectToRoute('home');
        }

        // Récupérer les informations de suivi pour la commande
        $trackings = $orderTrackingRepository->findBy(['order' => $order]);

        // Rendre la page de suivi avec les informations de la commande et son suivi
        return $this->render('order/tracking.html.twig', [
            'order' => $order,
            'trackings' => $trackings,
        ]);
    }

    #[Route('/order/tracking', name: 'order_tracking_list')]
    public function listUserOrders(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'You must be logged in to view your orders.');
            return $this->redirectToRoute('app_login');
        }

        $orders = $orderRepository->findBy(['user' => $user]);

        return $this->render('order/trackingList.html.twig', [
            'orders' => $orders,
        ]);
    }
}
