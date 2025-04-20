<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate the login data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

    // dd($request->only('email', 'password')); // Will dump the email and password before attempting login

    
        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            // Redirect based on user role
            return redirect()->intended($this->redirectPath());
        }


        return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
    }


    protected function redirectPath()
    {
        // Redirect to specific dashboards based on the user's role
        $role = Auth::User()->role_id ?? 1;

        switch ($role) {
            case 2:
                return route('admin.dashboard');
            case 3:
                return route('superadmin.dashboard');
            default:
                return route('user.dashboard');
        }
    }


 
}
