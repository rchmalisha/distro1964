<?php

namespace App\Http\Controllers;

use App\Models\DetailOrder;
use App\Models\MaterialNeeds;
use Illuminate\Http\Request;

class MaterialNeedsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = MaterialNeeds::with(['material', 'order'])
        ->orderBy('tgl_pesan', 'desc')
        ->get();
        return view('materialneeds.index', compact('data'));
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
    public function storeFromOrder($kode_pesan)
    {
        $details = DetailOrder::where('kode_pesan', $kode_pesan)
        ->with(['material','order', 'service'])
        ->get();
        foreach ($details as $detail) {
            $panjang_m = $detail->ukuran_panjang/100;
            $lebar_m = $detail->ukuran_lebar/100;
            $luas_total = $panjang_m * $lebar_m * $detail->jumlah_pesan;
            $waste = 0.1;
            $total_dgn_waste = $luas_total * (1 + $waste);

            MaterialNeeds::create([
                'kode_pesan' => $detail->kode_pesan,
                'kode_bahan' => $detail->kode_bahan,
                'jenis_jasa' => $detail->service->nama_barang ?? '-',
                'jenis_bahan' => $detail->material->kategori_bahan ?? '-',
                'ukuran_panjang' => $detail->ukuran_panjang,
                'ukuran_lebar' => $detail->ukuran_lebar,
                'jumlah_pesanan' => $detail->jumlah_pesan,
                'waste_persen' => 10,
                'kebutuhan_bahan_meter' => $total_dgn_waste,
                'tgl_pesan' => $detail->order->tgl_pesan,
            ]);
        }
        return true;
    }

    /**
     * Display the specified resource.
     */
    public function show(MaterialNeeds $materialNeeds)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MaterialNeeds $materialNeeds)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MaterialNeeds $materialNeeds)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MaterialNeeds $materialNeeds)
    {
        //
    }
}
