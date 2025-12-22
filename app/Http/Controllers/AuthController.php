<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
// password reset functionality removed per user request
use Carbon\Carbon;
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

        $username = $request->input('username');
        $password = $request->input('password');

        // Provide more detailed feedback: user not found vs wrong password.
        $user = User::where('username', $username)->orWhere('email', $username)->first();

        if (!$user) {
            // Log failed attempt
            Log::warning('Login failed - user not found', [
                'input' => $username,
                'ip' => $request->ip(),
                'ua' => $request->header('User-Agent'),
                'time' => Carbon::now()->toDateTimeString(),
            ]);

            return back()->withErrors(['username' => 'User tidak ditemukan.']);
        }

        // Check password
        if (!Hash::check($password, $user->password)) {
            Log::warning('Login failed - wrong password', [
                'username' => $user->username,
                'email' => $user->email,
                'ip' => $request->ip(),
                'ua' => $request->header('User-Agent'),
                'time' => Carbon::now()->toDateTimeString(),
            ]);

            return back()->withErrors(['username' => 'Password salah.']);
        }

        // All good - login the user
        Auth::login($user);
        Log::info('Login successful', ['username' => $user->username, 'email' => $user->email, 'ip' => $request->ip()]);
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('sign-in')->with('berhasil', 'Anda telah berhasil sign out.');
    }
    // password reset methods removed per user request

}
