@extends('layout.main')
@section('title', 'Pembayaran Pesanan')

@section('content')
<div class="container mx-auto p-6 space-y-6">
    {{-- Pesan error jika ada --}}
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg border border-red-300">
            <h3 class="font-semibold mb-2">Terjadi kesalahan:</h3>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="paymentForm" action="{{ route('sales.store') }}" method="POST">
        @csrf
        
        {{-- CARD 1: Informasi Pesanan --}}
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Informasi Pesanan</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Kode Pesanan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Kode Pesanan</label>
                    <input type="text" name="kode_pesan" 
                        class="w-full border rounded-lg p-2 bg-gray-100" 
                        value="{{ $order->kode_pesan }}" 
                        readonly>
                </div>

                {{-- Nama Pelanggan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Nama Pelanggan</label>
                    <input type="text" 
                        class="w-full border rounded-lg p-2 bg-gray-100" 
                        value="{{ $order->customer->nama_cus }}" 
                        readonly>
                </div>

                {{-- No. Telepon --}}
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">No. Telepon</label>
                    <input type="text" 
                        class="w-full border rounded-lg p-2 bg-gray-100" 
                        value="{{ $order->customer->no_telp }}" 
                        readonly>
                </div>

                {{-- Tanggal Pesan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Pesan</label>
                    <input type="text" 
                        class="w-full border rounded-lg p-2 bg-gray-100" 
                        value="{{ \Carbon\Carbon::parse($order->tgl_pesan)->format('d M Y') }}" 
                        readonly>
                </div>

                {{-- Tanggal Ambil --}}
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Ambil</label>
                    <input type="text" 
                        class="w-full border rounded-lg p-2 bg-gray-100" 
                        value="{{ \Carbon\Carbon::parse($order->tgl_ambil)->format('d M Y') }}" 
                        readonly>
                </div>
            </div>
        </div>

        {{-- CARD 2: Rincian Pesanan --}}
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Rincian Pesanan</h2>

            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-3 py-2 text-center w-12">No</th>
                            <th class="px-3 py-2">Nama Barang</th>
                            <th class="px-3 py-2">Nama Bahan</th>
                            <th class="px-3 py-2">Ukuran Bahan</th>
                            <th class="px-3 py-2 text-center">Jumlah</th>
                            <th class="px-3 py-2 text-center">Harga Satuan</th>
                            <th class="px-3 py-2 text-center">Subtotal</th>
                            <th class="px-3 py-2 text-center w-10">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($order->detailOrders as $index => $detail)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="text-center px-3 py-2">{{ $index + 1 }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">{{ $detail->service->nama_barang ?? '-' }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">{{ $detail->material->nama_bahan ?? '-' }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-center">
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
                                <td class="px-3 py-2 text-center">{{ $detail->jumlah_pesan }}</td>
                                <td class="px-3 py-2 text-right whitespace-nowrap">
                                    Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                </td>
                                <td class="px-3 py-2 text-right font-semibold whitespace-nowrap">
                                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <input type="checkbox" class="w-4 h-4 text-green-600 rounded cursor-pointer status-checkbox" 
                                        name="status[{{ $detail->id }}]" title="Tandai item sebagai lengkap">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-4 text-center text-gray-500">
                                    Belum ada item dalam pesanan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- CARD 3: Pembayaran --}}
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Pembayaran</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Kolom Kiri: Ringkasan Biaya --}}
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700 font-medium">Total Harga:</span>
                        <span class="font-semibold text-gray-900">
                            Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-700 font-medium">Biaya Lainnya:</span>
                        <span class="font-semibold text-gray-900">
                            Rp {{ number_format($order->biaya_lainnya, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-700 font-medium">Potongan Harga:</span>
                        <span class="font-semibold text-gray-900">
                            - Rp {{ number_format($order->potongan_harga, 0, ',', '.') }}
                        </span>
                    </div>

                    <hr class="my-2">

                    <div class="flex justify-between items-center text-lg font-semibold">
                        <span class="text-gray-800">Total Akhir:</span>
                        <span class="text-green-700">
                            Rp {{ number_format($order->total_akhir, 0, ',', '.') }}
                        </span>
                    </div>
                    <input type="hidden" name="total_akhir" id="total_akhir" value="{{ intval($order->total_akhir) }}">
                </div>

                {{-- Kolom Kanan: Input Pembayaran --}}
                <div class="space-y-4">
                    {{-- Jumlah Bayar --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Jumlah Bayar</label>
                        <input type="text" id="bayar_display"
                            class="w-full border rounded-lg p-3 text-lg font-semibold"
                            placeholder="Masukkan jumlah uang yang dibayarkan"
                            oninput="formatBayar(this)"
                            required>
                        <input type="hidden" name="bayar" id="bayar" value="0">
                    </div>

                    {{-- Kembalian --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Kembalian</label>
                        <input type="text" id="kembalian_display"
                            class="w-full border rounded-lg p-3 text-lg font-semibold bg-gray-100"
                            value="Rp 0"
                            readonly>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-6 space-x-3">
                <a href="{{ route('orders.index') }}"
                   class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Simpan Pembayaran
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    e.preventDefault(); // hentikan submit default

    const checkboxes = document.querySelectorAll('.status-checkbox');
    let allChecked = true;

    checkboxes.forEach(cb => {
        if (!cb.checked) allChecked = false;
    });

    if (!allChecked) {
        Swal.fire({
            icon: 'warning',
            title: 'Belum lengkap',
            text: 'Semua item harus dicentang sebelum menyimpan pembayaran!'
        });
        return;
    }

    // ambil form action dan data
    const form = e.target;
    const action = form.action;
    const formData = new FormData(form);

    // kirim via fetch
    fetch(action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
    })
    .then(response => response.json())
    .then(data => {
        if(data.success && data.print_url){
            // buka tab baru untuk print
            window.open(data.print_url, '_blank');

            // redirect tab sekarang ke sales.index
            window.location.href = "{{ route('sales.index') }}";
        } else if(data.errors){
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: Object.values(data.errors).join('\n')
            });
        }
    })
    .catch(error => console.error(error));
});

function formatRupiah(angka) {
    const numberString = angka.toString().replace(/[^\d]/g, '');
    const number = parseInt(numberString) || 0;
    return "Rp " + number.toLocaleString("id-ID");
}

function parseRupiah(str) {
    if (!str) return 0;
    return parseInt(str.toString().replace(/[^\d]/g, "")) || 0;
}

function formatBayar(input) {
    // Ambil posisi cursor sebelum formatting
    const cursorPos = input.selectionStart;

    // Ambil angka asli
    let numericValue = parseRupiah(input.value);

    // Set hidden input
    document.getElementById('bayar').value = numericValue;

    // Format tampilan
    input.value = formatRupiah(numericValue);

    // Hitung ulang kembalian
    hitungKembalian();

    // Kembalikan cursor ke posisi akhir
    input.setSelectionRange(input.value.length, input.value.length);
}

function hitungKembalian() {
    const bayar = parseRupiah(document.getElementById('bayar').value);
    const totalAkhir = parseRupiah(document.getElementById('total_akhir').value);

    let kembalian = bayar - totalAkhir;
    if (kembalian < 0) kembalian = 0; 

    document.getElementById('kembalian_display').value = formatRupiah(kembalian);
}
</script>

@endsection
