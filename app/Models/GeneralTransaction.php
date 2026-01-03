<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Account; // Diperlukan untuk mendefinisikan relasi ke model Account

class GeneralTransaction extends Model
{
    protected $fillable = [
        'tanggal',
        'jenis_transaksi',
        'kode_akun',
        'nominal',
        'keterangan'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'kode_akun', 'kode_akun');
    }
}
