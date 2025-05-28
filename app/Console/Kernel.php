<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CustomerSubscription;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Carbon\Carbon;

class AutoRenewSubscriptions extends Command
{
    protected $signature = 'subscriptions:auto-renew';
    protected $description = 'Automatically renew subscriptions near expiry';

    public function handle()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $threshold = Carbon::now()->addHour(); // check subscriptions expiring within 1 hour

        $subsToRenew = CustomerSubscription::where('status', 'active')
            ->where('end_date', '<=', $threshold)
            ->get();

        foreach ($subsToRenew as $subscription) {
            try {
                $amountInCents = $subscription->amount * 100;

                $paymentIntent = PaymentIntent::create([
                    'amount' => $amountInCents,
                    'currency' => $subscription->currency,
                    'customer' => $subscription->stripe_customer_id,
                    'payment_method' => $subscription->payment_method_id,
                    'off_session' => true,
                    'confirm' => true,
                    'metadata' => [
                        'subscription_id' => $subscription->id,
                        'renewal' => true,
                    ],
                ]);

                $startDate = Carbon::parse($subscription->end_date)->addSecond();
                $periodInSeconds = Carbon::parse($subscription->end_date)->diffInSeconds(Carbon::parse($subscription->start_date));
                $endDate = $startDate->copy()->addSeconds($periodInSeconds);

                $newSubscription = CustomerSubscription::create([
                    'customer_name' => $subscription->customer_name,
                    'customer_email' => $subscription->customer_email,
                    'customer_phone' => $subscription->customer_phone,
                    'client_id' => $subscription->client_id,
                    'package_type' => $subscription->package_type,
                    'billing_cycle' => $subscription->billing_cycle,
                    'payment_intent_id' => $paymentIntent->id,
                    'stripe_customer_id' => $subscription->stripe_customer_id,
                    'payment_method_id' => $subscription->payment_method_id,
                    'amount' => $subscription->amount,
                    'currency' => $subscription->currency,
                    'status' => 'active',
                    'ip_address' => null,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ]);

                $subscription->update(['status' => 'renewed']);

                $this->info("Subscription {$subscription->id} renewed successfully.");

            } catch (\Stripe\Exception\CardException $e) {
                $this->error("Payment failed for subscription {$subscription->id}: " . $e->getMessage());
            } catch (\Exception $e) {
                $this->error("Error renewing subscription {$subscription->id}: " . $e->getMessage());
            }
        }

        $this->info('Auto renewal process complete.');
    }
}
