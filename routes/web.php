<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BalanceSheetController;
use App\Http\Controllers\TrialBalanceController;
use App\Http\Controllers\GeneralLedgerController;
use App\Http\Controllers\ProfitAndLossController;
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

Route::get('/profit-and-loss', [ProfitAndLossController::class, 'index'])->name('profit.loss.index');
Route::get('/profit-and-loss/print', [ProfitAndLossController::class, 'print'])->name('profit.loss.print');

Route::get('/balance-sheet', [BalanceSheetController::class, 'index'])->name('balance.sheet.index');
Route::get('/balance-sheet/print', [App\Http\Controllers\BalanceSheetController::class, 'print'])->name('balance.sheet.print');

