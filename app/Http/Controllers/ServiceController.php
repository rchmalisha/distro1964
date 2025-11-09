<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // Menampilkan semua data jasa
     public function index()
    {
        $services = Service::all();

        // 🔹 Buat kode otomatis di sini
        $lastService = Service::orderBy('kode_jasa', 'desc')->first();

        if (!$lastService) {
            $newCode = 'SE-001';
        } else {
            $lastNumber = (int) substr($lastService->kode_jasa, 3);
            $newCode = 'SE-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        }

        return view('services.index', compact('services', 'newCode'));
    }

    // Menampilkan form tambah jasa
    // public function create()
    // {
    //     // Ambil kode terakhir dari database
    //     $lastService = Service::orderBy('kode_jasa', 'desc')->first();

    //     // Tentukan kode baru
    //     if (!$lastService) {
    //         $newCode = 'SE-001';
    //     } else {
    //         $lastNumber = (int) substr($lastService->kode_jasa, 3);
    //         $newCode = 'SE-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    //     }

    //     return view('services.create', compact('newCode'));
    // }


    // Menyimpan data jasa baru
    public function store(Request $request)
    {
        $request->validate([
            'kode_jasa' => 'required|unique:services',
            'nama_jasa' => 'required',
            'kategori_jasa' => 'required',
            'harga' => 'required|numeric',
        ]);

        Service::create($request->all());

        return redirect()->route('services.index')->with('success', 'Data jasa berhasil ditambahkan!');
    }

    // Menampilkan form edit
    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    // Update data jasa
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'nama_jasa' => 'required',
            'kategori_jasa' => 'required',
            'harga' => 'required|numeric',
        ]);

        $service->update($request->all());
        return redirect()->route('services.index')->with('success', 'Data jasa berhasil diperbarui!');
    }

    // Hapus data jasa
    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Data jasa berhasil dihapus!');
    }
}
