<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientLoginController;
use App\Http\Controllers\CompanyInfoController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FieldSettingController;

// Landing Page
// Rename the visitor home route

Route::middleware(['client.auth'])->group(function () {
    Route::get('/', [VisitorController::class, 'index'])->name('visitor.home');
    Route::get('/check-in', [VisitorController::class, 'showCheckIn'])->name('visitor.checkin');
    Route::get('/visitor/select-role/{id}', [VisitorController::class, 'showRoleSelection'])->name('visitor.selectRole');
    Route::get('/visitor/select-purpose/{id}', [VisitorController::class, 'selectPurpose'])->name('visitor.selectPurpose');
    Route::get('/visitor/capture/{id}', [VisitorController::class, 'captureImageView'])->name('visitor.captureImage');
    Route::get('/visitor/capture_id/{id}', [VisitorController::class, 'captureIdView'])->name('visitor.captureIdView');
    Route::get('/visitor/{id}/emergency-contact', [VisitorController::class, 'showEmergencyContactForm'])->name('visitor.showEmergencyContact');
    Route::get('/visitor/confirmation/{id}', [VisitorController::class, 'showAgreement'])->name('visitor.agreement');
    Route::get('/visitor/success/{id}', [VisitorController::class, 'visitor_success'])->name('visitor.success');
});

Route::get('/client/logout', function() {
    Auth::logout(); // Logout user if authenticated
    session()->flush(); // Clear session
    return redirect('/client/login'); // Redirect to login page
})->name('client.logout');

//Route::get('/', [VisitorController::class, 'index'])->name('visitor.home');  // visitor homepage route

// Keep the home route for authenticated users
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');

// Visitor Check-in & Check-out (Public)
//Route::get('/check-in', [VisitorController::class, 'showCheckIn'])->name('visitor.checkin');
Route::post('/check-in', [VisitorController::class, 'storeCheckIn'])->name('visitor.storeCheckIn');
Route::post('/update-visitor', [VisitorController::class, 'update_visitor'])->name('update.visitor');

Route::post('/pre-registor-visitor', [VisitorController::class, 'pre_registor_visitor'])->name('pre-registor.visitor');


Route::get('/check-out', [VisitorController::class, 'showCheckOut'])->name('visitor.checkout');
Route::post('/check-out', [VisitorController::class, 'storeCheckOut'])->name('visitor.storeCheckOut');

// Admin Routes (Require Authentication)
Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');


Route::post('/visitor/upload-photo', [VisitorController::class, 'uploadPhoto'])->name('visitor.uploadPhoto');

//Route::get('/visitor/capture/{id}', [VisitorController::class, 'captureImageView'])->name('visitor.captureImage');
Route::post('/visitor/capture/{id}', [VisitorController::class, 'storeCapturedImage'])->name('visitor.storeCapturedImage');


//Route::get('/visitor/capture_id/{id}', [VisitorController::class, 'captureIdView'])->name('visitor.captureIdView');
Route::post('/visitor/capture_id/{id}', [VisitorController::class, 'storeCapturedIdImage'])->name('visitor.storeCapturedIdImage');


Route::get('/visitor/check-in-complete', [VisitorController::class, 'checkInComplete'])->name('visitor.checkInComplete');

//Route::get('/visitor/select-role/{id}', [VisitorController::class, 'showRoleSelection'])->name('visitor.selectRole');
Route::post('/visitor/set-role/{id}', [VisitorController::class, 'setRole'])->name('visitor.setRole');


Route::resource('employees', EmployeeController::class);

//Route::get('/visitor/select-purpose/{id}', [VisitorController::class, 'selectPurpose'])->name('visitor.selectPurpose');
Route::post('/visitor/store-purpose/{id}', [VisitorController::class, 'storePurpose'])->name('visitor.storePurpose');

Route::post('/visitor/check-pre-registered', [VisitorController::class, 'checkPreRegistered'])->name('visitor.checkPreRegistered');

//Route::get('/visitor/{id}/emergency-contact', [VisitorController::class, 'showEmergencyContactForm'])->name('visitor.showEmergencyContact');
Route::post('/visitor/{id}/emergency-contact', [VisitorController::class, 'storeEmergencyContact'])->name('visitor.storeEmergencyContact');

//Route::get('/visitor/confirmation/{id}', [VisitorController::class, 'showAgreement'])
//    ->name('visitor.agreement');

