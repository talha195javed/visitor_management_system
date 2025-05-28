<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CustomerSubscription;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'client') {
            $hasActiveSubscription = CustomerSubscription::where('client_id', $user->id)
                ->where('status', 'active')
                ->where('end_date', '>', now())
                ->exists();

            if (!$hasActiveSubscription) {
                Auth::logout();

                return redirect('/login')->with([
                    'no_subscription' => true,
                    'message' => 'You do not have any active subscription. Please contact Admin or buy a New Package from Main Page.'
                ]);
            }
        }

        return redirect()->intended($this->redirectPath());
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
            'user_type' => 'required|in:client,super_admin',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}

