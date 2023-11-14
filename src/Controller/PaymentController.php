<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Order;
use App\Entity\Payment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use App\Service\CartService;

class PaymentController extends AbstractController
{
  private $stripeSecretKey;
  private $logger;

  public function __construct(string $stripeSecretKey, LoggerInterface $logger)
  {
    $this->stripeSecretKey = $stripeSecretKey;
    $this->logger = $logger;
  }

  #[Route('/payment', name: 'payment')]
  public function index(Request $request): Response
  {
    // Obtenir l'ID de la commande à partir de la session
    $orderId = $request->getSession()->get('orderId');

    // Si l'ID de la commande n'est pas trouvé, rediriger vers le panier
    if (!$orderId) {
      $this->addFlash('error', 'Order ID not found.');
      return $this->redirectToRoute('cart_index');
    }

    // Rendre la vue 'payment/index.html.twig' avec les données nécessaires
    return $this->render('payment/index.html.twig', [
      'stripe_public_key' => $this->getParameter('stripe.public_key'),
      'order_id' => $orderId
    ]);
  }

  #[Route('/payment/charge', name: 'payment_charge', methods: ['POST'])]
  public function charge(Request $request, EntityManagerInterface $em, CartService $cartService): Response
  {
    // Définir la clé secrète Stripe
    Stripe::setApiKey($this->stripeSecretKey);

    // Obtenir le jeton de carte de crédit à partir de la requête
    $token = $request->request->get('stripeToken');

    // Montant à payer en centimes (1000 = 10€)
    $amount = 1000;

    try {
      // Créer une charge Stripe
      $charge = \Stripe\Charge::create([
        'amount' => $amount,
        'currency' => 'eur',
        'description' => 'Example charge',
        'source' => $token,
      ]);

      // Créer un nouvel enregistrement de paiement
      $payment = new Payment();
      $payment->setAmount($amount / 100); // Convertir les centimes en euros
      $orderId = $request->request->get('order_id');

      // Trouver la commande correspondante dans la base de données
      $order = $em->getRepository(Order::class)->find($orderId);

      // Si la commande n'est pas trouvée, générer une exception
      if (!$order) {
        throw new \Exception('Order not found.');
      }

      // Mettre à jour le montant total de la commande en centimes
      $totalAmount = $order->getTotalPrice() * 100;
      $payment->setAmount($totalAmount / 100);

      // Associer le paiement à la commande
      $payment->setOrder($order);
      $payment->setTransactionId($charge->id);
      $payment->setStatus($charge->status);
      $payment->setPaymentMethod("carte de crédit");
      $payment->setCreatedAt(new \DateTimeImmutable());

      // Persiste l'enregistrement de paiement
      $em->persist($payment);

      // Si le paiement réussit, mettre à jour le statut de la commande et gérer les stocks
      if ($charge->status === 'succeeded') {
        $order->setStatus('confirmed');

        foreach ($order->getOrderDetails() as $orderDetail) {
          $product = $orderDetail->getProduct();
          $currentStock = $product->getStockQuantity();
          $quantityOrdered = $orderDetail->getQuantity();
          $newStock = $currentStock - $quantityOrdered;

          // S'assurer que le stock ne devient pas négatif
          $product->setStockQuantity(max($newStock, 0));
          $em->persist($product);
        }

        // Appliquer les modifications à la base de données
        $em->flush();

        // Vider le panier après le paiement
        $cartService->emptyCart();
      }

      // Rediriger vers la page de succès du paiement
      return $this->redirectToRoute('payment_success');
    } catch (\Exception $e) {
      // En cas d'erreur, enregistrer l'erreur et rediriger vers la page d'échec du paiement
      $this->logger->error('Payment failed: ' . $e->getMessage());
      $this->addFlash('error', 'Payment failed: ' . $e->getMessage());
      return $this->redirectToRoute('payment_failed');
    }
  }

  #[Route('/payment/success', name: 'payment_success')]
  public function paymentSuccess(): Response
  {
    return $this->render('payment/success.html.twig');
  }

  #[Route('/payment/failed', name: 'payment_failed')]
  public function paymentFailed(): Response
  {
    return $this->render('payment/failed.html.twig');
  }
}
