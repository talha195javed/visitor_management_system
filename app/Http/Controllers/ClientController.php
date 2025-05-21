<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CustomerSubscription;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'company' => 'nullable|string|max:255',
        ], [
            'email.unique' => 'Email already registered. Please sign in.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $client = Client::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'company' => $request->company,
                'api_token' => Str::random(60),
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'client',
                'api_token' => Str::random(60),
            ]);

            return response()->json([
                'success' => true,
                'token' => $client->api_token,
                'user' => $client
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.'
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $client = Client::where('email', $request->email)->first();

        if (!$client || !Hash::check($request->password, $client->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials. Please try again.'
            ], 401);
        }

        $client->api_token = Str::random(60);
        $client->save();
        $subscriptions = CustomerSubscription::where('client_id', $client->id)->get();
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token' => $client->api_token,
            'user' => [
                'id' => $client->id,
                'name' => $client->name,
                'email' => $client->email,
                'phone' => $client->phone,
                'company' => $client->company,
            ],
            'subscriptions' => $subscriptions,
        ]);
    }


    public function latest(Request $request)
    {
        $userId = $request->query('user_id');

        $client = Client::where('id', $userId)->first();

        $subscriptions = CustomerSubscription::where('client_id', $client->id)->get();
        return response()->json([
            'success' => true,
            'token' => $client->api_token,
            'user' => [
                'id' => $client->id,
                'name' => $client->name,
                'email' => $client->email,
                'phone' => $client->phone,
                'company' => $client->company,
            ],
            'subscriptions' => $subscriptions,
        ]);
    }


    public function getUser(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }

    public function index(Request $request)
        {
            $clients = Client::orderBy('id', 'desc')->get();

            $stats = [
                'total' => Client::count(),
                'active' => CustomerSubscription::where('status', 'active')->count(),
                'revenue' => CustomerSubscription::sum('amount'),
                'recent' => CustomerSubscription::where('created_at', '>=', now()->subDays(30))->count()
            ];

            return view('client.index', compact('clients', 'stats'));
        }

    public function client_subscriptions($id)
    {
        $subscriptions = CustomerSubscription::where('client_id', $id)->orderBy('id', 'desc')->get();

        $stats = [
            'total' => CustomerSubscription::where('client_id', $id)->count(),
            'active' => CustomerSubscription::where('client_id', $id)->where('status', 'active')->count(),
            'revenue' => CustomerSubscription::where('client_id', $id)->sum('amount'),
            'recent' => CustomerSubscription::where('client_id', $id)->where('created_at', '>=', now()->subDays(30))->count()
        ];

        return view('client.subscription_index', compact('subscriptions', 'stats'));
    }

    /**
     * Get all visitors for a specific client
     *
     * @param int $clientId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClientVisitors($clientId)
    {
        try {
            // Validate client exists
            $client = User::findOrFail($clientId);

            // Get visitors for this client
            $visitors = Visitor::where('client_id', $clientId)
                ->orderBy('id', 'desc')
                ->get();
            return response()->json([
                'success' => true,
                'visitors' => $visitors,
                'client_name' => $client->user_name
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching visitor data'
            ], 500);
        }
    }
}
