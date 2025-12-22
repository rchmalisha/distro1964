<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\FixedAsset;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function salesMonthly()
    {
        $rows = Sales::selectRaw("DATE_FORMAT(tgl_transaksi, '%Y-%m') as month, COUNT(*) as count")
            ->whereNotNull('tgl_transaksi')
            ->where('tgl_transaksi', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = now()->subMonths($i)->format('Y-m');
            $months[$m] = 0;
        }

        foreach ($rows as $r) {
            $months[$r->month] = (int) $r->count;
        }

        $values = array_values($months);
        $hasData = array_sum($values) > 0;
        return response()->json([ 'labels' => array_keys($months), 'data' => $values, 'hasData' => $hasData ]);
    }

    public function revenueMonthly()
    {
        $rows = Sales::selectRaw("DATE_FORMAT(tgl_transaksi, '%Y-%m') as month, SUM(total_akhir) as revenue")
            ->whereNotNull('tgl_transaksi')
            ->where('tgl_transaksi', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = now()->subMonths($i)->format('Y-m');
            $months[$m] = 0;
        }

        foreach ($rows as $r) {
            $months[$r->month] = (float) $r->revenue;
        }

        $values = array_values($months);
        $hasData = array_sum($values) > 0;
        return response()->json([ 'labels' => array_keys($months), 'data' => $values, 'hasData' => $hasData ]);
    }

    public function summary()
    {
        $totalSales = Sales::count();
        $totalRevenue = Sales::sum('total_akhir');
        $activeAssets = FixedAsset::whereNull('tanggal_jual')->count();
        $assetValue = FixedAsset::whereNull('tanggal_jual')->sum('harga_perolehan');

        return response()->json([
            'totalSales' => $totalSales,
            'totalRevenue' => (float) $totalRevenue,
            'activeAssets' => $activeAssets,
            'assetValue' => (float) $assetValue,
        ]);
    }

    public function recentActivity()
    {
        // Get latest 8 sales
        $sales = Sales::selectRaw("'sale' as type, kode_jual as ref, tgl_transaksi as date, total_akhir as amount, NULL as supplier")
            ->whereNotNull('tgl_transaksi')
            ->orderBy('tgl_transaksi', 'desc')
            ->limit(8)
            ->get()
            ->toArray();

        // Get latest 8 purchases
        $purchases = \App\Models\Purchasing::selectRaw("'purchase' as type, kode_bahan as ref, tanggal_pembelian as date, total_harga as amount, pemasok as supplier")
            ->whereNotNull('tanggal_pembelian')
            ->orderBy('tanggal_pembelian', 'desc')
            ->limit(8)
            ->get()
            ->toArray();

        $merged = array_merge($sales, $purchases);

        usort($merged, function ($a, $b) {
            return strtotime($b['date']) <=> strtotime($a['date']);
        });

        $recent = array_slice($merged, 0, 10);

        return response()->json($recent);
    }
}
