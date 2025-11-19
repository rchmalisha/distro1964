@extends('layout.main')
@section('title', 'Daftar Akun')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="bg-white shadow-lg rounded-2xl p-6 border border-slate-200">
        <div class="flex justify-between items-center mb-6">
            <h6 class="text-2xl font-bold text-slate-700">Daftar Akun</h6>
        </div>

        {{-- Button: Tambah Akun (opens modal) --}}
        <div class="mb-6 flex items-center justify-between">
            <div>
                <button @click="$store.modal.openAdd = true" type="button"
                    class="bg-slate-700 text-white px-4 py-2 rounded-lg hover:bg-slate-800 transition">
                    + Tambah Akun
                </button>
            </div>
            {{-- Placeholder untuk Search dan Rows Per Page (jika ingin ditambahkan nanti) --}}
        </div>

        {{-- Tabel Data (Sesuai Acuan: border border-slate-200 shadow rounded-2xl bg-white) --}}
        <div class="border border-slate-200 shadow rounded-2xl bg-white">
            <div class="overflow-x-auto">
                <table id="sortableTable" class="items-center w-full text-slate-600 border-collapse">
                    <thead class="bg-slate-100 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-3 border border-slate-200 text-left">Kode Akun</th>
                            <th class="px-6 py-3 border border-slate-200 text-left">Nama Akun</th>
                            <th class="px-6 py-3 border border-slate-200 text-left">Jenis Akun</th>
                            <th class="px-6 py-3 border border-slate-200 text-left">Saldo Normal</th>
                            <th class="px-6 py-3 border border-slate-200 text-center">Saldo Awal</th>
                            <th class="px-6 py-3 border border-slate-200 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accounts as $account)
                        <tr class="border-b hover:bg-slate-50">
                            <td class="px-6 py-3 border border-slate-200">{{ $account->kode_akun }}</td>
                            <td class="px-6 py-3 border border-slate-200">{{ $account->nama_akun }}</td>
                            <td class="px-6 py-3 border border-slate-200 capitalize">{{ $account->jenis_akun }}</td>
                            <td class="px-6 py-3 border border-slate-200 capitalize">{{ $account->saldo_normal }}</td>
                            <td class="px-6 py-3 border border-slate-200 text-center">Rp {{ number_format($account->saldo_awal, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 border border-slate-200 text-center">
                                <button @click="$store.modal.openEdit = '{{ $account->id }}'" type="button"
                                    class="text-blue-500 hover:text-blue-700 transition">
                                    Edit
                                </button>
                                |
                                <form action="{{ route('accounts.destroy', $account->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Yakin hapus akun ini?')" type="submit"
                                        class="text-red-500 hover:text-red-700 transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-slate-500">Belum ada data akun.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Akun (Sudah Diperbaiki Penengahan) --}}
<div x-show="$store.modal.openAdd" x-transition x-cloak style="z-index:9999"
    class="fixed inset-0 flex items-center justify-center">
    <div @click="$store.modal.openAdd = false" style="z-index:9998"
        class="absolute inset-0 bg-black opacity-50"></div>

    <div @click.outside="$store.modal.openAdd = false" style="z-index:9999"
        class="relative bg-white rounded-2xl shadow-lg w-full max-w-md sm:max-w-lg p-6 mx-4">

        <button @click="$store.modal.openAdd = false" type="button"
            class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-2xl font-bold focus:outline-none">
            &times;
        </button>

        <h2 class="text-xl font-semibold text-slate-700 mb-4">Tambah Data Akun</h2>

        <form method="POST" action="{{ route('accounts.store') }}" class="space-y-4">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Kode Akun</label>
                    <input type="text" name="kode_akun" class="w-full border rounded-lg px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Nama Akun</label>
                    <input type="text" name="nama_akun" class="w-full border rounded-lg px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Jenis Akun</label>
                    <select name="jenis_akun" class="w-full border rounded-lg px-3 py-2" required>
                        <option value="" disabled selected>Pilih jenis akun</option>
                        @foreach(['aset lancar','aset tetap','liabilitas','ekuitas','pendapatan','beban'] as $jenis)
                            <option value="{{ $jenis }}">{{ ucfirst($jenis) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Saldo Normal</label>
                    <select name="saldo_normal" class="w-full border rounded-lg px-3 py-2" required>
                        <option value="debit">Debit</option>
                        <option value="kredit">Kredit</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Saldo Awal</label>
                    <input type="number" name="saldo_awal" class="w-full border rounded-lg px-3 py-2" step="0.01">
                </div>
            </div>

            <div class="pt-4 flex justify-end space-x-3">
                <button type="button" @click="$store.modal.openAdd = false"
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

{{-- Modal Edit Akun (Sudah Diperbaiki Penengahan dan Kode Akun) --}}
@foreach($accounts as $account)
<div x-show="$store.modal.openEdit === '{{ $account->id }}'" x-transition x-cloak style="z-index:9999"
    class="fixed inset-0 flex items-center justify-center">
    <div @click="$store.modal.openEdit = null" style="z-index:9998"
        class="absolute inset-0 bg-black opacity-50"></div>

    <div @click.outside="$store.modal.openEdit = null" style="z-index:9999"
        class="relative bg-white rounded-2xl shadow-lg w-full max-w-md sm:max-w-lg p-6 mx-4">

        <button @click="$store.modal.openEdit = null" type="button"
            class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-2xl font-bold focus:outline-none">
            &times;
        </button>

        <h2 class="text-xl font-semibold text-slate-700 mb-4">Edit Data Akun</h2>

        <form method="POST" action="{{ route('accounts.update', $account->id) }}" class="space-y-4">
            @csrf @method('PUT')

            <div class="space-y-4">
                {{-- KODE AKUN SUDAH BISA DIEDIT --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Kode Akun</label>
                    <input type="text" name="kode_akun" value="{{ $account->kode_akun }}"
                        class="w-full border rounded-lg px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Nama Akun</label>
                    <input type="text" name="nama_akun" value="{{ $account->nama_akun }}"
                        class="w-full border rounded-lg px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Jenis Akun</label>
                    <select name="jenis_akun" class="w-full border rounded-lg px-3 py-2" required>
                        <option value="" disabled>Pilih jenis akun</option>
                        @foreach(['aset lancar','aset tetap','liabilitas','ekuitas','pendapatan','beban'] as $jenis)
                            <option value="{{ $jenis }}" {{ $account->jenis_akun == $jenis ? 'selected' : '' }}>
                                {{ ucfirst($jenis) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Saldo Normal</label>
                    <select name="saldo_normal" class="w-full border rounded-lg px-3 py-2" required>
                        <option value="debit" {{ $account->saldo_normal == 'debit' ? 'selected' : '' }}>Debit</option>
                        <option value="kredit" {{ $account->saldo_normal == 'kredit' ? 'selected' : '' }}>Kredit</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Saldo Awal</label>
                    <input type="number" name="saldo_awal" value="{{ $account->saldo_awal }}"
                        class="w-full border rounded-lg px-3 py-2" step="0.01">
                </div>
            </div>

            <div class="pt-4 flex justify-end space-x-3">
                <button type="button" @click="$store.modal.openEdit = null"
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
@endforeach

{{-- Script SweetAlert2 untuk Notifikasi (Sesuai Acuan) --}}
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