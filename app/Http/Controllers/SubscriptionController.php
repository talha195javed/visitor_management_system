<?php

namespace App\Http\Controllers;

use App\Models\CustomerSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = CustomerSubscription::orderBy('id', 'desc')->get();

        $stats = [
            'total' => CustomerSubscription::count(),
            'active' => CustomerSubscription::where('status', 'active')->count(),
            'revenue' => CustomerSubscription::sum('amount'),
            'recent' => CustomerSubscription::where('created_at', '>=', now()->subDays(30))->count()
        ];

        return view('subscriptions.index', compact('subscriptions', 'stats'));
    }

    public function client_index()
    {
        $currentUser = auth()->user();
        $userType = $currentUser->role;

        $query = CustomerSubscription::query()->orderBy('id', 'desc');

        if ($userType === 'client') {
            $query->where('customer_email', $currentUser->email);
        }

        $subscriptions = $query->get();

        // Split subscriptions into active and expired
        $activeSubscriptions = $subscriptions->filter(function ($subscription) {
            return \Carbon\Carbon::parse($subscription->end_date)->isFuture();
        });

        $expiredSubscriptions = $subscriptions->filter(function ($subscription) {
            return \Carbon\Carbon::parse($subscription->end_date)->isPast();
        });

        $baseQuery = CustomerSubscription::query();
        if ($userType === 'client') {
            $baseQuery->where('customer_email', $currentUser->email);
        }

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'active' => (clone $baseQuery)->where('status', 'active')->count(),
            'revenue' => (clone $baseQuery)->sum('amount'),
            'recent' => (clone $baseQuery)->where('created_at', '>=', now()->subDays(30))->count(),
        ];

        return view('subscriptions.client_index', compact('activeSubscriptions', 'expiredSubscriptions', 'stats'));
    }


    public function show($id)
    {
        $subscription = CustomerSubscription::findOrFail($id);
        return view('subscriptions.show', compact('subscription'));
    }

    public function edit($id)
    {
        $subscription = CustomerSubscription::findOrFail($id);
        return view('subscriptions.edit', compact('subscription'));
    }

    public function update(Request $request, $id)
    {
        $subscription = CustomerSubscription::findOrFail($id);

        $data = [
            'customer_name' => $request->has('customer_name') ? $request->input('customer_name') : $subscription->customer_name,
            'amount'        => $request->has('amount') ? $request->input('amount') : $subscription->amount,
            'status'        => $request->has('status') ? $request->input('status') : $subscription->status,
            'auto_renew'    => $request->has('auto_renew') ? (bool)$request->input('auto_renew') : $subscription->auto_renew,
            'start_date'    => $request->has('start_date') ? $request->input('start_date') : $subscription->start_date,
            'end_date'      => $request->has('end_date') ? $request->input('end_date') : $subscription->end_date,
        ];

        try {
            $validated = Validator::make($data, [
                'customer_name' => 'required|string|max:255',
                'amount' => 'required|numeric',
                'status' => 'required|string',
                'auto_renew' => 'required|boolean',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
            ])->validate();
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }

        $existingStatus = $subscription->status;
        $existingEndDate = Carbon::parse($subscription->end_date);
        $newStatus = $validated['status'];
        $newEndDate = Carbon::parse($validated['end_date']);
        $today = Carbon::today();

        if ($newStatus === 'cancelled' && $existingStatus !== 'cancelled') {
            $validated['end_date'] = $today;
        } elseif ($newStatus === 'offer_time' && $newEndDate->isFuture() && $existingEndDate->isPast()) {
            $validated['status'] = 'offer time';
            $validated['end_date'] = $newEndDate;
        }

        $subscription->status = $validated['status'];
        $subscription->start_date = $validated['start_date'];
        $subscription->end_date = $validated['end_date'];
        $subscription->auto_renew = $validated['auto_renew'];
        $subscription->save();

        $route = Auth::user()->role == 'superAdmin' ? 'admin.subscriptions.index' : 'admin.client_subscriptions.index';

        return redirect()->route($route)
            ->with('success', 'Subscription updated successfully');
    }


    public function destroy($id)
    {
        $subscription = CustomerSubscription::findOrFail($id);

        if ($subscription->end_date > now()) {
            $subscription->end_date = now();
        }

        $subscription->status = 'expired';
        $subscription->save();

        return response()->json([
            'success' => true,
            'message' => 'Subscription expired successfully'
        ]);
    }

    public function saveCustomerDetails(Request $request)
    {
        Log::info('Incoming Request:', $request->all());

        DB::listen(function ($query) {
            Log::info('SQL Query: ' . $query->sql, $query->bindings);
        });

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'client_id' => 'required|string|max:20',
            'package_type' => 'required|string|in:basic,professional,enterprise',
            'duration' => 'required|string|in:monthly,yearly',
            'payment_intent_id' => 'required|string',
            'amount' => 'required|numeric',
            'currency' => 'required|string|size:3',
        ]);

        try {
            // Create subscription record
            $subscription = CustomerSubscription::create([
                'customer_name' => $validated['name'],
                'customer_email' => $validated['email'],
                'customer_phone' => $validated['phone'],
                'client_id' => $validated['client_id'],
                'package_type' => $validated['package_type'],
                'billing_cycle' => $validated['duration'],
                'payment_intent_id' => $validated['payment_intent_id'],
                'payment_method_id' => $validated['payment_method_id'],
                'amount' => $validated['amount'],
                'currency' => $validated['currency'],
                'status' => 'active',
                'ip_address' => $request->ip(),
                'start_date' => now(),
                'end_date' => $validated['duration'] === 'yearly' ? now()->addYear() : now()->addMonth(),
            ]);

            if ($subscription) {
                Log::info('Subscription Saved Successfully', $subscription->toArray());
                return response()->json([
                    'success' => true,
                    'message' => 'Customer details and subscription saved successfully',
                    'data' => $subscription
                ]);
            } else {
                Log::error('Failed to Save Subscription');
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to save customer subscription'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Exception while saving subscription', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function renewSubscription(Request $request)
    {
        \Log::debug('Raw input:', $request->all());
        $validated = $request->validate([
            'subscription_id' => 'required|exists:customer_subscriptions,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'payment_method_id' => 'required|string',
            'amount' => 'required|numeric',
            'auto_renew' => 'sometimes|boolean',
            'is_new_card' => 'sometimes|boolean'
        ]);

        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $subscription = CustomerSubscription::find($validated['subscription_id']);
            $amount = $validated['amount'] * 100; // Convert to cents

            // Create payment intent
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $amount,
                'currency' => $subscription->currency,
                'customer' => $subscription->stripe_customer_id,
                'payment_method' => $validated['payment_method_id'],
                'off_session' => true,
                'confirm' => !$validated['is_new_card'],
                'metadata' => [
                    'subscription_id' => $subscription->id,
                    'renewal' => true
                ]
            ]);

            if (!$validated['is_new_card'] && $paymentIntent->status === 'succeeded') {
                return $this->createRenewal($subscription, $validated, $paymentIntent->id);
            }

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'requires_action' => $paymentIntent->status === 'requires_action'
            ]);

        } catch (\Stripe\Exception\CardException $e) {
            Log::error('Stripe Card Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Renewal Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'An error occurred. Please try again.'
            ], 500);
        }
    }

    private function createRenewal($oldSubscription, $data, $paymentIntentId)
    {
        try {
            $startDate = Carbon::parse($oldSubscription->end_date)->addSecond();
            $endDate = $data['billing_cycle'] === 'yearly'
                ? $startDate->copy()->addYear()
                : $startDate->copy()->addMonth();

            $newSubscription = CustomerSubscription::create([
                'customer_name' => $oldSubscription->customer_name,
                'customer_email' => $oldSubscription->customer_email,
                'customer_phone' => $oldSubscription->customer_phone,
                'client_id' => $oldSubscription->client_id,
                'package_type' => $oldSubscription->package_type,
                'billing_cycle' => $data['billing_cycle'],
                'payment_intent_id' => $paymentIntentId,
                'stripe_customer_id' => $oldSubscription->stripe_customer_id,
                'payment_method_id' => $data['payment_method_id'],
                'amount' => $data['amount'],
                'currency' => $oldSubscription->currency,
                'status' => 'active',
                'ip_address' => request()->ip(),
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);

            $oldSubscription->update([
                'status' => 'renewed',
                'renewed_by' => $newSubscription->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subscription renewed successfully',
                'data' => $newSubscription
            ]);

        } catch (\Exception $e) {
            Log::error('Create Renewal Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to create renewal: ' . $e->getMessage()
            ], 500);
        }
    }
}
