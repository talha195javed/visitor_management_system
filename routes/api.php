<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VisitorController;

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
