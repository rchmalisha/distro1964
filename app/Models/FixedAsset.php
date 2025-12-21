<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixedAsset extends Model
{
    use HasFactory;
    protected $table = 'fixed_assets';
    protected $fillable = [
        'kode_aset',
        'nama_aset',
        'kategori_aset',
        'tanggal_perolehan',
        'harga_perolehan',
        'nilai_residu',
        'umur_ekonomis',
        'metode_penyusutan',
        'tanggal_jual',
        'harga_jual',
        'pembeli',
        'catatan',
    ];

    public function getPenyusutanTahunanAttribute()
    {
        if (!$this->umur_ekonomis || $this->umur_ekonomis == 0) {
            return 0;
        }
        return ($this->harga_perolehan - $this->nilai_residu) / $this->umur_ekonomis;
    }

    public function getAkumulasiPenyusutanAttribute()
    {
        $tahunPerolehan = date('Y', strtotime($this->tanggal_perolehan));
        $tahunSekarang = date('Y');
        $umurDipakai = max (0, $tahunSekarang - $tahunPerolehan);
        $akumulasi = $umurDipakai * $this->penyusutan_tahunan;
        return min($akumulasi, $this->harga_perolehan - $this->nilai_residu);
    }

    public function getNilaiBukuAttribute()
    {
        return $this->harga_perolehan - $this->akumulasi_penyusutan;
    }
}
