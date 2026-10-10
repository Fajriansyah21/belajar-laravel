<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            // 'email' tetap diterima agar pemanggil form lama tidak langsung rusak.
            'login' => ['required_without:email', 'nullable', 'string'],
            'email' => ['required_without:login', 'nullable', 'string'],
            'password' => ['required', 'string'],
        ]);

        $identifier = $credentials['login'] ?? $credentials['email'];
        $loginField = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([
            $loginField => $identifier,
            'password' => $credentials['password'],
        ], $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('mahasiswa.index'));
        }

        return back()
            ->withErrors([
                'login' => 'Email/username atau password tidak sesuai.',
            ])
            ->withInput($request->only('login', 'email')); 
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}