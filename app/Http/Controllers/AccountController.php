<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::all();
        return view('accounting.accounts.index', compact('accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_akun' => 'required|unique:accounts',
            'nama_akun' => 'required',
            'jenis_akun' => 'required',
            'saldo_normal' => 'required',
            'saldo_awal' => 'numeric'
        ]);

        Account::create($request->all());
        return redirect()->back()->with('success', 'Akun berhasil ditambahkan.');
    }

    public function update(Request $request, Account $account)
    {
        $request->validate([
            'nama_akun' => 'required',
            'jenis_akun' => 'required',
            'saldo_normal' => 'required',
            'saldo_awal' => 'numeric'
        ]);

        $account->update($request->all());
        return redirect()->back()->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(Account $account)
    {
        $account->delete();
        return redirect()->back()->with('success', 'Akun berhasil dihapus.');
    }
}

