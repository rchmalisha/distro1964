@extends('layout.main')
@section('title', 'Penjualan')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
<!-- Card Utama -->
<div class="bg-white shadow-lg rounded-2xl p-6 border border-slate-200">
    <div class="flex justify-between items-center mb-6">
        <h6 class="text-2xl font-bold text-slate-700">Data Penjualan</h6>
    </div>
    
    <form method="GET" class="flex flex-wrap gap-4 mb-4 items-end">
        <!-- Filter Tanggal -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Dari</label>
            <input type="date" name="tanggal_awal" 
                class="border rounded p-2"
                value="{{ request('tanggal_awal') }}">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Sampai</label>
            <input type="date" name="tanggal_akhir" 
                class="border rounded p-2"
                value="{{ request('tanggal_akhir') }}">
        </div>

        <!-- Filter Nama Barang -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Barang</label>
            <input type="text" name="nama_barang" 
                class="border rounded p-2"
                placeholder="Cari barang..." 
                value="{{ request('nama_barang') }}">
        </div>

        <!-- Filter Kategori Jasa -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Kategori Jasa</label>
            <select name="kategori_jasa" class="border rounded p-2 w-40">
                <option value="">Pilih Kategori</option>
                <option value="Semua" {{ request('kategori_jasa')=='Semua' ? 'selected' : '' }}>Semua</option>
                <option value="Cutting" {{ request('kategori_jasa')=='Cutting' ? 'selected' : '' }}>Cutting</option>
                <option value="Sablon" {{ request('kategori_jasa')=='Sablon' ? 'selected' : '' }}>Sablon</option>
            </select>
        </div>

        <!-- Tombol Filter -->
        <button class="bg-blue-600 text-white px-4 py-2 rounded h-[42px]">
            Filter
        </button>

        <!-- Tombol Reset -->
        <a href="{{ route('sales.index') }}" 
        class="bg-gray-400 text-white px-4 py-2 rounded h-[42px] flex items-center">
            Reset
        </a>

        <!-- Tombol Cetak -->
        <a href="{{ route('sales.report', request()->query()) }}"
        class="bg-green-600 text-white px-4 py-2 rounded h-[42px] flex items-center 
        {{ count(request()->query()) == 0 ? 'pointer-events-none opacity-50' : '' }}">
            Cetak
        </a>
    </form>

    <!-- Search and show per page -->
    <div class="flex flex-wrap items-center justify-between mb-4 gap-4">
        <div>
            <label class="mr-2 text-gray-700">Show</label>
            <select id="rowsPerPage" class="border rounded px-2 py-1">
                <option value="5">5</option>
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
            <span class="ml-1 text-gray-700">entries</span>
        </div>
    </div>

    {{-- Tabel daftar penjualan --}}
    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table  id="sortableTable" class="min-w-full text-gray-700">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs text-center font-semibold tracking-wider">
                <tr>
                    <th class="px-4 py-3 text-left border whitespace-nowrap">No</th>
                    <th class="px-4 py-3 border whitespace-nowrap">Tanggal</th>
                    <th class="px-4 py-3 border whitespace-nowrap">Kode Jual</th>
                    <th class="px-4 py-3 border">Nama Pelanggan</th>
                    <th class="px-4 py-3 border whitespace-nowrap">Nama Barang</th>
                    <th class="px-4 py-3 border whitespace-nowrap">Kategori Jasa</th>
                    <th class="px-4 py-3 border whitespace-nowrap">Jumlah</th>
                    <th class="px-4 py-3 border whitespace-nowrap">Total Harga</th>
                    <th class="px-4 py-3 border whitespace-nowrap">Biaya Lainnya</th>
                    <th class="px-4 py-3 border">Potongan Harga</th>
                    <th class="px-4 py-3 border whitespace-nowrap">Total Akhir</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm divide-y divide-gray-200">
                @forelse ($sales as $index => $item)
                <tr class="border-b hover:bg-gray-50 whitespace-nowrap">
                    <td class="px-4 py-3 border">{{ $index + 1 }}</td>
                    <td class="px-4 py-3 border">{{ \Carbon\Carbon::parse($item->tgl_transaksi)->format('d M Y') }}</td>
                    <td class="px-4 py-3 border text-blue-600 hover:underline">
                        @if($item->kode_jual)
                            <a href="{{ route('sales.print', ['kode_jual' => $item->kode_jual]) }}">
                                {{ $item->kode_jual }}
                            </a>
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $item->order->customer->nama_cus }}</td>
                    <td class="px-4 py-3 border">
                        @foreach ($item->detailOrders as $detail)
                            {{ $detail->service->nama_barang }}<br>
                        @endforeach
                    </td>
                    <td class="px-4 py-3 border">
                        @foreach ($item->detailOrders as $detail)
                            {{ $detail->service->kategori_jasa }}<br>
                        @endforeach
                    </td>
                    <td class="px-4 py-3 border text-center">
                        @foreach ($item->detailOrders as $detail)
                            {{ $detail->jumlah_pesan }}<br>
                        @endforeach
                    </td>
                    <td class="px-4 py-3 border text-right">
                        @foreach ($item->detailOrders as $detail)
                            Rp {{ number_format($detail->subtotal, 0,',','.') }}<br>
                        @endforeach
                    </td>
                    <td class="px-4 py-3 border text-right">
                        Rp {{ number_format($item->order->biaya_lainnya, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-3 border text-right">
                        Rp {{ number_format($item->order->potongan_harga, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-3 border text-right">
                        Rp {{ number_format($item->order->total_harga, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-4 py-4 text-center text-gray-500">
                            Belum ada penjualan yang tercatat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- Pagination Info -->
    <div id="paginationControls"
        class="flex justify-between items-center px-2 py-2 mt-2 mb-2 mx-4 bg-white rounded-lg text-sm text-gray-600">
    </div>
</div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const table = document.getElementById("sortableTable");
    const tbody = table.getElementsByTagName("tbody")[0];
    const rowsPerPageSelect = document.getElementById("rowsPerPage");
    const pagination = document.getElementById("paginationControls");

    let allRows = Array.from(tbody.querySelectorAll("tr"));
    let filteredRows = [...allRows];
    let currentPage = 1;
    let rowsPerPage = parseInt(rowsPerPageSelect.value);

    function renderTable() {
        tbody.innerHTML = "";
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        filteredRows.slice(start, end).forEach(row => tbody.appendChild(row));

        const totalPages = Math.ceil(filteredRows.length / rowsPerPage) || 1;
        pagination.innerHTML = `
            <div>
                Menampilkan ${filteredRows.length ? start + 1 : 0} - 
                ${Math.min(end, filteredRows.length)} dari ${filteredRows.length} data
            </div>
            <div class="flex items-center gap-2">
                <button ${currentPage === 1 ? "disabled" : ""} class="px-2 py-1 border rounded" onclick="changePage(${currentPage - 1})">Prev</button>
                <span>Hal ${currentPage} dari ${totalPages}</span>
                <button ${currentPage === totalPages ? "disabled" : ""} class="px-2 py-1 border rounded" onclick="changePage(${currentPage + 1})">Next</button>
            </div>
        `;
    }

    window.changePage = (page) => {
        currentPage = page;
        renderTable();
    };

    rowsPerPageSelect.addEventListener("change", () => {
        rowsPerPage = parseInt(rowsPerPageSelect.value);
        currentPage = 1; // reset ke halaman pertama
        renderTable();
    });

    renderTable(); // render pertama kali
});
</script>
@endsection

