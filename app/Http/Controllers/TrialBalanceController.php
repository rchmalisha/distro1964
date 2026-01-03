<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class TrialBalanceController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month'); // 1–12 | '0' (Semua Bulan)
        $year  = $request->input('year');  // yyyy

        $trialBalance = collect();

        if (!$year) {
            return view('accounting.trial_balance.index', compact(
                'trialBalance',
                'month',
                'year'
            ));
        }

        $accounts = Account::orderBy('kode_akun')->get();

        $trialBalance = $accounts->map(function ($acc) use ($month, $year) {

            $masterSaldoAwal = (float) $acc->saldo_awal;

            /*
            =====================================================
            MODE 1: SEMUA BULAN (JANUARI–DESEMBER)
            =====================================================
            */
            if ($month === '0') {

                $mutasi = DB::table('general_journal_details as d')
                    ->join('general_journals as j', 'd.general_journal_id', '=', 'j.id')
                    ->selectRaw('SUM(d.debit) as debit, SUM(d.kredit) as kredit')
                    ->where('d.kode_akun', $acc->kode_akun)
                    ->whereYear('j.tanggal_jurnal', $year)
                    ->first();

                $debit  = (float) ($mutasi->debit ?? 0);
                $kredit = (float) ($mutasi->kredit ?? 0);

                if ($acc->saldo_normal === 'debit') {
                    $saldoAkhir = $masterSaldoAwal + ($debit - $kredit);
                } else {
                    $saldoAkhir = $masterSaldoAwal + ($kredit - $debit);
                }

            /*
            =====================================================
            MODE 2: BULAN TERTENTU
            =====================================================
            */
            } else {

                // MUTASI SEBELUM BULAN TERPILIH
                $before = DB::table('general_journal_details as d')
                    ->join('general_journals as j', 'd.general_journal_id', '=', 'j.id')
                    ->selectRaw('SUM(d.debit) as debit, SUM(d.kredit) as kredit')
                    ->where('d.kode_akun', $acc->kode_akun)
                    ->whereYear('j.tanggal_jurnal', $year)
                    ->whereMonth('j.tanggal_jurnal', '<', $month)
                    ->first();

                $beforeDebit  = (float) ($before->debit ?? 0);
                $beforeKredit = (float) ($before->kredit ?? 0);

                if ($acc->saldo_normal === 'debit') {
                    $saldoAwalPeriode = $masterSaldoAwal + ($beforeDebit - $beforeKredit);
                } else {
                    $saldoAwalPeriode = $masterSaldoAwal + ($beforeKredit - $beforeDebit);
                }

                // MUTASI BULAN BERJALAN
                $periode = DB::table('general_journal_details as d')
                    ->join('general_journals as j', 'd.general_journal_id', '=', 'j.id')
                    ->selectRaw('SUM(d.debit) as debit, SUM(d.kredit) as kredit')
                    ->where('d.kode_akun', $acc->kode_akun)
                    ->whereYear('j.tanggal_jurnal', $year)
                    ->whereMonth('j.tanggal_jurnal', $month)
                    ->first();

                $periodeDebit  = (float) ($periode->debit ?? 0);
                $periodeKredit = (float) ($periode->kredit ?? 0);

                if ($acc->saldo_normal === 'debit') {
                    $saldoAkhir = $saldoAwalPeriode + ($periodeDebit - $periodeKredit);
                } else {
                    $saldoAkhir = $saldoAwalPeriode + ($periodeKredit - $periodeDebit);
                }
            }

            // SALDO AKHIR HANYA TAMPIL DI 1 KOLOM
            return (object) [
                'kode_akun' => $acc->kode_akun,
                'nama_akun' => $acc->nama_akun,
                'debit'     => $acc->saldo_normal === 'debit' ? max($saldoAkhir, 0) : 0,
                'kredit'    => $acc->saldo_normal === 'kredit' ? max($saldoAkhir, 0) : 0,
            ];
        });

        return view('accounting.trial_balance.index', compact(
            'trialBalance',
            'month',
            'year'
        ));
    }
}
