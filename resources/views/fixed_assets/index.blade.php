@extends('layout.main')
@section('title', 'Aset Tetap')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    @php
        $categoryLabels = [
            'mesin' => 'Mesin',
            'peralatan_it' => 'Peralatan IT',
        ];
    @endphp
    <!-- Card Utama -->
    <div class="bg-white shadow-lg rounded-2xl p-6 border border-slate-200">
        <div class="flex justify-between items-center mb-6">
            <h6 class="text-2xl font-bold text-slate-700">Halaman Aset Tetap</h6>
        </div>

        {{-- Button: Tambah Aset (opens modal) --}}
        <div class="mb-6 flex items-center justify-between">
            <div>
                <button id="openAddBtn" type="button"
                    class="bg-slate-700 text-white px-4 py-2 rounded-lg hover:bg-slate-800 transition">
                    + Tambah Aset
                </button>
            </div>
        </div>

        {{-- Modal: Add / Edit Aset --}}
        <div id="assetModal" style="z-index:9999" class="hidden fixed inset-0 items-center justify-center">
            <!-- Overlay -->
            <div id="modalOverlay" style="z-index:9998" class="absolute inset-0 bg-black opacity-50"></div>

            <!-- Modal box -->
            <div style="z-index:9999" class="relative bg-white rounded-2xl shadow-lg w-full max-w-2xl sm:max-w-lg p-6 mx-4 max-h-96 overflow-y-auto">
                <!-- Tombol X di pojok kanan atas -->
                <button id="closeModalX"
                    type="button"
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-2xl font-bold focus:outline-none">
                    &times;
                </button>

                <h2 id="formTitle" class="text-xl font-semibold text-slate-700 mb-4">Tambah Aset Tetap</h2>

                <form id="assetForm" action="{{ route('fixed_assets.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="_method" id="form_method" value="POST">
                    <input type="hidden" name="id" id="asset_id">
                    <input type="hidden" name="nilai_residu" id="nilai_residu">
                    <input type="hidden" name="umur_ekonomis" id="umur_ekonomis">
                    <input type="hidden" name="metode_penyusutan" id="metode_penyusutan" value="garis_lurus">

                    <div class="space-y-4 max-h-[45vh] overflow-y-auto pr-2">
                        <div>
                            <label class="block text-sm font-medium mb-1">Kode Aset</label>
                            <input type="text" name="kode_aset" id="kode_aset"
                                class="w-full border rounded-lg px-3 py-2 bg-gray-100"
                                value="{{ $kodeBahan }}" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Nama Aset</label>
                            <input type="text" name="nama_aset" id="nama_aset"
                                class="w-full border rounded-lg px-3 py-2" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Kategori Aset</label>
                            <select name="kategori_aset" id="kategori_aset"
                                class="w-full border rounded-lg px-3 py-2" required>
                                <option value="" disabled selected>Pilih kategori aset</option>
                                <option value="mesin">Mesin</option>
                                <option value="peralatan_it">Peralatan IT</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Tanggal Perolehan</label>
                            <input type="date" name="tanggal_perolehan" id="tanggal_perolehan"
                                class="w-full border rounded-lg px-3 py-2" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Harga Perolehan</label>
                            <input type="number" name="harga_perolehan" id="harga_perolehan"
                                class="w-full border rounded-lg px-3 py-2" step="0.01" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Catatan</label>
                            <textarea name="catatan" id="catatan"
                                class="w-full border rounded-lg px-3 py-2" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end space-x-3">
                        <button type="button" id="cancelModal"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-slate-700 text-white px-4 py-2 rounded-lg hover:bg-slate-800 transition">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal: Detail Aset --}}
        <div id="detailModal" style="z-index:9999" class="hidden fixed inset-0 items-center justify-center">
            <!-- Overlay -->
            <div style="z-index:9998" class="absolute inset-0 bg-black opacity-50"></div>

            <!-- Modal box -->
            <div style="z-index:9999" class="relative bg-white rounded-2xl shadow-lg w-full max-w-2xl p-6 mx-4 max-h-96 overflow-y-auto">
                <!-- Tombol X di pojok kanan atas -->
                <button id="closeDetailX"
                    type="button"
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-2xl font-bold focus:outline-none">
                    &times;
                </button>

                <div class="mb-6">
                    <p class="text-sm text-gray-500">Kode Aset</p>
                    <h2 id="detailKodeAset" class="text-xl font-semibold text-slate-700">-</h2>
                </div>

                <div class="mb-6">
                    <p class="text-sm text-gray-500">Nama Aset</p>
                    <h3 id="detailNamaAset" class="text-lg font-medium text-slate-700">-</h3>
                </div>

                <div class="mb-6">
                    <p class="text-sm text-gray-500">Umur Ekonomis</p>
                    <h3 id="detailUmurEkonomi" class="text-lg font-medium text-slate-700">-</h3>
                </div>

                <div class="mb-6">
                    <p class="text-sm text-gray-500">Metode</p>
                    <h3 id="detailMetodePenyusutan" class="text-lg font-medium text-slate-700">-</h3></span>
                </div>

                <div class="border-t pt-4">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-100 text-xs uppercase font-semibold">
                                <tr>
                                    <th class="px-4 py-2 border border-slate-200 text-left">No.</th>
                                    <th class="px-4 py-2 border border-slate-200 text-left">Tahun</th>
                                    <th class="px-4 py-2 border border-slate-200 text-left">Harga Perolehan</th>
                                    <th class="px-4 py-2 border border-slate-200 text-left">Nilai Residu</th>
                                    <th class="px-4 py-2 border border-slate-200 text-left">Penyusutan per Tahun</th>
                                    <th class="px-4 py-2 border border-slate-200 text-left">Akumulasi Penyusutan</th>
                                    <th class="px-4 py-2 border border-slate-200 text-left">Nilai Buku Akhir Tahun</th>
                                </tr>
                            </thead>
                            <tbody id="detailScheduleBody">
                                <!-- Schedule rows will be injected here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="button" id="closeDetailBtn"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        {{-- Modal: Jual Aset --}}
        <div id="saleModal" style="z-index:9999" class="hidden fixed inset-0 items-center justify-center">
            <!-- Overlay -->
            <div style="z-index:9998" class="absolute inset-0 bg-black opacity-50"></div>

            <!-- Modal box -->
            <div style="z-index:9999" class="relative bg-white rounded-2xl shadow-lg w-full max-w-md p-6 mx-4">
                <!-- Tombol X di pojok kanan atas -->
                <button id="closeSaleX"
                    type="button"
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-2xl font-bold focus:outline-none">
                    &times;
                </button>

                <h2 class="text-xl font-semibold text-slate-700 mb-4">Catat Penjualan Aset</h2>

                <div class="mb-4 pb-4 border-b">
                    <p class="text-sm text-gray-500">Aset</p>
                    <p id="saleAssetName" class="text-lg font-medium text-slate-700">-</p>
                </div>

                <form id="saleForm" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="_method" value="PATCH">
                    <input type="hidden" name="asset_id" id="sale_asset_id">

                    <div>
                        <label class="block text-sm font-medium mb-1">Tanggal Penjualan</label>
                        <input type="date" name="tanggal_jual" id="tanggal_jual"
                            class="w-full border rounded-lg px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Harga Jual</label>
                        <input type="number" name="harga_jual" id="harga_jual"
                            class="w-full border rounded-lg px-3 py-2" step="0.01" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Pembeli</label>
                        <input type="text" name="pembeli" id="pembeli"
                            class="w-full border rounded-lg px-3 py-2" required>
                    </div>

                    <div class="pt-4 flex justify-end space-x-3">
                        <button type="button" id="closeSaleBtn"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-slate-700 text-white px-4 py-2 rounded-lg hover:bg-slate-800 transition">
                            Simpan
                        </button>
                    </div>
                </form>
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

            <div>
                <input id="searchInput"
                    type="text"
                    placeholder="Cari aset..."
                    class="border rounded px-3 py-1 w-48 focus:ring focus:ring-blue-200" />
            </div>
        </div>

        {{-- Tabel Data --}}
        <div class="border border-slate-200 shadow rounded-2xl bg-white">
            <div class="overflow-x-auto">
                <table id="sortableTable" class="items-center w-full text-slate-600 border-collapse">
                    <thead class="bg-slate-100 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-3 border border-slate-200 text-center w-12">No.</th>
                            <th class="px-6 py-3 border border-slate-200 text-left cursor-pointer" onclick="sortTable(1)">Kode Aset</th>
                            <th class="px-6 py-3 border border-slate-200 text-left cursor-pointer" onclick="sortTable(2)">Nama Aset</th>
                            <th class="px-6 py-3 border border-slate-200 text-left cursor-pointer" onclick="sortTable(3)">Kategori</th>
                            <th class="px-6 py-3 border border-slate-200 text-center cursor-pointer" onclick="sortTable(4)">Tanggal Perolehan</th>
                            <th class="px-6 py-3 border border-slate-200 text-center cursor-pointer" onclick="sortTable(5)">Harga Perolehan</th>
                            <th class="px-6 py-3 border border-slate-200 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assets as $index => $asset)
                        <tr class="border-b hover:bg-slate-50">
                            <td class="px-6 py-3 border border-slate-200 text-center">{{ $index + 1 }}</td>
                            <td class="px-6 py-3 border border-slate-200">{{ $asset->kode_aset }}</td>
                            <td class="px-6 py-3 border border-slate-200">{{ $asset->nama_aset }}</td>
                            <td class="px-6 py-3 border border-slate-200">{{ $categoryLabels[$asset->kategori_aset] ?? $asset->kategori_aset }}</td>
                            <td class="px-6 py-3 border border-slate-200 text-center">{{ \Carbon\Carbon::parse($asset->tanggal_perolehan)->format('d-m-Y') }}</td>
                            <td class="px-6 py-3 border border-slate-200 text-center">Rp {{ number_format($asset->harga_perolehan, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 border border-slate-200 text-center">
                                <button type="button"
                                    class="text-blue-500 hover:text-blue-700"
                                    onclick="showDetail({{ $asset->id }})">
                                    Detail
                                </button>
                                |
                                <button type="button"
                                    class="text-green-500 hover:text-green-700"
                                    onclick="editAsset({{ $asset->id }}, '{{ $asset->kode_aset }}', '{{ $asset->nama_aset }}', '{{ $asset->kategori_aset }}', '{{ $asset->tanggal_perolehan }}', '{{ $asset->harga_perolehan }}', '{{ $asset->nilai_residu }}', '{{ $asset->umur_ekonomis }}', '{{ $asset->metode_penyusutan }}', '{{ $asset->catatan }}')">
                                    Edit
                                </button>
                                |
                                <button type="button"
                                    class="text-orange-500 hover:text-orange-700"
                                    onclick="openSaleModal({{ $asset->id }}, '{{ $asset->nama_aset }}')">
                                    Jual
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-slate-500">Belum ada data aset</td>
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
</div>

{{-- Script --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    // ========== MODAL CONTROLS ==========
    const assetModal = document.getElementById("assetModal");
    const detailModal = document.getElementById("detailModal");
    const saleModal = document.getElementById("saleModal");
    const openAddBtn = document.getElementById("openAddBtn");
    const closeModalX = document.getElementById("closeModalX");
    const cancelModal = document.getElementById("cancelModal");
    const closeDetailX = document.getElementById("closeDetailX");
    const closeDetailBtn = document.getElementById("closeDetailBtn");
    const closeSaleX = document.getElementById("closeSaleX");
    const closeSaleBtn = document.getElementById("closeSaleBtn");
    const assetForm = document.getElementById("assetForm");
    const saleForm = document.getElementById("saleForm");

    // Open/Close Add Modal
    openAddBtn.addEventListener("click", () => {
        resetForm();
        assetModal.classList.remove("hidden");
        assetModal.classList.add("flex");
    });

    closeModalX.addEventListener("click", () => {
        assetModal.classList.add("hidden");
        assetModal.classList.remove("flex");
    });

    cancelModal.addEventListener("click", () => {
        assetModal.classList.add("hidden");
        assetModal.classList.remove("flex");
    });

    document.getElementById("modalOverlay")?.addEventListener("click", () => {
        assetModal.classList.add("hidden");
        assetModal.classList.remove("flex");
    });

    // Close Detail Modal
    closeDetailX.addEventListener("click", () => {
        detailModal.classList.add("hidden");
        detailModal.classList.remove("flex");
    });

    closeDetailBtn.addEventListener("click", () => {
        detailModal.classList.add("hidden");
        detailModal.classList.remove("flex");
    });

    // Close Sale Modal
    closeSaleX.addEventListener("click", () => {
        saleModal.classList.add("hidden");
        saleModal.classList.remove("flex");
    });

    closeSaleBtn.addEventListener("click", () => {
        saleModal.classList.add("hidden");
        saleModal.classList.remove("flex");
    });

    // ========== TABLE PAGINATION & SEARCH ==========
    const table = document.getElementById("sortableTable");
    const tbody = table.getElementsByTagName("tbody")[0];
    const searchInput = document.getElementById("searchInput");
    const rowsPerPageSelect = document.getElementById("rowsPerPage");
    const pagination = document.getElementById("paginationControls");
    let allRows = Array.from(tbody.querySelectorAll("tr"));
    let filteredRows = [...allRows];
    let currentPage = 1;
    let rowsPerPage = parseInt(rowsPerPageSelect.value);
    let sortDirection = true;
    let currentSortColumn = null;

    // Render tabel dengan pagination
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

    // Pagination
    window.changePage = (page) => {
        currentPage = page;
        renderTable();
    };

    // Filter pencarian
    function filterTable() {
        const term = searchInput.value.toLowerCase();
        filteredRows = allRows.filter(row => {
            return Array.from(row.querySelectorAll("td"))
                .slice(0, -1)
                .some(cell => cell.textContent.toLowerCase().includes(term));
        });
        currentPage = 1;
        renderTable();
    }

    searchInput.addEventListener("keyup", filterTable);
    rowsPerPageSelect.addEventListener("change", () => {
        rowsPerPage = parseInt(rowsPerPageSelect.value);
        currentPage = 1;
        renderTable();
    });

    renderTable();

    // ========== FORM FUNCTIONS ==========
    // Set nilai_residu dan umur_ekonomis berdasarkan kategori
    const kategoriAssetSelect = document.getElementById("kategori_aset");
    const kategoriDefaults = {
        'mesin': { nilai_residu_percent: 5, umur_ekonomis: 10 },
        'peralatan_it': { nilai_residu_percent: 5, umur_ekonomis: 5 }
    };

    function updateDefaultsByCategory() {
        const kategori = kategoriAssetSelect.value;
        const defaults = kategoriDefaults[kategori];

        if (defaults) {
            const hargaPerolehan = parseFloat(document.getElementById("harga_perolehan").value) || 0;
            const nilaiResidu = (hargaPerolehan * defaults.nilai_residu_percent) / 100;

            document.getElementById("nilai_residu").value = nilaiResidu;
            document.getElementById("umur_ekonomis").value = defaults.umur_ekonomis;
        }
    }

    kategoriAssetSelect.addEventListener("change", updateDefaultsByCategory);
    document.getElementById("harga_perolehan").addEventListener("blur", updateDefaultsByCategory);

    // Form submit handler - pastikan perhitungan selesai sebelum submit
    assetForm.addEventListener("submit", (e) => {
        updateDefaultsByCategory();
    });

    function resetForm() {
        document.getElementById("formTitle").textContent = "Tambah Aset Tetap";
        document.getElementById("form_method").value = "POST";
        assetForm.action = "{{ route('fixed_assets.store') }}";
        assetForm.reset();
        document.getElementById("kode_aset").value = "{{ $kodeBahan }}";
        document.getElementById("metode_penyusutan").value = "garis_lurus";
    }

    window.editAsset = (id, kodeAset, namaAset, kategoriAset, tglPerolehan, hargaPerolehan, nilaiResidu, umurEkonomi, metodePenyusutan, catatan) => {
        document.getElementById("formTitle").textContent = "Edit Aset Tetap";
        document.getElementById("form_method").value = "PATCH";
        assetForm.action = `/fixed-assets/${id}`;

        document.getElementById("asset_id").value = id;
        document.getElementById("kode_aset").value = kodeAset;
        document.getElementById("nama_aset").value = namaAset;
        document.getElementById("kategori_aset").value = kategoriAset;
        document.getElementById("tanggal_perolehan").value = tglPerolehan;
        document.getElementById("harga_perolehan").value = hargaPerolehan;
        document.getElementById("nilai_residu").value = nilaiResidu;
        document.getElementById("umur_ekonomis").value = umurEkonomi;
        document.getElementById("metode_penyusutan").value = metodePenyusutan;
        document.getElementById("catatan").value = catatan;

        assetModal.classList.remove("hidden");
        assetModal.classList.add("flex");
    };

    window.showDetail = async (id) => {
        try {
            const response = await fetch(`/fixed-assets/${id}/detail`);
            const asset = await response.json();

            document.getElementById("detailKodeAset").textContent = asset.kode_aset;
            document.getElementById("detailNamaAset").textContent = asset.nama_aset;
            document.getElementById("detailUmurEkonomi").textContent = asset.umur_ekonomis;
            document.getElementById("detailMetodePenyusutan").textContent = asset.metode_penyusutan === 'garis_lurus' ? 'Garis Lurus' : asset.metode_penyusutan;

            const formatCurrency = (value) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(value);

            const tbody = document.getElementById('detailScheduleBody');
            tbody.innerHTML = '';

            const schedule = asset.schedule || [];
                if (schedule.length === 0) {
                tbody.innerHTML = `<tr><td class="px-4 py-2 border border-slate-200 text-center" colspan="7">Tidak ada data penyusutan</td></tr>`;
            } else {
                schedule.forEach(row => {
                    const tr = document.createElement('tr');
                    tr.className = 'border-b hover:bg-slate-50';
                    tr.innerHTML = `
                        <td class="px-4 py-2 border border-slate-200 text-center">${row.no}</td>
                        <td class="px-4 py-2 border border-slate-200">${row.tahun}</td>
                        <td class="px-4 py-2 border border-slate-200">${formatCurrency(row.harga_perolehan)}</td>
                        <td class="px-4 py-2 border border-slate-200">${formatCurrency(row.nilai_residu)}</td>
                        <td class="px-4 py-2 border border-slate-200">${formatCurrency(row.penyusutan)}</td>
                        <td class="px-4 py-2 border border-slate-200">${formatCurrency(row.akumulasi)}</td>
                        <td class="px-4 py-2 border border-slate-200">${formatCurrency(row.nilai_buku)}</td>
                    `;
                    tbody.appendChild(tr);
                });
            }

            detailModal.classList.remove("hidden");
            detailModal.classList.add("flex");
        } catch (error) {
            console.error('Error:', error);
        }
    };

    window.openSaleModal = (id, namaAset) => {
        document.getElementById("sale_asset_id").value = id;
        document.getElementById("saleAssetName").textContent = namaAset;
        saleForm.action = `/fixed-assets/${id}/sale`;

        document.getElementById("tanggal_jual").value = "";
        document.getElementById("harga_jual").value = "";
        document.getElementById("pembeli").value = "";

        saleModal.classList.remove("hidden");
        saleModal.classList.add("flex");
    };
});

// ========== SORTING ==========
function sortTable(columnIndex) {
    const table = document.getElementById("sortableTable");
    const tbody = table.getElementsByTagName("tbody")[0];
    const rows = Array.from(tbody.querySelectorAll("tr")).filter(row => row.textContent.trim() !== "");

    if (rows.length === 0) return;

    let sortDirection = true;
    const currentSortColumn = table.getAttribute("data-sort-column");
    if (currentSortColumn === columnIndex.toString()) {
        sortDirection = table.getAttribute("data-sort-direction") !== "true";
    }

    rows.sort((a, b) => {
        const cellA = a.querySelectorAll("td")[columnIndex]?.textContent.trim() || "";
        const cellB = b.querySelectorAll("td")[columnIndex]?.textContent.trim() || "";

        let valA = isNaN(cellA) ? cellA.toLowerCase() : parseFloat(cellA);
        let valB = isNaN(cellB) ? cellB.toLowerCase() : parseFloat(cellB);

        if (sortDirection) {
            return valA > valB ? 1 : -1;
        } else {
            return valA < valB ? 1 : -1;
        }
    });

    tbody.innerHTML = "";
    rows.forEach(row => tbody.appendChild(row));

    table.setAttribute("data-sort-column", columnIndex);
    table.setAttribute("data-sort-direction", sortDirection);
}
</script>
@endsection
