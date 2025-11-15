@extends('layout.main')
@section('title', 'Data Pesanan')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
<!-- Card Utama -->
<div class="bg-white shadow-lg rounded-2xl p-6 border border-slate-200">
    <div class="flex justify-between items-center mb-6">
        <h6 class="text-2xl font-bold text-slate-700">Data Pesanan</h6>
    </div>
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
    
    {{-- Tabel daftar pesanan --}}
    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table  id="sortableTable" class="min-w-full text-gray-700">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-semibold tracking-wider">
                <tr>
                    <th class="w-[30px] px-4 py-3 text-left border">No.</th>
                    <th class="px-4 py-3 text-center border">Kode Pesanan</th>
                    <th class="px-4 py-3 text-center border">Nama Pelanggan</th>
                    <th class="px-4 py-3 text-center border">Tanggal Pesan</th>
                    <th class="px-4 py-3 text-center border">Tanggal Ambil</th>
                    <th class="px-4 py-3 text-center border">Detail Order</th>
                    <th class="px-4 py-3 text-center border">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm divide-y divide-gray-200">
                @forelse ($orders as $index => $order)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $order->kode_pesan }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $order->customer->nama_cus }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ \Carbon\Carbon::parse($order->tgl_pesan)->format('d M Y') }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ \Carbon\Carbon::parse($order->tgl_ambil)->format('d M Y') }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <a href="{{ route('orders.show', $order->id) }}"
                            class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-1.5 rounded-lg transition">
                                Lihat Detail
                            </a>
                        </td>
                        <td class="px-3 py-2 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('sales.create', $order->id) }}"
                                    class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1.5 rounded">
                                    Pembayaran
                                </a>
                                <form id="cancelForm{{ $order->id }}" action="{{ route('orders.cancel', $order->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button onclick="confirmCancel({{ $order->id }})"
                                    class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1.5 rounded">
                                    Batal
                                </button>
                            </div>
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

function confirmCancel(orderId) {
    Swal.fire({
        title: 'Batalkan Pesanan?',
        text: 'Pesanan yang dibatalkan tidak bisa dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, batalkan',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('cancelForm' + orderId).submit();
        }
    });
}
</script>

@endsection

