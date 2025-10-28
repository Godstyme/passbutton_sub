<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Support\Facades\Log;
use App\Services\Payments\PaymentFactory;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function initialize(Request $request)
    {
        // 1️⃣ Validate input
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'payment_gateway' => 'required|in:paystack,flutterwave,stripe',
            'auto_renew' => 'boolean'
        ]);

        // 2️⃣ Get plan and gateway service
        $plan = Plan::findOrFail($request->plan_id);
        $gateway = \App\Services\Payments\PaymentFactory::make($request->payment_gateway);

        // 3️⃣ Prepare payload
        $payload = [
            'email' => $request->user()->email,
            'amount' => $plan->price,
            'currency' => 'NGN',
            'callback_url' => route('payment.verify'),
            'metadata' => [
                'user_id' => $request->user()->id,
                'plan_id' => $plan->id,
                'gateway' => $request->payment_gateway,
                'auto_renew' => $request->auto_renew ?? false,
            ],
        ];

        // 4️⃣ Initialize payment with chosen gateway
        $response = $gateway->initializePayment($payload);

        // 5️⃣ Return JSON response
        return response()->json($response, 201);
    }


    public function verify(Request $request)
    {
        $reference = $request->get('reference');
        $gateway = PaymentFactory::make();
        $result = $gateway->verifyPayment($reference);

        if (in_array($result['data']['status'] ?? $result['status'], ['success', 'paid'])) {
            return response()->json(['message' => 'Subscription activated']);
        }

        return response()->json(['message' => 'Verification failed'], 400);
    }

    public function cancel(Request $request)
    {
        // Stripe redirect here if user cancels checkout
        Log::warning('Payment cancelled', $request->all());

        return response()->json([
            'status' => 'cancelled',
            'message' => 'Payment was cancelled'
        ]);
    }
}
