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

        // Tentukan periode awal & akhir
        if (!empty($month) && $month !== 'all') {
            // Jika user memilih bulan tertentu
            $startDate = Carbon::createFromDate((int)$year, (int)$month, 1)->startOfMonth();
            $endDate   = Carbon::createFromDate((int)$year, (int)$month, 1)->endOfMonth();
        } else {
            // Jika user memilih "Semua Bulan"
            $startDate = Carbon::createFromDate((int)$year, 1, 1)->startOfYear();
            $endDate   = Carbon::createFromDate((int)$year, 12, 31)->endOfYear();
            $month = 'all';
        }

        // Ambil semua akun
        $accounts = Account::select('id', 'kode_akun', 'nama_akun', 'jenis_akun', 'saldo_awal')->get()->keyBy('kode_akun');

        // ================================
        // Hitung saldo awal dinamis
        // ================================
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

        // ================================
        // Ambil mutasi transaksi dalam periode terpilih
        // ================================
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

        // ================================
        // Hitung saldo akhir untuk setiap akun
        // ================================
        foreach ($accounts as $akun) {
            $awal = $saldoAwalTambahan->get($akun->kode_akun);
            $mutasi = $journalMutations->get($akun->kode_akun);

            $awal_debit  = $awal->total_debit ?? 0;
            $awal_kredit = $awal->total_kredit ?? 0;
            $mutasi_debit  = $mutasi->total_debit ?? 0;
            $mutasi_kredit = $mutasi->total_kredit ?? 0;

            // Hitung saldo awal berjalan
            if (in_array(strtolower($akun->jenis_akun), ['aset lancar', 'aset tetap'])) {
                $saldo_awal_berjalan = $akun->saldo_awal + ($awal_debit - $awal_kredit);
                $akun->saldo = $saldo_awal_berjalan + ($mutasi_debit - $mutasi_kredit);
            } else {
                $saldo_awal_berjalan = $akun->saldo_awal + ($awal_kredit - $awal_debit);
                $akun->saldo = $saldo_awal_berjalan + ($mutasi_kredit - $mutasi_debit);
            }
        }

        // ================================
        // Kelompokkan akun berdasarkan jenis
        // ================================
        $aset_lancar = $accounts->where('jenis_akun', 'aset lancar')->values();
        $aset_tetap  = $accounts->where('jenis_akun', 'aset tetap')->values();
        $liabilitas  = $accounts->where('jenis_akun', 'liabilitas')->values();
        $ekuitas     = $accounts->where('jenis_akun', 'ekuitas')->values();

        // ================================
        // Hitung total per kategori
        // ================================
        $total_aset_lancar = $aset_lancar->sum('saldo');
        $total_aset_tetap  = $aset_tetap->sum('saldo');
        $total_liabilitas  = $liabilitas->sum('saldo');
        $total_ekuitas     = $ekuitas->sum('saldo');

        // ================================
        // Total besar
        // ================================
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
        $month = $request->get('month');
        $year  = $request->get('year') ?? date('Y');

        // Tentukan periode awal & akhir
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

        // Ambil semua akun
        $accounts = Account::select('id', 'kode_akun', 'nama_akun', 'jenis_akun', 'saldo_awal')->get()->keyBy('kode_akun');

        // Ambil mutasi jurnal
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

        // Hitung saldo akhir
        foreach ($accounts as $akun) {
            $mutasi = $journalMutations->get($akun->kode_akun);
            $total_debit  = $mutasi->total_debit ?? 0;
            $total_kredit = $mutasi->total_kredit ?? 0;

            $akun->saldo = in_array(strtolower($akun->jenis_akun), ['aset lancar', 'aset tetap'])
                ? $akun->saldo_awal + ($total_debit - $total_kredit)
                : $akun->saldo_awal + ($total_kredit - $total_debit);
        }

        // Kelompokkan akun
        $aset_lancar = $accounts->where('jenis_akun', 'aset lancar')->values();
        $aset_tetap  = $accounts->where('jenis_akun', 'aset tetap')->values();
        $liabilitas  = $accounts->where('jenis_akun', 'liabilitas')->values();
        $ekuitas     = $accounts->where('jenis_akun', 'ekuitas')->values();

        // Hitung total
        $total_aset_lancar = $aset_lancar->sum('saldo');
        $total_aset_tetap  = $aset_tetap->sum('saldo');
        $total_liabilitas  = $liabilitas->sum('saldo');
        $total_ekuitas     = $ekuitas->sum('saldo');

        $total_aset = $total_aset_lancar + $total_aset_tetap;
        $total_liabilitas_ekuitas = $total_liabilitas + $total_ekuitas;

        // Nama file PDF (bisa digunakan nanti untuk export)
        // Tambahkan nama bulan untuk PDF
        if ($month !== 'all') {
            $monthName = Carbon::createFromDate($year, $month, 1)->locale('id')->translatedFormat('F');
        } else {
            $monthName = 'Januari-Desember';
        }
        $fileName = "Neraca_{$monthName}_{$year}.pdf";

        // Render view preview tanpa auto print
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

    private function getBalanceSheetData($month, $year)
    {
        if (!empty($month) && $month !== 'all') {
            $startDate = Carbon::createFromDate((int)$year, (int)$month, 1)->startOfMonth();
            $endDate   = Carbon::createFromDate((int)$year, (int)$month, 1)->endOfMonth();
        } else {
            $startDate = Carbon::createFromDate((int)$year, 1, 1)->startOfYear();
            $endDate   = Carbon::createFromDate((int)$year, 12, 31)->endOfYear();
            $month = 'all';
        }

        $accounts = Account::select('id', 'kode_akun', 'nama_akun', 'jenis_akun', 'saldo_awal')->get()->keyBy('kode_akun');

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
            $awal = $saldoAwalTambahan->get($akun->kode_akun);
            $mutasi = $journalMutations->get($akun->kode_akun);

            $awal_debit  = $awal->total_debit ?? 0;
            $awal_kredit = $awal->total_kredit ?? 0;
            $mutasi_debit  = $mutasi->total_debit ?? 0;
            $mutasi_kredit = $mutasi->total_kredit ?? 0;

            if (in_array(strtolower($akun->jenis_akun), ['aset lancar', 'aset tetap'])) {
                $saldo_awal_berjalan = $akun->saldo_awal + ($awal_debit - $awal_kredit);
                $akun->saldo = $saldo_awal_berjalan + ($mutasi_debit - $mutasi_kredit);
            } else {
                $saldo_awal_berjalan = $akun->saldo_awal + ($awal_kredit - $awal_debit);
                $akun->saldo = $saldo_awal_berjalan + ($mutasi_kredit - $mutasi_debit);
            }
        }

        $aset_lancar = $accounts->where('jenis_akun', 'aset lancar')->values();
        $aset_tetap  = $accounts->where('jenis_akun', 'aset tetap')->values();
        $liabilitas  = $accounts->where('jenis_akun', 'liabilitas')->values();
        $ekuitas     = $accounts->where('jenis_akun', 'ekuitas')->values();

        $total_aset_lancar = $aset_lancar->sum('saldo');
        $total_aset_tetap  = $aset_tetap->sum('saldo');
        $total_liabilitas  = $liabilitas->sum('saldo');
        $total_ekuitas     = $ekuitas->sum('saldo');

        $total_aset = $total_aset_lancar + $total_aset_tetap;
        $total_liabilitas_ekuitas = $total_liabilitas + $total_ekuitas;

        return compact(
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
        );
    }
}
