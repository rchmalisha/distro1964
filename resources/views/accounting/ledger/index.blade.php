@extends('layout.main')

@section('title', 'Buku Besar')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="bg-white shadow-lg rounded-2xl p-6 border border-slate-200">

        {{-- Header --}}
        <div class="mb-6 flex justify-center">
            <div class="w-full rounded-xl shadow-soft-md bg-gradient-to-tl from-purple-700 to-pink-500 px-8 py-6 flex flex-col items-center" style="max-width: 98vw;">
                <span class="inline-block bg-white bg-opacity-20 p-2 rounded-lg mb-2">
                    <i class="fa-solid fa-book-open text-2xl text-slate-700"></i>
                </span>
                <h2 class="text-2xl font-bold text-slate-700 tracking-tight mb-1 drop-shadow">Buku Besar</h2>
                
                @if(request('from') && request('to'))
                    @php
                        \Carbon\Carbon::setLocale('id');
                        $from = \Carbon\Carbon::parse(request('from'))->translatedFormat('d F Y');
                        $to = \Carbon\Carbon::parse(request('to'))->translatedFormat('d F Y');
                    @endphp
                    <span class="text-base font-semibold">
                        Periode {{ $from }} s/d {{ $to }}
                    </span>
                    <span class="text-sm">(dalam Rupiah)</span>
                    @if(request('account'))
                        {{-- Ambil nama akun dari daftar akun yang tersedia --}}
                        @php
                            $selectedAccount = $accounts->where('kode_akun', request('account'))->first();
                            $accountName = $selectedAccount ? $selectedAccount->nama_akun : 'Semua Akun';
                        @endphp
                        <span class="text-sm">Akun: <strong>{{ $accountName }}</strong></span>
                    @endif
                @endif
            </div>
        </div>

        {{-- Filter (Tetap Tampil) --}}
        <form method="GET" class="flex flex-wrap items-end gap-4 mb-6 justify-between">
            <div class="flex gap-4 items-end flex-wrap">
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">Dari Tanggal</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="border rounded-lg px-3 py-2 w-48 focus:border-slate-700 focus:ring-slate-700">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">Sampai Tanggal</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="border rounded-lg px-3 py-2 w-48 focus:border-slate-700 focus:ring-slate-700">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">Nama Akun</label>
                    <select name="account" id="account" class="border rounded-lg px-3 py-2 w-60 account-select">
                        <option value="">-- Semua Akun --</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->kode_akun }}" {{ request('account') == $account->kode_akun ? 'selected' : '' }}>
                                {{ $account->kode_akun }} - {{ $account->nama_akun }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="bg-slate-700 text-white px-4 py-2 rounded-lg hover:bg-slate-800 transition text-sm font-medium">
                        Filter
                    </button>
                    {{-- Tombol Reset mengarahkan ke URL bersih, sehingga tabel tersembunyi --}}
                    <a href="{{ route('ledger.index') }}"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition text-sm font-medium">
                        Reset
                    </a>
                </div>
            </div>

            {{-- Show Entries (Tetap Tampil) --}}
            <div class="flex items-center">
                <label class="mr-2 text-gray-700">Show</label>
                <select name="entries" class="border rounded px-2 py-1">
                    @foreach ([10, 25, 50, 100] as $value)
                        <option value="{{ $value }}" {{ request('entries', 10) == $value ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
                <span class="ml-1 text-gray-700">entries</span>
            </div>
        </form>

        {{-- KONDISI UNTUK MENAMPILKAN TABEL DAN PAGINATION --}}
        @if (request('from') && request('to'))
            
            {{-- Tabel Data --}}
            <div class="border border-slate-200 shadow rounded-2xl bg-white">
                <div class="overflow-x-auto">
                    {{-- MINIMUM WIDTH tetap dipertahankan, namun Keterangan dibuat lebih lebar --}}
                    <table class="items-center w-full text-slate-600 border-collapse min-w-[1000px]">
                        <thead class="bg-slate-100 text-xs uppercase font-semibold">
                            <tr>
                                {{-- Tambahkan whitespace-nowrap pada header --}}
                                <th class="px-6 py-3 border border-slate-200 text-left whitespace-nowrap">Kode Jurnal</th>
                                <th class="px-6 py-3 border border-slate-200 text-left whitespace-nowrap">Tanggal</th>
                                {{-- KOLOM KETERANGAN DIBUAT LEBIH LEBAR (w-96) --}}
                                <th class="px-6 py-3 border border-slate-200 text-left whitespace-nowrap w-96">Keterangan</th>
                                <th class="px-6 py-3 border border-slate-200 text-center whitespace-nowrap">Debit</th>
                                <th class="px-6 py-3 border border-slate-200 text-center whitespace-nowrap">Kredit</th>
                                <th class="px-6 py-3 border border-slate-200 text-center whitespace-nowrap">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ledgers as $ledger)
                                @php
                                    \Carbon\Carbon::setLocale('id');
                                    // Tentukan apakah ini baris Saldo Awal
                                    $isInitialBalance = $ledger->keterangan_jurnal === 'Saldo Awal';
                                    
                                    // Tanggal transaksi. Jika Saldo Awal, gunakan tanggal_jurnal dari Controller (yaitu tanggal 'from')
                                    $tanggal = $isInitialBalance ? \Carbon\Carbon::parse($ledger->tanggal_jurnal)->translatedFormat('d F Y') : \Carbon\Carbon::parse($ledger->tanggal_jurnal)->translatedFormat('d F Y');

                                    // Styling khusus untuk baris saldo awal
                                    $rowClass = $isInitialBalance ? 'bg-slate-200 font-bold hover:bg-slate-300' : 'border-b hover:bg-slate-50';
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    {{-- Kode Jurnal --}}
                                    <td class="px-6 py-3 border border-slate-200 whitespace-nowrap">
                                        {{ $isInitialBalance ? '' : $ledger->kode_jurnal }}
                                    </td>
                                    {{-- Tanggal --}}
                                    <td class="px-6 py-3 border border-slate-200 whitespace-nowrap">
                                        {{ $tanggal }}
                                    </td>
                                    {{-- Keterangan (DITETAPKAN whitespace-nowrap) --}}
                                    <td class="px-6 py-3 border border-slate-200 whitespace-nowrap">
                                        {{ $ledger->keterangan_jurnal }}
                                    </td>
                                    {{-- Debit (Kosongkan jika Saldo Awal) --}}
                                    <td class="px-6 py-3 border border-slate-200 text-right whitespace-nowrap">
                                        @if (!$isInitialBalance && $ledger->debit > 0)
                                            {{ number_format($ledger->debit, 0, ',', '.') }}
                                        @else
                                            &nbsp; 
                                        @endif
                                    </td>
                                    {{-- Kredit (Kosongkan jika Saldo Awal) --}}
                                    <td class="px-6 py-3 border border-slate-200 text-right whitespace-nowrap">
                                        @if (!$isInitialBalance && $ledger->kredit > 0)
                                            {{ number_format($ledger->kredit, 0, ',', '.') }}
                                        @else
                                            &nbsp; 
                                        @endif
                                    </td>
                                    {{-- Saldo --}}
                                    <td class="px-6 py-3 border border-slate-200 text-right font-semibold whitespace-nowrap">
                                        {{ number_format($ledger->saldo, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-slate-500">Tidak ada data buku besar</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="flex justify-center mt-6">
                {{ $ledgers->links() }}
            </div>

        @else
            {{-- Tampilkan pesan jika belum ada filter --}}
            <div class="text-center py-10 text-gray-500 border border-slate-200 rounded-2xl bg-white shadow">
                Silakan pilih <strong>Periode Tanggal</strong> dan tekan tombol <strong>Filter</strong> untuk menampilkan Buku Besar.
            </div>
        @endif

    </div>
</div>

{{-- Tambahkan Select2 --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.account-select').select2({
            placeholder: "Cari atau pilih nama akun...",
            allowClear: true,
            width: 'resolve'
        });
    });
</script>
@endsection