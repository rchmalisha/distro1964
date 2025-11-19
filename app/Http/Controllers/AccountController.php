<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        // 🚀 PERUBAHAN DI SINI:
        // Menggunakan orderBy('kode_akun') untuk mengurutkan data berdasarkan kode akun
        // secara ascending (terkecil ke terbesar atau A-Z)
        $accounts = Account::orderBy('kode_akun')->get(); 
        
        return view('accounting.accounts.index', compact('accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // Tambahkan pengecekan unique untuk kode_akun di method store
            'kode_akun' => 'required|unique:accounts,kode_akun',
            'nama_akun' => 'required',
            'jenis_akun' => 'required',
            'saldo_normal' => 'required',
            'saldo_awal' => 'nullable|numeric' // Mengubah ke nullable|numeric jika boleh kosong
        ]);

        Account::create($request->all());
        return redirect()->back()->with('success', 'Akun berhasil ditambahkan.');
    }

    public function update(Request $request, Account $account)
    {
        $request->validate([
            // ⚠️ PENTING: Jika Kode Akun diizinkan diedit, validasi unique harus diubah
            // agar mengabaikan akun yang sedang diedit
            'kode_akun' => 'required|unique:accounts,kode_akun,'.$account->id, // Menambahkan validasi kode akun
            'nama_akun' => 'required',
            'jenis_akun' => 'required',
            'saldo_normal' => 'required',
            'saldo_awal' => 'nullable|numeric' // Mengubah ke nullable|numeric jika boleh kosong
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