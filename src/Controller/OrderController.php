<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Adress;
use App\Entity\OrderDetail;
use App\Form\AdressFormType;
use App\Service\CartService;
use App\Entity\OrderTracking;
use App\Form\OrderConfirmationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class OrderController extends AbstractController
{
    #[Route('/order/prepare', name: 'order_prepare')]
    public function prepareOrder(Request $request, CartService $cartService, EntityManagerInterface $em): Response
    {
        // Vérification si l'utilisateur est connecté
        $user = $this->getUser();
        if (!$user instanceof User) {
            $this->addFlash('error', 'You must be logged in to confirm an order.');
            return $this->redirectToRoute('app_login');
        }

        // Vérification du rôle de l'utilisateur
        if (!in_array('ROLE_CUSTOMER', $user->getRoles()) && !in_array('ROLE_CRAFTSMAN', $user->getRoles())) {
            $this->addFlash('error', 'Only customers and craftsmen can confirm orders.');
            return $this->redirectToRoute('product_index');
        }

        // Obtention du contenu du panier
        $cart = $cartService->getFullCart();
        if (empty($cart)) {
            $this->addFlash('error', 'Your cart is empty.');
            return $this->redirectToRoute('cart_index');
        }

        // Création du formulaire de commande
        $orderForm = $this->createForm(OrderConfirmationFormType::class);
        $orderForm->handleRequest($request);

        if ($orderForm->isSubmitted() && $orderForm->isValid()) {
            $data = $orderForm->getData();

            // Vérifier le stock pour chaque produit dans le panier
            foreach ($cartService->getFullCart() as $item) {
                if ($item['quantity'] > $item['product']->getStockQuantity()) {
                    $this->addFlash('error', 'Sorry, but there\'s not enough stock left for ' . $item['product']->getName() . '.');
                    return $this->redirectToRoute('order_prepare');
                }
            }

            // Vérifier si une adresse est sélectionnée ou saisie
            if (empty($data['selectedAddress']) || ($data['selectedAddress'] === 'new_address' && !$this->isAddressFilled($data))) {
                $this->addFlash('error', 'Please select or fill in an address.');
                return $this->redirectToRoute('order_prepare');
            }

            if ($orderForm->get('saveAddress')->isClicked()) {
                // Logique pour enregistrer l'adresse
                $this->createOrUpdateAddress($user, $data, $em);
                $this->addFlash('success', 'Address saved successfully.');
                return $this->redirectToRoute('order_prepare');
            } elseif ($orderForm->isValid()) {
                // Logique pour confirmer la commande
                $order = new Order();
                $order->setUser($user);
                $order->setStatus('pending');
                $order->generateTrackingToken();

                // Assignation de firstName et lastName
                $order->setFirstName($data['firstName']);
                $order->setLastName($data['lastName']);

                // Gestion des adresses
                if ($data['selectedAddress'] === 'new_address') {
                    $address = $this->createOrUpdateAddress($user, $data, $em);
                } elseif ($data['selectedAddress'] === 'billing_default') {
                    $address = $user->getDefaultBillingAddress();
                } elseif ($data['selectedAddress'] === 'delivery_default') {
                    $address = $user->getDefaultDeliveryAddress();
                } else {
                    $address = null;
                }

                if ($address) {
                    $order->setAddress($address);
                } else {
                    $this->addFlash('error', 'No address selected.');
                    return $this->redirectToRoute('order_prepare');
                }
                // Calcul du montant total de la commande
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

                // Stockage de l'ID de la commande dans la session
                $cartService->setOrderIdInSession($order->getId());

                return $this->redirectToRoute('payment');
            }
        }


        return $this->render('order/confirm.html.twig', [
            'orderForm' => $orderForm->createView(),
            'user' => $user,
            'items' => $cart,
            'total' => $cartService->getTotal(),
        ]);
    }
    // La méthode isAddressFilled vérifie si les champs de l'adresse sont tous remplis lorsque l'option "Use a new address" est sélectionnée.
    private function isAddressFilled($data): bool
    {
        return !empty($data['street']) && !empty($data['city']) && !empty($data['postalCode']) && !empty($data['country']);
    }

    // Méthode auxiliaire pour créer ou mettre à jour l'adresse
    private function createOrUpdateAddress(User $user, array $data, EntityManagerInterface $em): Adress
    {
        $existingAddress = null;
        foreach ($user->getAddresses() as $address) {
            if ($address->getType() === $data['type']) {
                $existingAddress = $address;
                break;
            }
        }

        if ($existingAddress) {
            // Mise à jour de l'adresse existante
            $address = $existingAddress;
        } else {
            // Création d'une nouvelle adresse
            $address = new Adress();
            $address->setUser($user);
        }

        // Mise à jour des données de l'adresse
        $address->setStreet($data['street']);
        $address->setCity($data['city']);
        $address->setState($data['state']);
        $address->setPostalCode($data['postalCode']);
        $address->setCountry($data['country']);
        $address->setType($data['type']);

        // Définition comme adresse par défaut (à voir si je change ou laisse)
        if ($data['type'] === 'billing') {
            $user->setDefaultBillingAddress($address);
        } elseif ($data['type'] === 'delivery') {
            $user->setDefaultDeliveryAddress($address);
        }

        $em->persist($address);
        $em->flush();

        return $address;
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
            // Je dois pas oublier de gérer l'erreur ici
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

            // Créer un suivi de commande
            $tracking = new OrderTracking();
            $tracking->setOrder($order);
            $tracking->setStatus('Order Placed');
            $em->persist($tracking);
            $em->flush();

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

            // Ici, je pourrais vider le panier si nécessaire

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
