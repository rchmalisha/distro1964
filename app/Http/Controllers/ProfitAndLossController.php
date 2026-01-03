<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ProfitAndLossController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month;
        $year  = $request->year;

        /* ===============================
         * FILTER TANGGAL (REUSABLE)
         * =============================== */
        $filterJournalDate = function ($q) use ($month, $year) {
            if ($year) {
                $q->whereYear('tanggal_jurnal', $year);
            }
            if ($month && $month !== 'all') {
                $q->whereMonth('tanggal_jurnal', $month);
            }
        };

        $filterTransactionDate = function ($q) use ($month, $year) {
            if ($year) {
                $q->whereYear('tanggal', $year);
            }
            if ($month && $month !== 'all') {
                $q->whereMonth('tanggal', $month);
            }
        };

        $filterPurchasingDate = function ($q) use ($month, $year) {
            if ($year) {
                $q->whereYear('tanggal_pembelian', $year);
            }
            if ($month && $month !== 'all') {
                $q->whereMonth('tanggal_pembelian', $month);
            }
        };


        /* ===============================
         * 1. PENDAPATAN (4101)
         * =============================== */
        $pendapatan = DB::table('general_journal_details')
            ->join('general_journals', 'general_journal_details.general_journal_id', '=', 'general_journals.id')
            ->where('general_journal_details.kode_akun', '4101')
            ->where($filterJournalDate)
            ->sum('general_journal_details.kredit');

        /* ===============================
         * 2. BIAYA BAHAN HABIS PAKAI
         * =============================== */
        $materials = [
            'biaya_dtf' => 'MT-01',
            'biaya_polyflex_standar' => 'MT-02',
            'biaya_polyflex_stabilo' => 'MT-03',
            'biaya_polyflex_reflective' => 'MT-04',
            'biaya_polyflex_gold' => 'MT-05',
            'biaya_polyflex_silver' => 'MT-06',
        ];

        $materialCosts = [];
        $total_bahan_habis_pakai = 0;

        foreach ($materials as $key => $kodeBahan) {
            $nilai = DB::table('purchasings')
                ->where('kode_bahan', $kodeBahan)
                ->where($filterPurchasingDate)
                ->sum('total_harga');

            $materialCosts[$key] = $nilai;
            $total_bahan_habis_pakai += $nilai;
        }

        // Kantong Kresek dikosongkan dulu
        $biaya_kantong_kresek = 0;

        /* ===============================
         * 3. BIAYA OPERASIONAL
         * =============================== */
        $biaya_listrik_internet = DB::table('general_transactions')
            ->where('kode_akun', '5102')
            ->where($filterTransactionDate)
            ->sum('nominal');

        $biaya_tinta_dtf = DB::table('general_transactions')
            ->where('kode_akun', '5103')
            ->where($filterTransactionDate)
            ->sum('nominal');

        $total_operasional = $biaya_listrik_internet + $biaya_tinta_dtf;

        /* ===============================
         * 4. TOTAL BIAYA & LABA RUGI
         * =============================== */
        $total_biaya = $total_bahan_habis_pakai + $total_operasional;
        $laba_rugi   = $pendapatan - $total_biaya;

        return view('reports.profit_and_loss.index', array_merge(
            compact(
                'month',
                'year',
                'pendapatan',
                'total_bahan_habis_pakai',
                'total_operasional',
                'total_biaya',
                'laba_rugi',
                'biaya_kantong_kresek',
                'biaya_listrik_internet',
                'biaya_tinta_dtf'
            ),
            $materialCosts
        ));
    }

    /* ===============================
     * PRINT PDF
     * =============================== */
    public function print(Request $request)
    {
        $data = $this->index($request)->getData();

        $pdf = Pdf::loadView('reports.profit_and_loss.print', (array) $data);

        $month = $request->month;
        $year  = $request->year;

        $filename = $month === 'all'
            ? "Laporan_Laba_Rugi_{$year}.pdf"
            : "Laporan_Laba_Rugi_{$month}_{$year}.pdf";

        return $pdf->stream($filename);
    }
}
