<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;

class ClientLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('client_login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $client = User::where('email', $credentials['email'])->first();

        if ($client && Hash::check($credentials['password'], $client->password)) {
            // ✅ Store client_id in session
            session(['client_id' => $client->id]);

            // ✅ Set client_id in cookie (60 minutes)
            return response()->json([
                'success' => true,
                'client_id' => $client->id,
                'redirect' => session()->pull('intended_url', '/')
            ])->withCookie(cookie('client_id', $client->id, 60));
        }

        // ❌ Invalid login response
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ], 401);
    }



    public function logout(Request $request)
    {
        // ✅ Clear session client_id
        session()->forget('client_id');

        // ✅ Clear client_id cookie
        return response()->json(['success' => true])
            ->withCookie(cookie()->forget('client_id'));
    }
}
