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
                $q->where('kode_akun', '3101')   // modal
                  ->orWhere('kode_akun', '3103') // prive
                  ->orWhere('kode_akun', 'like', '5%'); // beban (termasuk 5101)
            })
            ->orderBy('kode_akun')
            ->get();

        return view('general-transaction.index', compact('transactions', 'accounts'));
    }

    /* ===============================
     * VALIDATION
     * =============================== */
    private function validateRequest(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_transaksi' => 'required|in:pemasukan,pengeluaran',
            'kode_akun' => [
                'required',
                function ($attr, $value, $fail) use ($request) {

                    // PEMASUKAN → hanya modal
                    if ($request->jenis_transaksi === 'pemasukan' && $value !== '3101') {
                        $fail('Pemasukan hanya boleh menggunakan akun Modal Pemilik.');
                    }

                    // PENGELUARAN → prive atau beban
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

            $transaction = GeneralTransaction::create([
                'tanggal'          => $request->tanggal,
                'jenis_transaksi'  => $request->jenis_transaksi,
                'kode_akun'        => $request->kode_akun,
                'nominal'          => $request->nominal,
                'keterangan'       => $request->keterangan,
            ]);

            $this->createJournal($request, $transaction->id);
        });

        return redirect()->back()->with('success', 'Transaksi berhasil ditambahkan');
    }

    /* ===============================
     * UPDATE (TANPA DUPLIKASI)
     * =============================== */
    public function update(Request $request, $id)
    {
        $this->validateRequest($request);

        DB::transaction(function () use ($request, $id) {

            $transaction = GeneralTransaction::findOrFail($id);

            $transaction->update([
                'tanggal'          => $request->tanggal,
                'jenis_transaksi'  => $request->jenis_transaksi,
                'kode_akun'        => $request->kode_akun,
                'nominal'          => $request->nominal,
                'keterangan'       => $request->keterangan,
            ]);

            // hapus jurnal lama
            GeneralJournal::where('ref_tipe', 'general_transaction')
                ->where('ref_id', $transaction->id)
                ->delete();

            // buat jurnal baru
            $this->createJournal($request, $transaction->id);
        });

        return redirect()->back()->with('success', 'Transaksi berhasil diperbarui');
    }

    /* ===============================
     * CREATE JOURNAL (FINAL LOGIC)
     * =============================== */
    private function createJournal(Request $request, $refId)
    {
        $akun = Account::where('kode_akun', $request->kode_akun)->first();

        /** ===============================
         * PEMASUKAN
         * =============================== */
        if ($request->jenis_transaksi === 'pemasukan') {

            $entries = [
                ['kode_akun' => '1101', 'posisi' => 'debit',  'nominal' => $request->nominal],
                ['kode_akun' => '3101', 'posisi' => 'kredit', 'nominal' => $request->nominal],
            ];

            $keteranganJurnal = 'Tambahan Modal Pemilik';

        } 
        /** ===============================
         * PENGELUARAN
         * =============================== */
        else {

            $entries = [
                ['kode_akun' => $request->kode_akun, 'posisi' => 'debit',  'nominal' => $request->nominal],
                ['kode_akun' => '1101', 'posisi' => 'kredit', 'nominal' => $request->nominal],
            ];

            // KHUSUS PEMBELIAN KANTONG KRESEK
            if ($request->kode_akun === '5101') {

                $keteranganJurnal = 'Pembelian Kantong Kresek'
                    . ($request->keterangan ? ' - ' . $request->keterangan : '');

            }
            // BEBAN LAIN
            elseif (str_starts_with($request->kode_akun, '5')) {

                $keteranganJurnal = 'Pembayaran ' . $akun->nama_akun
                    . ($request->keterangan ? ' - ' . $request->keterangan : '');

            }
            // PRIVE
            else {

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

            GeneralJournal::where('ref_tipe', 'general_transaction')
                ->where('ref_id', $id)
                ->delete();

            GeneralTransaction::findOrFail($id)->delete();
        });

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus');
    }
}
