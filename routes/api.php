<?php

use App\Http\Controllers\ClientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VisitorController;
use App\Models\CustomerData;
use Illuminate\Support\Carbon;
use App\Models\CustomerSubscription;

Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

Route::post('/visitor/checkPreRegistered', [VisitorController::class, 'checkPreRegistered']);

Route::get('/visitor/visibleFields', [VisitorController::class, 'getVisibleFields']);

Route::post('/visitor/store-checkin', [VisitorController::class, 'storeAppCheckin']);

Route::get('/visitor/setRoles', [VisitorController::class, 'setAppRoleSelection']);

Route::post('/visitor/set-role', [VisitorController::class, 'setAppRoleAssign']);

Route::get('/visitor/selctAppEmployee', [VisitorController::class, 'selctAppEmployee']);

Route::post('/visitor/setAppPurpose', [VisitorController::class, 'setAppPurpose']);

Route::post('/visitor/storeAppCapturedImage', [VisitorController::class, 'storeAppCapturedImage']);

Route::post('/visitor/storeAppCapturedIDImage', [VisitorController::class, 'storeAppCapturedIDImage']);

Route::post('/visitor/appEmergencyContact', [VisitorController::class, 'appEmergencyContact']);

Route::post('/visitor/appPrivacyAgreement', [VisitorController::class, 'appPrivacyAgreement']);

Route::post('/visitor/search_visitor', [VisitorController::class, 'search_visitor']);

Route::get('/visitor/search', function (Request $request) {
    $query = $request->query('q');
    $searchBy = $request->query('searchBy');

    if (!$query) {
        return response()->json([]);
    }

    $visitors = ($searchBy === 'id')
        ? Visitor::where('id', 'LIKE', "%{$query}%")->get()
        : Visitor::where('full_name', 'LIKE', "%{$query}%")->get();

    return response()->json($visitors);
});

Route::post('/contact/submit', function(Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'company' => 'nullable|string|max:255',
        'message' => 'required|string',
    ]);

    try {
        $contact = CustomerData::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'company' => $validated['company'] ?? null,
            'message' => $validated['message'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your message! We will get back to you soon.',
            'data' => $contact
        ]);

    } catch (\Exception $e) {
        Log::error('Contact form submission failed: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Failed to submit your message. Please try again later.'
        ], 500);
    }
});


Route::post('/create-payment-intent', function (Request $request) {
    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

    try {
        // Make payment_method_id optional for initial creation
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method_id' => 'sometimes|string|starts_with:pm_',
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'client_id' => 'required|integer',
            'currency' => 'sometimes|string|size:3',
            'plan' => 'sometimes|string'
        ]);

        // Create PaymentIntent without payment method initially
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $validated['amount'],
            'currency' => $validated['currency'] ?? 'aed',
            'metadata' => [
                'plan' => $validated['plan'] ?? 'unknown',
                'client_id' => $validated['client_id']
            ],
        ]);

        return response()->json([
            'success' => true,
            'clientSecret' => $paymentIntent->client_secret,
            'payment_intent_id' => $paymentIntent->id
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => env('APP_DEBUG') ? $e->getMessage() : 'Payment processing failed'
        ], 500);
    }
});

Route::post('/save-customer-details', function(Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'client_id' => 'required|integer',
        'package_type' => 'required|in:basic,professional,enterprise',
        'duration' => 'required|in:monthly,yearly',
        'payment_intent_id' => 'required|string',
        'payment_method_id' => 'required|string',
        'amount' => 'required|numeric|min:0.5',
        'currency' => 'required|string|size:3'
    ]);

    try {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        // Create or retrieve customer
        $customer = \Stripe\Customer::create([
            'email' => $validated['email'],
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'metadata' => ['client_id' => $validated['client_id']]
        ]);

        // Attach payment method
        $paymentMethod = \Stripe\PaymentMethod::retrieve($validated['payment_method_id']);
        $paymentMethod->attach(['customer' => $customer->id]);

        // Update customer with default payment method
        \Stripe\Customer::update($customer->id, [
            'invoice_settings' => ['default_payment_method' => $paymentMethod->id]
        ]);

        // Date calculations
        $startDate = now();
        $endDate = $validated['duration'] === 'yearly'
            ? $startDate->copy()->addYear()
            : $startDate->copy()->addMonth();

        // Create subscription
        $subscription = CustomerSubscription::create([
            'customer_name' => $validated['name'],
            'customer_email' => $validated['email'],
            'customer_phone' => $validated['phone'],
            'client_id' => $validated['client_id'],
            'package_type' => $validated['package_type'],
            'billing_cycle' => $validated['duration'],
            'payment_intent_id' => $validated['payment_intent_id'],
            'stripe_customer_id' => $customer->id,
            'payment_method_id' => $validated['payment_method_id'],
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
            'data' => $subscription
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => env('APP_DEBUG') ? $e->getMessage() : 'Failed to save subscription'
        ], 500);
    }
});


Route::post('/client/register', [ClientController::class, 'register']);
Route::post('/client/login', [ClientController::class, 'login']);
Route::get('/client/latest', [ClientController::class, 'latest']);
Route::middleware('auth:sanctum')->get('/user', [ClientController::class, 'getUser']);
