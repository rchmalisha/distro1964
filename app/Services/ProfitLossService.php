<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ProfitLossService
{
    protected $month;
    protected $year;

    public function __construct($month = null, $year = null)
    {
        $this->month = $month;
        $this->year  = $year;
    }

    protected function filterJournalDate($query)
    {
        if ($this->year) {
            $query->whereYear('tanggal_jurnal', $this->year);
        }
        if ($this->month && $this->month !== 'all') {
            $query->whereMonth('tanggal_jurnal', $this->month);
        }
    }

    protected function filterTransactionDate($query)
    {
        if ($this->year) {
            $query->whereYear('tanggal', $this->year);
        }
        if ($this->month && $this->month !== 'all') {
            $query->whereMonth('tanggal', $this->month);
        }
    }

    protected function filterPurchasingDate($query)
    {
        if ($this->year) {
            $query->whereYear('tanggal_pembelian', $this->year);
        }
        if ($this->month && $this->month !== 'all') {
            $query->whereMonth('tanggal_pembelian', $this->month);
        }
    }

    public function calculate()
    {
        /* ===============================
         * 1. PENDAPATAN
         * =============================== */
        $pendapatan = DB::table('general_journal_details as d')
            ->join('general_journals as j', 'd.general_journal_id', '=', 'j.id')
            ->where('d.kode_akun', '4101')
            ->where(function($q) { $this->filterJournalDate($q); })
            ->sum('d.kredit');

        /* ===============================
         * 2. BIAYA BAHAN HABIS PAKAI
         * =============================== */
        $materials = [
            'biaya_dtf'                 => 'MT-01',
            'biaya_polyflex_standar'    => 'MT-02',
            'biaya_polyflex_stabilo'    => 'MT-03',
            'biaya_polyflex_reflective' => 'MT-04',
            'biaya_polyflex_gold'       => 'MT-05',
            'biaya_polyflex_silver'     => 'MT-06',
        ];

        $materialCosts = [];
        $total_bahan_habis_pakai = 0;

        foreach ($materials as $key => $kodeBahan) {
            $nilai = DB::table('purchasings')
                ->where('kode_bahan', $kodeBahan)
                ->where(function($q) { $this->filterPurchasingDate($q); })
                ->sum('total_harga');

            $materialCosts[$key] = $nilai;
            $total_bahan_habis_pakai += $nilai;
        }

        /* ===============================
         * 2a. KANTONG KRESEK
         * =============================== */
        $biaya_kantong_kresek = DB::table('general_transactions')
            ->where('kode_akun', '5101')
            ->where(function($q) { $this->filterTransactionDate($q); })
            ->sum('nominal');

        $total_bahan_habis_pakai += $biaya_kantong_kresek;

        /* ===============================
         * 3. BIAYA OPERASIONAL
         * =============================== */
        $biaya_listrik_internet = DB::table('general_transactions')
            ->where('kode_akun', '5102')
            ->where(function($q) { $this->filterTransactionDate($q); })
            ->sum('nominal');

        $biaya_tinta_dtf = DB::table('general_transactions')
            ->where('kode_akun', '5103')
            ->where(function($q) { $this->filterTransactionDate($q); })
            ->sum('nominal');

        $total_operasional = $biaya_listrik_internet + $biaya_tinta_dtf;

        /* ===============================
         * 4. TOTAL BIAYA & LABA RUGI
         * =============================== */
        $total_biaya = $total_bahan_habis_pakai + $total_operasional;
        $laba_rugi   = $pendapatan - $total_biaya;

        return array_merge([
            'pendapatan' => $pendapatan,
            'biaya_kantong_kresek' => $biaya_kantong_kresek,
            'biaya_listrik_internet' => $biaya_listrik_internet,
            'biaya_tinta_dtf' => $biaya_tinta_dtf,
            'total_bahan_habis_pakai' => $total_bahan_habis_pakai,
            'total_operasional' => $total_operasional,
            'total_biaya' => $total_biaya,
            'laba_rugi' => $laba_rugi,
        ], $materialCosts);
    }
}
