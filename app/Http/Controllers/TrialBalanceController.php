<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class TrialBalanceController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month');   // null | 1–12 | '0'
        $year  = $request->input('year');    // null | yyyy

        $trialBalance = collect();

        if ($year) {

            $accounts = Account::orderBy('kode_akun')->get();

            $trialBalance = $accounts->map(function ($acc) use ($month, $year) {

                // ---------------------------------------------------------
                // 1. SALDO AWAL MASTER
                // ---------------------------------------------------------
                $masterSaldoAwal = (float) $acc->saldo_awal;

                // ---------------------------------------------------------
                // 2. MUTASI SEBELUM PERIODE (untuk saldo awal periode)
                // ---------------------------------------------------------
                $mutasiSebelum = DB::table('general_journal_details as d')
                    ->join('general_journals as j', 'd.general_journal_id', '=', 'j.id')
                    ->selectRaw('SUM(d.debit) as total_debit, SUM(d.kredit) as total_kredit')
                    ->where('d.kode_akun', $acc->kode_akun)
                    ->whereYear('j.tanggal_jurnal', '<=', $year);

                if ($month && $month !== '0') {
                    $mutasiSebelum->whereMonth('j.tanggal_jurnal', '<', $month);
                }

                $mutasiSebelum = $mutasiSebelum->first() ?? (object)[
                    'total_debit' => 0,
                    'total_kredit' => 0
                ];

                $beforeDebit  = (float) $mutasiSebelum->total_debit;
                $beforeKredit = (float) $mutasiSebelum->total_kredit;

                // Hitung saldo awal periode
                if ($acc->saldo_normal === 'debit') {
                    $saldoAwalPeriode = $masterSaldoAwal + ($beforeDebit - $beforeKredit);
                } else {
                    $saldoAwalPeriode = $masterSaldoAwal + ($beforeKredit - $beforeDebit);
                }

                // ---------------------------------------------------------
                // 3. MUTASI PERIODE INI
                // ---------------------------------------------------------
                $mutasiPeriode = DB::table('general_journal_details as d')
                    ->join('general_journals as j', 'd.general_journal_id', '=', 'j.id')
                    ->selectRaw('SUM(d.debit) as total_debit, SUM(d.kredit) as total_kredit')
                    ->where('d.kode_akun', $acc->kode_akun)
                    ->whereYear('j.tanggal_jurnal', $year);

                if ($month && $month !== '0') {
                    $mutasiPeriode->whereMonth('j.tanggal_jurnal', $month);
                }

                $mutasiPeriode = $mutasiPeriode->first() ?? (object)[
                    'total_debit' => 0,
                    'total_kredit' => 0
                ];

                $periodeDebit  = (float) $mutasiPeriode->total_debit;
                $periodeKredit = (float) $mutasiPeriode->total_kredit;

                // ---------------------------------------------------------
                // 4. HITUNG SALDO AKHIR
                // ---------------------------------------------------------
                if ($acc->saldo_normal === 'debit') {
                    $saldoAkhir = $saldoAwalPeriode + ($periodeDebit - $periodeKredit);
                } else {
                    $saldoAkhir = $saldoAwalPeriode + ($periodeKredit - $periodeDebit);
                }

                // ---------------------------------------------------------
                // 5. TEMPATKAN SALDO AKHIR HANYA DI 1 KOLOM
                // ---------------------------------------------------------
                $debitCol  = 0;
                $kreditCol = 0;

                if ($acc->saldo_normal === 'debit') {
                    $debitCol = max($saldoAkhir, 0);
                } else {
                    $kreditCol = max($saldoAkhir, 0);
                }

                return (object) [
                    'kode_akun' => $acc->kode_akun,
                    'nama_akun' => $acc->nama_akun,
                    'debit'     => $debitCol,
                    'kredit'    => $kreditCol,
                ];
            });
        }

        return view('accounting.trial_balance.index', compact(
            'trialBalance',
            'month',
            'year'
        ));
    }

}
