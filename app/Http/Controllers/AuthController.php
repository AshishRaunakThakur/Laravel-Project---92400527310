<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function loginForm() { return view('auth.login'); }
    public function registerForm() { return view('auth.register'); }

    public function login(Request $request)
    {
        $credentials = $request->validate(['email' => ['required', 'email'], 'password' => ['required', 'string']]);
        $credentials['email'] = strtolower($credentials['email']);
        $account = User::whereRaw('LOWER(email) = ?', [$credentials['email']])->first();
        if ($account?->isCampusUser() && !str_ends_with($credentials['email'], '@marwadiuniversity.ac.in')) {
            return back()->withErrors(['email' => 'Campus users must sign in with their @marwadiuniversity.ac.in email address.'])->onlyInput('email');
        }
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'The email or password is incorrect.'])->onlyInput('email');
        }
        if (!$request->user()->is_active) {
            Auth::logout();
            return back()->withErrors(['email' => 'This account is inactive. Please contact an administrator.'])->onlyInput('email');
        }
        $request->session()->regenerate();
        return redirect()->route('dashboard');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'regex:/^[^@\s]+@marwadiuniversity\.ac\.in$/i', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);
        $user = User::create(['name' => $data['name'], 'email' => strtolower($data['email']), 'password' => Hash::make($data['password']), 'role' => 'campus_user', 'is_active' => true]);
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('dashboard')->with('success', 'Welcome! Your campus account is ready.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
