@extends('layout.main')
@section('title', 'Pemesanan Produk dan Jasa')

@section('content')
<div class="container mx-auto p-6 space-y-6">
    <form action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- CARD 1: Informasi Pelanggan --}}
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Informasi Pelanggan</h2>

            {{-- <form id="orderForm" method="POST" action="{{ route('orders.store') }}">
                @csrf --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- <!-- Kode Pesanan -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Kode Pesanan</label>
                        <input type="text" name="kode_pesan" id="kode_pesan"
                            class="w-full border rounded-lg px-3 py-2 bg-gray-100"
                            value="{{ $newCode }}" readonly>
                    </div> --}}

                    <!-- Nama Pelanggan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Nama Pelanggan</label>
                        <input type="text" name="nama_cus" 
                            class="w-full border rounded-lg p-2 mt-1" 
                            required>
                    </div>

                    <!-- No. Telepon -->
                    <div>
                    <label for="no_telp" class="block text-sm font-medium text-gray-600">
                        No. Telepon
                    </label>
                    <input
                        id="no_telp"
                        type="tel"
                        name="no_telp"
                        inputmode="numeric"
                        pattern="^[0-9]{10,13}$"
                        class="w-full border rounded-lg p-2 mt-1"
                        required
                    >
                    </div>

                    <!-- Tanggal Pesan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Tanggal Pesan</label>
                        <input type="date" name="tgl_pesan" 
                            class="w-full border rounded-lg p-2 mt-1" 
                            value="{{ date('Y-m-d') }}" 
                            required>
                    </div>

                    <!-- Tanggal Ambil -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Tanggal Ambil</label>
                        <input type="date" name="tgl_ambil" 
                            class="w-full border rounded-lg p-2 mt-1" 
                            required>
                    </div>
                </div>
            {{-- </form> --}}
        </div>

        {{-- CARD 2: Rincian Pesanan --}}
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Rincian Pesanan</h2>

            <div class="flex justify-between items-center mb-4">
                <button type="button" onclick="addRow()" 
                    class="bg-slate-700 hover:bg-slate-900 text-white px-4 py-2 text-sm rounded-lg shadow transition-all">
                    + Tambah Item
                </button>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full text-sm text-gray-700" id="detailTable">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-3 py-2 text-center w-12">No</th>
                            <th class="px-3 py-2">Nama Jasa</th>
                            <th class="px-3 py-2">Nama Bahan</th>
                            <th class="px-3 py-2">Ukuran Bahan</th>
                            <th class="px-3 py-2 text-center">Jumlah</th>
                            <th class="px-3 py-2 text-center">Harga Satuan</th>
                            <th class="px-3 py-2 text-center">Subtotal</th>
                            <th class="px-3 py-2 text-center w-10">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot class="bg-gray-50 font-semibold">
                        <tr>
                            <td colspan="6" class="text-right px-3 py-2 border">Total Harga:</td>
                            <td class="px-3 py-2 border text-right">
                                <span id="totalHarga">Rp 0</span>
                                <input type="hidden" name="total_harga" id="totalHargaInput" value="0">
                            </td>
                            <td class="border"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- CARD 3: Total dan File --}}
        <div class="bg-white rounded-xl shadow p-4 mt-4">
            <h2 class="text-lg font-semibold mb-3">Ringkasan Order</h2>

            <div class="grid grid-cols-2 gap-6">
                {{-- Kolom kiri: Upload & Keterangan --}}
                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Upload File (Opsional)</label>

                        <div class="flex items-center gap-3">
                            <!-- Tombol upload -->
                            <label for="upload_file" 
                                class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg transition">
                                <i class="fa-solid fa-upload mr-2"></i> Pilih File
                            </label>

                            <!-- Nama file yang dipilih akan muncul di sini -->
                            <span id="file-name" class="text-gray-600 text-sm italic">Belum ada file dipilih</span>
                            
                            <!-- Tombol hapus file (disembunyikan dulu) -->
                            <button type="button" id="remove-file-btn" 
                            class="hidden text-red-500 hover:text-red-700 text-sm"
                            onclick="removeFile()">
                            <i class="fa-solid fa-trash mr-1"></i> Hapus
                            </button>
                        </div>

                        <!-- Input file disembunyikan -->
                        <input type="file" id="upload_file" name="upload_file" class="hidden" 
                            onchange="showFileName(this)">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Keterangan (Opsional)</label>
                        <textarea name="keterangan" class="w-full border rounded-lg px-3 py-2" rows="3"></textarea>
                    </div>
                </div>

                {{-- Kolom kanan: Ringkasan Harga --}}
                <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-700 font-medium">Total Harga:</span>
                    <span id="totalSubtotalDisplay" class="font-semibold text-gray-900">Rp 0</span>
                    <input type="hidden" name="total_subtotal" id="totalSubtotalInput" value="0">
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-gray-700 font-medium">Biaya Jasa:</span>
                    <div class="flex items-center gap-2">
                    <input type="text" id="biayaJasaDisplay"
                            class="w-40 text-right border-gray-300 rounded-lg p-2 text-sm"
                            oninput="formatBiayaJasa(this)">
                    <input type="hidden" name="biaya_jasa" id="biayaJasaInput" value="0">
                    </div>
                </div>

                <hr>

                <div class="flex justify-between items-center text-lg font-semibold">
                    <span class="text-gray-800">Total Akhir:</span>
                    <span id="totalAkhirDisplay" class="text-green-700">Rp 0</span>
                    <input type="hidden" name="total_akhir" id="totalAkhirInput" value="0">
                </div>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                    Simpan Order
                </button>
            </div>
        </div>
    </form>
