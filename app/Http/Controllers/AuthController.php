<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showSignUp()
    {
        return view('layout.sign-up');
    }

    public function signUp(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'email' => 'nullable|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('sign-in')->with('berhasil', 'Akun berhasil dibuat! Silakan sign in.');
    }

    public function showSignIn()
    {
        return view('layout.sign-in');
    }

    public function signIn(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('sign-in')->with('berhasil', 'Anda telah berhasil sign out.');
    }

}
