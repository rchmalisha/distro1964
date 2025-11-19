<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Sales;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\JournalService;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        // Jika belum ada filter → jangan tampilkan data
        if (!$request->filled('tanggal_awal') &&
            !$request->filled('tanggal_akhir') &&
            !$request->filled('nama_barang') &&
            !$request->filled('kategori_jasa')) 
        {
            return view('sales.index', ['sales' => []]);
        }

        $query = Sales::with(['order.customer', 'detailOrders.service']);

        // Filter tanggal
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tgl_transaksi', [
                $request->tanggal_awal,
                $request->tanggal_akhir
            ]);
        }

        // Filter nama barang
        if ($request->filled('nama_barang')) {
            $query->whereHas('detailOrders.service', function($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
            });
        }

        // Filter kategori jasa
        if ($request->kategori_jasa && $request->kategori_jasa != 'Semua') {
            $query->whereHas('detailOrders.service', function ($q) use ($request) {
                $q->where('kategori_jasa', $request->kategori_jasa);
            });
        }

        $sales = $query->get();

        return view('sales.index', compact('sales'));
    }

    public function create($order_id = null)
    {
        $order = Order::with('customer', 'detailOrders.service', 'detailOrders.material')
            ->where('id', $order_id)
            ->orWhere('kode_pesan', $order_id)
            ->firstOrFail();
        
        return view('sales.create', compact('order'));
    }

    public function store(Request $request, JournalService $journalService)
    {
        $validated = $request->validate([
            'kode_pesan' => 'required|string|exists:orders,kode_pesan',
            'bayar' => 'required|numeric|min:0',
            'total_akhir' => 'required|numeric|min:0',
            'status' => 'required|array',
        ]);

        $order = Order::where('kode_pesan', $validated['kode_pesan'])->firstOrFail();
        $bayar = (int) $validated['bayar'];
        $totalAkhir = (int) $validated['total_akhir'];

        if ($bayar < $totalAkhir) {
            return back()->withErrors(['bayar' => 'Pembayaran tidak mencukupi.']);
        }

        if (count($validated['status']) !== $order->detailOrders->count()) {
            return back()->withErrors(['status' => 'Semua item harus dicentang.']);
        }

        DB::beginTransaction();

        try {

            $kodeSales = $this->generateKodeSales();
            $kembalian = $bayar - $totalAkhir;

            // Simpan data penjualan
            $sale = Sales::create([
                'kode_jual' => $kodeSales,
                'kode_pesan' => $order->kode_pesan,
                'tgl_transaksi' => Carbon::now(),
                'total_akhir' => $totalAkhir,
                'bayar' => $bayar,
                'kembalian' => $kembalian,
            ]);

            // ===============================
            //   JURNAL OTOMATIS PENJUALAN
            // ===============================
            $journalService->createJournal(
                tanggal: Carbon::now()->format('Y-m-d'),
                keterangan: 'Penjualan jasa - ' . $sale->kode_jual,
                entries: [
                    [
                        'kode_akun' => '1101',
                        'posisi' => 'debit',
                        'nominal' => $totalAkhir
                    ],
                    [
                        'kode_akun' => '4101',
                        'posisi' => 'kredit',
                        'nominal' => $totalAkhir
                    ],
                ],
                ref_tipe: 'sales',
                ref_id: $sale->id
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'print_url' => route('sales.print', $sale->kode_jual)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Gagal membuat jurnal penjualan: ' . $e->getMessage());

            return back()->with('error', 'Penjualan tersimpan, tetapi jurnal gagal dicatat.');
        }
    }



    private function generateKodeSales()
    {
        $prefix = 'SAL-' . date('Ymd');
        $lastSales = Sales::where('kode_jual', 'like', $prefix . '%')
            ->orderBy('kode_jual', 'desc')
            ->first();

        if ($lastSales) {
            $lastNumber = (int) substr($lastSales->kode_jual, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . '-' . $newNumber;
    }

    public function print($kode_jual)
    {
        $sales = Sales::with('order.customer', 'detailOrders.service')->findOrFail($kode_jual);
        return view('sales.print', compact('sales'));
    }

public function report(Request $request)
{
    // Ambil filter tanggal jika ada
    $start_date = $request->query('start_date');
    $end_date = $request->query('end_date');

    // Query penjualan berdasarkan periode + eager load detailOrders dan service
    $sales = Sales::with('detailOrders.service'); // pastikan relasi sudah ada di model

    if ($start_date && $end_date) {
        $sales->whereBetween('tanggal', [$start_date, $end_date]);
    }

    $sales = $sales->get();

    return view('sales.report', compact('sales', 'start_date', 'end_date'));
}



}
