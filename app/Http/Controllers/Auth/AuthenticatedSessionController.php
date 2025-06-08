<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
{
    // Validate credentials first
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    // Check if the user exists
    $user = User::where('username', $request->username)->first();

    if (!$user) {
        return back()->withErrors([
            'username' => 'User not found. Please register.',
        ])->onlyInput('username');
    }

    // Check if the password is correct
    if (!Hash::check($request->password, $user->password)) {
        return back()->withErrors([
            'password' => 'Incorrect password.',
        ])->onlyInput('username');
    }

    // Log the user in
    Auth::login($user);
    $request->session()->regenerate();

    return redirect()->intended(route('dashboard', absolute: false));
}


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
