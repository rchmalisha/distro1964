@extends('layout.main')
@section('title', 'Data Jasa')

@section('content')
<div class="w-full px-6 py-6 mx-auto">

  <div class="flex justify-between items-center mb-4">
    <h6 class="text-lg font-bold text-slate-700">Data Jasa</h6>
  </div>

  {{-- Button: Tambah Jasa (opens modal) --}}
  <div class="mb-6 flex items-center justify-between">
    <div>
      <button id="openAddBtn" type="button"
        class="bg-slate-700 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
        + Tambah Jasa
      </button>
    </div>
  </div>

{{-- Modal: Add / Edit Jasa --}}
<div id="serviceModal" style="z-index:9999" class="hidden fixed inset-0 items-center justify-center">
  <!-- Overlay -->
  <div id="modalOverlay" style="z-index:9998" class="absolute inset-0 bg-black opacity-50"></div>

  <!-- Modal box -->
  <div style="z-index:9999" class="relative bg-white rounded-lg shadow-lg w-full max-w-2xl mx-auto p-6">
    
    <!-- Tombol X di pojok kanan atas -->
    <button id="closeModalX"
      type="button"
      class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-2xl font-bold focus:outline-none">
      &times;
    </button>

    <h2 id="formTitle" class="text-lg font-semibold text-slate-700 mb-4">Tambah Jasa</h2>

    <form id="serviceForm" action="{{ route('services.store') }}" method="POST" class="space-y-4">
      @csrf
      <input type="hidden" name="_method" id="form_method" value="POST">
      <input type="hidden" name="id" id="service_id">

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">Kode Jasa</label>
          <input type="text" name="kode_jasa" id="kode_jasa"
                class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300"
                value="{{ $newCode }}" readonly>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Nama Jasa</label>
          <input type="text" name="nama_jasa" id="nama_jasa" class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" required>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Kategori Jasa</label>
          <select name="kategori_jasa" id="kategori_jasa"
                  class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300"
                  required>
              <option value="" disabled selected>Pilih kategori jasa</option>
              <option value="Cutting">Cutting</option>
              <option value="Sablon">Sablon</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Harga</label>
          <input type="number" name="harga" id="harga" class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300" step="0.01" required>
        </div>
      </div>

      <div class="pt-4 flex justify-end space-x-3">
        <button type="button" id="cancelModal" class="bg-slate-700 text-white px-4 py-2 rounded-lg hover:bg-gray-400 transition">
          Batal
        </button>
        <button type="submit" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
          Simpan
        </button>
      </div>
    </form>
  </div>
</div>


  {{-- Tabel Data --}}
  <div class="border border-slate-200 shadow rounded-2xl bg-white">
    <div class="overflow-x-auto">
      <table class="items-center w-full text-slate-600 border-collapse">
        <thead class="bg-slate-100 text-xs uppercase font-semibold">
          <tr>
            <th class="px-6 py-3 text-left">No</th>
            <th class="px-6 py-3 text-left">Kode Jasa</th>
            <th class="px-6 py-3 text-left">Nama Jasa</th>
            <th class="px-6 py-3 text-left">Kategori</th>
            <th class="px-6 py-3 text-center">Harga</th>
            <th class="px-6 py-3 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($services as $index => $service)
          <tr class="border-b hover:bg-slate-50">
            <td class="px-6 py-3">{{ $index + 1 }}</td>
            <td class="px-6 py-3">{{ $service->kode_jasa }}</td>
            <td class="px-6 py-3">{{ $service->nama_jasa }}</td>
            <td class="px-6 py-3">{{ $service->kategori_jasa }}</td>
            <td class="px-6 py-3 text-center">Rp {{ number_format($service->harga, 0, ',', '.') }}</td>
            <td class="px-6 py-3 text-center">
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
  </div>
</div>

{{-- Script --}}
<script>
  const modal = document.getElementById('serviceModal');
  const overlay = document.getElementById('modalOverlay');
  const openAddBtn = document.getElementById('openAddBtn');
  const cancelModal = document.getElementById('cancelModal');
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

  // Open modal for adding
  openAddBtn.addEventListener('click', () => {
    serviceForm.reset();
    serviceForm.action = '{{ route('services.store') }}';
    formMethod.value = 'POST';
    formTitle.textContent = 'Tambah Jasa';
    document.getElementById('service_id').value = '';
    document.getElementById('kode_jasa').value = @json($newCode); // 🔹 isi otomatis
    document.getElementById('kode_jasa').readOnly = true; // 🔹 tetap readonly
    showModal();
  });

  // Close handlers
  cancelModal.addEventListener('click', closeModal);
  overlay.addEventListener('click', closeModal);
  const closeModalX = document.getElementById('closeModalX');
  closeModalX.addEventListener('click', closeModal);

  // Called from table edit button
  function editService(kode, nama, kategori, harga) {
    formTitle.textContent = 'Edit Jasa';
    serviceForm.action = baseUrl + '/' + kode;
    formMethod.value = 'PUT';
    document.getElementById('kode_jasa').value = kode;
    document.getElementById('kode_jasa').readOnly = true; // 🔹 readonly juga saat edit
    document.getElementById('nama_jasa').value = nama;
    document.getElementById('kategori_jasa').value = kategori;
    document.getElementById('harga').value = harga;
    showModal();
  }

  // Expose editService to global scope so inline onclick handlers can call it
  window.editService = editService;
</script>
{{-- ✅ Notifikasi SweetAlert2 --}}
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
