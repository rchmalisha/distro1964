@extends('layout.main')
@section('title', 'Penjualan')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-semibold text-gray-700 mb-6">Daftar Pesanan</h1>

    {{-- Pesan sukses atau error --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel daftar pesanan --}}
    <div class="bg-white shadow rounded-xl overflow-hidden">
        <table class="min-w-full table-auto border-collapse">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-medium border-b">Kode Pesan</th>
                    <th class="px-4 py-3 text-left text-sm font-medium border-b">Nama Pelanggan</th>
                    <th class="px-4 py-3 text-left text-sm font-medium border-b">Tanggal Pesan</th>
                    <th class="px-4 py-3 text-left text-sm font-medium border-b">Tanggal Ambil</th>
                    <th class="px-4 py-3 text-center text-sm font-medium border-b">Detail Order</th>
                    <th class="px-4 py-3 text-center text-sm font-medium border-b">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 divide-y divide-gray-200">
                @forelse ($orders as $order)
                    <tr>
                        <td class="px-4 py-3">{{ $order->kode_pesan }}</td>
                        <td class="px-4 py-3">{{ $order->customer->nama_cus }}</td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($order->tgl_pesan)->format('d M Y') }}</td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($order->tgl_ambil)->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('orders.show', $order->id) }}"
                               class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-1.5 rounded-lg transition">
                                Lihat Detail
                            </a>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href=""
                               class="bg-green-500 hover:bg-green-600 text-white text-sm px-3 py-1.5 rounded-lg transition">
                                Pembayaran
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                            Belum ada pesanan yang tercatat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

