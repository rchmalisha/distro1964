<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MaterialController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return redirect()->route('sign-in.form');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::resource('materials', MaterialController::class);

Route::get('/sign-up', [AuthController::class, 'showSignUp'])->name('sign-up.form');
Route::post('/sign-up', [AuthController::class, 'signUp'])->name('sign-up');

Route::get('/sign-in', [AuthController::class, 'showSignIn'])->name('sign-in.form');
Route::post('/sign-in', [AuthController::class, 'signIn'])->name('sign-in');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

