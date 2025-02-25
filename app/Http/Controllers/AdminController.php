<?php

namespace App\Http\Controllers;

use App\Models\MailLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function users_list()
    {
        $users = User::all();
        return view('admin.user_list', compact('users'));
    }

    public function emails_list()
    {
        $emails = MailLog::all();
        return view('admin.email_list', compact('emails'));
    }

    public function user_show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user_show', compact('user'));
    }
    // Show user creation form
    public function showCreateForm()
    {
        return view('admin.create');
    }

    // Store new user
    public function store(Request $request)
    {
        // Validate the input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create new user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // Redirect with success message
        return redirect()->route('admin.users.list')->with('success', 'User created successfully');
    }

    public function update_user(Request $request)
    {
        $user = User::find($request->user_id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        if ($request->name !== $user->name) {
            $user->name = $validatedData['name'];
        }

        if ($request->email !== $user->email) {
            $user->email = $validatedData['email'];
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return response()->json(['success' => true]);

    }
}

