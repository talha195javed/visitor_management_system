<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\Payment;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Subscription as StripeSubscription;
use Stripe\Webhook;
use Stripe\Exception\ApiErrorException;
use Carbon\Carbon;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::all();
        $annualBilling = request()->has('annual') ? true : false;

        return view('packages.index', compact('packages', 'annualBilling'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'billing_period' => 'required|in:month,year',
            'start_option' => 'required|in:now,expiry'
        ]);

        $package = Package::findOrFail($request->package_id);
        $user = auth()->user();

        // Check if user has active subscription
        $hasActiveSubscription = $user->subscriptions()->where('end_date', '>', now())->exists();

        // Validate start option
        if ($request->start_option === 'expiry' && !$hasActiveSubscription) {
            return back()->with('error', 'You need an active subscription to start on expiry');
        }

        // Calculate price
        $price = $request->billing_period === 'year' ? $package->yearly_price : $package->monthly_price;

        // Create Stripe payment intent
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $price * 100,
                'currency' => 'aed',
                'metadata' => [
                    'user_id' => $user->id,
                    'package_id' => $package->id,
                    'billing_period' => $request->billing_period,
                    'start_option' => $request->start_option
                ],
            ]);

            return view('packages.checkout', [
                'clientSecret' => $paymentIntent->client_secret,
                'package' => $package,
                'price' => $price,
                'billingPeriod' => $request->billing_period,
                'startOption' => $request->start_option,
                'stripeKey' => config('services.stripe.key')
            ]);

        } catch (ApiErrorException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'package' => 'required|string',
            'billing' => 'required|in:month,year',
            'package_date' => 'required|in:now,expiry'
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount * 100,
                'currency' => 'aed',
                'metadata' => [
                    'user_id' => auth()->id(),
                    'package' => $request->package,
                    'billing' => $request->billing,
                    'package_date' => $request->package_date
                ],
            ]);

            return response()->json(['clientSecret' => $paymentIntent->client_secret]);

        } catch (ApiErrorException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function saveCustomerDetails(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'package_type' => 'required|string',
            'duration' => 'required|in:monthly,yearly',
            'payment_intent_id' => 'required|string',
            'amount' => 'required|numeric',
            'currency' => 'required|string',
            'package_date' => 'required|in:now,expiry'
        ]);

        $user = auth()->user();
        $package = Package::where('name', $request->package_type)->first();

        if (!$package) {
            return response()->json(['error' => 'Package not found'], 404);
        }

        // Create payment record
        $payment = Payment::create([
            'user_id' => $user->id,
            'stripe_payment_intent_id' => $request->payment_intent_id,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'status' => 'succeeded',
            'payment_details' => $request->except(['_token'])
        ]);

        // Determine subscription dates
        $startDate = now();
        $endDate = $request->duration === 'yearly' ? now()->addYear() : now()->addMonth();

        // If starting on expiry of current subscription
        if ($request->package_date === 'expiry') {
            $currentSubscription = $user->subscriptions()
                ->where('end_date', '>', now())
                ->orderBy('end_date', 'desc')
                ->first();

            if ($currentSubscription) {
                $startDate = $currentSubscription->end_date;
                $endDate = $request->duration === 'yearly'
                    ? Carbon::parse($startDate)->addYear()
                    : Carbon::parse($startDate)->addMonth();
            }
        }

        // Create subscription
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'status' => 'active',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_active' => true
        ]);

        // Update payment with subscription ID
        $payment->update(['subscription_id' => $subscription->id]);

        return response()->json(['success' => true]);
    }

    public function success()
    {
        return view('packages.success');
    }

    public function cancel()
    {
        return view('packages.cancel');
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                // Handle payment success
                break;

            case 'invoice.payment_succeeded':
                $invoice = $event->data->object;
                // Handle recurring payment success
                break;

            case 'invoice.payment_failed':
                $invoice = $event->data->object;
                // Handle payment failure
                break;
        }

        return response()->json(['status' => 'success']);
    }
}
