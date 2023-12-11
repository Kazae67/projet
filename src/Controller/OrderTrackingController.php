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
    #[Route('/order/tracking/{token}', name: 'order_tracking')]
    public function trackOrder(string $token, OrderRepository $orderRepository, OrderTrackingRepository $orderTrackingRepository): Response
    {
        // Récupère la commande par son token de suivi
        $order = $orderRepository->findOneBy(['trackingToken' => $token]);

        // Vérifie si la commande existe
        if (!$order) {
            $this->addFlash('error', 'Order not found.');
            return $this->redirectToRoute('home');
        }

        // Vérifie si l'utilisateur a le droit de suivre cette commande
        if ($order->getUser() !== $this->getUser()) {
            $this->addFlash('error', 'Access denied.');
            return $this->redirectToRoute('home');
        }

        // Récupère les informations de suivi pour la commande
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
    #[Route('/my-sold-orders', name: 'my_sold_orders')]
    public function mySoldOrders(OrderRepository $orderRepository, OrderTrackingRepository $orderTrackingRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('User not found or not logged in.');
        }

        // Trouver toutes les commandes où les produits de l'utilisateur ont été vendus
        $soldOrders = $orderRepository->findSoldOrdersByUser($user);

        // Récupère les informations de suivi pour ces commandes
        $trackingInfos = [];
        foreach ($soldOrders as $order) {
            $trackings = $orderTrackingRepository->findBy(['order' => $order]);
            $trackingInfos[$order->getId()] = $trackings;
        }

        return $this->render('order/soldOrders.html.twig', [
            'trackingInfos' => $trackingInfos
        ]);
    }

    #[Route('/my-sold-orders', name: 'my_sold_orders')]
    public function listUserSoldOrders(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'You must be logged in to view your sold orders.');
            return $this->redirectToRoute('app_login');
        }

        $soldOrders = $orderRepository->findSoldOrdersByUser($user);

        return $this->render('order/soldOrders.html.twig', [
            'soldOrders' => $soldOrders,
        ]);
    }

    #[Route('/sold-order/tracking/{token}', name: 'sold_order_tracking')]
    public function trackSoldOrder(string $token, OrderRepository $orderRepository, OrderTrackingRepository $orderTrackingRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'You must be logged in to view this.');
            return $this->redirectToRoute('app_login');
        }

        $soldOrder = $orderRepository->findSoldOrderByTokenAndSeller($token, $user);

        if (!$soldOrder) {
            $this->addFlash('error', 'Order not found or access denied.');
            return $this->redirectToRoute('my_sold_orders');
        }

        $trackings = $orderTrackingRepository->findBy(['order' => $soldOrder]);

        return $this->render('order/soldOrderTracking.html.twig', [
            'soldOrder' => $soldOrder,
            'trackings' => $trackings,
        ]);
    }
}
