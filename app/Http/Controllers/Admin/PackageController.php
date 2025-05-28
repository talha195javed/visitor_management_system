<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerSubscription;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $packages = $this->getPackageData();
        $client = auth()->user();

        if (!$client) {
            redirect('/client/login');
        }


        $subscriptions = CustomerSubscription::where('client_id', $client->id)->get();
        $hasActiveSubscription = false;
        $latestActiveSubscription = null;

        if ($subscriptions->isNotEmpty()) {
            $now = now();

            $activeSubscriptions = $subscriptions->filter(function ($sub) use ($now) {
                return strtotime($sub->end_date) > $now->timestamp;
            });

            if ($activeSubscriptions->isNotEmpty()) {
                $sortedActiveSubscriptions = $activeSubscriptions->sortByDesc(function ($sub) {
                    return strtotime($sub->end_date);
                });

                $latestActiveSubscription = $sortedActiveSubscriptions->first();
                $hasActiveSubscription = true;
            }
        }

        return view('admin.packages.index', compact(
            'packages',
            'client',
            'hasActiveSubscription',
            'latestActiveSubscription'
        ));
    }

    public function createPaymentIntent(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Validate inputs with same rules as the first method
            $validated = $request->validate([
                'amount' => 'required|numeric|min:1',
                'payment_method_id' => 'sometimes|string|starts_with:pm_',
                'currency' => 'sometimes|string|size:3',
                'plan' => 'sometimes|string',
            ]);

            // Create PaymentIntent WITHOUT payment_method initially (same as first method)
            $paymentIntent = PaymentIntent::create([
                'amount' => $validated['amount'] * 100, // Remember amount is usually in cents
                'currency' => $validated['currency'] ?? 'aed',
                'metadata' => [
                    'plan' => $validated['plan'] ?? 'unknown',
                    'user_id' => auth()->id(),
                ],
            ]);

            return response()->json([
                'success' => true,
                'clientSecret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
                'requires_action' => $paymentIntent->status === 'requires_action',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Stripe\Exception\CardException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getError()->message
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => config('app.debug') ? $e->getMessage() : 'Payment processing failed'
            ], 500);
        }
    }

    public function saveCustomerDetails(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'client_id' => 'required|integer',
            'package_type' => 'required|string|in:basic,professional,enterprise',
            'duration' => 'required|string|in:monthly,yearly',
            'payment_intent_id' => 'required|string',
            'payment_method_id' => 'required|string',
            'amount' => 'required|numeric',
            'currency' => 'required|string|size:3',
            'startNow' => 'sometimes|boolean',
            'startOnExpiry' => 'sometimes|boolean',
            'existing_subscription_id' => 'sometimes|nullable|integer',
            'existing_subscription_end_date' => 'sometimes|nullable|date',
        ]);

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            // Retrieve or create customer on Stripe
            if (!empty($request->stripe_customer_id)) {
                $customer = \Stripe\Customer::retrieve($request->stripe_customer_id);
            } else {
                $customer = \Stripe\Customer::create([
                    'email' => $validated['email'],
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
                    'metadata' => ['client_id' => $validated['client_id']],
                ]);
            }

            // Attach payment method to customer if not already attached
            $paymentMethod = \Stripe\PaymentMethod::retrieve($validated['payment_method_id']);
            if (!$paymentMethod->customer) {
                $paymentMethod->attach(['customer' => $customer->id]);
            }

            // Set default payment method on customer
            \Stripe\Customer::update($customer->id, [
                'invoice_settings' => ['default_payment_method' => $paymentMethod->id]
            ]);

            // Calculate subscription start date
            $startDate = now();
            if ($request->startOnExpiry && $request->existing_subscription_end_date) {
                $existingEndDate = \Carbon\Carbon::parse($request->existing_subscription_end_date);
                $startDate = $existingEndDate->copy()->addSecond();
            } elseif ($request->startNow && !empty($request->existing_subscription_id)) {
                $startDate = now();
                $existingSub = CustomerSubscription::find($request->existing_subscription_id);
                if ($existingSub) {
                    $existingSub->end_date = now();
                    $existingSub->save();
                }
            }

            // Calculate end date based on start date and duration
            $endDate = $validated['duration'] === 'yearly'
                ? $startDate->copy()->addYear()
                : $startDate->copy()->addMonth();

            // Create subscription in DB
            $subscription = CustomerSubscription::create([
                'customer_name' => $validated['name'],
                'customer_email' => $validated['email'],
                'customer_phone' => $validated['phone'],
                'client_id' => $validated['client_id'],
                'package_type' => $validated['package_type'],
                'billing_cycle' => $validated['duration'],
                'payment_intent_id' => $validated['payment_intent_id'],
                'stripe_customer_id' => $customer->id,
                'payment_method_id' => $paymentMethod->id,
                'amount' => $validated['amount'],
                'currency' => $validated['currency'],
                'status' => 'active',
                'auto_renew' => true,
                'ip_address' => $request->ip(),
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subscription purchased successfully!',
                'data' => $subscription,
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to save customer details: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Failed to process subscription. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    protected function getPackageData()
    {
        return [
            [
                'title' => 'Basic',
                'monthly_price' => '40',
                'annual_price' => '36',
                'period' => 'month',
                'features' => [
                    "Up to 200 visitor check-ins/month",
                    "Email notifications",
                    "Basic visitor logs",
                    "Email support"
                ]
            ],
            [
                'title' => 'Professional',
                'monthly_price' => '80',
                'annual_price' => '72',
                'period' => 'month',
                'features' => [
                    "Unlimited visitor check-ins",
                    "Email & SMS notifications",
                    "Advanced analytics",
                    "System Fields Configuration",
                    "Priority chat support"
                ],
                'popular' => true
            ],
            [
                'title' => 'Enterprise',
                'monthly_price' => '120',
                'annual_price' => '108',
                'period' => 'month',
                'features' => [
                    "Unlimited everything",
                    "Custom integrations",
                    "System Fields Configuration",
                    "Background Image options",
                    "24/7 priority support",
                    "Custom workflows"
                ]
            ]
        ];
    }

    public function renew_index($id)
    {
        $subscription = CustomerSubscription::findOrFail($id);
        $customer = null;
        $paymentMethods = [];

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Get customer's payment methods if stripe_customer_id exists
            if ($subscription->stripe_customer_id) {
                $customer = \Stripe\Customer::retrieve($subscription->stripe_customer_id);
                $paymentMethods = \Stripe\PaymentMethod::all([
                    'customer' => $subscription->stripe_customer_id,
                    'type' => 'card'
                ])->data;
            }

            // Get payment method from payment intent if no stripe customer exists
            $paymentMethodFromIntent = null;
            if ($subscription->payment_intent_id && empty($paymentMethods)) {
                $intent = \Stripe\PaymentIntent::retrieve($subscription->payment_intent_id);
                if (!empty($intent->payment_method)) {
                    $paymentMethodFromIntent = \Stripe\PaymentMethod::retrieve($intent->payment_method);
                }
            }

        } catch (\Exception $e) {
            \Log::error("Stripe error: " . $e->getMessage());
        }

        return view('subscriptions.renew', compact(
            'subscription',
            'paymentMethods',
            'paymentMethodFromIntent',
            'customer'
        ));
    }

    protected function getLastActiveSubscription($clientId)
    {
        return CustomerSubscription::where('client_id', $clientId)
            ->where('status', 'active')
            ->orderBy('end_date', 'desc')
            ->first();
    }

    public function renewUsingLastSubscriptionPrice(Request $request)
    {
        $request->validate([
            'client_id' => 'required|integer',
            'payment_method_id' => 'required|string',
        ]);

        $clientId = $request->client_id;
        $paymentMethodId = $request->payment_method_id;

        $lastSubscription = $this->getLastActiveSubscription($clientId);

        if (!$lastSubscription) {
            return response()->json([
                'success' => false,
                'message' => 'No active subscription found for renewal.',
            ], 404);
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $amountInCents = $lastSubscription->amount * 100;

            // Create a PaymentIntent to charge off-session with saved card
            $paymentIntent = PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => $lastSubscription->currency,
                'customer' => $lastSubscription->stripe_customer_id,
                'payment_method' => $paymentMethodId,
                'off_session' => true,
                'confirm' => true,
                'metadata' => [
                    'subscription_id' => $lastSubscription->id,
                    'renewal' => true,
                ],
            ]);

            // Calculate new subscription dates
            $startDate = now();
            $endDate = $lastSubscription->billing_cycle === 'yearly' ? now()->addYear() : now()->addMonth();

            // Create new subscription record in DB
            $newSubscription = CustomerSubscription::create([
                'customer_name' => $lastSubscription->customer_name,
                'customer_email' => $lastSubscription->customer_email,
                'customer_phone' => $lastSubscription->customer_phone,
                'client_id' => $clientId,
                'package_type' => $lastSubscription->package_type,
                'billing_cycle' => $lastSubscription->billing_cycle,
                'payment_intent_id' => $paymentIntent->id,
                'stripe_customer_id' => $lastSubscription->stripe_customer_id,
                'payment_method_id' => $paymentMethodId,
                'amount' => $lastSubscription->amount,
                'currency' => $lastSubscription->currency,
                'status' => 'active',
                'ip_address' => $request->ip(),
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);

            // Mark old subscription as renewed (optional)
            $lastSubscription->update(['status' => 'renewed']);

            return response()->json([
                'success' => true,
                'message' => 'Subscription renewed successfully.',
                'data' => $newSubscription,
            ]);
        } catch (\Stripe\Exception\CardException $e) {
            // Handle card errors (like declined payments)
            return response()->json([
                'success' => false,
                'message' => 'Payment failed: ' . $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to renew subscription: ' . $e->getMessage(),
            ], 500);
        }
    }

}
