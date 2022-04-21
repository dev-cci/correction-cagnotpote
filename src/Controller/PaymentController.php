<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'app_payment')]
    public function index(): Response
    {
        \Stripe\Stripe::setApiKey('sk_test_aY17Hbx3t6GacRy9k35F5c1G00b8kFjGMf');

        $jsonStr = file_get_contents('php://input');
        $jsonObj = json_decode($jsonStr);

        // Create a PaymentIntent with amount and currency
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $jsonObj->amount * 100,
            'currency' => 'eur',
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);

        $output = [
            'clientSecret' => $paymentIntent->client_secret,
        ];

        return $this->json($output);
    }
}
