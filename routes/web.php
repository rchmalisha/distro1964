<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaterialNeedsController;
use App\Http\Controllers\TrialBalanceController;
use App\Http\Controllers\GeneralLedgerController;
use App\Http\Controllers\GeneralJournalController;

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

// Route Menu Akuntansi
Route::get('/daftar-akun', [AccountController::class, 'index'])->name('accounts.index');
Route::post('/daftar-akun', [AccountController::class, 'store'])->name('accounts.store');
Route::put('/daftar-akun/{account}', [AccountController::class, 'update'])->name('accounts.update');
Route::delete('/daftar-akun/{account}', [AccountController::class, 'destroy'])->name('accounts.destroy');

Route::get('/jurnal-umum', [GeneralJournalController::class, 'index'])->name('journal.index');
Route::get('/buku-besar', [GeneralLedgerController::class, 'index'])->name('ledger.index');
Route::get('/trial-balance', [TrialBalanceController::class, 'index'])->name('trial-balance.index');


// ======== FITUR DATA JASA ========
Route::middleware('auth')->group(function () {
    Route::resource('services', ServiceController::class);
    // Kebutuhan bahan (material needs)
    Route::resource('materialneeds', MaterialNeedsController::class)->only(['index']);
});


// ======== FITUR ORDER & DETAIL ORDER ========
Route::middleware('auth')->group(function () {
    // CRUD utama untuk Order
    Route::resource('orders', OrderController::class);

    // Tambahan untuk kelola detail order (ajax/modal)
    Route::get('orders/{order}/details', [OrderController::class, 'showDetails'])->name('orders.details');
    Route::post('orders/{order}/details', [OrderController::class, 'storeDetail'])->name('orders.details.store');
    Route::put('orders/{order}/details/{detail}', [OrderController::class, 'updateDetail'])->name('orders.details.update');
    Route::delete('orders/{order}/details/{detail}', [OrderController::class, 'destroyDetail'])->name('orders.details.destroy');
});
