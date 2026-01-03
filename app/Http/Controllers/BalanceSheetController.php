<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class BalanceSheetController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month');
        $year  = $request->get('year') ?? date('Y');

        if (!empty($month) && $month !== 'all') {
            $startDate = Carbon::createFromDate((int)$year, (int)$month, 1)->startOfMonth();
            $endDate   = Carbon::createFromDate((int)$year, (int)$month, 1)->endOfMonth();
        } else {
            $startDate = Carbon::createFromDate((int)$year, 1, 1)->startOfYear();
            $endDate   = Carbon::createFromDate((int)$year, 12, 31)->endOfYear();
            $month = 'all';
        }

        // Ambil semua akun termasuk saldo_normal
        $accounts = Account::select('id', 'kode_akun', 'nama_akun', 'jenis_akun', 'saldo_awal', 'saldo_normal')
            ->get()
            ->keyBy('kode_akun');

        // Saldo awal berjalan (transaksi sebelum periode)
        $saldoAwalTambahan = DB::table('general_journal_details as d')
            ->join('general_journals as j', 'j.id', '=', 'd.general_journal_id')
            ->where('j.tanggal_jurnal', '<', $startDate)
            ->whereYear('j.tanggal_jurnal', $year)
            ->select(
                'd.kode_akun',
                DB::raw('COALESCE(SUM(d.debit), 0) as total_debit'),
                DB::raw('COALESCE(SUM(d.kredit), 0) as total_kredit')
            )
            ->groupBy('d.kode_akun')
            ->get()
            ->keyBy('kode_akun');

        // Mutasi periode terpilih
        $journalMutations = DB::table('general_journal_details as d')
            ->join('general_journals as j', 'j.id', '=', 'd.general_journal_id')
            ->whereBetween('j.tanggal_jurnal', [$startDate, $endDate])
            ->select(
                'd.kode_akun',
                DB::raw('COALESCE(SUM(d.debit), 0) as total_debit'),
                DB::raw('COALESCE(SUM(d.kredit), 0) as total_kredit')
            )
            ->groupBy('d.kode_akun')
            ->get()
            ->keyBy('kode_akun');

        // Hitung saldo tiap akun dengan memperhatikan saldo_normal
        foreach ($accounts as $akun) {
            $awal = $saldoAwalTambahan->get($akun->kode_akun);
            $mutasi = $journalMutations->get($akun->kode_akun);

            $awal_debit    = $awal->total_debit ?? 0;
            $awal_kredit   = $awal->total_kredit ?? 0;
            $mutasi_debit  = $mutasi->total_debit ?? 0;
            $mutasi_kredit = $mutasi->total_kredit ?? 0;

            // Hitung net mutasi + saldo awal berdasarkan saldo_normal
            if (strtolower($akun->saldo_normal) === 'debit') {
                // normal debit: saldo = saldo_awal + (debit - kredit)
                $akun->saldo = ($akun->saldo_awal ?? 0)
                    + ($awal_debit - $awal_kredit)
                    + ($mutasi_debit - $mutasi_kredit);
            } else {
                // normal kredit: saldo = saldo_awal + (kredit - debit)
                $akun->saldo = ($akun->saldo_awal ?? 0)
                    + ($awal_kredit - $awal_debit)
                    + ($mutasi_kredit - $mutasi_debit);
            }
        }

        // Kelompokkan
        $aset_lancar = $accounts->where('jenis_akun', 'aset lancar')->values();
        $aset_tetap  = $accounts->where('jenis_akun', 'aset tetap')->values();
        $liabilitas  = $accounts->where('jenis_akun', 'liabilitas')->values();
        $ekuitas     = $accounts->where('jenis_akun', 'ekuitas')->values();

        // Hitung total aset (positif sesuai saldo yang dihitung)
        $total_aset_lancar = $aset_lancar->sum('saldo');
        $total_aset_tetap  = $aset_tetap->sum('saldo');

        // Hitung total liabilitas & ekuitas dengan memperhatikan akun debit-normal yang harus mengurangi
        $total_liabilitas = 0;
        foreach ($liabilitas as $a) {
            $kontribusi = (strtolower($a->saldo_normal) === 'debit') ? -1 * $a->saldo : $a->saldo;
            $total_liabilitas += $kontribusi;
        }

        $total_ekuitas = 0;
        foreach ($ekuitas as $a) {
            $kontribusi = (strtolower($a->saldo_normal) === 'debit') ? -1 * $a->saldo : $a->saldo;
            $total_ekuitas += $kontribusi;
        }

        $total_aset = $total_aset_lancar + $total_aset_tetap;
        $total_liabilitas_ekuitas = $total_liabilitas + $total_ekuitas;

        return view('reports.balance_sheet.index', compact(
            'aset_lancar',
            'aset_tetap',
            'liabilitas',
            'ekuitas',
            'total_aset_lancar',
            'total_aset_tetap',
            'total_liabilitas',
            'total_ekuitas',
            'total_aset',
            'total_liabilitas_ekuitas',
            'month',
            'year'
        ));
    }

    public function print(Request $request)
    {
        // Sama dengan index, pastikan select saldo_normal juga diambil
        $month = $request->get('month');
        $year  = $request->get('year') ?? date('Y');

        if (!empty($month) && $month !== 'all') {
            $startDate = Carbon::createFromDate((int)$year, (int)$month, 1)->startOfMonth();
            $endDate   = Carbon::createFromDate((int)$year, (int)$month, 1)->endOfMonth();
            $monthName = Carbon::createFromDate($year, $month, 1)->locale('id')->translatedFormat('F');
        } else {
            $startDate = Carbon::createFromDate((int)$year, 1, 1)->startOfYear();
            $endDate   = Carbon::createFromDate((int)$year, 12, 31)->endOfYear();
            $month = 'all';
            $monthName = 'Januari-Desember';
        }

        $accounts = Account::select('id', 'kode_akun', 'nama_akun', 'jenis_akun', 'saldo_awal', 'saldo_normal')
            ->get()
            ->keyBy('kode_akun');

        $journalMutations = DB::table('general_journal_details as d')
            ->join('general_journals as j', 'j.id', '=', 'd.general_journal_id')
            ->whereBetween('j.tanggal_jurnal', [$startDate, $endDate])
            ->select(
                'd.kode_akun',
                DB::raw('COALESCE(SUM(d.debit), 0) as total_debit'),
                DB::raw('COALESCE(SUM(d.kredit), 0) as total_kredit')
            )
            ->groupBy('d.kode_akun')
            ->get()
            ->keyBy('kode_akun');

        foreach ($accounts as $akun) {
            $mutasi = $journalMutations->get($akun->kode_akun);
            $total_debit  = $mutasi->total_debit ?? 0;
            $total_kredit = $mutasi->total_kredit ?? 0;

            if (strtolower($akun->saldo_normal) === 'debit') {
                $akun->saldo = ($akun->saldo_awal ?? 0) + ($total_debit - $total_kredit);
            } else {
                $akun->saldo = ($akun->saldo_awal ?? 0) + ($total_kredit - $total_debit);
            }
        }

        $aset_lancar = $accounts->where('jenis_akun', 'aset lancar')->values();
        $aset_tetap  = $accounts->where('jenis_akun', 'aset tetap')->values();
        $liabilitas  = $accounts->where('jenis_akun', 'liabilitas')->values();
        $ekuitas     = $accounts->where('jenis_akun', 'ekuitas')->values();

        $total_aset_lancar = $aset_lancar->sum('saldo');
        $total_aset_tetap  = $aset_tetap->sum('saldo');

        $total_liabilitas = 0;
        foreach ($liabilitas as $a) {
            $total_liabilitas += (strtolower($a->saldo_normal) === 'debit') ? -1 * $a->saldo : $a->saldo;
        }

        $total_ekuitas = 0;
        foreach ($ekuitas as $a) {
            $total_ekuitas += (strtolower($a->saldo_normal) === 'debit') ? -1 * $a->saldo : $a->saldo;
        }

        $total_aset = $total_aset_lancar + $total_aset_tetap;
        $total_liabilitas_ekuitas = $total_liabilitas + $total_ekuitas;

        if ($month !== 'all') {
            $monthName = Carbon::createFromDate($year, $month, 1)->locale('id')->translatedFormat('F');
        } else {
            $monthName = 'Januari-Desember';
        }
        $fileName = "Neraca_{$monthName}_{$year}.pdf";

        return view('reports.balance_sheet.print', compact(
            'aset_lancar',
            'aset_tetap',
            'liabilitas',
            'ekuitas',
            'total_aset_lancar',
            'total_aset_tetap',
            'total_liabilitas',
            'total_ekuitas',
            'total_aset',
            'total_liabilitas_ekuitas',
            'month',
            'year',
            'monthName',
            'fileName'
        ));
    }

    // getBalanceSheetData: jika dipakai, ubah dengan logika yang sama seperti index di atas
    private function getBalanceSheetData($month, $year)
    {
        // Anda bisa implementasikan sama persis seperti index() agar konsisten
        return $this->index(request()); // atau duplikasi logika jika butuh return data bukan view
    }
}
