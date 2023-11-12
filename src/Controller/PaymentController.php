<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Order;
use App\Entity\Payment;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;

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
    $orderId = $request->getSession()->get('orderId');

    if (!$orderId) {
      $this->addFlash('error', 'Order ID not found.');
      return $this->redirectToRoute('cart_index'); // Redirigez vers un endroit approprié
    }

    return $this->render('payment/index.html.twig', [
      'stripe_public_key' => $this->getParameter('stripe.public_key'),
      'order_id' => $orderId
    ]);
  }

  #[Route('/payment/charge', name: 'payment_charge', methods: ['POST'])]
  public function charge(Request $request, EntityManagerInterface $em): Response
  {
    Stripe::setApiKey($this->stripeSecretKey);

    $token = $request->request->get('stripeToken');
    $amount = 1000; // En centime 1000 = 10€

    try {
      $charge = \Stripe\Charge::create([
        'amount' => $amount,
        'currency' => 'eur',
        'description' => 'Example charge',
        'source' => $token,
      ]);

      // Crée une nouvelle entité Payment et enregistre les détails du paiement
      $payment = new Payment();
      $payment->setAmount($amount / 100);
      $orderId = $request->request->get('order_id');

      // Récupère l'objet Order approprié
      $order = $em->getRepository(Order::class)->find($orderId);
      if (!$order) {
        throw new \Exception('Order not found.');
      }

      $payment->setOrder($order);
      $payment->setTransactionId($charge->id);
      $payment->setStatus($charge->status); // "succeeded" ou autre statut de Stripe
      $payment->setPaymentMethod("carte de crédit"); // Méthode de payement 
      $payment->setCreatedAt(new \DateTimeImmutable());

      // Associer le paiement à une commande
      // $payment->setOrder($order); // pour obtenir l'objet Order approprié

      $em->persist($payment);
      $em->flush();
      // Traiter le succès du paiement, enregistrer le paiement, etc.
      // $charge contiendra la réponse de l'API Stripe

      return $this->redirectToRoute('payment_success');
    } catch (\Exception $e) {
      $this->logger->error('Payment failed: ' . $e->getMessage());
      $this->addFlash('error', 'Payment failed: ' . $e->getMessage());
      return $this->redirectToRoute('payment_failed');
    }
  }

  #[Route('/payment/success', name: 'payment_success')]
  public function paymentSuccess(): Response
  {
    // j'devrais ajouter le traitement à la bdd
    return $this->render('payment/success.html.twig');
  }

  #[Route('/payment/failed', name: 'payment_failed')]
  public function paymentFailed(): Response
  {
    return $this->render('payment/failed.html.twig');
  }
}
