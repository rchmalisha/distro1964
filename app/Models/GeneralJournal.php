<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralJournal extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_jurnal',
        'no_bukti',
        'tanggal_jurnal',
        'keterangan_jurnal',
        'ref_tipe',
        'ref_id',
    ];

    public function details()
    {
        return $this->hasMany(GeneralJournalDetail::class);
    }
}

