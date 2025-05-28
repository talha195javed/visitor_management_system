<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);

            // Handle the event
            switch ($event->type) {
                case 'invoice.payment_succeeded':
                    // handle invoice paid event
                    break;
                case 'customer.subscription.updated':
                    // handle subscription updated
                    break;
                // Add other cases as needed
                default:
                    Log::info('Received unknown event type ' . $event->type);
            }

            return response()->json(['status' => 'success']);
        } catch (SignatureVerificationException $e) {
            Log::error('Webhook signature verification failed: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        } catch (\Exception $e) {
            Log::error('Error processing webhook: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook handling error'], 500);
        }
    }
}
