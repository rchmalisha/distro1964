<?php

namespace App\Http\Controllers;

use App\Models\GeneralTransaction;
use App\Models\Account;
use App\Models\GeneralJournal;
use App\Services\JournalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralTransactionController extends Controller
{
    protected $journalService;

    public function __construct(JournalService $journalService)
    {
        $this->journalService = $journalService;
    }

    /* ===============================
     * INDEX
     * =============================== */
    public function index()
    {
        $transactions = GeneralTransaction::with('account')
            ->orderByDesc('tanggal')
            ->get();

        $accounts = Account::where(function ($q) {
                $q->where('kode_akun', '3101')
                  ->orWhere('kode_akun', '3103')
                  ->orWhere('kode_akun', 'like', '5%');
            })
            ->orderBy('kode_akun')
            ->get();

        return view('general-transaction.index', compact('transactions', 'accounts'));
    }

    /* ===============================
     * VALIDATION RULES
     * =============================== */
    private function validateRequest(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_transaksi' => 'required|in:pemasukan,pengeluaran',
            'kode_akun' => [
                'required',
                function ($attr, $value, $fail) use ($request) {
                    if ($request->jenis_transaksi === 'pemasukan' && $value !== '3101') {
                        $fail('Pemasukan hanya boleh menggunakan akun Modal Pemilik.');
                    }

                    if (
                        $request->jenis_transaksi === 'pengeluaran' &&
                        !($value === '3103' || str_starts_with($value, '5'))
                    ) {
                        $fail('Pengeluaran hanya boleh menggunakan akun Prive atau Beban.');
                    }
                }
            ],
            'nominal' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string'
        ]);
    }

    /* ===============================
     * STORE
     * =============================== */
    public function store(Request $request)
    {
        $this->validateRequest($request);

        DB::transaction(function () use ($request) {

            $transaction = GeneralTransaction::create($request->only([
                'tanggal','jenis_transaksi','kode_akun','nominal','keterangan'
            ]));

            $this->createJournal($request, $transaction->id);
        });

        return redirect()->back()->with('success', 'Transaksi berhasil ditambahkan');
    }

    /* ===============================
     * UPDATE (TIDAK DUPLIKASI)
     * =============================== */
    public function update(Request $request, $id)
    {
        $this->validateRequest($request);

        DB::transaction(function () use ($request, $id) {

            $transaction = GeneralTransaction::findOrFail($id);

            // 1. Update transaksi (TANPA CREATE BARU)
            $transaction->update($request->only([
                'tanggal','jenis_transaksi','kode_akun','nominal','keterangan'
            ]));

            // 2. Hapus jurnal lama
            $oldJournal = GeneralJournal::where('ref_tipe', 'general_transaction')
                ->where('ref_id', $transaction->id)
                ->first();

            if ($oldJournal) {
                $oldJournal->delete(); // detail ikut terhapus (cascade)
            }

            // 3. Buat jurnal baru
            $this->createJournal($request, $transaction->id);
        });

        return redirect()->back()->with('success', 'Transaksi berhasil diperbarui');
    }

    /* ===============================
     * CREATE JOURNAL (REUSABLE)
     * =============================== */
    private function createJournal(Request $request, $refId)
    {
        $akun = Account::where('kode_akun', $request->kode_akun)->first();

        if ($request->jenis_transaksi === 'pemasukan') {

            $entries = [
                ['kode_akun' => '1101', 'posisi' => 'debit',  'nominal' => $request->nominal],
                ['kode_akun' => '3101', 'posisi' => 'kredit', 'nominal' => $request->nominal],
            ];

            $keteranganJurnal = 'Tambahan Modal Pemilik';

        } else {

            $entries = [
                ['kode_akun' => $request->kode_akun, 'posisi' => 'debit',  'nominal' => $request->nominal],
                ['kode_akun' => '1101', 'posisi' => 'kredit', 'nominal' => $request->nominal],
            ];

            if (str_starts_with($request->kode_akun, '5')) {
                $keteranganJurnal = 'Pembayaran ' . $akun->nama_akun
                    . ($request->keterangan ? ' - ' . $request->keterangan : '');
            } else {
                $keteranganJurnal = 'Pengambilan Prive Pemilik';
            }
        }

        $this->journalService->createJournal(
            $request->tanggal,
            $keteranganJurnal,
            $entries,
            'general_transaction',
            $refId
        );
    }

    /* ===============================
     * DESTROY
     * =============================== */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {

            $transaction = GeneralTransaction::findOrFail($id);

            $journal = GeneralJournal::where('ref_tipe', 'general_transaction')
                ->where('ref_id', $transaction->id)
                ->first();

            if ($journal) {
                $journal->delete();
            }

            $transaction->delete();
        });

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus');
    }
}
