<?php

namespace App\Controller;

use App\Form\OrderConfirmationFormType;
use App\Form\AdressFormType;
use App\Service\CartService;
use App\Entity\Order;
use App\Entity\User;
use App\Entity\OrderDetail;
use App\Entity\Adress;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class OrderController extends AbstractController
{
    #[Route('/order/prepare', name: 'order_prepare')]
    public function prepareOrder(Request $request, CartService $cartService, EntityManagerInterface $em): Response
    {
        // Vérifier si l'utilisateur est connecté
        $user = $this->getUser();
        if (!$user instanceof User) {
            $this->addFlash('error', 'You must be logged in to confirm an order.');
            return $this->redirectToRoute('app_login');
        }

        // Vérifier le rôle de l'utilisateur (doit être un client ou un artisan)
        if (!in_array('ROLE_CUSTOMER', $user->getRoles()) && !in_array('ROLE_CRAFTSMAN', $user->getRoles())) {
            $this->addFlash('error', 'Only customers and craftsmen can confirm orders.');
            return $this->redirectToRoute('product_index');
        }

        // Obtenir le contenu complet du panier
        $cart = $cartService->getFullCart();
        if (empty($cart)) {
            $this->addFlash('error', 'Your cart is empty.');
            return $this->redirectToRoute('cart_index');
        }

        // Créer le formulaire de commande
        $orderForm = $this->createForm(OrderConfirmationFormType::class);
        $orderForm->handleRequest($request);

        // Créer l'objet Adresse et le formulaire correspondant
        $address = new Adress();
        $addressForm = $this->createForm(AdressFormType::class, $address);
        $addressForm->handleRequest($request);

        // Traitement du formulaire de commande
        if ($orderForm->isSubmitted() && $orderForm->isValid()) {
            // Créer une nouvelle commande
            $order = new Order();
            $order->setUser($user);
            $order->setStatus('pending');

            // Calculer le montant total de la commande
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
            $em->flush();

            // Stocker l'ID de la commande dans la session
            $cartService->setOrderIdInSession($order->getId());

            // Rediriger vers la page de paiement si le formulaire d'adresse n'a pas été soumis
            if (!$addressForm->isSubmitted()) {
                return $this->redirectToRoute('payment');
            }
        }

        // Traitement du formulaire d'adresse
        if ($addressForm->isSubmitted() && $addressForm->isValid()) {
            $this->handleAddressSubmission($address, $user, $em);
            $this->addFlash('success', 'Address saved successfully.');
            // Redirection éventuelle vers une autre page

        }

        // Rendre la vue 'order/confirm.html.twig' avec les données nécessaires
        return $this->render('order/confirm.html.twig', [
            'orderForm' => $orderForm->createView(),
            'addressForm' => $addressForm->createView(),
            'user' => $user,
            'items' => $cart,
            'total' => $cartService->getTotal(),
        ]);
    }

    // Méthode auxiliaire pour gérer la soumission d'adresse
    private function handleAddressSubmission(Adress $address, User $user, EntityManagerInterface $em): void
    {
        // Recherche d'une adresse existante du même type (facturation ou livraison)
        $existingAddress = $this->findExistingAddressOfType($user, $address->getType());

        if ($existingAddress) {
            // Mise à jour de l'adresse existante
            $existingAddress->updateFromOther($address);
            $em->persist($existingAddress);
        } else {
            // Création d'une nouvelle adresse
            $address->setUser($user);
            $em->persist($address);
        }

        // Mise à jour des adresses par défaut en fonction du type
        if ($address->getType() === 'billing') {
            $user->setDefaultBillingAddress($existingAddress ?: $address);
        } elseif ($address->getType() === 'delivery') {
            $user->setDefaultShippingAddress($existingAddress ?: $address);
        }

        $em->flush();
    }

    // Méthode auxiliaire pour trouver une adresse existante du même type
    private function findExistingAddressOfType(User $user, string $type): ?Adress
    {
        foreach ($user->getAddresses() as $existingAddr) {
            if ($existingAddr->getType() === $type) {
                return $existingAddr;
            }
        }
        return null;
    }

    #[Route('/payment', name: 'payment')]
    public function payment(Request $request, EntityManagerInterface $em): Response
    {
        // Récupérer l'ID de la commande à partir de la requête
        $order_id = $request->query->get('order_id');
        // Rechercher la commande correspondante dans la base de données
        $order = $em->getRepository(Order::class)->find($order_id);

        // Débogage : Afficher les valeurs
        dump($order_id, $order);

        if (!$order) {
            // Gérer l'erreur ici, par exemple, rediriger ou afficher un message d'erreur
        }

        // Rendre la vue 'payment/index.html.twig' avec les données nécessaires
        return $this->render('payment/index.html.twig', [
            'stripe_public_key' => $this->getParameter('stripe.public_key'),
            'order' => $order
        ]);
    }

    #[Route('/order/confirm/{order_id}', name: 'order_confirm')]
    public function confirmOrder(int $order_id, EntityManagerInterface $em): Response
    {
        // Confirmer la commande en mettant à jour son statut
        $order = $em->getRepository(Order::class)->find($order_id);
        if ($order) {
            $order->setStatus('confirmed');

            // Parcours chaque détail de la commande pour mettre à jour le stock
            foreach ($order->getOrderDetails() as $orderDetail) {
                $product = $orderDetail->getProduct();
                $currentStock = $product->getStockQuantity();
                $quantityOrdered = $orderDetail->getQuantity();
                $newStock = $currentStock - $quantityOrdered;

                // Débogage: Afficher les valeurs
                dd([
                    'Product ID' => $product->getId(),
                    'Current Stock' => $currentStock,
                    'Quantity Ordered' => $quantityOrdered,
                    'New Stock' => $newStock
                ]);

                // S'assurer que le stock ne devient pas négatif
                $product->setStockQuantity(max($newStock, 0));
                $em->persist($product);
            }

            $em->flush();

            // Ici, tu pourrais vider le panier si nécessaire

            $this->addFlash('success', 'Your order has been successfully confirmed.');
            return $this->redirectToRoute('order_thank_you');
        } else {
            $this->addFlash('error', 'Order not found.');
            return $this->redirectToRoute('cart_index');
        }
    }

    #[Route('/order/thank-you', name: 'order_thank_you')]
    public function thankYou(): Response
    {
        return $this->render('order/thankYou.html.twig');
    }
}
