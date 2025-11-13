@extends('layout.main')
@section('title', 'Rincian Pesanan')

@section('content')
<div class="container mx-auto p-6 space-y-6">

    {{-- CARD 1: Informasi Pelanggan --}}
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Informasi Pelanggan</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-600">Kode Pesanan</label>
                <input type="text" value="{{ $order->kode_pesan }}" readonly
                       class="w-full border rounded-lg p-2 mt-1">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">Nama Pelanggan</label>
                <input type="text" value="{{ $order->customer->nama_cus }}" readonly
                       class="w-full border rounded-lg p-2 mt-1">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">No. Telepon</label>
                <input type="text" value="{{ $order->customer->no_telp }}" readonly
                       class="w-full border rounded-lg p-2 mt-1">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">Tanggal Pesan</label>
                <input type="date" value="{{ $order->tgl_pesan }}" readonly
                       class="w-full border rounded-lg p-2 mt-1">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">Tanggal Ambil</label>
                <input type="date" value="{{ $order->tgl_ambil }}" readonly
                       class="w-full border rounded-lg p-2 mt-1">
            </div>
        </div>
    </div>

    {{-- CARD 2: Detail Pemesanan --}}
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Detail Pemesanan</h2>
        {{-- FILE UPLOAD --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-600 mb-1">File Pesanan</label>
            @if ($order->upload_file)
                <a href="{{ asset('storage/' . $order->upload_file) }}" 
                target="_blank" 
                class="text-blue-600 hover:underline">
                Lihat File
                </a>
            @else
                <span class="text-gray-500 italic">Tidak ada file</span>
            @endif
        </div>

        {{-- KETERANGAN --}}
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Keterangan</label>
            <textarea class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-50 text-gray-700 p-2" rows="3" readonly>{{ $order->keterangan ?? '-' }}</textarea>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full text-sm text-left text-gray-600 border border-gray-300">
                <thead class="bg-gray-100 text-gray-700 uppercase text-xs text-center">
                    <tr>
                        <th class="w-[50px] px-2 py-2 border text-center">No.</th>
                        <th class="px-4 py-2 border text-center">Nama Barang</th>
                        <th class="w-[120px] px-4 py-2 border text-center">Ukuran</th>
                        <th class="w-[200px] px-4 py-2 border text-center">Jumlah Pesanan</th>
                        <th class="w-[150px] px-4 py-2 border text-center">Harga Satuan</th>
                        <th class="w-[150px] px-4 py-2 border text-center">Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($order->detailOrders as $index => $detail)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-2 py-2 border text-center">{{ $index + 1 }}</td>
                        <td class="px-4 py-2 border">
                            {{ $detail->service->nama_jasa ?? $detail->material->nama_material ?? '-' }}
                        </td>
                        <td class="px-4 py-2 border text-center">
                            @php
                                $panjang = $detail->ukuran_panjang ?? null;
                                $lebar = $detail->ukuran_lebar ?? null;
                                // Hilangkan .00 jika bilangan bulat
                                $panjang_display = ($panjang == floor($panjang)) ? number_format($panjang, 0, ',', '.') : rtrim(rtrim(number_format($panjang, 2, ',', '.'), '0'), ',');
                                $lebar_display = ($lebar == floor($lebar)) ? number_format($lebar, 0, ',', '.') : rtrim(rtrim(number_format($lebar, 2, ',', '.'), '0'), ',');
                            @endphp

                            @if ($panjang && $lebar)
                                {{ $panjang_display }} x {{ $lebar_display }} cm
                            @elseif ($panjang)
                                {{ $panjang_display }} cm
                            @elseif ($lebar)
                                {{ $lebar_display }} cm
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-2 border text-center">{{ $detail->jumlah_bahan }}</td>
                        <td class="px-4 py-2 border text-right">
                            Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2 border text-right font-semibold">
                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-right font-semibold border">Total Harga</td>
                        <td class="px-4 py-2 text-right font-bold border text-green-600">
                            Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                        </td>
                    </tr>

                    @if ($order->biaya_lainnya)
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-right font-semibold border">Biaya Lainnya</td>
                        <td class="px-4 py-2 text-right border">
                            Rp {{ number_format($order->biaya_lainnya, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endif

                    @if ($order->potongan_harga)
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-right font-semibold border">Potongan Harga</td>
                        <td class="px-4 py-2 text-right border">
                            Rp {{ number_format($order->potongan_harga, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endif

                    <tr>
                        <td colspan="5" class="px-4 py-2 text-right font-bold border">Total Akhir</td>
                        <td class="px-4 py-2 text-right font-bold border text-blue-600">
                            Rp {{ number_format($order->total_akhir, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Tombol Aksi --}}
    <div class="flex justify-end space-x-3">
        <a href="{{ route('orders.index') }}"
           class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
           Kembali
        </a>
        <a href="{{ route('orders.print', $order->id) }}"
           target="_blank"
           class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
           Cetak Nota
        </a>
    </div>
</div>
@endsection
