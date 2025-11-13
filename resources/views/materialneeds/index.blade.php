@extends('layout.main')
@section('title', 'Kebutuhan Bahan')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="bg-white shadow-lg rounded-2xl p-6 border border-slate-200">
        <div class="flex items-center justify-between mb-6">
            <h6 class="text-2xl font-bold text-slate-700">Daftar Kebutuhan Bahan</h6>
        </div>

                <div class="mb-4 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <label class="mr-2 text-gray-700">Show</label>
                        <select id="rowsPerPage" class="border rounded px-2 py-1">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span class="ml-1 text-gray-700">entries</span>
                    </div>

                            <div class="flex items-center gap-3">
                                <label class="text-gray-700">Dari</label>
                                <input id="dateFrom" type="date" class="border rounded px-2 py-1" />
                                <label class="text-gray-700">Sampai</label>
                                <input id="dateTo" type="date" class="border rounded px-2 py-1" />
                                <label class="text-gray-700">Jenis Bahan</label>
                                <select id="jenisBahan" class="border rounded px-2 py-1">
                                    <option value="all">Semua</option>
                                    <option value="dtf">DTF</option>
                                    <option value="polyflex">Polyflex</option>
                                </select>
                            </div>
                </div>

            <div class="overflow-x-auto">
                <table id="sortableTable" class="items-center w-full text-slate-600 border-collapse">
                    <thead class="bg-slate-100 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-4 py-3 border border-slate-200 text-left cursor-pointer">No</th>
                            <th class="px-4 py-3 border border-slate-200 text-left cursor-pointer" onclick="sortTable(1)">Tanggal Pesanan</th>
                            <th class="px-4 py-3 border border-slate-200 text-left cursor-pointer" onclick="sortTable(2)">Jenis Jasa</th>
                            <th class="px-4 py-3 border border-slate-200 text-left cursor-pointer" onclick="sortTable(3)">Jenis Bahan</th>
                            <th class="px-4 py-3 border border-slate-200 text-left">Ukuran Desain (cm)</th>
                            <th class="px-4 py-3 border border-slate-200 text-right cursor-pointer" onclick="sortTable(5)">Jumlah Pesanan</th>
                            <th class="px-4 py-3 border border-slate-200 text-right cursor-pointer" onclick="sortTable(6)">Waste (%)</th>
                            <th class="px-4 py-3 border border-slate-200 text-right cursor-pointer" onclick="sortTable(7)">Kebutuhan Bahan (m)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $row)
                        <tr class="border-b hover:bg-slate-50">
                            <td class="px-4 py-3 border border-slate-200">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 border border-slate-200">{{ \Carbon\Carbon::parse($row->tgl_pesan)->format('d-m-Y') }}</td>
                            <td class="px-4 py-3 border border-slate-200">{{ $row->jenis_jasa }}</td>
                            <td class="px-4 py-3 border border-slate-200">{{ strtoupper($row->jenis_bahan) }}</td>
                            <td class="px-4 py-3 border border-slate-200">{{ rtrim(rtrim((string)$row->ukuran_panjang, '0'), '.') }} × {{ rtrim(rtrim((string)$row->ukuran_lebar, '0'), '.') }}</td>
                            <td class="px-4 py-3 border border-slate-200 text-right">{{ number_format($row->jumlah_pesanan, 0, '.', ',') }}</td>
                            <td class="px-4 py-3 border border-slate-200 text-right">{{ number_format($row->waste_persen, 0, '.', ',') }}%</td>
                            <td class="px-4 py-3 border border-slate-200 text-right">{{ number_format($row->kebutuhan_bahan_meter, 2, '.', ',') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-6 text-slate-500">Belum ada data kebutuhan bahan</td>
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
@endsection

{{-- Script: search / sort / pagination (mirip services.index) --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    const table = document.getElementById("sortableTable");
    if (!table) return; // safeguard if table not rendered
    const tbody = table.getElementsByTagName("tbody")[0];
        const searchInput = document.getElementById("searchInput");
        const rowsPerPageSelect = document.getElementById("rowsPerPage");
    const pagination = document.getElementById("paginationControls");
    let allRows = Array.from(tbody.querySelectorAll("tr"));
    let filteredRows = [...allRows];
    let currentPage = 1;
    let rowsPerPage = parseInt(rowsPerPageSelect.value || 10);
    let sortDirection = true;
    let currentSortColumn = null;
        const dateFromInput = document.getElementById('dateFrom');
        const dateToInput = document.getElementById('dateTo');
        const jenisBahanSelect = document.getElementById('jenisBahan');
            const resetBtn = document.getElementById('resetFilters');

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

        function parseDMY(dateStr) {
            // expected d-m-Y or d/m/Y or yyyy-mm-dd
            if (!dateStr) return null;
            // if already ISO (yyyy-mm-dd)
            if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) {
                return new Date(dateStr + 'T00:00:00');
            }
            const parts = dateStr.match(/(\d{1,2})[\-\/](\d{1,2})[\-\/]?(\d{2,4})/);
            if (!parts) return null;
            let d = parseInt(parts[1],10);
            let m = parseInt(parts[2],10) - 1;
            let y = parseInt(parts[3],10);
            if (y < 100) y += 2000;
            return new Date(y, m, d);
        }

        function filterTable() {
            const term = (searchInput && searchInput.value) ? searchInput.value.toLowerCase() : '';
            const jenisFilter = (jenisBahanSelect && jenisBahanSelect.value) ? jenisBahanSelect.value : 'all';
            const fromDate = dateFromInput && dateFromInput.value ? new Date(dateFromInput.value + 'T00:00:00') : null;
            const toDate = dateToInput && dateToInput.value ? new Date(dateToInput.value + 'T23:59:59') : null;

            filteredRows = allRows.filter(row => {
                // text search
                if (term && !row.textContent.toLowerCase().includes(term)) return false;
                // jenis bahan filter (column index 3)
                if (jenisFilter && jenisFilter !== 'all') {
                    const jb = (row.cells[3] && row.cells[3].innerText) ? row.cells[3].innerText.trim().toLowerCase() : '';
                    if (!jb.includes(jenisFilter.toLowerCase())) return false;
                }
                // date range filter (column index 1) - table shows d-m-Y
                if ((fromDate || toDate) && row.cells[1]) {
                    const cellDateStr = row.cells[1].innerText.trim();
                    const cellDate = parseDMY(cellDateStr);
                    if (!cellDate) return false;
                    if (fromDate && cellDate < fromDate) return false;
                    if (toDate && cellDate > toDate) return false;
                }
                return true;
            });

            currentPage = 1;
            renderTable();
        }

    function sortTable(columnIndex) {
        // numeric columns: jumlah (5), waste (6), kebutuhan (7)
        const numericCols = [5,6,7];
        const isNumeric = numericCols.includes(columnIndex);
        sortDirection = (currentSortColumn === columnIndex) ? !sortDirection : true;
        currentSortColumn = columnIndex;

        filteredRows.sort((a, b) => {
            const aText = (a.cells[columnIndex] && a.cells[columnIndex].innerText) ? a.cells[columnIndex].innerText.trim() : '';
            const bText = (b.cells[columnIndex] && b.cells[columnIndex].innerText) ? b.cells[columnIndex].innerText.trim() : '';
            if (isNumeric) {
                const aNum = parseFloat(aText.replace(/[^0-9\-,\.]/g, '').replace(/,/g, '')) || 0;
                const bNum = parseFloat(bText.replace(/[^0-9\-,\.]/g, '').replace(/,/g, '')) || 0;
                return sortDirection ? aNum - bNum : bNum - aNum;
            } else {
                return sortDirection ? aText.localeCompare(bText, 'id', { numeric: true }) : bText.localeCompare(aText, 'id', { numeric: true });
            }
        });

        currentPage = 1;
        renderTable();
    }

    // Attach click handlers for header sorting (we added onclick in th for key columns)
    // also set cursor for all headers
    table.querySelectorAll("th").forEach((th, i) => {
        th.style.cursor = "pointer";
    });

        if (searchInput) searchInput.addEventListener("input", filterTable);
        if (jenisBahanSelect) jenisBahanSelect.addEventListener('change', filterTable);
        if (dateFromInput) dateFromInput.addEventListener('change', filterTable);
        if (dateToInput) dateToInput.addEventListener('change', filterTable);
            if (resetBtn) resetBtn.addEventListener('click', () => {
                if (dateFromInput) dateFromInput.value = '';
                if (dateToInput) dateToInput.value = '';
                if (jenisBahanSelect) jenisBahanSelect.value = 'all';
                if (searchInput) searchInput.value = '';
                if (rowsPerPageSelect) { rowsPerPageSelect.value = '10'; rowsPerPage = 10; }
                currentPage = 1;
                filterTable();
            });
        if (rowsPerPageSelect) rowsPerPageSelect.addEventListener("change", () => {
        rowsPerPage = parseInt(rowsPerPageSelect.value);
        currentPage = 1;
        renderTable();
    });

    // initial render
    filterTable();
});
</script>