</div>

<script>
let rowIndex = 0;

function formatRupiah(value) {
    if (!value) return '';
    const number = parseFloat(value.toString().replace(/[^\d]/g, '')) || 0;
    return 'Rp ' + number.toLocaleString('id-ID');
}

function parseRupiah(value) {
    return parseFloat(value.toString().replace(/[^\d]/g, '')) || 0;
}

function addRow() {
    const tableBody = document.getElementById('detailTable').querySelector('tbody');
    const row = document.createElement('tr');
    row.classList.add('border-b', 'hover:bg-gray-50');
    row.innerHTML = `
        <td class="text-center px-2 py-2">${++rowIndex}</td>
        <td class="px-2 py-2">
            <select name="detail_orders[${rowIndex}][kode_jasa]" class="w-full border-gray-300 rounded-lg p-2 text-sm focus:ring focus:ring-blue-300">
                <option value="">-- Pilih --</option>
                @foreach($services as $s)
                    <option value="{{ $s->kode_jasa }}">{{ $s->nama_jasa }}</option>
                @endforeach
            </select>
        </td>
        <td class="px-2 py-2">
            <select name="detail_orders[${rowIndex}][kode_bahan]" 
                    class="select-bahan w-full border-gray-300 rounded-lg p-2 text-sm focus:ring focus:ring-blue-300">
                <option value="">-- Pilih --</option>
                @foreach($materials as $m)
                    <option value="{{ $m->kode_bahan }}" data-harga="{{ $m->harga_bahan }}">
                        {{ $m->nama_bahan }}
                    </option>
                @endforeach
            </select>
        </td>
        <td class="px-2 py-2">
            <input type="text" name="detail_orders[${rowIndex}][ukuran_bahan]" class="w-full border-gray-300 rounded-lg p-2 text-sm text-center">
        </td>
        <td class="px-2 py-2 text-center">
            <input type="number" name="detail_orders[${rowIndex}][jumlah_bahan]" class="w-24 border-gray-300 rounded-lg p-2 text-sm text-center" oninput="updateSubtotal(this)">
        </td>
        <td class="px-2 py-2 text-center">
            <input type="text" name="detail_orders[${rowIndex}][harga_satuan_display]" 
                class="harga-satuan-display w-28 border-gray-300 rounded-lg p-2 text-sm text-center">
            <input type="hidden" name="detail_orders[${rowIndex}][harga_satuan]" 
                class="harga-satuan-hidden" value="0">
        </td>
        <td class="px-2 py-2 text-right">
            <input type="text" name="detail_orders[${rowIndex}][subtotal_display]" class="w-28 border-gray-300 rounded-lg p-2 text-sm text-right subtotal" readonly>
            <input type="hidden" name="detail_orders[${rowIndex}][subtotal]" value="0">
        </td>
        <td class="px-2 py-2 text-center">
            <button type="button" onclick="removeRow(this)" class="p-2 text-red-500 hover:text-red-700 transition" title="Hapus item">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    tableBody.appendChild(row);
}

document.addEventListener('change', function (e) {
    // Ketika bahan dipilih
    if (e.target.classList.contains('select-bahan')) {
        const harga = parseFloat(e.target.selectedOptions[0].getAttribute('data-harga')) || 0;
        const row = e.target.closest('tr');

        const hargaDisplay = row.querySelector('.harga-satuan-display');
        const hargaHidden = row.querySelector('.harga-satuan-hidden');

        hargaDisplay.value = formatRupiah(harga);
        hargaHidden.value = harga;

        updateSubtotal(row);
    }
});

document.addEventListener('input', function(e) {
    // Ketika jumlah diubah
    if (e.target.classList.contains('jumlah-bahan')) {
        const row = e.target.closest('tr');
        updateSubtotal(row);
    }
});

function removeRow(button) {
    button.closest('tr').remove();
    const rows = document.querySelectorAll('#detailTable tbody tr');
    rowIndex = 0;
    rows.forEach((r) => r.cells[0].textContent = ++rowIndex);
    hitungTotalHarga();
}

function formatHarga(input) {
    const value = input.value;
    input.value = formatRupiah(value);
    const hiddenInput = input.nextElementSibling;
    hiddenInput.value = parseRupiah(value);
    updateSubtotal(input);
}

function updateSubtotal(el) {
    const row = el.closest('tr');
    const qty = parseFloat(row.querySelector('[name*="[jumlah_bahan]"]').value) || 0;
    const price = parseFloat(row.querySelector('[name*="[harga_satuan]"]').value) || 0;
    const subtotalHidden = row.querySelector('[name*="[subtotal]"]');
    const subtotalDisplay = row.querySelector('[name*="[subtotal_display]"]');

    const total = qty * price;
    subtotalHidden.value = total;
    subtotalDisplay.value = formatRupiah(total);

    // 🔹 setiap kali subtotal berubah, total keseluruhan ikut diupdate
    hitungTotalHarga();
}

function hitungTotalHarga() {
    let total = 0;
    document.querySelectorAll('input[name*="[subtotal]"]').forEach(input => {
        total += parseFloat(input.value) || 0;
    });

    // update bagian footer tabel
    document.getElementById('totalHarga').textContent = formatRupiah(total);
    document.getElementById('totalHargaInput').value = total;
    
    // update bagian ringkasan kanan
    document.getElementById('totalSubtotalDisplay').textContent = formatRupiah(total);
    document.getElementById('totalSubtotalInput').value = total;

    updateTotalAkhir();
}

// --- Biaya Jasa (format & hitung total akhir) ---
function formatBiayaJasa(input) {
  input.value = formatRupiah(input.value);
  const parsed = parseRupiah(input.value);
  document.getElementById('biayaJasaInput').value = parsed;
  updateTotalAkhir();
}

// --- Total Akhir = Total Harga (dari tabel) + Biaya Jasa ---
function updateTotalAkhir() {
  const totalHarga = parseFloat(document.getElementById('totalHargaInput').value) || 0;
  const biayaJasa = parseFloat(document.getElementById('biayaJasaInput').value) || 0;
  const totalAkhir = totalHarga + biayaJasa;

  document.getElementById('totalAkhirInput').value = totalAkhir;
  document.getElementById('totalAkhirDisplay').textContent = formatRupiah(totalAkhir);
}

  // Hanya izinkan angka (0–9)
  const telInput = document.getElementById('no_telp');
  telInput.addEventListener('input', function () {
    // Hapus semua karakter non-angka
    this.value = this.value.replace(/[^0-9]/g, '');
    // Batasi maksimal 13 digit
    if (this.value.length > 13) {
      this.value = this.value.slice(0, 13);
    }
  });

//Upload File Name Display
function showFileName(input) {
  const fileNameSpan = document.getElementById('file-name');
  const removeBtn = document.getElementById('remove-file-btn');

  if (input.files.length > 0) {
    fileNameSpan.textContent = input.files[0].name;
    removeBtn.classList.remove('hidden');
  } else {
    fileNameSpan.textContent = 'Belum ada file dipilih';
    removeBtn.classList.add('hidden');
  }
}

function removeFile() {
  const input = document.getElementById('upload_file');
  const fileNameSpan = document.getElementById('file-name');
  const removeBtn = document.getElementById('remove-file-btn');

  // Reset input file
  input.value = '';
  fileNameSpan.textContent = 'Belum ada file dipilih';
  removeBtn.classList.add('hidden');
}
</script>
@endsection
