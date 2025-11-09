<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        $lastMaterial = Material::latest('kode_bahan')->first();
        $num = $lastMaterial ? intval(substr($lastMaterial->kode_bahan, 3)) + 1 : 1;
        $kodeBahan = 'MT-' . str_pad($num, 2, '0', STR_PAD_LEFT);

        $data = Material::all();
        return view('materials.index', compact('data', 'kodeBahan'));
    }

    public function create()
    {
        return view('materials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'kategori_bahan' => 'required|in:dtf,polyflex',
            'harga_bahan' => 'required|numeric',
        ]);

        Material::create([
            'nama_bahan' => $request->nama_bahan,
            'kategori_bahan' => $request->kategori_bahan,
            'harga_bahan' => $request->harga_bahan,
        ]);

        return redirect()->route('materials.index')->with('success', 'Data Bahan berhasil ditambahkan.');
    }

    public function update (Request $request, $kode_bahan)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'kategori_bahan' => 'required|in:dtf,polyflex',
            'harga_bahan' => 'required|numeric',
        ]);

        $material = Material::findOrFail($kode_bahan);
        $material->update([
            'nama_bahan' => $request->nama_bahan,
            'kategori_bahan' => $request->kategori_bahan,
            'harga_bahan' => $request->harga_bahan,
        ]);

        return redirect()->route('materials.index')->with('success', 'Data Bahan berhasil diperbarui.');
    }

    public function destroy($kode_bahan)
    {
        $material = Material::findOrFail($kode_bahan);
        $material->delete();

        return redirect()->route('materials.index')->with('success', 'Data Bahan berhasil dihapus.');
    }
}
