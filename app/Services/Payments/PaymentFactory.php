<?php

namespace App\Services\Payments;

class PaymentFactory
{
   // public static function make(): PaymentGatewayInterface
   // {
   //    return match (env('PAYMENT_PROVIDER')) {
   //       'flutterwave' => new FlutterwaveService(),
   //       'stripe' => new StripeService(),
   //       default => new PaystackService(),
   //    };
   // }

   public static function make(?string $gateway = null): PaymentGatewayInterface
   {
      // Use the user-specified gateway if provided, otherwise fallback to env
      $selectedGateway = $gateway ?? env('PAYMENT_PROVIDER', 'paystack');

      return match (strtolower($selectedGateway)) {
         'flutterwave' => new FlutterwaveService(),
         'stripe' => new StripeService(),
         'paystack' => new PaystackService(),
         default => throw new \Exception("Unsupported payment gateway: {$selectedGateway}"),
      };
   }
}
