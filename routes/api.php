<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VisitorController;
use App\Models\CustomerData;

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
