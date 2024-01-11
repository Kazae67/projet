<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Order;
use App\Entity\Adress;
use App\Entity\Payment;
use App\Service\CartService;
use Psr\Log\LoggerInterface;
use App\Entity\ArchivedOrder;
use App\Entity\OrderTracking;
use App\Entity\ArchivedOrderDetail;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\PdfGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{
  private $stripeSecretKey;
  private $logger;
  private $pdfGenerator;


  public function __construct(string $stripeSecretKey, LoggerInterface $logger, PdfGenerator $pdfGenerator)
  {
    $this->stripeSecretKey = $stripeSecretKey;
    $this->logger = $logger;
    $this->pdfGenerator = $pdfGenerator;
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
        'description' => 'charge',
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
      $payment->setPaymentMethod("Credit Card");
      $payment->setCreatedAt(new \DateTimeImmutable());

      // Persiste l'enregistrement de paiement
      $em->persist($payment);

      // Si le paiement réussit, mettre à jour le statut de la commande et gérer les stocks
      if ($charge->status === 'succeeded') {
        $order->setStatus('confirmed');
        // Récupération de l'adresse sélectionnée pour la commande
        $selectedAddress = $order->getAddress();
        $firstName = $order->getFirstName();
        $lastName = $order->getLastName();

        // Archiver la commande avec l'adresse sélectionnée
        $this->archiveOrder($order, $em, $selectedAddress, $firstName, $lastName);
        // Mettre à jour le suivi de la commande
        $tracking = new OrderTracking();
        $tracking->setOrder($order);
        $tracking->setStatus('Payment Confirmed');
        $em->persist($tracking);

        foreach ($order->getOrderDetails() as $orderDetail) {
          $product = $orderDetail->getProduct();
          $product->setStockQuantity(max(0, $product->getStockQuantity() - $orderDetail->getQuantity()));
          $em->persist($product);
        }

        // Génération du PDF de la facture
        $pdfContent = $this->pdfGenerator->generatePdfFromTemplate('order/invoice.html.twig', [
          'order' => $order
        ]);

        // Sauvegarde du PDF dans un répertoire (pour nous)
        $pdfPath = $this->getParameter('kernel.project_dir') . '/public/invoices/invoice-' . $order->getId() . '.pdf';
        file_put_contents($pdfPath, $pdfContent);

        $em->flush();
        $cartService->emptyCart();
      }

      return $this->redirectToRoute('payment_success');
    } catch (\Exception $e) {
      $this->logger->error('Payment failed: ' . $e->getMessage());
      $this->addFlash('error', 'Payment failed: ' . $e->getMessage());
      return $this->redirectToRoute('payment_failed');
    }
  }

  private function archiveOrder(Order $order, EntityManagerInterface $em, Adress $selectedAddress, string $firstName, string $lastName): void
  {
    $archivedOrder = new ArchivedOrder();
    $archivedOrder->setUserName($order->getUser()->getUsername());
    $archivedOrder->setTotal($order->getTotalPrice());
    $archivedOrder->setStatus($order->getStatus());
    $archivedOrder->setCreatedAt($order->getCreatedAt());

    $addressDetails = $this->formatAddress($selectedAddress);
    $archivedOrder->setAddressDetails($addressDetails);

    // Ajout de firstName et lastName à l'ordre archivé
    $archivedOrder->setFirstName($firstName);
    $archivedOrder->setLastName($lastName);

    foreach ($order->getOrderDetails() as $detail) {
      $archivedDetail = new ArchivedOrderDetail();
      $archivedDetail->setArchivedOrder($archivedOrder);
      $archivedDetail->setProductName($detail->getProduct()->getName());
      $archivedDetail->setQuantity($detail->getQuantity());
      $archivedDetail->setPrice($detail->getPrice());

      $em->persist($archivedDetail);
    }

    $em->persist($archivedOrder);
    $em->flush();
  }
  // Méthode auxiliaire pour formater l'adresse en une chaîne
  private function formatAddress(?Adress $address): string
  {
    if (!$address) {
      return 'Address not provided';
    }

    return sprintf(
      '%s, %s, %s, %s, %s',
      $address->getStreet(),
      $address->getCity(),
      $address->getState(),
      $address->getPostalCode(),
      $address->getCountry()
    );
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

  #[Route('/download-invoice/{orderId}', name: 'download_invoice')]
  public function downloadInvoice(int $orderId, EntityManagerInterface $em): Response
  {
      // Recherche de la commande par son ID dans la base de données
      $order = $em->getRepository(Order::class)->find($orderId);
  
      // Gestion de l'absence de la commande
      if (!$order) {
          throw $this->createNotFoundException('Order not found.');
      }
  
      // Génération du contenu PDF de la facture
      $pdfContent = $this->pdfGenerator->generatePdfFromTemplate('order/invoice.html.twig', [
          'order' => $order // Transmission des données de la commande au template
      ]);
  
      // Création de la réponse HTTP pour le téléchargement
      $response = new Response($pdfContent);
      $response->headers->set('Content-Type', 'application/pdf'); // Définition du type de contenu en PDF
      $response->headers->set('Content-Disposition', 'attachment; filename="invoice-' . $orderId . '.pdf"'); // En-têtes pour le téléchargement du fichier
  
      return $response; // Retourne la réponse contenant le PDF
  }
}
