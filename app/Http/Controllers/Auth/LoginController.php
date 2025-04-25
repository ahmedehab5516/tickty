<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    /**
     * Handle login attempt.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validate the login data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        // Attempt login
        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            // Redirect based on user role
            return redirect()->intended($this->redirectPath());
        }

        // Return back with an error if login fails
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Get the redirect path based on user role.
     *
     * @return string
     */
    protected function redirectPath()
    {
        // Fetch the authenticated user
        $user = Auth::user();

        // If user is authenticated, check the role
        if ($user) {
            // Fetch the role_title using role_id
            $roleTitle = DB::table('roles')
                ->where('id', $user->role_id)
                ->value('role_title');

            // Redirect based on the role
            switch ($roleTitle) {
                case 'admin':
                    return route('admin.dashboard');
                case 'superadmin':
                    return route('superadmin.dashboard');
                case 'staff':
                    return route('staff.dashboard');
                default:
                    return route('user.dashboard');
            }
        }

        // Default redirect if no role found (fallback)
        return route('home');
    }
}
