<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class GeneralLedgerController extends Controller
{
    /**
     * Menampilkan Buku Besar (General Ledger) dengan perhitungan Saldo Awal dan Running Balance.
     * Saldo Awal dihitung dari accounts.saldo_awal ditambah mutasi jurnal sebelumnya.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $accounts = Account::orderBy('kode_akun', 'asc')->get();
        
        // --- 1. INISIALISASI VARIABEL PENTING ---
        // Inisialisasi wajib untuk menghindari error 'Undefined variable' jika filter kosong.
        $ledgers = new LengthAwarePaginator([], 0, 10, 1); 
        $initialRow = null; 
        $isDebitNormal = true; // Default

        // Validasi: Pastikan semua filter wajib sudah terisi
        if ($request->filled('from') && $request->filled('to') && $request->filled('account')) {
            $from = $request->from;
            $to = $request->to;
            $selectedAccountCode = $request->account;

            // --- 2. AMBIL DETAIL AKUN (SALDO NORMAL & SALDO AWAL MASTER) ---
            $selectedAccount = Account::where('kode_akun', $selectedAccountCode)->first();
            
            if (!$selectedAccount) {
                // Akun tidak ditemukan
                return view('accounting.ledger.index', compact('ledgers', 'accounts', 'initialRow'));
            }
            
            // Dapatkan tipe saldo normal dari field 'saldo_normal' (D atau K)
            $isDebitNormal = $selectedAccount->saldo_normal === 'debit';

            // Dapatkan Saldo Awal master dari field 'saldo_awal'
            $masterInitialBalance = (float) $selectedAccount->saldo_awal; 
            
            // --- 3. HITUNG NET MUTASI TRANSAKSI SEBELUM TANGGAL FILTER ($from) ---
            
            $movementBeforeFilterQuery = DB::table('general_journal_details as d')
                ->join('general_journals as j', 'd.general_journal_id', '=', 'j.id')
                ->where('j.tanggal_jurnal', '<', $from)
                ->where('d.kode_akun', $selectedAccountCode); 

            $movementResult = $movementBeforeFilterQuery->select(
                DB::raw('SUM(d.debit) as total_debit'), 
                DB::raw('SUM(d.kredit) as total_kredit')
            )->first();

            // Hitung Net Mutasi (sesuai saldo normal)
            $netMovementBeforeFilter = 0;
            if ($movementResult) {
                $totalDebit = (float)$movementResult->total_debit;
                $totalKredit = (float)$movementResult->total_kredit;

                if ($isDebitNormal) {
                    // Akun normal Debit: (Debit - Kredit)
                    $netMovementBeforeFilter = $totalDebit - $totalKredit; 
                } else {
                    // Akun normal Kredit: (Kredit - Debit)
                    $netMovementBeforeFilter = $totalKredit - $totalDebit;
                }
            }

            // SALDO AWAL LARI = Saldo Master (dari Accounts) + Net Mutasi Sebelumnya (dari Jurnal)
            $initialRunningBalance = $masterInitialBalance + $netMovementBeforeFilter;
            
            // Baris Saldo Awal yang akan ditampilkan
            $initialRow = (object) [
                'kode_jurnal' => '',
                'tanggal_jurnal' => $from, 
                'keterangan_jurnal' => 'Saldo Awal',
                'debit' => 0,
                'kredit' => 0,
                'saldo' => $initialRunningBalance, 
            ];
            
            // --- 4. AMBIL SEMUA TRANSAKSI DALAM PERIODE FILTER [from, to] ---
            
            $transactionQuery = DB::table('general_journal_details as d')
                ->join('general_journals as j', 'd.general_journal_id', '=', 'j.id')
                ->select(
                    'j.kode_jurnal',
                    'j.tanggal_jurnal',
                    'j.keterangan_jurnal',
                    'd.debit',
                    'd.kredit',
                    'd.kode_akun'
                )
                ->whereBetween('j.tanggal_jurnal', [$from, $to])
                ->orderBy('j.tanggal_jurnal', 'asc')
                ->orderBy('j.id', 'asc')
                ->where('d.kode_akun', $selectedAccountCode); 

            $transactionRows = $transactionQuery->get();

            // --- 5. HITUNG RUNNING BALANCE PADA SEMUA TRANSAKSI PERIODE FILTER ---
            
            $running = $initialRunningBalance;
            
            $transactionRowsWithSaldo = $transactionRows->map(function ($row) use (&$running, $isDebitNormal) {
                $debit = (float)$row->debit;
                $kredit = (float)$row->kredit;

// Penambahan/Pengurangan saldo disesuaikan dengan tipe saldo normal
                if ($isDebitNormal) {
                    // Akun Normal Debit: Debit (+) Kredit (-)
                    $running += $debit; 
                    $running -= $kredit; 
                } else {
                    // Akun Normal Kredit: Kredit (+) Debit (-)
                    $running += $kredit; 
                    $running -= $debit; 
                }

                $row->saldo = $running;
                return $row;
            });

            // --- 6. PAGINATION MANUAL (MEMASTIKAN SALDO AWAL MUNCUL DI HALAMAN 1) ---
            
            $perPage = (int) $request->get('entries', 10);
            $page = LengthAwarePaginator::resolveCurrentPage();
            
            $totalTransactions = $transactionRowsWithSaldo->count();
            $offset = ($page - 1) * $perPage;

            $currentTransactions = $transactionRowsWithSaldo->slice($offset, $perPage)->values();
            
            // Saldo Awal hanya digabungkan jika berada di halaman 1
            $currentItems = ($page == 1) 
                ? collect([$initialRow])->merge($currentTransactions)
                : $currentTransactions;

            // Total item di paginator harus mencakup saldo awal
            $totalItemsForPaginator = $totalTransactions + 1;
            
            $ledgers = new LengthAwarePaginator(
                $currentItems,
                $totalItemsForPaginator,
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'query' => $request->query(),
                ]
            );
        }

        // --- 7. KIRIM DATA KE VIEW ---
        return view('accounting.ledger.index', compact('ledgers', 'accounts', 'initialRow'));
    }
}