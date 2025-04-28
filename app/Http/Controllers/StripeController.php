<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Payment; // If saving to DB

class StripeController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentIntent = PaymentIntent::create([
            'amount' => $request->amount * 100, // Convert to cents
            'currency' => 'usd',
            'metadata' => [
                'user_id' => $request->user_id, // Optional
                'order_id' => $request->order_id,
            ],
        ]);

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch (\Exception $e) {
            return response('Webhook Error', 403);
        }

        // Handle successful payment
        if ($event->type === 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;

            // Save to database (example)
            Payment::create([
                'stripe_payment_id' => $paymentIntent->id,
                'user_id' => $paymentIntent->metadata->user_id,
                'amount' => $paymentIntent->amount / 100,
                'status' => $paymentIntent->status,
            ]);
        }

        return response('Webhook Handled', 200);
    }
}
