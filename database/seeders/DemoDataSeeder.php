<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Sales;
use App\Models\Purchasing;
use App\Models\FixedAsset;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Materials
        $materials = [
            ['kode_bahan' => 'MT-01', 'nama_bahan' => 'DTF Film 1m', 'kategori_bahan' => 'dtf', 'harga_bahan' => 50000],
            ['kode_bahan' => 'MT-02', 'nama_bahan' => 'Polyflex 1m', 'kategori_bahan' => 'polyflex', 'harga_bahan' => 45000],
            ['kode_bahan' => 'MT-03', 'nama_bahan' => 'DTF Film 2m', 'kategori_bahan' => 'dtf', 'harga_bahan' => 90000],
        ];
        foreach ($materials as $m) {
            Material::updateOrCreate(['kode_bahan' => $m['kode_bahan']], $m);
        }

        // Services
        $services = [
            ['kode_jasa' => 'JS-01', 'nama_barang' => 'Printing Satuan', 'kategori_jasa' => 'printing', 'harga' => 20000],
            ['kode_jasa' => 'JS-02', 'nama_barang' => 'Finishing', 'kategori_jasa' => 'finishing', 'harga' => 10000],
            ['kode_jasa' => 'JS-03', 'nama_barang' => 'Cutting', 'kategori_jasa' => 'cutting', 'harga' => 15000],
        ];
        foreach ($services as $s) {
            Service::updateOrCreate(['kode_jasa' => $s['kode_jasa']], $s);
        }

        // Customers
        $customers = [];
        for ($i = 1; $i <= 3; $i++) {
            $customers[] = Customer::firstOrCreate([
                'nama_cus' => 'Demo Pelanggan ' . $i
            ], ['no_telp' => '08123456' . str_pad($i, 3, '0', STR_PAD_LEFT)]);
        }

        // Purchasings (recent)
        Purchasing::updateOrCreate([
            'kode_bahan' => 'MT-01', 'tanggal_pembelian' => Carbon::now()->subMonths(2)->toDateString()
        ], [
            'nama_bahan' => 'DTF Film 1m',
            'ukuran_meter' => 20,
            'pemasok' => 'Supplier A',
            'harga_per_meter' => 48000,
            'total_harga' => 48000 * 20,
        ]);

        // Fixed assets
        FixedAsset::updateOrCreate(['kode_aset' => 'FA-001'], [
            'nama_aset' => 'Mesin Cutting',
            'kategori_aset' => 'mesin',
            'tanggal_perolehan' => Carbon::create(2024, 6, 15)->toDateString(),
            'harga_perolehan' => 12000000,
            'nilai_residu' => 600000,
            'umur_ekonomis' => 5,
            'metode_penyusutan' => 'garis_lurus',
        ]);

        FixedAsset::updateOrCreate(['kode_aset' => 'FA-002'], [
            'nama_aset' => 'Komputer Desain',
            'kategori_aset' => 'peralatan_it',
            'tanggal_perolehan' => Carbon::create(2023, 3, 10)->toDateString(),
            'harga_perolehan' => 15000000,
            'nilai_residu' => 750000,
            'umur_ekonomis' => 4,
            'metode_penyusutan' => 'garis_lurus',
        ]);

        // Orders & Sales across last 12 months
        $today = Carbon::now();
        for ($m = 0; $m < 12; $m++) {
            $month = $today->copy()->subMonths($m);
            // create between 1..4 sales per month
            $count = rand(1, 4);
            for ($j = 1; $j <= $count; $j++) {
                $kodePesan = 'ORD-' . $month->format('Ym') . '-' . str_pad($j, 2, '0', STR_PAD_LEFT);
                $customer = $customers[array_rand($customers)];
                $order = Order::firstOrCreate([
                    'kode_pesan' => $kodePesan
                ], [
                    'pelanggan_id' => $customer->id,
                    'tgl_pesan' => $month->copy()->day(rand(1, 20))->toDateString(),
                    'tgl_ambil' => $month->copy()->day(rand(21, 28))->toDateString(),
                    'total_harga' => 100000 * $j,
                    'biaya_lainnya' => 0,
                    'potongan_harga' => 0,
                    'total_akhir' => 100000 * $j,
                    'upload_file' => null,
                    'keterangan' => 'Demo order ' . $kodePesan,
                ]);

                // create sales record
                $kodeJual = 'SJ-' . $month->format('Ym') . '-' . str_pad($j, 2, '0', STR_PAD_LEFT);
                Sales::updateOrCreate(['kode_jual' => $kodeJual], [
                    'kode_pesan' => $order->kode_pesan,
                    'tgl_transaksi' => $month->copy()->day(rand(1, 28))->toDateString(),
                    'total_akhir' => (int) ($order->total_akhir),
                    'bayar' => (int) ($order->total_akhir),
                    'kembalian' => 0,
                ]);
            }
        }
    }
}
