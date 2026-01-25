<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && $user->status == 1 && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            return redirect()->intended($user->is_admin ? '/admin/dashboard' : '/user');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials or account is inactive.',
        ]);
    }

    public function showRegister()
    {
        return view('admin.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Check if any admin exists, if not, make this user admin
        $isAdmin = User::where('is_admin', true)->count() === 0;
        // $status = $isAdmin ? 1 : 0; // Active if first admin, inactive otherwise

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 0,
            'is_admin' => 0,
        ]);

        Auth::login($user);

        if ($user->is_admin && $user->status == 1) {
            return redirect('/admin/dashboard');
        } else {
            Auth::logout(); // Don't keep them logged in if not active
            return redirect('/')->with('success', 'Account created successfully. Please wait for admin approval to login.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
}
