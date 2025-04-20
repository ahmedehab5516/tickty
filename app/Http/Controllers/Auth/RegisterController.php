<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Optional: assign default role
        $user->role_id = '1'; // You can customize this or pull from $request
        $user->save();

        // Log the user in
        Auth::login($user);

        // Redirect based on role
        return redirect($this->redirectTo($user));
    }

    protected function redirectTo($user)
    {
        switch ($user->role_id) {
            case 3:
                return route('superadmin.dashboard');
            case 2:
                return route('admin.dashboard');
            case 1:
            default:
                return route('user.dashboard');
        }
    }
}
