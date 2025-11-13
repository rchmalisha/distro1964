<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_pesan', 'pelanggan_id', 'tgl_pesan', 'tgl_ambil', 'total_harga', 
        'biaya_lainnya', 'potongan_harga', 'total_akhir', 'upload_file', 'keterangan'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function detailOrders()
    {
        return $this->hasMany(DetailOrder::class, 'kode_pesan', 'kode_pesan');
    }
}
