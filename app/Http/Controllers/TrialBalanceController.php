<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class TrialBalanceController extends Controller
{
    public function index(Request $request)
    {
        // Ambil input bulan dan tahun tanpa default
        $month = $request->input('month');
        $year = $request->input('year');

        $trialBalance = collect();

        // Jika bulan & tahun dipilih baru ambil data
        if ($month && $year) {
            // Ambil semua akun
            $accounts = Account::orderBy('kode_akun', 'asc')->get();

            // Ambil saldo total debit dan kredit dari jurnal untuk bulan & tahun tersebut
            $balances = DB::table('general_journal_details as d')
                ->join('general_journals as j', 'd.general_journal_id', '=', 'j.id')
                ->select(
                    'd.kode_akun',
                    DB::raw('SUM(d.debit) as total_debit'),
                    DB::raw('SUM(d.kredit) as total_kredit')
                )
                ->whereMonth('j.tanggal_jurnal', $month)
                ->whereYear('j.tanggal_jurnal', $year)
                ->groupBy('d.kode_akun')
                ->get()
                ->keyBy('kode_akun');

            // Gabungkan hasil dengan daftar akun
            $trialBalance = $accounts->map(function ($acc) use ($balances) {
                $saldo = $balances[$acc->kode_akun] ?? null;
                return (object) [
                    'kode_akun' => $acc->kode_akun,
                    'nama_akun' => $acc->nama_akun,
                    'debit' => $saldo->total_debit ?? 0,
                    'kredit' => $saldo->total_kredit ?? 0,
                ];
            });
        }

        return view('accounting.trial_balance.index', [
            'trialBalance' => $trialBalance,
            'month' => $month,
            'year' => $year,
        ]);
    }
}
