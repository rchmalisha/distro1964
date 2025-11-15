<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_pesan', 'kode_jasa', 'kode_bahan',
        'ukuran_panjang', 'ukuran_lebar',
        'jumlah_pesan', 'harga_satuan', 'subtotal'
    ];
    
    public function service()
    {
        return $this->belongsTo(Service::class, 'kode_jasa', 'kode_jasa');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'kode_bahan', 'kode_bahan');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'kode_pesan', 'kode_pesan');
    }
}
