<?php

namespace App\Http\Controllers;

use App\Models\FixedAsset;
use Illuminate\Http\Request;
use Carbon\Carbon;

use function Symfony\Component\Clock\now;

class FixedAssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assets = FixedAsset::whereNull('tanggal_jual')->get();
        $kodeBahan = $this->generateKodeAset();
        return view('fixed_assets.index', compact('assets', 'kodeBahan'));
    }

    /**
     * Generate unique asset code based on date
     */
    public function generateKodeAset()
    {
        $date = now()->format('Ymd');
        $last = FixedAsset::where('kode_aset', 'like', $date . '%')->orderBy('kode_aset', 'desc')->first();

        $num = $last ? intval(substr($last->kode_aset, -3)) + 1 : 1;
        return $date . str_pad($num, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Store new fixed asset
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_aset' => 'required|unique:fixed_assets,kode_aset',
            'nama_aset' => 'required|string|max:255',
            'kategori_aset' => 'required|string|max:100',
            'tanggal_perolehan' => 'required|date',
            'harga_perolehan' => 'required|numeric|min:0',
            'nilai_residu' => 'required|numeric|min:0',
            'umur_ekonomis' => 'required|integer|min:1',
            'metode_penyusutan' => 'required|in:garis_lurus',
            'catatan' => 'nullable|string',
        ]);

        FixedAsset::create($request->all());
        return redirect()->route('fixed_assets.index')->with('success', 'Aset Tetap berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function getDetail($id)
    {
        $asset = FixedAsset::findOrFail($id);

        // Prepare full depreciation schedule (monthly prorated distribution across calendar years)
        $harga = (float) $asset->harga_perolehan;
        $residu = (float) $asset->nilai_residu;
        $umur = (int) $asset->umur_ekonomis;
        $metode = $asset->metode_penyusutan ?? 'garis_lurus';

        $total_months = $umur * 12;
        $depreciable_amount = max(0, $harga - $residu);

        $monthly_depr = $total_months > 0 ? ($depreciable_amount / $total_months) : 0;

        $start = Carbon::parse($asset->tanggal_perolehan);
        $startYear = (int) $start->format('Y');
        $startMonth = (int) $start->format('n'); // 1-12

        $months_counted = 0;
        $akumulasi = 0;
        $schedule = [];

        $year = $startYear;
        $no = 1;

        while ($months_counted < $total_months) {
            if ($months_counted === 0) {
                $months_this = min(12 - ($startMonth - 1), $total_months - $months_counted);
            } else {
                $months_this = min(12, $total_months - $months_counted);
            }

            if ($months_this <= 0) {
                $depr_this = 0;
            } else {
                $depr_this = (int) round($monthly_depr * $months_this);
            }

            $months_counted += $months_this;
            $akumulasi += $depr_this;
            $nilai_buku = $harga - $akumulasi;

            $schedule[] = [
                'no' => $no,
                'tahun' => $year,
                'harga_perolehan' => $harga,
                'nilai_residu' => $residu,
                'penyusutan' => $depr_this,
                'akumulasi' => $akumulasi,
                'nilai_buku' => $nilai_buku,
                'months' => $months_this,
            ];

            $year++;
            $no++;
        }

        // Adjust rounding difference on last entry so akumulasi equals depreciable amount
        $lastIndex = count($schedule) - 1;
        if ($lastIndex >= 0) {
            $calculated_akumulasi = $schedule[$lastIndex]['akumulasi'];
            $diff = round($depreciable_amount - $calculated_akumulasi);
            if ($diff !== 0) {
                $schedule[$lastIndex]['penyusutan'] += $diff;
                $schedule[$lastIndex]['akumulasi'] += $diff;
                $schedule[$lastIndex]['nilai_buku'] -= $diff;
            }
        }

        $data = $asset->toArray();
        $data['schedule'] = $schedule;

        return response()->json($data);
    }

    /**
     * Update fixed asset
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_aset' => 'required|string|max:255',
            'kategori_aset' => 'required|string|max:100',
            'tanggal_perolehan' => 'required|date',
            'harga_perolehan' => 'required|numeric|min:0',
            'nilai_residu' => 'required|numeric|min:0',
            'umur_ekonomis' => 'required|integer|min:1',
            'metode_penyusutan' => 'required|in:garis_lurus',
            'catatan' => 'nullable|string',
        ]);

        $asset = FixedAsset::findOrFail($id);
        $asset->update($request->all());
        return redirect()->route('fixed_assets.index')->with('success', 'Aset Tetap berhasil diperbarui.');
    }

    /**
     * Record asset sale
     */
    public function recordSale(Request $request, $id)
    {
        $asset = FixedAsset::findOrFail($id);

        $request->validate([
            'tanggal_jual' => 'required|date',
            'harga_jual' => 'required|numeric|min:0',
            'pembeli' => 'required|string|max:255',
        ]);

        $asset->update($request->only('tanggal_jual', 'harga_jual', 'pembeli'));
        return redirect()->route('fixed_assets.index')->with('success', 'Penjualan aset berhasil dicatat.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $asset = FixedAsset::findOrFail($id);
        $asset->delete();
        return redirect()->route('fixed_assets.index')->with('success', 'Aset Tetap berhasil dihapus.');
    }
}
