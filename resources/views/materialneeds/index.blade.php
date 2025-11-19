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
                        <label class="text-gray-700">Tanggal</label>
                        <input type="date" id="filterDate" class="border rounded px-2 py-1 cursor-pointer" />
                        <label class="text-gray-700">Jenis Bahan</label>
                        <select id="jenisBahan" class="border rounded px-2 py-1">
                            <option value="all">Semua</option>
                            <option value="dtf">DTF</option>
                            <option value="polyflex">Polyflex</option>
                        </select>
                    </div>
                </div>

            <!-- Summary table per hari/jenis bahan -->
            <div class="overflow-x-auto mb-6">
                <table id="summaryTable" class="items-center w-full text-slate-600 border-collapse">
                    <thead class="bg-slate-100 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-4 py-3 border border-slate-200 text-left">No</th>
                            <th class="px-4 py-3 border border-slate-200 text-left">Tanggal</th>
                            <th class="px-4 py-3 border border-slate-200 text-left">Nama Bahan</th>
                            <th class="px-4 py-3 border border-slate-200 text-right">Total Kebutuhan (m)</th>
                            <th class="px-4 py-3 border border-slate-200 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Group data by date (Y-m-d) then by kode_bahan and sum kebutuhan_bahan_meter
                            // keep nama_bahan for display (from relation material)
                            $summary = collect($data)
                                ->groupBy(function($item){ return \Carbon\Carbon::parse($item->tgl_pesan)->format('Y-m-d'); })
                                ->map(function($groupByDate){
                                    return $groupByDate->groupBy('kode_bahan')->map(function($g, $kode){
                                        return [
                                            'kode_bahan' => $kode,
                                            'nama_bahan' => $g->first()->material->nama_bahan ?? $kode,
                                            'total' => $g->sum('kebutuhan_bahan_meter'),
                                        ];
                                    });
                                });
                            $rowNo = 0;
                        @endphp

                        @if($summary->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center py-6 text-slate-500">Belum ada data kebutuhan bahan</td>
                            </tr>
                        @else
                            @foreach($summary as $date => $groups)
                                @foreach($groups as $kode => $info)
                                    @php
                                        $rowNo++;
                                        // Get jenis_bahan from first item in group for filtering
                                        $jenisBahan = $data->filter(function($item) use ($date, $kode) {
                                            return \Carbon\Carbon::parse($item->tgl_pesan)->format('Y-m-d') === $date && $item->kode_bahan === $kode;
                                        })->first()?->jenis_bahan ?? 'unknown';
                                    @endphp
                                    <tr class="border-b hover:bg-slate-50" data-tanggal="{{ $date }}" data-jenis="{{ strtolower($jenisBahan) }}">
                                        <td class="px-4 py-3 border border-slate-200">{{ $rowNo }}</td>
                                        <td class="px-4 py-3 border border-slate-200">{{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</td>
                                        <td class="px-4 py-3 border border-slate-200">{{ $info['nama_bahan'] }}</td>
                                        <td class="px-4 py-3 border border-slate-200 text-right">{{ number_format((float)$info['total'], 2, '.', ',') }}</td>
                                        <td class="px-4 py-3 border border-slate-200 text-center">
                                            <button type="button" class="px-3 py-1 bg-blue-500 text-white rounded detailBtn" data-tanggal="{{ $date }}" data-kode="{{ $kode }}" data-nama="{{ $info['nama_bahan'] }}">Detail</button>
                                            <form action="{{ route('purchasing.createFromNeed') }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="kode_bahan" value="{{ $kode }}">
                                                <input type="hidden" name="nama_bahan" value="{{ $info['nama_bahan'] }}">
                                                <input type="hidden" name="total_meter" value="{{ $info['total'] }}">
                                                <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded ml-2">Beli</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination Info for Summary Table -->
            <div id="summaryPaginationControls" class="flex justify-between items-center px-2 py-2 mt-2 mb-6 bg-white rounded-lg text-sm text-gray-600">
            </div>

            <!-- Hidden detailed table (kept for existing listing & JS) - moved inside hidden container -->
            <div id="hiddenDetailed" style="display:none;">
                <div class="overflow-x-auto">
                    <table id="sortableTable" class="items-center w-full text-slate-600 border-collapse">
                        <thead class="bg-slate-100 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-4 py-3 border border-slate-200 text-left cursor-pointer">No</th>
                                <th class="px-4 py-3 border border-slate-200 text-left cursor-pointer" onclick="sortTable(1)">Tanggal Pesanan</th>
                                <th class="px-4 py-3 border border-slate-200 text-left cursor-pointer" onclick="sortTable(2)">Jenis Jasa</th>
                                {{-- <th class="px-4 py-3 border border-slate-200 text-left cursor-pointer" onclick="sortTable(3)">Jenis Bahan</th> --}}
                                <th class="px-4 py-3 border border-slate-200 text-left">Nama Bahan</th>
                                <th class="px-4 py-3 border border-slate-200 text-left">Ukuran Desain (cm)</th>
                                <th class="px-4 py-3 border border-slate-200 text-right cursor-pointer" onclick="sortTable(6)">Jumlah Pesanan</th>
                                <th class="px-4 py-3 border border-slate-200 text-right cursor-pointer" onclick="sortTable(7)">Waste (%)</th>
                                <th class="px-4 py-3 border border-slate-200 text-right cursor-pointer" onclick="sortTable(8)">Kebutuhan Bahan (m)</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($data as $index => $row)
                            <tr class="border-b hover:bg-slate-50" data-kode="{{ $row->kode_bahan }}">
                                <td class="px-4 py-3 border border-slate-200">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 border border-slate-200">{{ \Carbon\Carbon::parse($row->tgl_pesan)->format('d-m-Y') }}</td>
                                <td class="px-4 py-3 border border-slate-200">{{ $row->jenis_jasa }}</td>
                                {{-- <td class="px-4 py-3 border border-slate-200">{{ strtoupper($row->jenis_bahan) }}</td> --}}
                                <td class="px-4 py-3 border border-slate-200">{{ $row->material->nama_bahan ?? '-' }}</td>
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
                <!-- Pagination Info (kept hidden with detailed table) -->
                <div id="paginationControls" class="flex justify-between items-center px-2 py-2 mt-2 mb-2 mx-4 bg-white rounded-lg text-sm text-gray-600">
                </div>
            </div>
    </div>
</div>
@endsection

{{-- Script: Filter Summary Table --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Get elements
    const summaryTable = document.getElementById('summaryTable');
    const filterDateInput = document.getElementById('filterDate');
    const jenisBahanSelect = document.getElementById('jenisBahan');
    const rowsPerPageSelect = document.getElementById('rowsPerPage');
    const paginationControls = document.getElementById('summaryPaginationControls');

    // Early exit if elements not found
    if (!summaryTable || !filterDateInput || !jenisBahanSelect) {
        console.error('Required elements not found', {
            summaryTable: !!summaryTable,
            filterDateInput: !!filterDateInput,
            jenisBahanSelect: !!jenisBahanSelect
        });
        return;
    }

    const tbody = summaryTable.querySelector('tbody');
    if (!tbody) {
        console.error('Summary table tbody not found');
        return;
    }

    // Pagination variables
    let currentPage = 1;
    let rowsPerPage = 10;

    // Get all data rows (exclude empty message row)
    function getAllDataRows() {
        return Array.from(tbody.querySelectorAll('tr')).filter(row => {
            // Exclude colspan rows (empty message)
            return !row.querySelector('td[colspan]');
        });
    }

    function parseDateDMY(dateStr) {
        if (!dateStr) return null;
        // Parse format: "13-11-2025"
        const parts = dateStr.match(/(\d{1,2})-(\d{1,2})-(\d{4})/);
        if (!parts) return null;
        const day = parseInt(parts[1], 10);
        const month = parseInt(parts[2], 10) - 1; // JS months are 0-indexed
        const year = parseInt(parts[3], 10);
        return new Date(year, month, day);
    }

    function dateToYMD(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function getVisibleRows() {
        const rows = getAllDataRows();
        return rows.filter(row => row.style.display !== 'none');
    }

    function renderPagination() {
        const visibleRows = getVisibleRows();
        const totalPages = Math.ceil(visibleRows.length / rowsPerPage) || 1;

        if (currentPage > totalPages) {
            currentPage = 1;
        }

        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        paginationControls.innerHTML = `
            <div>
                Menampilkan ${visibleRows.length ? start + 1 : 0} - ${Math.min(end, visibleRows.length)} dari ${visibleRows.length} data
            </div>
            <div class="flex items-center gap-2">
                <button ${currentPage === 1 ? "disabled" : ""} class="px-2 py-1 border rounded" onclick="changeSummaryPage(${currentPage - 1})">Prev</button>
                <span>Hal ${currentPage} dari ${totalPages}</span>
                <button ${currentPage === totalPages ? "disabled" : ""} class="px-2 py-1 border rounded" onclick="changeSummaryPage(${currentPage + 1})">Next</button>
            </div>
        `;
    }

    window.changeSummaryPage = (page) => {
        currentPage = page;
        applyFilters();
    };

    function applyFilters() {
        const selectedDate = filterDateInput.value || ''; // YYYY-MM-DD format
        const selectedJenis = jenisBahanSelect.value || 'all';

        console.log('Applying filters:', { selectedDate, selectedJenis });

        const rows = getAllDataRows();
        let visibleCount = 0;

        rows.forEach(row => {
            let shouldShow = true;

            // Filter by date
            if (selectedDate) {
                const dateCell = row.cells[1];
                if (dateCell) {
                    const cellDateStr = dateCell.innerText.trim();
                    const parsedDate = parseDateDMY(cellDateStr);
                    if (!parsedDate || dateToYMD(parsedDate) !== selectedDate) {
                        shouldShow = false;
                    }
                }
            }

            // Filter by jenis bahan
            if (shouldShow && selectedJenis !== 'all') {
                const jenisData = row.getAttribute('data-jenis') || '';
                if (!jenisData.toLowerCase().includes(selectedJenis.toLowerCase())) {
                    shouldShow = false;
                }
            }

            row.style.display = shouldShow ? '' : 'none';
            if (shouldShow) visibleCount++;
        });

        // Show/hide empty message
        let emptyRow = tbody.querySelector('tr td[colspan]');
        if (visibleCount === 0) {
            // No visible rows, show empty message if it doesn't exist
            if (!emptyRow) {
                const newEmptyRow = document.createElement('tr');
                newEmptyRow.innerHTML = '<td colspan="5" class="text-center py-6 text-slate-500">Belum ada data kebutuhan bahan</td>';
                tbody.appendChild(newEmptyRow);
            } else {
                emptyRow.parentElement.style.display = '';
            }
            paginationControls.innerHTML = '';
        } else {
            // Visible rows exist, hide empty message
            if (emptyRow) {
                emptyRow.parentElement.style.display = 'none';
            }

            // Reset pagination and render
            currentPage = 1;
            renderPagination();
        }

        console.log('Visible rows after filter:', visibleCount);
    }

    // Attach event listeners
    filterDateInput.addEventListener('change', applyFilters);
    jenisBahanSelect.addEventListener('change', applyFilters);

    if (rowsPerPageSelect) {
        rowsPerPageSelect.addEventListener('change', () => {
            rowsPerPage = parseInt(rowsPerPageSelect.value);
            currentPage = 1;
            applyFilters();
        });
    }

    // Initial render
    renderPagination();

    console.log('Filter Summary Table script initialized');
});
</script>

{{-- Script: search / sort / pagination (mirip services.index) --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    const table = document.getElementById("sortableTable");
    if (!table) return; // safeguard if table not rendered
    const tbody = table.getElementsByTagName("tbody")[0];
        const searchInput = document.getElementById("searchInput");
        const rowsPerPageSelect = document.getElementById("rowsPerPage");
    const pagination = document.getElementById("paginationControls");
    const filterDateInput = document.getElementById('filterDate');
    const jenisBahanSelect = document.getElementById('jenisBahan');
    let allRows = Array.from(tbody.querySelectorAll("tr"));
    let filteredRows = [...allRows];
    let currentPage = 1;
    let rowsPerPage = parseInt(rowsPerPageSelect.value || 10);
    let sortDirection = true;
    let currentSortColumn = null;

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

        function parseDateDMY(dateStr) {
            // Parse tanggal format d-m-Y (dari cell display) ke Date object
            if (!dateStr) return null;
            const parts = dateStr.match(/(\d{1,2})-(\d{1,2})-(\d{4})/);
            if (!parts) return null;
            const d = parseInt(parts[1], 10);
            const m = parseInt(parts[2], 10) - 1;
            const y = parseInt(parts[3], 10);
            return new Date(y, m, d);
        }

        function toYMDString(date) {
            // Convert Date to YYYY-MM-DD string
            const year = date.getFullYear();
            const month = ('0' + (date.getMonth() + 1)).slice(-2);
            const day = ('0' + date.getDate()).slice(-2);
            return `${year}-${month}-${day}`;
        }

        function filterTable() {
            const term = (searchInput && searchInput.value) ? searchInput.value.toLowerCase() : '';
            const jenisFilter = (jenisBahanSelect && jenisBahanSelect.value) ? jenisBahanSelect.value : 'all';
            const selectedDate = filterDateInput && filterDateInput.value ? filterDateInput.value : null;

            filteredRows = allRows.filter(row => {
                // text search
                if (term && !row.textContent.toLowerCase().includes(term)) return false;
                // jenis bahan filter (column index 3)
                if (jenisFilter && jenisFilter !== 'all') {
                    const jb = (row.cells[3] && row.cells[3].innerText) ? row.cells[3].innerText.trim().toLowerCase() : '';
                    if (!jb.includes(jenisFilter.toLowerCase())) return false;
                }
                // date filter (column index 1) - table shows d-m-Y format
                if (selectedDate && row.cells[1]) {
                    const cellDateStr = row.cells[1].innerText.trim();
                    const cellDate = parseDateDMY(cellDateStr);
                    if (!cellDate) return false;
                    const cellYMD = toYMDString(cellDate);
                    if (cellYMD !== selectedDate) return false;
                }
                return true;
            });

            currentPage = 1;
            renderTable();
        }

    function sortTable(columnIndex) {
    // numeric columns: jumlah (6), waste (7), kebutuhan (8) -- adjusted for added Nama Bahan column
    const numericCols = [6,7,8];
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
        if (filterDateInput) filterDateInput.addEventListener('change', filterTable);
        if (rowsPerPageSelect) rowsPerPageSelect.addEventListener("change", () => {
        rowsPerPage = parseInt(rowsPerPageSelect.value);
        currentPage = 1;
        renderTable();
    });

    // initial render
    filterTable();
});
</script>

<!-- Modal for detail -> shows filtered detailed rows -->
<div id="detailModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black opacity-40" id="detailModalOverlay"></div>
    <div class="bg-white rounded-lg shadow-lg z-50 w-11/12 max-w-4xl p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-semibold">Rincian Kebutuhan Bahan</h3>
            <button id="closeDetailModal" class="px-3 py-1 rounded bg-gray-200">Tutup</button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-slate-600 border-collapse">
                <thead class="bg-slate-100 text-xs uppercase font-semibold">
                    <tr>
                        <th class="px-4 py-2 border">No</th>
                        <th class="px-4 py-2 border">Tanggal Pesanan</th>
                        <th class="px-4 py-2 border">Jenis Jasa</th>
                        <th class="px-4 py-2 border">Jenis Bahan</th>
                        <th class="px-4 py-2 border">Ukuran Desain (cm)</th>
                        <th class="px-4 py-2 border text-right">Jumlah Pesanan</th>
                        <th class="px-4 py-2 border text-right">Waste (%)</th>
                        <th class="px-4 py-2 border text-right">Kebutuhan Bahan (m)</th>
                    </tr>
                </thead>
                        <tbody id="modalDetailTbody">
                    <!-- filled by JS -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Helper to parse d-m-Y or yyyy-mm-dd to Date
    function parseDMY_local(dateStr) {
        if (!dateStr) return null;
        if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) return new Date(dateStr + 'T00:00:00');
        const parts = dateStr.match(/(\d{1,2})[\-\/](\d{1,2})[\-\/]?(\d{2,4})/);
        if (!parts) return null;
        let d = parseInt(parts[1],10);
        let m = parseInt(parts[2],10) - 1;
        let y = parseInt(parts[3],10);
        if (y < 100) y += 2000;
        return new Date(y, m, d);
    }

    function toYMD(date) {
        if (!date) return null;
        const yyyy = date.getFullYear();
        const mm = ('0'+(date.getMonth()+1)).slice(-2);
        const dd = ('0'+date.getDate()).slice(-2);
        return `${yyyy}-${mm}-${dd}`;
    }

    const detailButtons = document.querySelectorAll('.detailBtn');
    const modal = document.getElementById('detailModal');
    const modalOverlay = document.getElementById('detailModalOverlay');
    const closeBtn = document.getElementById('closeDetailModal');
    const modalTbody = document.getElementById('modalDetailTbody');

    // gather hidden detailed rows
    const hiddenRows = Array.from(document.querySelectorAll('#sortableTable tbody tr'));

    function openModalFor(dateYmd, kode) {
        modalTbody.innerHTML = '';
        let found = 0;
        hiddenRows.forEach(r => {
            const cellDate = r.cells[1] ? r.cells[1].innerText.trim() : '';
            const parsed = parseDMY_local(cellDate);
            const rowYmd = parsed ? toYMD(parsed) : null;
            const rowKode = r.getAttribute('data-kode') ? r.getAttribute('data-kode').toString() : '';
            if (rowYmd === dateYmd && rowKode === kode) {
                // clone the row and append (but adjust numbering)
                const clone = r.cloneNode(true);
                // replace first cell with incremental number
                const tr = document.createElement('tr');
                tr.className = 'border-b hover:bg-slate-50';
                const cells = Array.from(clone.children).map(td => td.innerHTML);
                // build new cells preserving classes
                for (let i = 0; i < cells.length; i++) {
                    const td = document.createElement('td');
                    td.className = clone.children[i].className;
                    td.innerHTML = cells[i];
                    tr.appendChild(td);
                }
                // set first cell to found+1
                if (tr.children[0]) tr.children[0].innerText = (found + 1);
                modalTbody.appendChild(tr);
                found++;
            }
        });
        if (found === 0) {
            const tr = document.createElement('tr');
            tr.innerHTML = '<td colspan="9" class="text-center py-4">Tidak ada rincian untuk pilihan ini.</td>';
            modalTbody.appendChild(tr);
        }
        // show modal: remove hidden and add flex to center
        modal.classList.remove('hidden');
        modal.classList.add('flex', 'items-center', 'justify-center');
    }

    detailButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const date = btn.getAttribute('data-tanggal');
            const kode = btn.getAttribute('data-kode');
            if (!date || !kode) return;
            openModalFor(date, kode);
        });
    });

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex', 'items-center', 'justify-center');
    }
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (modalOverlay) modalOverlay.addEventListener('click', closeModal);
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });
});
</script>
