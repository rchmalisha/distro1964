@extends('layout.main')
@section('title', 'Data Bahan')
@section('content')
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold">Data Bahan</h2>
        <button
            onclick="(function(){const m=document.getElementById('modalTambah'); m.classList.remove('hidden'); m.classList.add('flex');})()"
            class="px-4 py-2 bg-slate-700 text-white rounded-lg transition">
            + Tambah Bahan
        </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-200 rounded-lg">
                <thead class="text-black bg-gray-100">
                    <tr>
                        <th class="border border-gray-500 py-3 px-6 text-center text-xl font-bold text-gray-700">Kode</th>
                        <th class="border border-gray-500 py-3 px-6 text-center text-xl font-bold text-gray-700">Nama</th>
                        <th class="border border-gray-500 py-3 px-6 text-center text-xl font-bold text-gray-700">Harga Bahan</th>
                        <th class="border border-gray-500 py-3 px-6 text-center text-xl font-bold text-gray-700">Kategori</th>
                        <th class="border border-gray-500 py-3 px-6 text-center text-xl font-bold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $b)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-500 py-4 px-6 text-gray-600 text-center">{{ $b->kode_bahan }}</td>
                            <td class="border border-gray-500 py-4 px-6 text-gray-600">{{ $b->nama_bahan }}</td>
                            <td class="border border-gray-500 py-4 px-6 text-gray-600">Rp{{ number_format($b->harga_bahan, 0, ',', '.') }}</td>
                            <td class="border border-gray-500 py-4 px-6 text-gray-600 capitalize">{{ $b->kategori_bahan }}</td>
                            <td class="border border-gray-500 py-4 px-6 text-center border-b">
                                <button
                                    type="button"
                                    onclick="openEditModal('{{ $b->kode_bahan }}', '{{ $b->nama_bahan }}', '{{ $b->kategori_bahan }}', '{{ $b->harga_bahan }}')"
                                    class="px-4 py-2 bg-blue-600 text-black rounded-full hover:bg-blue-700">
                                    Edit
                                </button> |
                                <form action="{{ route('materials.destroy', $b->kode_bahan) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus data ini?')"
                                    class="text-red-600 hover:underline">
                                    Hapus
                                </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 px-6 text-gray-500">
                                Belum ada data bahan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('modals')
    <div id="modalTambah" class="hidden fixed inset-0 z-[9999] items-center justify-center bg-black/50" onclick="(function(){const m=document.getElementById('modalTambah'); m.classList.add('hidden'); m.classList.remove('flex');})()">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md sm:max-w-lg p-6 mx-4 relative" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between mb-4">
            <h3 class="text-xl font-semibold">Tambah Data Bahan</h3>
                    <button type="button" aria-label="Close" class="text-gray-500 hover:text-gray-700" onclick="(function(){const m=document.getElementById('modalTambah'); m.classList.add('hidden'); m.classList.remove('flex');})()">&times;</button>
        </div>
        <form action="{{ route('materials.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block font-medium">Kode Bahan (otomatis)</label>
                <input type="text" name="kode_bahan" class="w-full border border-gray-300 rounded-lg bg-gray-100 px-3 py-2" value="{{ $kodeBahan }}" readonly>
            </div>

            <div>
                <label class="block font-medium">Nama Bahan</label>
                <input type="text" name="nama_bahan" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
            </div>

            <div>
                <label class="block font-medium">Harga Bahan</label>
                <input type="number" name="harga_bahan" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
            </div>

            <div>
                <label class="block font-medium">Kategori</label>
                <select name="kategori_bahan" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="dtf">DTF</option>
                    <option value="polyflex">Polyflex</option>
                </select>
            </div>

            <div class="pt-2 flex justify-end space-x-2">
                <div>
                    <button type="button" class="px-4 py-2 bg-slate-700 text-white rounded-lg transition" onclick="(function(){const m=document.getElementById('modalTambah'); m.classList.add('hidden'); m.classList.remove('flex');})()">Batal</button>
                </div>
                <button type="submit" class="px-4 py-2 bg-slate-700 text-white rounded-lg transition">Simpan</button>
            </div>
        </form>
    </div>
    </div>

    <div id="modalEdit" class="hidden fixed inset-0 z-[9999] items-center justify-center bg-black/50" onclick="(function(){const m=document.getElementById('modalEdit'); m.classList.add('hidden'); m.classList.remove('flex');})()">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md sm:max-w-lg p-6 mx-4 relative" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between mb-4">
            <h3 class="text-xl font-semibold">Edit Data Bahan</h3>
            <button type="button" aria-label="Close" class="text-gray-500 hover:text-gray-700" onclick="(function(){const m=document.getElementById('modalEdit'); m.classList.add('hidden'); m.classList.remove('flex');})()">&times;</button>
        </div>
        <form id="editForm" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-medium">Kode Bahan</label>
            <input type="text" name="kode_bahan" id="editKode" class="w-full border border-gray-300 rounded-lg bg-gray-100 px-3 py-2" readonly>
        </div>
        <div>
            <label class="block font-medium">Nama Bahan</label>
            <input type="text" name="nama_bahan" id="editNama" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
        </div>

        <div>
            <label class="block font-medium">Harga Bahan (Rp)</label>
            <input type="number" name="harga_bahan" id="editHarga" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
        </div>

        <div>
            <label class="block font-medium">Kategori</label>
            <select name="kategori_bahan" id="editKategori" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
            <option value="dtf">DTF</option>
            <option value="polyflex">Polyflex</option>
            </select>
        </div>

        <div class="pt-2 flex justify-end space-x-2">
            <div>
                <button type="button" class="px-4 py-2 bg-slate-700 text-white rounded-lg transition" onclick="(function(){const m=document.getElementById('modalTambah'); m.classList.add('hidden'); m.classList.remove('flex');})()">Batal</button>                </div>
                <button type="submit" class="px-4 py-2 bg-slate-700 text-white rounded-lg transition">Simpan</button>
            </div>
        </form>
    </div>
    </div>
    <script>
        function openEditModal(kode, nama, kategori, harga) {
            document.getElementById('editNama').value = nama;
            document.getElementById('editHarga').value = harga;
            document.getElementById('editKategori').value = kategori;
            document.getElementById('editForm').action = '/materials/' + kode;
            const m = document.getElementById('modalEdit'); m.classList.remove('hidden'); m.classList.add('flex');
        }
    </script>
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
