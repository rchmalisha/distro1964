@extends('layout.main')
@section('title', 'Daftar Pesanan')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-semibold text-gray-700 mb-6">Daftar Pesanan</h1>

    {{-- Button: Input Pesanan --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
        <button
            onclick="window.location.href='{{ route('orders.create') }}'"
            class="bg-slate-700 text-white px-4 py-2 rounded-lg hover:bg-slate-800 transition">
            + Input Pesanan
        </button>
        </div>
    </div>

    {{-- Tabel daftar pesanan --}}
    <div class="bg-white shadow rounded-xl overflow-hidden">
        <table class="min-w-full table-auto border-collapse">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="w-[30px] px-4 py-3 text-left text-sm font-medium border">No.</th>
                    <th class="px-4 py-3 text-left text-sm font-medium border">Kode Pesanan</th>
                    <th class="px-4 py-3 text-left text-sm font-medium border">Nama Pelanggan</th>
                    <th class="px-4 py-3 text-left text-sm font-medium border">Tanggal Pesan</th>
                    <th class="px-4 py-3 text-left text-sm font-medium border">Tanggal Ambil</th>
                    <th class="px-4 py-3 text-center text-sm font-medium border">Detail Order</th>
                    <th class="px-4 py-3 text-center text-sm font-medium border">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 divide-y divide-gray-200">
                @forelse ($orders as $index => $order)
                    <tr>
                        <td class="px-4 py-3 border">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 border">{{ $order->kode_pesan }}</td>
                        <td class="px-4 py-3 border">{{ $order->customer->nama_cus }}</td>
                        <td class="px-4 py-3 border">{{ \Carbon\Carbon::parse($order->tgl_pesan)->format('d M Y') }}</td>
                        <td class="px-4 py-3 border">{{ \Carbon\Carbon::parse($order->tgl_ambil)->format('d M Y') }}</td>
                        <td class="px-4 py-3 border text-center">
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

