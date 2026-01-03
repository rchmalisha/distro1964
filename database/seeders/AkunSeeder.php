<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AkunSeeder extends Seeder
{
    public function run()
    {
        // Nonaktifkan foreign key checks agar bisa truncate tabel dengan relasi
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Hapus data pada tabel yang memiliki relasi ke accounts
        DB::table('general_journals')->delete();
        DB::table('accounts')->truncate();

        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = Carbon::now();

        // Di bagian array $accounts:
        $accounts = [
            // === Aset Lancar ===
            ['kode_akun' => '1101', 'nama_akun' => 'Kas', 'jenis_akun' => 'aset lancar', 'saldo_awal' => 1400000],

            // === Aset Tetap ===
            ['kode_akun' => '1201', 'nama_akun' => 'Mesin dan Peralatan', 'jenis_akun' => 'aset tetap', 'saldo_awal' => 35000000],
            ['kode_akun' => '1202', 'nama_akun' => 'Aset Tidak Berwujud', 'jenis_akun' => 'aset tetap', 'saldo_awal' => 480000],


            // Kontra Aset (Akumulasi - Kredit)
            ['kode_akun' => '1301', 'nama_akun' => 'Akumulasi Penyusutan Mesin dan Peralatan', 'jenis_akun' => 'aset tetap', 'saldo_awal' => 35000000],

            // === Liabilitas ===
            ['kode_akun' => '2101', 'nama_akun' => 'Utang Usaha', 'jenis_akun' => 'liabilitas', 'saldo_awal' => 0],

            // === Ekuitas ===
            ['kode_akun' => '3101', 'nama_akun' => 'Modal Pemilik', 'jenis_akun' => 'ekuitas', 'saldo_awal' => 1445000],
            ['kode_akun' => '3102', 'nama_akun' => 'Laba', 'jenis_akun' => 'ekuitas', 'saldo_awal' => 1435000],
            ['kode_akun' => '3103', 'nama_akun' => 'Prive Pemilik', 'jenis_akun' => 'ekuitas', 'saldo_awal' => 1000000],
            ['kode_akun' => '3104', 'nama_akun' => 'Laba/Rugi Tahun Berjalan', 'jenis_akun' => 'ekuitas', 'saldo_awal' => 0],
            ['kode_akun' => '3105', 'nama_akun' => 'Ikhtisar Laba Rugi', 'jenis_akun' => 'ekuitas', 'saldo_awal' => 0],

            // === Pendapatan ===
            ['kode_akun' => '4101', 'nama_akun' => 'Penjualan', 'jenis_akun' => 'pendapatan', 'saldo_awal' => 0],
            ['kode_akun' => '4201', 'nama_akun' => 'Laba Penjualan Aset Tetap', 'jenis_akun' => 'pendapatan', 'saldo_awal' => 0],

            // === Beban ===
            ['kode_akun' => '5101', 'nama_akun' => 'Biaya Bahan Habis Pakai', 'jenis_akun' => 'beban', 'saldo_awal' => 0],
            ['kode_akun' => '5102', 'nama_akun' => 'Biaya Listrik & Internet', 'jenis_akun' => 'beban', 'saldo_awal' => 0],
            ['kode_akun' => '5103', 'nama_akun' => 'Biaya Tinta Printer DTF', 'jenis_akun' => 'beban', 'saldo_awal' => 0],
            ['kode_akun' => '6201', 'nama_akun' => 'Beban Penyusutan Mesin', 'jenis_akun' => 'beban', 'saldo_awal' => 0],
            ['kode_akun' => '6202', 'nama_akun' => 'Beban Penyusutan Peralatan', 'jenis_akun' => 'beban', 'saldo_awal' => 0],
            ['kode_akun' => '6301', 'nama_akun' => 'Rugi Penjualan Aset Tetap', 'jenis_akun' => 'beban', 'saldo_awal' => 0],
        ];

        foreach ($accounts as $account) {
            // Default saldo normal berdasarkan jenis akun
            $saldoNormal = match (strtolower($account['jenis_akun'])) {
                'aset lancar', 'aset tetap', 'beban' => 'debit',
                'liabilitas', 'ekuitas', 'pendapatan' => 'kredit',
                default => 'debit',
            };

            // Deteksi akun kontra
            $namaAkun = strtolower($account['nama_akun']);
            if (
                str_contains($namaAkun, 'akumulasi') ||
                str_contains($namaAkun, 'penyisihan') ||
                str_contains($namaAkun, 'retur') ||
                str_contains($namaAkun, 'diskon') ||
                str_contains($namaAkun, 'prive')
            ) {
                // Balik saldo normalnya jika akun kontra
                $saldoNormal = $saldoNormal === 'debit' ? 'kredit' : 'debit';
            }

            DB::table('accounts')->insert(array_merge($account, [
                'saldo_normal' => $saldoNormal,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }
}
