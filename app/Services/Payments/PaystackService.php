<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\Http;

class PaystackService implements PaymentGatewayInterface
{
   public function initializePayment(array $data)
   {
      $response = Http::withToken(env('PAYSTACK_SECRET_KEY'))
         ->post('https://api.paystack.co/transaction/initialize', $data);

      return $response->json();
   }

   public function verifyPayment(string $reference)
   {
      $response = Http::withToken(env('PAYSTACK_SECRET_KEY'))
         ->get("https://api.paystack.co/transaction/verify/{$reference}");

      return $response->json();
   }
}
