<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\Http;

class FlutterwaveService implements PaymentGatewayInterface
{
   public function initializePayment(array $data)
   {
      $response = Http::withToken(env('FLW_SECRET_KEY'))
         ->post('https://api.flutterwave.com/v3/payments', $data);

      return $response->json();
   }

   public function verifyPayment(string $reference)
   {
      $response = Http::withToken(env('FLW_SECRET_KEY'))
         ->get("https://api.flutterwave.com/v3/transactions/{$reference}/verify");

      return $response->json();
   }
}
