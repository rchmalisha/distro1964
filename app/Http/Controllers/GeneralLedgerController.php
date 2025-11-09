<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class GeneralLedgerController extends Controller
{
    public function index(Request $request)
    {
        // Ambil daftar akun untuk dropdown filter
        $accounts = Account::orderBy('kode_akun', 'asc')->get();

        // Build base query (join detail + journal)
        $baseQuery = DB::table('general_journal_details as d')
            ->join('general_journals as j', 'd.general_journal_id', '=', 'j.id')
            ->select(
                'j.kode_jurnal',
                'j.tanggal_jurnal',
                'j.keterangan_jurnal',
                'd.debit',
                'd.kredit',
                'd.kode_akun'
            )
            ->orderBy('j.tanggal_jurnal', 'asc')
            ->orderBy('j.id', 'asc'); // tambahan agar urutan konsisten

        // Apply date filter
        if ($request->filled('from') && $request->filled('to')) {
            $from = $request->from;
            $to = $request->to;
            $baseQuery->whereBetween('j.tanggal_jurnal', [$from, $to]);
        }

        // Apply account filter (kode akun)
        if ($request->filled('account')) {
            $baseQuery->where('d.kode_akun', $request->account);
        }

        // Ambil semua baris yang cocok untuk menghitung running balance
        $allRows = $baseQuery->get();

        // Hitung running balance (saldo) kumulatif
        $running = 0;
        $withSaldo = $allRows->map(function ($row) use (&$running) {
            $running += (float)$row->debit - (float)$row->kredit;
            $row->saldo = $running;
            return $row;
        });

        // Pagination manual agar saldo tetap akurat antar halaman
        $perPage = (int) $request->get('entries', 10);
        $page = LengthAwarePaginator::resolveCurrentPage();
        $offset = ($page - 1) * $perPage;

        $currentItems = $withSaldo->slice($offset, $perPage)->values();

        $ledgers = new LengthAwarePaginator(
            $currentItems,
            $withSaldo->count(),
            $perPage,
            $page,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'query' => $request->query(),
            ]
        );

        // pastikan view yang kamu pakai menerima $ledgers dan $accounts
        return view('accounting.ledger.index', compact('ledgers', 'accounts'));
    }
}
