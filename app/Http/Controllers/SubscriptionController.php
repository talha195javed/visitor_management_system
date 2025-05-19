<?php

namespace App\Http\Controllers;

use App\Models\CustomerSubscription;
use Illuminate\Http\Request;

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


        return view('subscriptions.index', compact('subscriptions', 'stats'));
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

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'package_type' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'status' => 'required|in:active,pending,cancelled,expired',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $subscription->update($validated);

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription updated successfully');
    }

    public function destroy($id)
    {
        $subscription = CustomerSubscription::findOrFail($id);
        $subscription->delete();

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription cancelled successfully');
    }

    public function saveCustomerDetails(Request $request)
    {
        // Log incoming request data
        Log::info('Incoming Request:', $request->all());

        // Listen to DB queries for debugging
        DB::listen(function ($query) {
            Log::info('SQL Query: ' . $query->sql, $query->bindings);
        });

        // Validate request
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
}
