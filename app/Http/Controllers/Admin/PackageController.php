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
            redirect('/client/login'); // or redirect to login
        }


        $subscriptions = CustomerSubscription::where('client_id', $client->id)->get();
        $hasActiveSubscription = false;
        $latestActiveSubscription = null;

        if ($subscriptions->isNotEmpty()) {
            $now = now();

            // Filter active subscriptions using Collection's filter method
            $activeSubscriptions = $subscriptions->filter(function ($sub) use ($now) {
                return strtotime($sub->end_date) > $now->timestamp;
            });

            if ($activeSubscriptions->isNotEmpty()) {
                // Sort by end_date descending using sortByDesc
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
            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount * 100,
                'currency' => $request->currency ?? 'aed',
                'metadata' => [
                    'plan' => $request->plan,
                    'user_id' => auth()->id()
                ],
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
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
            'amount' => 'required|numeric',
            'currency' => 'required|string|size:3',
            'startNow' => 'sometimes|boolean',
            'startOnExpiry' => 'sometimes|boolean',
            'existing_subscription_id' => 'sometimes|nullable|integer',
            'existing_subscription_end_date' => 'sometimes|nullable|date',
        ]);

        try {
            $startDate = now(); // default

            // If startOnExpiry is true
            if ($request->startOnExpiry && $request->existing_subscription_end_date) {
                $existingEndDate = \Carbon\Carbon::parse($request->existing_subscription_end_date);
                $startDate = $existingEndDate->copy()->addSecond();

                // If startNow is true and existing subscription exists, end that one now
            } elseif ($request->startNow && !empty($request->existing_subscription_id)) {
                $startDate = now();

                $existingSub = CustomerSubscription::find($request->existing_subscription_id);
                if ($existingSub) {
                    $existingSub->end_date = now();
                    $existingSub->save();
                }
            }

            // Calculate end date based on new start date
            $endDate = $validated['duration'] === 'yearly'
                ? $startDate->copy()->addYear()
                : $startDate->copy()->addMonth();

            // Create the new subscription
            $subscription = CustomerSubscription::create([
                'customer_name' => $validated['name'],
                'customer_email' => $validated['email'],
                'customer_phone' => $validated['phone'],
                'client_id' => $validated['client_id'],
                'package_type' => $validated['package_type'],
                'billing_cycle' => $validated['duration'],
                'payment_intent_id' => $validated['payment_intent_id'],
                'amount' => $validated['amount'],
                'currency' => $validated['currency'],
                'status' => 'active',
                'ip_address' => $request->ip(),
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subscription purchased successfully!',
                'data' => $subscription
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to save customer details: ' . $e->getMessage());

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
}
