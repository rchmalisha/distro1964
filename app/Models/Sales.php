<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $primaryKey = 'kode_jual';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_jual',
        'kode_pesan',
        'tgl_transaksi',
        'total_akhir',
        'bayar',
        'kembalian'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'kode_pesan', 'kode_pesan');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'pelanggan_id', 'id');
    }

    public function detailOrders()
    {
        return $this->hasMany(DetailOrder::class, 'kode_pesan', 'kode_pesan');
    }
}
