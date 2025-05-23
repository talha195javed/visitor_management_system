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
        // Check if customer already exists
        $customer = null;
        if (auth()->check()) {
            $existingSubscription = CustomerSubscription::where('client_id', auth()->id())->first();

            if ($existingSubscription && $existingSubscription->stripe_customer_id) {
                $customer = $existingSubscription->stripe_customer_id;
            } else {
                // Create new customer if doesn't exist
                $stripeCustomer = \Stripe\Customer::create([
                    'email' => auth()->user()->email,
                    'name' => auth()->user()->name,
                    'metadata' => [
                        'user_id' => auth()->id(),
                        'app_customer' => true
                    ]
                ]);
                $customer = $stripeCustomer->id;
            }
        }

        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $request->amount,
            'currency' => $request->currency ?? 'aed',
            'customer' => $customer,
            'setup_future_usage' => 'off_session', // Important for recurring payments
            'metadata' => [
                'plan' => $request->plan,
                'user_id' => auth()->id() ?? 'guest'
            ],
        ]);

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
            'stripe_customer_id' => $customer
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
});

Route::post('/save-customer-details', function(Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'client_id' => 'required|max:20',
        'package_type' => 'required|string|in:basic,professional,enterprise',
        'duration' => 'required|string|in:monthly,yearly',
        'payment_intent_id' => 'required|string',
        'amount' => 'required|numeric',
        'currency' => 'required|string|size:3',
        'stripe_customer_id' => 'sometimes|string', // Add validation
    ]);

    try {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        // Retrieve payment intent to get payment method
        $paymentIntent = \Stripe\PaymentIntent::retrieve($validated['payment_intent_id']);
        $paymentMethodId = $paymentIntent->payment_method;

        // Attach payment method to customer if not already attached
        if (!empty($validated['stripe_customer_id']) && $paymentMethodId) {
            $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentMethodId);

            if (!$paymentMethod->customer) {
                $paymentMethod->attach([
                    'customer' => $validated['stripe_customer_id']
                ]);

                // Set as default payment method
                \Stripe\Customer::update(
                    $validated['stripe_customer_id'],
                    ['invoice_settings' => ['default_payment_method' => $paymentMethodId]]
                );
            }
        }

        $startDate = now(); // default

        // If startOnExpiry is true
        if ($request->startOnExpiry && $request->existing_subscription_end_date) {
            $existingEndDate = Carbon::parse($request->existing_subscription_end_date);
            $startDate = $existingEndDate->copy()->addSecond();

        } elseif ($request->startNow) {
            $startDate = now();

            if (!empty($request->existing_subscription_id)) {
                $existingSub = CustomerSubscription::find($request->existing_subscription_id);
                if ($existingSub) {
                    $existingSub->end_date = now();
                    $existingSub->save();
                }
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
            'stripe_customer_id' => $validated['stripe_customer_id'] ?? null,
            'payment_method_id' => $paymentMethodId ?? null,
            'amount' => $validated['amount'],
            'currency' => $validated['currency'],
            'status' => 'active',
            'ip_address' => $request->ip(),
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Customer details and subscription saved successfully',
            'data' => $subscription
        ]);

    } catch (\Exception $e) {
        \Log::error('Failed to save customer details: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());

        return response()->json([
            'success' => false,
            'message' => 'Failed to save customer details. Please try again.',
            'error' => env('APP_DEBUG') ? $e->getMessage() : null
        ], 500);
    }
});


Route::post('/client/register', [ClientController::class, 'register']);
Route::post('/client/login', [ClientController::class, 'login']);
Route::get('/client/latest', [ClientController::class, 'latest']);
Route::middleware('auth:sanctum')->get('/user', [ClientController::class, 'getUser']);
