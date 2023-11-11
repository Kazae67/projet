<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Order;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PaymentController extends AbstractController
{
  private $stripeSecretKey;

  public function __construct(string $stripeSecretKey)
  {
    $this->stripeSecretKey = $stripeSecretKey;
  }

  #[Route('/payment', name: 'payment')]
  public function index(): Response
  {
    return $this->render('payment/index.html.twig', [
      'stripe_public_key' => $this->getParameter('stripe.public_key')
    ]);
  }

  #[Route('/payment/charge', name: 'payment_charge', methods: ['POST'])]
  public function charge(Request $request): Response
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

      // Traiter le succès du paiement, enregistrer le paiement, etc.
      // $charge contiendra la réponse de l'API Stripe

      return $this->redirectToRoute('payment_success');
    } catch (\Exception $e) {
      // Échoué
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
