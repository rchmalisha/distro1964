@extends('layout.main')
@section('title', 'Daftar Transaksi Lainnya')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="bg-white shadow-lg rounded-2xl p-6 border border-slate-200">
        <div class="flex justify-between items-center mb-6">
            <h6 class="text-2xl font-bold text-slate-700">Daftar Transaksi Lainnya</h6>
        </div>

        {{-- Button Tambah --}}
        <div class="mb-6">
            <button @click="$store.modal.openAdd = true" type="button"
                class="bg-slate-700 text-white px-4 py-2 rounded-lg hover:bg-slate-800 transition">
                + Tambah Transaksi
            </button>
        </div>

        {{-- Tabel --}}
        <div class="border border-slate-200 shadow rounded-2xl bg-white">
            <div class="overflow-x-auto">
                <table class="items-center w-full text-slate-600 border-collapse">
                    <thead class="bg-slate-100 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-3 border text-left">Tanggal</th>
                            <th class="px-6 py-3 border text-left">Jenis Transaksi</th>
                            <th class="px-6 py-3 border text-left">Nama Transaksi</th>
                            <th class="px-6 py-3 border text-right">Nominal</th>
                            <th class="px-6 py-3 border text-left">Keterangan</th>
                            <th class="px-6 py-3 border text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                        <tr class="border-b hover:bg-slate-50">
                            <td class="px-6 py-3 border">
                                {{ \Carbon\Carbon::parse($transaction->tanggal)->format('d/m/Y') }}
                            </td>

                            <td class="px-6 py-3 border">
                                <span class="font-medium capitalize
                                    {{ $transaction->jenis_transaksi == 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->jenis_transaksi }}
                                </span>
                            </td>

                            <td class="px-6 py-3 border">
                                @if($transaction->kode_akun === '5101')
                                Pembelian Kantong Kresek
                                @else
                                {{ $transaction->account->nama_akun ?? 'Akun Tidak Ditemukan' }}
                                @endif
                            </td>


                            <td class="px-6 py-3 border text-right">
                                Rp {{ number_format($transaction->nominal, 0, ',', '.') }}
                            </td>

                            <td class="px-6 py-3 border">
                                {{ $transaction->keterangan ?? '-' }}
                            </td>

                            <td class="px-6 py-3 border text-center">
                                <button @click="$store.modal.openEdit = '{{ $transaction->id }}'"
                                    class="text-blue-500 hover:text-blue-700">Edit</button>
                                |
                                <form action="{{ route('general-transaction.destroy', $transaction->id) }}"
                                    method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Yakin hapus transaksi ini?')"
                                        class="text-red-500 hover:text-red-700">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-slate-500">
                                Belum ada data transaksi lainnya.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ================= MODAL TAMBAH ================= --}}
<div x-show="$store.modal.openAdd" x-transition x-cloak style="z-index:9999"
    class="fixed inset-0 flex items-center justify-center">
    <div @click="$store.modal.openAdd = false" class="absolute inset-0 bg-black opacity-50"></div>

    <div @click.outside="$store.modal.openAdd = false"
        class="relative bg-white rounded-2xl shadow-lg w-full max-w-md p-6 mx-4">

        <button @click="$store.modal.openAdd = false"
            class="absolute top-3 right-3 text-gray-500 text-2xl">&times;</button>

        <h2 class="text-xl font-semibold text-slate-700 mb-4">Tambah Transaksi Lainnya</h2>

        <form method="POST" action="{{ route('general-transaction.store') }}" class="space-y-4">
            @csrf

            <div x-data="{ selectedTransactionType: 'pengeluaran' }" class="space-y-4">

                <div>
                    <label class="block text-sm font-medium mb-1">Jenis Transaksi</label>
                    <select name="jenis_transaksi" x-model="selectedTransactionType"
                        class="w-full border rounded-lg px-3 py-2">
                        <option value="pemasukan">Pemasukan</option>
                        <option value="pengeluaran">Pengeluaran</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Nama Transaksi</label>
                    <select name="kode_akun" class="w-full border rounded-lg px-3 py-2" required>
                        <option value="" disabled selected>Pilih Transaksi</option>
                        @foreach($accounts as $account)
                        <option value="{{ $account->kode_akun }}"
                            x-show="
            (selectedTransactionType === 'pemasukan' && '{{ $account->kode_akun }}' === '3101') ||
            (selectedTransactionType === 'pengeluaran' &&
                ('{{ $account->kode_akun }}' === '3103' ||
                 '{{ $account->kode_akun }}'.startsWith('5')))
        ">
                            {{ $account->kode_akun === '5101'
            ? 'Pembelian Kantong Kresek'
            : $account->nama_akun
        }}
                        </option>
                        @endforeach


                    </select>

                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}"
                        class="w-full border rounded-lg px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Nominal</label>
                    <input type="number" name="nominal" min="1"
                        class="w-full border rounded-lg px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Keterangan</label>
                    <textarea name="keterangan" rows="2"
                        class="w-full border rounded-lg px-3 py-2"></textarea>
                </div>

            </div>

            <div class="pt-4 flex justify-end space-x-3">
                <button type="button" @click="$store.modal.openAdd = false"
                    class="bg-gray-300 px-4 py-2 rounded-lg">Batal</button>
                <button type="submit"
                    class="bg-slate-700 text-white px-4 py-2 rounded-lg">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ================= MODAL EDIT ================= --}}
