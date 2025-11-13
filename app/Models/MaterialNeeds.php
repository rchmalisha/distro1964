<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialNeeds extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_pesan',
        'kode_bahan',
        'jenis_jasa',
        'jenis_bahan',
        'ukuran_panjang',
        'ukuran_lebar',
        'jumlah_pesanan',
        'waste_persen',
        'kebutuhan_bahan_meter',
        'tgl_pesan',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class, 'kode_bahan', 'kode_bahan');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'kode_pesan', 'kode_pesan');
    }
}
