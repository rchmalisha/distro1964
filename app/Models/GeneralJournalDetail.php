<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralJournalDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'general_journal_id',
        'kode_akun',
        'debit',
        'kredit',
    ];

    public function journal()
    {
        return $this->belongsTo(GeneralJournal::class);
    }
}

