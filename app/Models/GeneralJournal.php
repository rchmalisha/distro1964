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

    // Generate kode_jurnal otomatis
    protected static function booted()
    {
        static::creating(function ($journal) {
            if (empty($journal->kode_jurnal)) {
                $lastId = self::max('id') + 1;
                $journal->kode_jurnal = 'JRN-' . date('Ymd') . '-' . str_pad($lastId, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