@foreach($transactions as $transaction)
<div x-show="$store.modal.openEdit === '{{ $transaction->id }}'" x-transition x-cloak
    class="fixed inset-0 flex items-center justify-center" style="z-index:9999">
    <div @click="$store.modal.openEdit = null" class="absolute inset-0 bg-black opacity-50"></div>

    <div @click.outside="$store.modal.openEdit = null"
        class="relative bg-white rounded-2xl shadow-lg w-full max-w-md p-6 mx-4">

        <button @click="$store.modal.openEdit = null"
            class="absolute top-3 right-3 text-gray-500 text-2xl">&times;</button>

        <h2 class="text-xl font-semibold mb-4">Edit Transaksi Lainnya</h2>

        <form method="POST" action="{{ route('general-transaction.update', $transaction->id) }}" class="space-y-4">
            @csrf @method('PUT')

            <div x-data="{ selectedTransactionType: '{{ $transaction->jenis_transaksi }}' }" class="space-y-4">

                <div>
                    <label class="block text-sm font-medium mb-1">Jenis Transaksi</label>
                    <select name="jenis_transaksi" x-model="selectedTransactionType"
                        class="w-full border rounded-lg px-3 py-2">
                        <option value="pemasukan">Pemasukan</option>
                        <option value="pengeluaran">Pengeluaran</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Nama Akun</label>
                    <select name="kode_akun" class="w-full border rounded-lg px-3 py-2">
                        @foreach($accounts as $account)
                        <option value="{{ $account->kode_akun }}"
                            x-show="
            (selectedTransactionType === 'pemasukan' && '{{ $account->kode_akun }}' === '3101') ||
            (selectedTransactionType === 'pengeluaran' &&
                ('{{ $account->kode_akun }}' === '3103' ||
                 '{{ $account->kode_akun }}'.startsWith('5')))
        ">
                            {{ $account->kode_akun === '5101'
            ? 'Pembelian Kantong Kresek'
            : $account->nama_akun
        }}
                        </option>
                        @endforeach

                    </select>

                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Tanggal</label>
                    <input type="date" name="tanggal"
                        value="{{ \Carbon\Carbon::parse($transaction->tanggal)->format('Y-m-d') }}"
                        class="w-full border rounded-lg px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Nominal</label>
                    <input type="number" name="nominal" value="{{ $transaction->nominal }}"
                        class="w-full border rounded-lg px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Keterangan</label>
                    <textarea name="keterangan" rows="2"
                        class="w-full border rounded-lg px-3 py-2">{{ $transaction->keterangan }}</textarea>
                </div>

            </div>

            <div class="pt-4 flex justify-end space-x-3">
                <button type="button" @click="$store.modal.openEdit = null"
                    class="bg-gray-300 px-4 py-2 rounded-lg">Batal</button>
                <button type="submit"
                    class="bg-slate-700 text-white px-4 py-2 rounded-lg">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endforeach

@endsection