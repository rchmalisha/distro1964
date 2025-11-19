<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;
    protected $primaryKey = 'kode_jasa'; // Primary key bukan id
    public $incrementing = false; // Karena bukan auto increment
    protected $keyType = 'string'; // Karena tipe datanya string

     protected $fillable = [
        'kode_jasa',
        'nama_barang',
        'kategori_jasa',
        'harga',
    ];
    public function detailOrders()
    {
        return $this->hasMany(DetailOrder::class, 'kode_jasa', 'kode_jasa');
    }
}
