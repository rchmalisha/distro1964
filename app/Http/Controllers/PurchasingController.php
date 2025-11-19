<?php

namespace App\Http\Controllers;

use App\Models\Purchasing;
use Illuminate\Http\Request;
use App\Services\JournalService;
use Illuminate\Support\Facades\DB;

class PurchasingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchasings = Purchasing::orderByDesc('created_at')->get();
        return view('purchasing.index', compact('purchasings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchasing $purchasing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchasing $purchasing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchasing $purchasing, JournalService $journalService)
    {
        $validated = $request->validate([
            'tanggal_pembelian' => 'required|date',
            'pemasok' => 'required|string',
            'harga_per_meter' => 'required|numeric',
            'total_harga' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {

            // Update data pembelian
            $purchasing->update($validated);

            // === TAMBAHKAN BAGIAN INI (JURNAL OTOMATIS) ===
            $journalService->createJournal(
                tanggal: $validated['tanggal_pembelian'],
                keterangan: 'Pembelian bahan habis pakai - ' . $purchasing->nama_bahan,
                entries: [
                    [
                        'kode_akun' => '5105',     // Beban Bahan Habis Pakai
                        'posisi' => 'debit',
                        'nominal' => $validated['total_harga'],
                    ],
                    [
                        'kode_akun' => '1101',     // Kas
                        'posisi' => 'kredit',
                        'nominal' => $validated['total_harga'],
                    ],
                ],
                ref_tipe: 'purchasing',
                ref_id: $purchasing->id
            );
            // === END JURNAL OTOMATIS ===

            // Menghapus kebutuhan bahan agar tidak duplikat
            \App\Models\MaterialNeeds::where('kode_bahan', $purchasing->kode_bahan)->delete();

            DB::commit();

            return redirect()->route('purchasing.index')->with('success', 'Data pembelian berhasil diperbarui & jurnal otomatis tercatat.');

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Gagal mencatat jurnal: '.$e->getMessage());

            return redirect()->route('purchasing.index')->with('error', 'Pembelian tersimpan, namun jurnal gagal dicatat.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchasing $purchasing)
    {
        //
    }

    /**
     * Create purchasing entry from material need (custom action)
     */
    public function createFromNeed(Request $request)
    {
        $validated = $request->validate([
            'kode_bahan' => 'required|string',
            'nama_bahan' => 'required|string',
            'total_meter' => 'required|numeric',
        ]);

        // Create new purchasing entry, only fill basic info
        // Prevent creating duplicate pending purchasing for same material
        $exists = Purchasing::where('kode_bahan', $validated['kode_bahan'])->whereNull('tanggal_pembelian')->exists();
        if ($exists) {
            return redirect()->route('purchasing.index')->with('success', 'Sudah ada entri pembelian untuk bahan ini yang belum dilengkapi. Silakan lengkapi entri yang ada.');
        }

        $purchasing = Purchasing::create([
            'kode_bahan' => $validated['kode_bahan'],
            'nama_bahan' => $validated['nama_bahan'],
            'ukuran_meter' => $validated['total_meter'],
            // tanggal_pembelian, pemasok, harga_per_meter, total_harga: null
        ]);

        // Redirect to purchasing index (or show success message)
        return redirect()->route('purchasing.index')->with('success', 'Data kebutuhan bahan berhasil dikirim ke pembelian.');
    }
}