Route::post('/visitor/agreement/{id}', [VisitorController::class, 'storeAgreement'])
    ->name('visitor.storeAgreement');

//Route::get('/visitor/success/{id}', [VisitorController::class, 'visitor_success'])
//    ->name('visitor.success');

Route::get('employers_list', [EmployeeController::class, 'employers_list'])->name('employers_list');

Route::get('employers_archive_list', [EmployeeController::class, 'employers_archive_list'])->name('employers_archive_list');

Route::get('visitors_archive_list', [VisitorController::class, 'visitors_archive_list'])->name('visitors_archive_list');

Route::post('/employees/{id}/employers_restore', [EmployeeController::class, 'employers_restore'])->name('employers_restore');

Route::post('/visitors/{id}/visitors_restore', [VisitorController::class, 'visitors_restore'])->name('visitors_restore');


Route::prefix('visitors')->name('visitors.')->group(function () {
    Route::get('admin_list', [VisitorController::class, 'admin_list'])->name('admin_list');
    Route::get('admin_pre-register', [VisitorController::class, 'admin_preRegister'])->name('admin_pre_register');
    Route::get('admin_checked-in', [VisitorController::class, 'admin_checkedIn'])->name('admin_checked_in');
    Route::get('admin_checked-out', [VisitorController::class, 'admin_checkedOut'])->name('admin_checked_out');

    // Soft delete route (archive)
    Route::post('admin_archive/{id}', [VisitorController::class, 'admin_archive'])->name('admin_archive');
});

Route::get('/visitors/{id}', [VisitorController::class, 'show'])->name('visitors.show');

Route::get('/employee/{id}', [EmployeeController::class, 'employee_show'])->name('employee_show');

Route::post('/update-employee', [EmployeeController::class, 'update_employee'])->name('update_employee');

Route::get('/create-employee', [EmployeeController::class, 'create_employee'])->name('create_employee');

Route::post('/register-employee', [EmployeeController::class, 'register_employee'])->name('register_employee');

Route::post('/employees/{id}/archive', [EmployeeController::class, 'archive'])->name('employees.archive');

Route::post('/visitors/{id}/archive', [VisitorController::class, 'archive'])->name('visitors.archive');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/create', [AdminController::class, 'showCreateForm'])->name('users.create');

    Route::get('/users_list', [AdminController::class, 'users_list'])->name('users.list');

    Route::get('/clients_list', [AdminController::class, 'clients_list'])->name('clients.list');

    Route::get('/emails_list', [AdminController::class, 'emails_list'])->name('emails.list');

    Route::get('/user_show/{id}', [AdminController::class, 'user_show'])->name('users.user_show');

    Route::post('/user_store', [AdminController::class, 'store'])->name('users.store');
});
Route::post('/update-user', [AdminController::class, 'update_user'])->name('users.update_user');

Route::get('/admin/field-visibility', [FieldSettingController::class, 'index'])->name('admin.field_visibility');

Route::get('/admin/company-setting', [FieldSettingController::class, 'company_index'])->name('admin.company_setting');

Route::post('/admin/field-visibility/update', [FieldSettingController::class, 'update'])->name('admin.update_field_visibility');

Route::get('/visitor/search', [VisitorController::class, 'search_visitor'])->name('visitor.search');

Route::post('/company-info/upload', [CompanyInfoController::class, 'uploadImages'])->name('company_info.upload');

Route::get('/subscriptions/index', [SubscriptionController::class, 'index'])->name('admin.subscriptions.index');

Route::get('/client_subscriptions/{id}', [ClientController::class, 'client_subscriptions'])->name('admin.client_subscriptions.show');

Route::get('/clients/index', [ClientController::class, 'index'])->name('admin.clients.index');

Route::get('/subscriptions/{id}', [SubscriptionController::class, 'show'])->name('admin.subscriptions.show');


Route::get('/subscriptions/{id}/edit', [SubscriptionController::class, 'edit'])->name('admin.subscriptions.edit');
Route::put('/subscriptions/{id}', [SubscriptionController::class, 'update'])->name('admin.subscriptions.update');
Route::delete('/subscriptions/{id}', [SubscriptionController::class, 'destroy'])->name('admin.subscriptions.destroy');

Route::get('/client/login', [ClientLoginController::class, 'showLoginForm'])->name('client.login');
Route::post('/client/login', [ClientLoginController::class, 'login']);
Route::post('/client/logout', [ClientLoginController::class, 'logout'])->name('client.logout');
