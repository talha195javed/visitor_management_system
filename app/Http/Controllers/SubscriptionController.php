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
}
