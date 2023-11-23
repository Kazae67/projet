<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Adress;
use App\Entity\OrderDetail;
use App\Service\CartService;
use App\Entity\OrderTracking;
use App\Form\OrderConfirmationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('/order/prepare', name: 'order_prepare')]
    public function prepareOrder(Request $request, CartService $cartService, EntityManagerInterface $em): Response
    {
        /* Validation de l'authentification & de l'autorisation de l'utilisateur */
        // Vérification si l'utilisateur est connecté (1)
        $user = $this->getUser();
        if (!$user instanceof User) {
            $this->addFlash('error', 'You must be logged in to confirm an order.');
            return $this->redirectToRoute('app_login');
        }

        // Vérification du rôle de l'utilisateur (2)
        if (!in_array('ROLE_CUSTOMER', $user->getRoles()) && !in_array('ROLE_CRAFTSMAN', $user->getRoles())) {
            $this->addFlash('error', 'Only customers and craftsmen can confirm orders.');
            return $this->redirectToRoute('product_index');
        }

        /* Validation de l'état de la session utilisateur. 
        Cette vérification s'assure que l'utilisateur a des articles dans son panier avant de lui permettre de procéder à une commande. 
        Si le panier est vide, l'utilisateur est redirigé et informé par un message d'erreur, empêchant ainsi la création d'une commande vide.*/
        // Vérification du Contenu du Panier
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

            /* Validation/vérification du stock disponible pour chaque produit dans le panier */
            // Vérifier le stock pour chaque produit dans le panier (1)
            foreach ($cartService->getFullCart() as $item) {
                if ($item['quantity'] > $item['product']->getStockQuantity()) {
                    $this->addFlash('error', 'Sorry, but there\'s not enough stock left for ' . $item['product']->getName() . '.');
                    return $this->redirectToRoute('order_prepare');
                }
            }

            /* Validation/vérification des données de si une adresse est sélectionnée ou saisie */
            // Vérifier si une adresse est sélectionnée ou saisie (1)
            if (empty($data['selectedAddress']) || ($data['selectedAddress'] === 'new_address' && !$this->isAddressFilled($data))) {
                $this->addFlash('error', 'Please select or fill in an address.');
                return $this->redirectToRoute('order_prepare');
            }

            /* Gestion de l'état du formulaire, validation, et redirections sécurisées. 
            Cette section vérifie quelle action de formulaire est activée et redirige ou traite en conséquence, 
            ce qui prévient les soumissions de formulaire non valides ou inappropriées */
            // Traitement de la Soumission du Formulaire (1)
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

                /* La logique pour créer ou mettre à jour l'adresse, en fonction du type sélectionné par l'utilisateur, 
                est une manière de s'assurer que les données associées à l'utilisateur sont cohérentes */
                // Gestion des adresses(1)
                if ($data['selectedAddress'] === 'new_address') {
                    $address = $this->createOrUpdateAddress($user, $data, $em);
                } elseif ($data['selectedAddress'] === 'billing_default') {
                    $address = $user->getDefaultBillingAddress();
                    // Vérifier si l'adresse par défaut de facturation est active
                    if (!$address || !$address->getIsActive()) {
                        $address = null;
                    }
                } elseif ($data['selectedAddress'] === 'delivery_default') {
                    $address = $user->getDefaultDeliveryAddress();
                    // Vérifier si l'adresse par défaut de livraison est active
                    if (!$address || !$address->getIsActive()) {
                        $address = null;
                    }
                } else {
                    $address = null;
                }

                /* Validation des données utilisateur. 
                S'assure qu'une adresse a été correctement sélectionnée avant de poursuivre avec la création de la commande. 
                En l'absence d'une adresse valide, elle renvoie l'utilisateur à l'étape précédente avec un message d'erreur, 
                empêchant ainsi toute progression non sécurisée dans le processus de commande */
                // Vérification de l'Adresse Sélectionnée (1)
                if ($address) {
                    $order->setAddress($address);
                } else {
                    /* Gestion d'erreur */
                    // En cas d'absence d'adresse sélectionnée(1)
                    $this->addFlash('error', 'No address selected.');
                    return $this->redirectToRoute('order_prepare');
                }

                /* Validation et intégrité des données de commande. 
                Le calcul du total assure que le montant total de la commande reflète correctement les produits et leurs quantités dans le panier. 
                Cette étape est essentielle pour maintenir la précision et la fiabilité des données de commande */
                // Calcul du montant total de la commande (1)
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

                /* Utilisation de Sessions pour Stocker l'ID de Commande */
                // Stockage de l'ID de la commande dans la session après l'enregistrement de la commande (1)
                $cartService->setOrderIdInSession($order->getId());

                return $this->redirectToRoute('payment');
            }
        }

        /* Sécurité des données. 
        Le rendu de la vue avec des données précisément contrôlées garantit que l'utilisateur ne voit que ce qu'il est censé voir, 
        en fonction de l'état actuel de l'application */
        // Rendu de la Vue avec des Données Sécurisées (1)
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

    /* Validation et gestion des données utilisateur. Cette méthode s'assure que les adresses sont correctement gérées dans la base de données, 
    en mettant à jour une adresse existante ou en créant une nouvelle si nécessaire. Cela empêche les doublons et assure l'intégrité des données.*/
    // Logique pour Créer ou Mettre à Jour une Adresse (1)
    private function createOrUpdateAddress(User $user, array $data, EntityManagerInterface $em): Adress
    {
        /* Intégrité des données. Ce code permet de retrouver une adresse existante associée à l'utilisateur. 
        Cette vérification prévient la création d'adresses en double et garantit que les données associées à l'utilisateur sont correctes et à jour. */
        // Recherche d'une Adresse Existant (1)
        $existingAddress = null;
        foreach ($user->getAddresses() as $address) {
            if ($address->getType() === $data['type']) {
                $existingAddress = $address;
                break;
            }
        }

        // Si l'adresse existe
        if ($existingAddress) {
            // Mise à jour de l'adresse existante
            $address = $existingAddress;
        } else {
            // Création d'une nouvelle adresse
            $address = new Adress();
            $address->setUser($user);
        }

        // Marquer l'adresse comme active
        $address->setIsActive(true);

        /* Assurer la validation des données d'adresse avant de les enregistrer dans la base de données */
        // Mise à jour des données de l'adresse (1)
        $address->setStreet($data['street']);
        $address->setCity($data['city']);
        $address->setState($data['state']);
        $address->setPostalCode($data['postalCode']);
        $address->setCountry($data['country']);
        $address->setType($data['type']);

        /* Gestion des préférences utilisateur. 
        Cette logique permet à l'utilisateur de définir une adresse par défaut pour la facturation ou la livraison, 
        améliorant ainsi l'expérience utilisateur et réduisant les erreurs dans les commandes futures.*/
        // Définition comme adresse par défaut (1)
        if ($data['type'] === 'billing') {
            $user->setDefaultBillingAddress($address);
        } elseif ($data['type'] === 'delivery') {
            $user->setDefaultDeliveryAddress($address);
        }

        /* Gestion du Cycle de Vie des Entités avec EntityManager */
        // Persistance des modifications dans la base de données (1)
        $em->persist($address);
        $em->flush();

        return $address;
    }

    /* Validation de l'existence de la commande.
    Vérifie que l'ID de commande fourni correspond à une commande valide avant de procéder au paiement. 
    Cela empêche l'accès à la page de paiement pour des commandes inexistantes ou incorrectes. */
    // Méthode de Paiement(1)
    #[Route('/payment', name: 'payment')]
    public function payment(Request $request, EntityManagerInterface $em): Response
    {
        // Récupérer l'ID de la commande à partir de la requête
        $order_id = $request->query->get('order_id');
        // Rechercher la commande correspondante dans la base de données
        $order = $em->getRepository(Order::class)->find($order_id);

        // Débogage
        // dump($order_id, $order);

        if (!$order) {
            // Je dois pas oublier de gérer l'erreur ici
        }

        // Rendre la vue 'payment/index.html.twig' avec les données nécessaires
        return $this->render('payment/index.html.twig', [
            'stripe_public_key' => $this->getParameter('stripe.public_key'),
            'order' => $order
        ]);
    }

    /* Validation et gestion du statut de commande. 
    Assure que la commande à confirmer existe réellement et effectue les mises à jour nécessaires,
    notamment le statut de la commande et le suivi.
    Vérifie et ajuste le stock des produits commandés, garantissant l'intégrité du stock et évitant les problèmes de survente. */
    // Confirmation de Commande(1)
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
                // dd([
                //     'Product ID' => $product->getId(),
                //     'Current Stock' => $currentStock,
                //     'Quantity Ordered' => $quantityOrdered,
                //     'New Stock' => $newStock
                // ]);

                // S'assure que le stock ne devient pas négatif
                $product->setStockQuantity(max($newStock, 0));
                $em->persist($product);
            }

            $em->flush();

            // Ici, je pourrais envisage de vider le panier si nécessaire (à voir)

            // Si tout se passe bien
            $this->addFlash('success', 'Your order has been successfully confirmed.');
            return $this->redirectToRoute('order_thank_you');
        } else {
            // Si quelque chose ne fonctionne pas
            $this->addFlash('error', 'Order not found.');
            return $this->redirectToRoute('cart_index');
        }
    }

    // Page de Remerciement
    #[Route('/order/thank-you', name: 'order_thank_you')]
    public function thankYou(): Response
    {
        return $this->render('order/thankYou.html.twig');
    }
}
