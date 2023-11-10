<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class paymentController extends AbstractController{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/order/creaate-session-stripe', name: 'payment_stripe')]
    public function stripeCheckout(): RedirectResponse
    {
        $order = $this->em->getRepository(Order::class)->findOneBy($item)
        Stripe::setApiKey('sk_test_51OAZApEONNRmxI8s8QGk9wxatBTors1YM35uGSYHVnCvOIZ6tb7GjuINQKRR4tmWJXOUGOe65qBK4pHdKeGjGPex00Yx7uEAIH');
        
        $checkout_session = \Stripe\Checkout\Session::create([
          'line_items' => [[
            # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
            'price' => '{{PRICE_ID}}',
            'quantity' => 1,
          ]],
          'mode' => 'payment',
          'success_url' => $YOUR_DOMAIN . '/success.html',
          'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);


        $checkout_session = \Stripe\Checkout\Session::create([

    }
}