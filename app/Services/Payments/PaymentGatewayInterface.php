<?php

namespace App\Services\Payments;

interface PaymentGatewayInterface
{
   public function initializePayment(array $data);
   public function verifyPayment(string $reference);
}
