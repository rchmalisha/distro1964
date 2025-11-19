<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Models\GeneralJournalDetail;

class ProfitAndLossController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month');
        $year = $request->get('year');

        // Ambil semua akun pendapatan & beban
        $accounts = DB::table('accounts')
            ->whereIn('jenis_akun', ['pendapatan', 'beban'])
            ->get()
            ->keyBy('kode_akun');

        // Ambil total debit & kredit dari jurnal
        $query = DB::table('general_journal_details')
            ->join('general_journals', 'general_journal_details.general_journal_id', '=', 'general_journals.id')
            ->select(
                'general_journal_details.kode_akun',
                DB::raw('SUM(general_journal_details.debit) as total_debit'),
                DB::raw('SUM(general_journal_details.kredit) as total_kredit')
            )
            ->groupBy('general_journal_details.kode_akun');

        // Filter berdasarkan bulan dan tahun
        if ($month && $month != 'all') {
            $query->whereMonth('general_journals.tanggal_jurnal', $month);
        }
        if ($year) {
            $query->whereYear('general_journals.tanggal_jurnal', $year);
        }

        $journalTotals = $query->get()->keyBy('kode_akun');

        // Inisialisasi variabel
        $total_pendapatan = 0;
        $total_beban = 0;
        $pendapatan_akun = [];
        $beban_akun = [];

        // Hitung berdasarkan akun yang muncul di jurnal
        foreach ($journalTotals as $kode => $data) {
            if (!isset($accounts[$kode])) continue;

            $akun = $accounts[$kode];
            $jenis = strtolower($akun->jenis_akun);
            $nama = $akun->nama_akun;
            $debit = $data->total_debit;
            $kredit = $data->total_kredit;

            if ($jenis === 'pendapatan') {
                $nilai = $kredit - $debit;
                $total_pendapatan += $nilai;
                $pendapatan_akun[] = [
                    'nama_akun' => $nama,
                    'nilai' => $nilai
                ];
            }

            if ($jenis === 'beban') {
                $nilai = $debit - $kredit;
                $total_beban += $nilai;
                $beban_akun[] = [
                    'nama_akun' => $nama,
                    'nilai' => $nilai
                ];
            }
        }

        // Hitung laba bersih
        $laba_bersih = $total_pendapatan - $total_beban;

        return view('reports.profit_and_loss.index', compact(
            'month',
            'year',
            'pendapatan_akun',
            'beban_akun',
            'total_pendapatan',
            'total_beban',
            'laba_bersih'
        ));
    }


    public function print(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        // ambil data sesuai bulan dan tahun
        $pendapatan = GeneralJournalDetail::whereHas('journal', function ($q) use ($year, $month) {
            $q->whereYear('tanggal_jurnal', $year);
            if ($month !== 'all') {
                $q->whereMonth('tanggal_jurnal', $month);
            }
        })
            ->whereHas('account', fn($q) => $q->where('jenis_akun', 'pendapatan'))
            ->sum('kredit');

        $beban = GeneralJournalDetail::whereHas('journal', function ($q) use ($year, $month) {
            $q->whereYear('tanggal_jurnal', $year);
            if ($month !== 'all') {
                $q->whereMonth('tanggal_jurnal', $month);
            }
        })
            ->whereHas('account', fn($q) => $q->where('jenis_akun', 'beban'))
            ->sum('debit');

        $labaBersih = $pendapatan - $beban;

        $pdf = Pdf::loadView('reports.profit_and_loss.print', compact('month', 'year', 'pendapatan', 'beban', 'labaBersih'));

        if ($month === 'all') {
            $filename = 'Laporan_Laba_Rugi_Semua_Bulan_' . $year . '.pdf';
        } else {
            // Pastikan $month berupa integer, bukan string
            $monthInt = (int) $month;

            // Ambil nama bulan dalam Bahasa Indonesia
            $monthName = \Carbon\Carbon::createFromDate(null, $monthInt, 1)
                ->locale('id')
                ->translatedFormat('F');

            $filename = 'Laporan_Laba_Rugi_' . $monthName . '_' . $year . '.pdf';
        }

        return $pdf->stream($filename);
    }

    private function getProfitLossData($month, $year)
    {
        $query = DB::table('general_journal_details')
            ->join('accounts', 'general_journal_details.kode_akun', '=', 'accounts.kode_akun')
            ->join('general_journals', 'general_journal_details.general_journal_id', '=', 'general_journals.id')
            ->select(
                'accounts.nama_akun',
                'accounts.jenis_akun',
                DB::raw('SUM(general_journal_details.debit) as total_debit'),
                DB::raw('SUM(general_journal_details.kredit) as total_kredit')
            )
            ->groupBy('accounts.nama_akun', 'accounts.jenis_akun');

        if ($month && $year && $month !== 'all') {
            $query->whereMonth('general_journals.tanggal_jurnal', $month)
                ->whereYear('general_journals.tanggal_jurnal', $year);
        } elseif ($year && $month === 'all') {
            $query->whereYear('general_journals.tanggal_jurnal', $year);
        }

        $accounts = $query->get();

        $pendapatan = $accounts->where('jenis_akun', 'pendapatan')->sum(fn($a) => $a->total_kredit - $a->total_debit);
        $biaya_atk = $accounts->where('nama_akun', 'like', '%atk%')->sum(fn($a) => $a->total_debit - $a->total_kredit);
        $biaya_gaji = $accounts->where('nama_akun', 'like', '%gaji%')->sum(fn($a) => $a->total_debit - $a->total_kredit);
        $biaya_transportasi = $accounts->where('nama_akun', 'like', '%transport%')->sum(fn($a) => $a->total_debit - $a->total_kredit);
        $biaya_internet = $accounts->where('nama_akun', 'like', '%internet%')->sum(fn($a) => $a->total_debit - $a->total_kredit);
        $biaya_service = $accounts->where('nama_akun', 'like', '%service%')->sum(fn($a) => $a->total_debit - $a->total_kredit);
        $biaya_lainnya = $accounts->where('jenis_akun', 'beban')
            ->whereNotIn('nama_akun', ['Biaya ATK', 'Biaya Gaji', 'Biaya Transportasi', 'Biaya Internet', 'Biaya Service Komputer & Aplikasi'])
            ->sum(fn($a) => $a->total_debit - $a->total_kredit);

        $total_biaya = $biaya_atk + $biaya_gaji + $biaya_transportasi + $biaya_internet + $biaya_service + $biaya_lainnya;
        $laba_rugi = $pendapatan - $total_biaya;

        return compact('pendapatan', 'biaya_atk', 'biaya_gaji', 'biaya_transportasi', 'biaya_internet', 'biaya_service', 'biaya_lainnya', 'total_biaya', 'laba_rugi');
    }
}
