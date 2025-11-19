<?php

namespace App\Services;

use App\Models\GeneralJournal;
use App\Models\GeneralJournalDetail;
use Illuminate\Support\Facades\DB;

class JournalService
{
    /**
     * Mencatat jurnal umum otomatis sesuai struktur tabel Alisha
     *
     * @param string $tanggal
     * @param string $keterangan
     * @param array $entries
     * @param string|null $ref_tipe
     * @param int|null $ref_id
     */
    public function createJournal($tanggal, $keterangan, array $entries, $ref_tipe = null, $ref_id = null)
    {
        return DB::transaction(function () use ($tanggal, $keterangan, $entries, $ref_tipe, $ref_id) {

            // Buat kode jurnal otomatis: JNL-202501-0001
            $last = GeneralJournal::orderByDesc('id')->first();
            $nextNumber = $last ? $last->id + 1 : 1;
            $kode_jurnal = 'JNL-' . date('Ym', strtotime($tanggal)) . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            // Insert header jurnal
            $journal = GeneralJournal::create([
                'kode_jurnal' => $kode_jurnal,
                'tanggal_jurnal' => $tanggal,
                'keterangan_jurnal' => $keterangan,
                'ref_tipe' => $ref_tipe,
                'ref_id' => $ref_id,
            ]);

            // Insert detail jurnal
            foreach ($entries as $e) {
                GeneralJournalDetail::create([
                    'general_journal_id' => $journal->id,
                    'kode_akun' => $e['kode_akun'],
                    'debit' => $e['posisi'] === 'debit' ? $e['nominal'] : 0,
                    'kredit' => $e['posisi'] === 'kredit' ? $e['nominal'] : 0,
                ]);
            }

            return $journal;
        });
    }
}
