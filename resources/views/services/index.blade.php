@extends('layout.main')
@section('title', 'Data Barang Jasa')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
<!-- Card Utama -->
<div class="bg-white shadow-lg rounded-2xl p-6 border border-slate-200">
  <div class="flex justify-between items-center mb-6">
    <h6 class="text-2xl font-bold text-slate-700">Data Barang Jasa</h6>
  </div>

  {{-- Button: Tambah Jasa (opens modal) --}}
  <div class="mb-6 flex items-center justify-between">
    <div>
      <button id="openAddBtn" type="button"
        class="bg-slate-700 text-white px-4 py-2 rounded-lg hover:bg-slate-800 transition">
        + Tambah Barang Jasa
      </button>
    </div>
  </div>

  {{-- Modal: Add / Edit Jasa --}}
  <div id="serviceModal" style="z-index:9999" class="hidden fixed inset-0 items-center justify-center">
    <!-- Overlay -->
    <div id="modalOverlay" style="z-index:9998" class="absolute inset-0 bg-black opacity-50"></div>

    <!-- Modal box -->
    <div style="z-index:9999" class="relative bg-white rounded-2xl shadow-lg w-full max-w-md sm:max-w-lg p-6 mx-4">
      
      <!-- Tombol X di pojok kanan atas -->
      <button id="closeModalX"
        type="button"
        class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-2xl font-bold focus:outline-none">
        &times;
      </button>

      <h2 id="formTitle" class="text-xl font-semibold text-slate-700 mb-4">Tambah Data Jasa</h2>

      <form id="serviceForm" action="{{ route('services.store') }}" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="_method" id="form_method" value="POST">
        <input type="hidden" name="id" id="service_id">

        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-1">Kode Jasa</label>
            <input type="text" name="kode_jasa" id="kode_jasa"
                  class="w-full border rounded-lg px-3 py-2 bg-gray-100"
                  value="{{ $newCode }}" readonly>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Nama Barang</label>
            <input type="text" name="nama_jasa" id="nama_jasa" class="w-full border rounded-lg px-3 py-2" required>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Kategori Jasa</label>
            <select name="kategori_jasa" id="kategori_jasa"
                    class="w-full border rounded-lg px-3 py-2"
                    required>
                <option value="" disabled selected>Pilih kategori jasa</option>
                <option value="Cutting">Cutting</option>
                <option value="Sablon">Sablon</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Harga</label>
            <input type="number" name="harga" id="harga" class="w-full border rounded-lg px-3 py-2" step="0.01" required>
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
                  placeholder="Cari jasa..." 
                  class="border rounded px-3 py-1 w-48 focus:ring focus:ring-blue-200" />
      </div>
  </div>

  {{-- Tabel Data --}}
  <div class="border border-slate-200 shadow rounded-2xl bg-white">
    <div class="overflow-x-auto">
      <table id="sortableTable" class="items-center w-full text-slate-600 border-collapse">
        <thead class="bg-slate-100 text-xs uppercase font-semibold">
          <tr>
            <th class="px-6 py-3 border border-slate-200 text-left cursor-pointer" onclick="sortTable(0)">Kode Jasa</th>
            <th class="px-6 py-3 border border-slate-200 text-left cursor-pointer" onclick="sortTable(1)">Nama Barang</th>
            <th class="px-6 py-3 border border-slate-200 text-left cursor-pointer" onclick="sortTable(2)">Kategori Jasa</th>
            <th class="px-6 py-3 border border-slate-200 text-center cursor-pointer" onclick="sortTable(3)">Harga</th>
            <th class="px-6 py-3 border border-slate-200 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($services as $index => $service)
          <tr class="border-b hover:bg-slate-50">
            <td class="px-6 py-3 border border-slate-200">{{ $service->kode_jasa }}</td>
            <td class="px-6 py-3 border border-slate-200">{{ $service->nama_jasa }}</td>
            <td class="px-6 py-3 border border-slate-200">{{ $service->kategori_jasa }}</td>
            <td class="px-6 py-3 border border-slate-200 text-center">Rp {{ number_format($service->harga, 0, ',', '.') }}</td>
            <td class="px-6 py-3 border border-slate-200 text-center">
              <button type="button"
                class="text-blue-500 hover:text-blue-700"
                onclick="editService('{{ $service->kode_jasa }}', '{{ $service->nama_jasa }}', '{{ $service->kategori_jasa }}', '{{ $service->harga }}')">
                Edit
              </button>
              |
              <form action="{{ route('services.destroy', $service->kode_jasa) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Yakin hapus data ini?')">Hapus</button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center py-4 text-slate-500">Belum ada data jasa</td>
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

  // 🔹 Render tabel dengan pagination
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

  // 🔹 Pagination
  window.changePage = (page) => {
    currentPage = page;
    renderTable();
  };

  // 🔹 Filter pencarian
  function filterTable() {
    const term = searchInput.value.toLowerCase();
    filteredRows = allRows.filter(row => row.textContent.toLowerCase().includes(term));
    currentPage = 1;
    renderTable();
  }

  // 🔹 Sorting kolom
  function sortTable(columnIndex) {
    const isNumeric = columnIndex === 3; // kolom harga
    sortDirection = (currentSortColumn === columnIndex) ? !sortDirection : true;
    currentSortColumn = columnIndex;

    filteredRows.sort((a, b) => {
      const aText = a.cells[columnIndex].innerText.trim().replace(/Rp\s?/g, '').replace(/\./g, '');
      const bText = b.cells[columnIndex].innerText.trim().replace(/Rp\s?/g, '').replace(/\./g, '');
      if (isNumeric) {
        return sortDirection ? aText - bText : bText - aText;
      } else {
        return sortDirection
          ? aText.localeCompare(bText, 'id', { numeric: true })
          : bText.localeCompare(aText, 'id', { numeric: true });
      }
    });

    currentPage = 1;
    renderTable();
  }

  // 🔹 Tambahkan event listener ke header kolom
  table.querySelectorAll("th").forEach((th, i) => {
    if (i < 5) { // hanya kolom data, bukan aksi
      th.style.cursor = "pointer";
      th.addEventListener("click", () => sortTable(i));
    }
  });

  // 🔹 Event listener
  searchInput.addEventListener("input", filterTable);
  rowsPerPageSelect.addEventListener("change", () => {
    rowsPerPage = parseInt(rowsPerPageSelect.value);
    currentPage = 1;
    renderTable();
  });

  // 🔹 Render awal
  filterTable();

  /** =============================
   *  MODAL FORM TAMBAH & EDIT JASA
   * ============================== */
  const modal = document.getElementById('serviceModal');
  const overlay = document.getElementById('modalOverlay');
  const openAddBtn = document.getElementById('openAddBtn');
  const cancelModal = document.getElementById('cancelModal');
  const closeModalX = document.getElementById('closeModalX');
  const serviceForm = document.getElementById('serviceForm');
  const formMethod = document.getElementById('form_method');
  const formTitle = document.getElementById('formTitle');
  const baseUrl = @json(url('services'));

  function showModal() {
    modal.classList.remove('hidden');
    modal.classList.add('flex');
  }

  function closeModal() {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }

  // Tombol tambah
  openAddBtn.addEventListener('click', () => {
    serviceForm.reset();
    serviceForm.action = '{{ route('services.store') }}';
    formMethod.value = 'POST';
    formTitle.textContent = 'Tambah Jasa';
    document.getElementById('service_id').value = '';
    document.getElementById('kode_jasa').value = @json($newCode);
    document.getElementById('kode_jasa').readOnly = true;
    showModal();
  });

  // Tutup modal
  cancelModal.addEventListener('click', closeModal);
  overlay.addEventListener('click', closeModal);
  closeModalX.addEventListener('click', closeModal);

  // Edit jasa
  window.editService = (kode, nama, kategori, harga) => {
    formTitle.textContent = 'Edit Jasa';
    serviceForm.action = baseUrl + '/' + kode;
    formMethod.value = 'PUT';
    document.getElementById('kode_jasa').value = kode;
    document.getElementById('kode_jasa').readOnly = true;
    document.getElementById('nama_jasa').value = nama;
    document.getElementById('kategori_jasa').value = kategori;
    document.getElementById('harga').value = harga;
    showModal();
  };

  /** =============================
   *  RENDER AWAL
   * ============================== */
  renderTable();
});
</script>

@endsection
