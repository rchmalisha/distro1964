<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_akun',
        'nama_akun',
        'jenis_akun',
        'saldo_awal',
        'saldo_normal',
    ];
}
