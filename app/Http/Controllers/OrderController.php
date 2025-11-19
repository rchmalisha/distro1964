<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Material;
use App\Models\DetailOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\MaterialNeedsController;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('customer')
                    ->doesntHave('sales')
                    ->orderBy('tgl_pesan', 'desc')
                    ->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $orders = Order::with(['customer', 'detailOrders.service', 'detailOrders.material'])->get();
        $customers = Customer::all();
        $services = Service::all();
        $materials = Material::all();

        return view('orders.create', compact('orders', 'customers', 'services', 'materials'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // ✅ Validasi dasar
            $request->validate([
                'nama_cus' => 'required|string|max:100',
                'no_telp' => 'required|digits_between:10,13',
                'tgl_pesan' => 'required|date',
                'tgl_ambil' => 'required|date|after_or_equal:tgl_pesan',
                'detail_orders' => 'required|array|min:1',
            ]);

            // 1️⃣ Simpan pelanggan baru
            $customer = Customer::create([
                'nama_cus' => $request->nama_cus,
                'no_telp' => $request->no_telp,
            ]);

            // 2️⃣ Buat kode pesan unik
            $date = now()->format('Ymd'); // ambil tanggal hari ini, misalnya 20251112

            // ambil pesanan terakhir untuk tanggal hari ini
            $lastOrderToday = Order::where('kode_pesan', 'like', 'ORD-' . $date . '%')
                ->orderBy('kode_pesan', 'desc')
                ->first();

            if ($lastOrderToday) {
                // ambil angka terakhir dari kode pesan
                $lastNumber = (int) substr($lastOrderToday->kode_pesan, -3);
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            // buat kode baru
            $kodePesan = 'ORD-' . $date . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

            // 3️⃣ Simpan order utama
            $order = Order::create([
                'kode_pesan' => $kodePesan,
                'pelanggan_id' => $customer->id, // 🔥 disesuaikan dengan migration
                'tgl_pesan' => $request->tgl_pesan,
                'tgl_ambil' => $request->tgl_ambil,
                'total_harga' => $request->total_harga,
                'biaya_lainnya' => $request->biaya_lainnya,
                'potongan_harga' => $request->potongan_harga,
                'total_akhir' => $request->total_akhir,
                'upload_file' => $request->file('upload_file')
                    ? $request->file('upload_file')->store('uploads', 'public')
                    : null,
                'keterangan' => $request->keterangan,
            ]);

            // 4️⃣ Simpan detail orders
            if ($request->has('detail_orders')) {
                foreach ($request->detail_orders as $detail) {
                    DetailOrder::create([
                        'kode_pesan' => $kodePesan,
                        'kode_jasa' => $detail['kode_jasa'] ?? null,
                        'kode_bahan' => $detail['kode_bahan'] ?? null,
                        'ukuran_panjang' => $detail['ukuran_panjang'] ?? '',
                        'ukuran_lebar' => $detail['ukuran_lebar'] ?? '',
                        'jumlah_pesan' => $detail['jumlah_pesan'] ?? 0,
                        'harga_satuan' => $detail['harga_satuan'] ?? 0,
                        'subtotal' => $detail['subtotal'] ?? 0,
                    ]);
                }
                app(MaterialNeedsController::class)
                ->storeFromOrder($kodePesan);
            }

            DB::commit();
            return redirect()->route('orders.show', $order->id)->with('success', 'Pesanan berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan pesanan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function show($id)
    {
        $order = Order::with(['customer', 'detailOrders.service', 'detailOrders.material'])->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    public function print($id)
    {
        $order = Order::with(['customer', 'detailOrders.service', 'detailOrders.material'])->findOrFail($id);
        return view('orders.print', compact('order')); // tampilan versi print
    }
}
