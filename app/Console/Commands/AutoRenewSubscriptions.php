<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CustomerSubscription;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Customer;
use Stripe\PaymentMethod;
use Carbon\Carbon;

class AutoRenewSubscriptions extends Command
{
    protected $signature = 'subscriptions:auto-renew';
    protected $description = 'Automatically renew subscriptions near expiry';

    public function handle()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Get subscriptions that are ending within the next hour and have auto-renew enabled
        $threshold = Carbon::now()->addHour();

        $subsToRenew = CustomerSubscription::where('status', 'active')
            ->where('auto_renew', true)
            ->where('end_date', '<=', $threshold)
            ->get();

        foreach ($subsToRenew as $subscription) {
            try {
                // Skip if missing essential Stripe data
                if (empty($subscription->stripe_customer_id)) {
                    $this->logRenewalFailure(
                        $subscription,
                        'Missing Stripe customer ID',
                        'Cannot renew without Stripe customer reference'
                    );
                    continue;
                }

                if (empty($subscription->payment_method_id)) {
                    $this->logRenewalFailure(
                        $subscription,
                        'Missing payment method',
                        'No payment method available for automatic renewal'
                    );
                    continue;
                }

                // Convert amount to cents for Stripe
                $amountInCents = (int) ($subscription->amount * 100);

                // Verify the Stripe customer exists
                try {
                    $stripeCustomer = Customer::retrieve($subscription->stripe_customer_id);

                    if ($stripeCustomer->isDeleted()) {
                        $this->logRenewalFailure(
                            $subscription,
                            'Stripe customer deleted',
                            'Customer was deleted in Stripe'
                        );
                        continue;
                    }
                } catch (\Stripe\Exception\InvalidRequestException $e) {
                    $this->logRenewalFailure(
                        $subscription,
                        'Stripe customer not found',
                        $e->getMessage()
                    );
                    continue;
                }

                // Handle payment method attachment
                try {
                    $paymentMethod = PaymentMethod::retrieve($subscription->payment_method_id);

                    if (!$paymentMethod->customer || $paymentMethod->customer !== $subscription->stripe_customer_id) {
                        $paymentMethod->attach(['customer' => $subscription->stripe_customer_id]);

                        // Update customer's default payment method
                        Customer::update($subscription->stripe_customer_id, [
                            'invoice_settings' => [
                                'default_payment_method' => $subscription->payment_method_id
                            ]
                        ]);
                    }
                } catch (\Exception $e) {
                    $this->logRenewalFailure(
                        $subscription,
                        'Payment method error',
                        $e->getMessage()
                    );
                    continue;
                }

                // Create payment intent for renewal
                $paymentIntent = PaymentIntent::create([
                    'amount' => $amountInCents,
                    'currency' => strtolower($subscription->currency),
                    'customer' => $subscription->stripe_customer_id,
                    'payment_method' => $subscription->payment_method_id,
                    'off_session' => true,
                    'confirm' => true,
                    'metadata' => [
                        'subscription_id' => $subscription->id,
                        'client_id' => $subscription->client_id,
                        'renewal' => true,
                    ],
                ]);

                // Calculate new subscription period
                $startDate = Carbon::parse($subscription->end_date)->addSecond();
                $endDate = $this->calculateEndDate($startDate, $subscription->billing_cycle);

                // Create new subscription record
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
                    'auto_renew' => true,
                    'ip_address' => request()->ip(),
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ]);

                // Update old subscription record
                $subscription->update([
                    'status' => 'renewed',
                    'last_renewed_at' => now(),
                ]);

                $this->info("Successfully renewed subscription ID: {$subscription->id} for client ID: {$subscription->client_id}");

            } catch (\Stripe\Exception\CardException $e) {
                $this->logRenewalFailure(
                    $subscription,
                    'Payment failed',
                    $e->getMessage()
                );
            } catch (\Exception $e) {
                $this->logRenewalFailure(
                    $subscription,
                    'Renewal error',
                    $e->getMessage()
                );
            }
        }

        $this->info('Auto renewal process completed. Processed '.count($subsToRenew).' subscriptions.');
    }

    /**
     * Calculate the end date based on billing cycle
     */
    protected function calculateEndDate(Carbon $startDate, string $billingCycle): Carbon
    {
        return match ($billingCycle) {
            'monthly' => $startDate->copy()->addMonth(),
            'yearly' => $startDate->copy()->addYear(),
            default => $startDate->copy()->addMonth(), // fallback
        };
    }

    /**
     * Log failed renewal attempts
     */
    protected function logRenewalFailure(CustomerSubscription $subscription, string $reason, string $details): void
    {
        $subscription->update([
            'last_renewal_failed_at' => now(),
            'renewal_failure_reason' => $details,
        ]);

        $this->error("Failed to renew subscription ID: {$subscription->id} for client ID: {$subscription->client_id}. Reason: {$reason}. Details: {$details}");
    }
}
