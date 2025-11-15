<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchasing extends Model
{
    protected $fillable = [
        'kode_bahan',
        'nama_bahan',
        'ukuran_meter',
        'tanggal_pembelian',
        'pemasok',
        'harga_per_meter',
        'total_harga',
    ];
}
