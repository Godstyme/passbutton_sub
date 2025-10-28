<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\Http;

class StripeService implements PaymentGatewayInterface
{
   /**
    * Initialize a Stripe payment session
    */
   public function initializePayment($payload)
   {
      $secretKey = env('STRIPE_SECRET_KEY');

      $response = Http::withHeaders([
         'Authorization' => 'Bearer ' . $secretKey,
         'Content-Type'  => 'application/x-www-form-urlencoded',
      ])->asForm()->post('https://api.stripe.com/v1/checkout/sessions', [
         'mode' => 'payment',
         'success_url' => route('payment.verify') . '?session_id={CHECKOUT_SESSION_ID}',
         'cancel_url' => route('payment.cancel'),
         'line_items' => [[
            'price_data' => [
               'currency' => strtolower($payload['currency'] ?? 'NGN'),
               'product_data' => [
                  'name' => 'Subscription Plan',
               ],
               'unit_amount' => $payload['amount'] * 100, // Stripe uses kobo/cents
            ],
            'quantity' => 1,
         ]],
      ]);

      return $response->json();
   }

   /**
    * Verify a Stripe payment session
    */
   public function verifyPayment($sessionId)
   {
      $secretKey = env('STRIPE_SECRET_KEY');

      $response = Http::withHeaders([
         'Authorization' => 'Bearer ' . $secretKey,
      ])->get("https://api.stripe.com/v1/checkout/sessions/{$sessionId}");

      return $response->json();
   }
}
