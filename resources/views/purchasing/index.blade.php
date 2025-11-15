@extends('layout.main')
@section('title', 'Pembelian Bahan')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="bg-white shadow-lg rounded-2xl p-6 border border-slate-200">
        <div class="flex items-center justify-between mb-6">
            <h6 class="text-2xl font-bold text-slate-700">Daftar Pembelian Bahan</h6>
        </div>
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
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

            <div>
                <input id="searchInput" type="text" placeholder="Cari pembelian..." class="border rounded px-3 py-1 w-56 focus:ring focus:ring-blue-200" />
            </div>
        </div>

        <div class="border border-slate-200 shadow rounded-2xl bg-white">
            <div class="overflow-x-auto">
                <table id="purchasingTable" class="items-center w-full text-slate-600 border-collapse">
                    <thead class="bg-slate-100 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-3 border border-slate-200 text-left">No</th>
                            <th class="px-6 py-3 border border-slate-200 text-left">Kode Bahan</th>
                            <th class="px-6 py-3 border border-slate-200 text-left">Nama Bahan</th>
                            <th class="px-6 py-3 border border-slate-200 text-right">Ukuran (m)</th>
                            <th class="px-6 py-3 border border-slate-200 text-left">Tanggal Pembelian</th>
                            <th class="px-6 py-3 border border-slate-200 text-left">Pemasok</th>
                            <th class="px-6 py-3 border border-slate-200 text-right">Harga/m</th>
                            <th class="px-6 py-3 border border-slate-200 text-right">Total Harga</th>
                            <th class="px-6 py-3 border border-slate-200 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchasings as $i => $row)
                        <tr class="border-b hover:bg-slate-50" data-id="{{ $row->id }}">
                            <td class="px-6 py-3 border border-slate-200">{{ $i+1 }}</td>
                            <td class="px-6 py-3 border border-slate-200">{{ $row->kode_bahan }}</td>
                            <td class="px-6 py-3 border border-slate-200">{{ $row->nama_bahan }}</td>
                            <td class="px-6 py-3 border border-slate-200 text-right">{{ number_format($row->ukuran_meter,2,'.',',') }}</td>
                            <td class="px-6 py-3 border border-slate-200">{{ $row->tanggal_pembelian ? \Carbon\Carbon::parse($row->tanggal_pembelian)->format('d-m-Y') : '-' }}</td>
                            <td class="px-6 py-3 border border-slate-200">{{ $row->pemasok ?? '-' }}</td>
                            <td class="px-6 py-3 border border-slate-200 text-right">{{ $row->harga_per_meter ? number_format($row->harga_per_meter,2,'.',',') : '-' }}</td>
                            <td class="px-6 py-3 border border-slate-200 text-right">{{ $row->total_harga ? number_format($row->total_harga,2,'.',',') : '-' }}</td>
                            <td class="px-6 py-3 border border-slate-200 text-center">
                                @if(!$row->tanggal_pembelian || !$row->pemasok || !$row->harga_per_meter)
                                    <button type="button" class="px-3 py-1 bg-yellow-500 text-white rounded editBtn" data-id="{{ $row->id }}" data-kode="{{ $row->kode_bahan }}" data-nama="{{ $row->nama_bahan }}" data-ukuran="{{ $row->ukuran_meter }}">Edit Pembelian</button>
                                @else
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded">Lengkap</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-6 text-slate-500">Belum ada data pembelian bahan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Info -->
            <div id="paginationControls" class="flex justify-between items-center px-2 py-2 mt-2 mb-2 mx-4 bg-white rounded-lg text-sm text-gray-600">
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Pembelian -->
<div id="editModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black opacity-40" id="editModalOverlay"></div>
    <div class="bg-white rounded-lg shadow-lg z-50 w-full max-w-md mx-auto p-6 mt-20">
        <h3 class="text-lg font-semibold mb-4">Lengkapi Data Pembelian</h3>
        <form id="editForm" method="POST" action="{{ route('purchasing.update', 0) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="editId">
            <div class="mb-3">
                <label class="block text-gray-700">Nama Bahan</label>
                <input type="text" id="editNama" name="nama_bahan" class="border rounded px-2 py-1 w-full bg-gray-100" readonly>
            </div>
            <div class="mb-3">
                <label class="block text-gray-700">Ukuran (meter)</label>
                <input type="number" id="editUkuran" name="ukuran_meter" class="border rounded px-2 py-1 w-full bg-gray-100" readonly>
            </div>
            <div class="mb-3">
                <label class="block text-gray-700">Tanggal Pembelian</label>
                <input type="date" id="editTanggal" name="tanggal_pembelian" class="border rounded px-2 py-1 w-full" required>
            </div>
            <div class="mb-3">
                <label class="block text-gray-700">Nama Pemasok</label>
                <input type="text" id="editPemasok" name="pemasok" class="border rounded px-2 py-1 w-full" required>
            </div>
            <div class="mb-3">
                <label class="block text-gray-700">Harga per Meter</label>
                <input type="number" id="editHarga" name="harga_per_meter" class="border rounded px-2 py-1 w-full" required>
            </div>
            <div class="mb-3">
                <label class="block text-gray-700">Total Harga</label>
                <input type="number" id="editTotal" name="total_harga" class="border rounded px-2 py-1 w-full bg-gray-100" readonly>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" id="closeEditModal" class="px-3 py-1 rounded bg-gray-200">Batal</button>
                <button type="submit" class="px-3 py-1 rounded bg-blue-500 text-white">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.editBtn');
    const modal = document.getElementById('editModal');
    const overlay = document.getElementById('editModalOverlay');
    const closeBtn = document.getElementById('closeEditModal');
    const form = document.getElementById('editForm');
    const idInput = document.getElementById('editId');
    const namaInput = document.getElementById('editNama');
    const ukuranInput = document.getElementById('editUkuran');
    const tanggalInput = document.getElementById('editTanggal');
    const pemasokInput = document.getElementById('editPemasok');
    const hargaInput = document.getElementById('editHarga');
    const totalInput = document.getElementById('editTotal');

    editButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            idInput.value = btn.getAttribute('data-id');
            namaInput.value = btn.getAttribute('data-nama');
            ukuranInput.value = btn.getAttribute('data-ukuran');
            tanggalInput.value = '';
            pemasokInput.value = '';
            hargaInput.value = '';
            totalInput.value = '';
            form.action = form.action.replace(/\d+$/, btn.getAttribute('data-id'));
            modal.classList.remove('hidden');
            modal.classList.add('flex', 'items-center', 'justify-center');
        });
    });

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex', 'items-center', 'justify-center');
        form.action = form.action.replace(/\d+$/, '0');
    }
    closeBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', closeModal);
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeModal(); });

    hargaInput.addEventListener('input', function() {
        const ukuran = parseFloat(ukuranInput.value) || 0;
        const harga = parseFloat(hargaInput.value) || 0;
        totalInput.value = (ukuran * harga).toFixed(2);
    });
});
</script>
{{-- Table search / pagination script (consistent with other index pages) --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    const table = document.getElementById("purchasingTable");
    if (!table) return;
    const tbody = table.getElementsByTagName("tbody")[0];
    const searchInput = document.getElementById("searchInput");
    const rowsPerPageSelect = document.getElementById("rowsPerPage");
    const pagination = document.getElementById("paginationControls");
    let allRows = Array.from(tbody.querySelectorAll("tr"));
    let filteredRows = [...allRows];
    let currentPage = 1;
    let rowsPerPage = parseInt(rowsPerPageSelect.value || 10);

    function renderTable() {
        tbody.innerHTML = "";
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        filteredRows.slice(start, end).forEach(row => tbody.appendChild(row));

        const totalPages = Math.ceil(filteredRows.length / rowsPerPage) || 1;
        pagination.innerHTML = `
            <div>
                Menampilkan ${filteredRows.length ? start + 1 : 0} - ${Math.min(end, filteredRows.length)} dari ${filteredRows.length} data
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

    function filterTable() {
        const term = (searchInput && searchInput.value) ? searchInput.value.toLowerCase() : '';
        filteredRows = allRows.filter(row => row.textContent.toLowerCase().includes(term));
        currentPage = 1;
        renderTable();
    }

    rowsPerPageSelect.addEventListener('change', () => {
        rowsPerPage = parseInt(rowsPerPageSelect.value);
        currentPage = 1;
        renderTable();
    });
    if (searchInput) searchInput.addEventListener('input', filterTable);

    // initial render
    filterTable();
});
</script>

{{-- SweetAlert2 toast for flash messages (matches other pages) --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if (session('success'))
<script>
Swal.fire({
  toast: true,
  icon: 'success',
  title: '{{ session('success') }}',
  position: 'top-end',
  showConfirmButton: false,
  timer: 2000,
  timerProgressBar: true,
});
</script>
@endif
@endsection
