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
            ['kode_akun' => '1101', 'nama_akun' => 'Kas', 'jenis_akun' => 'aset lancar', 'saldo_awal' => 5000000],
            ['kode_akun' => '1102', 'nama_akun' => 'Bank', 'jenis_akun' => 'aset lancar', 'saldo_awal' => 95000000],
            ['kode_akun' => '1103', 'nama_akun' => 'Piutang Usaha', 'jenis_akun' => 'aset lancar', 'saldo_awal' => 2500000],

            // === Aset Tetap ===
            ['kode_akun' => '1201', 'nama_akun' => 'Tanah', 'jenis_akun' => 'aset tetap', 'saldo_awal' => 20000000],
            ['kode_akun' => '1202', 'nama_akun' => 'Bangunan', 'jenis_akun' => 'aset tetap', 'saldo_awal' => 30000000],
            ['kode_akun' => '1203', 'nama_akun' => 'Mesin', 'jenis_akun' => 'aset tetap', 'saldo_awal' => 10000000],
            ['kode_akun' => '1204', 'nama_akun' => 'Kendaraan', 'jenis_akun' => 'aset tetap', 'saldo_awal' => 6000000],
            ['kode_akun' => '1205', 'nama_akun' => 'Peralatan Kantor', 'jenis_akun' => 'aset tetap', 'saldo_awal' => 2000000],

            // Kontra Aset (Akumulasi - Kredit)
            ['kode_akun' => '1301', 'nama_akun' => 'Akumulasi Penyusutan Bangunan', 'jenis_akun' => 'aset tetap', 'saldo_awal' => 5000000],
            ['kode_akun' => '1302', 'nama_akun' => 'Akumulasi Penyusutan Mesin dan Peralatan Produksi', 'jenis_akun' => 'aset tetap', 'saldo_awal' => 2000000],
            ['kode_akun' => '1303', 'nama_akun' => 'Akumulasi Penyusutan Kendaraan', 'jenis_akun' => 'aset tetap', 'saldo_awal' => 2500000],
            ['kode_akun' => '1304', 'nama_akun' => 'Akumulasi Penyusutan Peralatan Kantor', 'jenis_akun' => 'aset tetap', 'saldo_awal' => 500000],

            // === Liabilitas ===
            ['kode_akun' => '2101', 'nama_akun' => 'Utang Usaha', 'jenis_akun' => 'liabilitas', 'saldo_awal' => 6000000],

            // === Ekuitas ===
            ['kode_akun' => '3101', 'nama_akun' => 'Modal Pemilik', 'jenis_akun' => 'ekuitas', 'saldo_awal' => 154500000],
            ['kode_akun' => '3102', 'nama_akun' => 'Prive Pemilik', 'jenis_akun' => 'ekuitas', 'saldo_awal' => 0],
            ['kode_akun' => '3103', 'nama_akun' => 'Laba/Rugi Tahun Berjalan', 'jenis_akun' => 'ekuitas', 'saldo_awal' => 0],
            ['kode_akun' => '3104', 'nama_akun' => 'Ikhtisar Laba Rugi', 'jenis_akun' => 'ekuitas', 'saldo_awal' => 0],

            // === Pendapatan ===
            ['kode_akun' => '4101', 'nama_akun' => 'Penjualan', 'jenis_akun' => 'pendapatan', 'saldo_awal' => 0],
            ['kode_akun' => '4201', 'nama_akun' => 'Laba Penjualan Aset Tetap', 'jenis_akun' => 'pendapatan', 'saldo_awal' => 0],

            // === Beban ===
            ['kode_akun' => '5101', 'nama_akun' => 'Biaya ATK', 'jenis_akun' => 'beban', 'saldo_awal' => 0],
            ['kode_akun' => '5102', 'nama_akun' => 'Beban Penyusutan Mesin', 'jenis_akun' => 'beban', 'saldo_awal' => 0],
            ['kode_akun' => '5103', 'nama_akun' => 'Biaya Transportasi', 'jenis_akun' => 'beban', 'saldo_awal' => 0],
            ['kode_akun' => '5104', 'nama_akun' => 'Biaya Internet', 'jenis_akun' => 'beban', 'saldo_awal' => 0],
            ['kode_akun' => '5105', 'nama_akun' => 'Biaya Service', 'jenis_akun' => 'beban', 'saldo_awal' => 0],
            ['kode_akun' => '5106', 'nama_akun' => 'Biaya Lainnya', 'jenis_akun' => 'beban', 'saldo_awal' => 0],
            ['kode_akun' => '6201', 'nama_akun' => 'Beban Penyusutan Bangunan', 'jenis_akun' => 'beban', 'saldo_awal' => 0],
            ['kode_akun' => '6202', 'nama_akun' => 'Beban Penyusutan Kendaraan', 'jenis_akun' => 'beban', 'saldo_awal' => 0],
            ['kode_akun' => '6203', 'nama_akun' => 'Beban Penyusutan Peralatan Kantor', 'jenis_akun' => 'beban', 'saldo_awal' => 0],
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
