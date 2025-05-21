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
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $request->amount,
            'currency' => $request->currency ?? 'aed',
            'metadata' => [
                'plan' => $request->plan,
                'user_id' => auth()->id() ?? 'guest' // If you have authentication
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
    ]);

    try {
        $startDate = now(); // default

        // If startOnExpiry is true
        if ($request->startOnExpiry && $request->existing_subscription_end_date) {
            $existingEndDate = Carbon::parse($request->existing_subscription_end_date);
            $startDate = $existingEndDate->copy()->addSecond();

            // If startNow is true and existing subscription exists, end that one now
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
