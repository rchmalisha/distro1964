<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index()
{
    $orders = Order::with('customer')->orderBy('tgl_pesan', 'desc')->get();
    return view('sales.index', compact('orders'));
}
}
