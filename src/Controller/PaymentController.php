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
    $orderId = $request->getSession()->get('orderId');

    if (!$orderId) {
      $this->addFlash('error', 'Order ID not found.');
      return $this->redirectToRoute('cart_index');
    }

    return $this->render('payment/index.html.twig', [
      'stripe_public_key' => $this->getParameter('stripe.public_key'),
      'order_id' => $orderId
    ]);
  }

  #[Route('/payment/charge', name: 'payment_charge', methods: ['POST'])]
  public function charge(Request $request, EntityManagerInterface $em, CartService $cartService): Response
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

      $payment = new Payment();
      $payment->setAmount($amount / 100); // Convertit les centimes en euros
      $orderId = $request->request->get('order_id');

      $order = $em->getRepository(Order::class)->find($orderId);
      if (!$order) {
        throw new \Exception('Order not found.');
      }

      $totalAmount = $order->getTotalPrice() * 100;
      $payment->setAmount($totalAmount / 100);

      $payment->setOrder($order);
      $payment->setTransactionId($charge->id);
      $payment->setStatus($charge->status);
      $payment->setPaymentMethod("carte de crédit");
      $payment->setCreatedAt(new \DateTimeImmutable());

      $em->persist($payment);

      if ($charge->status === 'succeeded') {
        $order->setStatus('confirmed');

        foreach ($order->getOrderDetails() as $orderDetail) {
          $product = $orderDetail->getProduct();
          $currentStock = $product->getStockQuantity();
          $quantityOrdered = $orderDetail->getQuantity();
          $newStock = $currentStock - $quantityOrdered;

          $product->setStockQuantity(max($newStock, 0));
          $em->persist($product);

          // Débogage: Afficher les valeurs
          // dd([
          //   'Product ID' => $product->getId(),
          //   'Current Stock' => $currentStock,
          //   'Quantity Ordered' => $quantityOrdered,
          //   'New Stock' => $newStock
          // ]);
        }

        $em->flush();
        $cartService->emptyCart(); // Vide le panier après le paiement
      }

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
    return $this->render('payment/success.html.twig');
  }

  #[Route('/payment/failed', name: 'payment_failed')]
  public function paymentFailed(): Response
  {
    return $this->render('payment/failed.html.twig');
  }
}
