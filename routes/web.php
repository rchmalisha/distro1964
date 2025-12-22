<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaterialNeedsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TrialBalanceController;
use App\Http\Controllers\GeneralLedgerController;
use App\Http\Controllers\BalanceSheetController;
use App\Http\Controllers\ProfitAndLossController;
use App\Http\Controllers\GeneralJournalController;
use App\Http\Controllers\PurchasingController;
use App\Http\Controllers\FixedAssetController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return redirect()->route('sign-in.form');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
Route::get('/dashboard/data/sales-monthly', [DashboardController::class, 'salesMonthly'])->middleware('auth');
Route::get('/dashboard/data/revenue-monthly', [DashboardController::class, 'revenueMonthly'])->middleware('auth');
Route::get('/dashboard/data/summary', [DashboardController::class, 'summary'])->middleware('auth');
Route::get('/dashboard/data/recent-activity', [DashboardController::class, 'recentActivity'])->middleware('auth');

// About Page
Route::get('/about', function () {
    return view('about');   // pastikan file view bernama about.blade.php
})->name('about');

Route::resource('materials', MaterialController::class);

Route::get('/sign-up', [AuthController::class, 'showSignUp'])->name('sign-up.form');
Route::post('/sign-up', [AuthController::class, 'signUp'])->name('sign-up');

Route::get('/sign-in', [AuthController::class, 'showSignIn'])->name('sign-in.form');
Route::post('/sign-in', [AuthController::class, 'signIn'])->name('sign-in');
Route::redirect('/login', '/sign-in')->name('login');

// password reset routes removed per request

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
    Route::get('orders/{order}/details', [OrderController::class, 'showDetails'])->name('orders.show');
    Route::get('orders/cetak_nota/{order}', [OrderController::class, 'print'])->name('orders.print');
    Route::post('orders/{order}/details', [OrderController::class, 'storeDetail'])->name('orders.details.store');
    Route::put('orders/{order}/details/{detail}', [OrderController::class, 'updateDetail'])->name('orders.details.update');
    Route::delete('orders/{order}/details/{detail}', [OrderController::class, 'destroyDetail'])->name('orders.details.destroy');
    Route::delete('/orders/{order}/cancel', [OrderController::class, 'destroy'])->name('orders.cancel');
});

// ======== FITUR DATA PESANAN / PEMBAYARAN ========
Route::middleware('auth')->group(function () {
    Route::get('sales', [SalesController::class, 'index'])->name('sales.index');
    Route::get('sales/create/{order_id}', [SalesController::class, 'create'])->name('sales.create');
    Route::post('sales', [SalesController::class, 'store'])->name('sales.store');
    Route::get('/sales/print', [SalesController::class, 'report'])->name('sales.report');
    Route::get('/sales/cetak_nota/{kode_jual}', [SalesController::class, 'print'])->name('sales.print');

});

Route::post('/purchasing/create-from-need', [PurchasingController::class, 'createFromNeed'])->name('purchasing.createFromNeed');

Route::resource('purchasing',PurchasingController::class);

// ======== FITUR ASET TETAP ========
Route::middleware('auth')->group(function () {
    Route::get('/fixed-assets', [FixedAssetController::class, 'index'])->name('fixed_assets.index');
    Route::post('/fixed-assets', [FixedAssetController::class, 'store'])->name('fixed_assets.store');
    Route::get('/fixed-assets/{id}/detail', [FixedAssetController::class, 'getDetail'])->name('fixed_assets.detail');
    Route::patch('/fixed-assets/{id}', [FixedAssetController::class, 'update'])->name('fixed_assets.update');
    Route::patch('/fixed-assets/{id}/sale', [FixedAssetController::class, 'recordSale'])->name('fixed_assets.sale');
    Route::delete('/fixed-assets/{id}', [FixedAssetController::class, 'destroy'])->name('fixed_assets.destroy');
});
