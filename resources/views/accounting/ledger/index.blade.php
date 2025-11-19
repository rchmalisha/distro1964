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
                        <span class="text-sm">Akun: {{ request('account') }}</span>
                    @endif
                @endif
            </div>
        </div>

        {{-- Filter (Kelas diubah agar lebih sederhana dan konsisten) --}}
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
                    {{-- Input Select2 dipertahankan, hanya kelas tampilan diubah --}}
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
                    <a href="{{ route('ledger.index') }}"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition text-sm font-medium">
                        Reset
                    </a>
                </div>
            </div>

            {{-- Show Entries (Disesuaikan dengan tampilan filter acuan) --}}
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

        {{-- Tabel Data (Sesuai Acuan: border border-slate-200 shadow rounded-2xl bg-white) --}}
        <div class="border border-slate-200 shadow rounded-2xl bg-white">
            <div class="overflow-x-auto">
                <table class="items-center w-full text-slate-600 border-collapse min-w-[800px]">
                    <thead class="bg-slate-100 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-3 border border-slate-200 text-left">Kode Jurnal</th>
                            <th class="px-6 py-3 border border-slate-200 text-left">Tanggal</th>
                            <th class="px-6 py-3 border border-slate-200 text-left">Keterangan</th>
                            <th class="px-6 py-3 border border-slate-200 text-center">Debit</th>
                            <th class="px-6 py-3 border border-slate-200 text-center">Kredit</th>
                            <th class="px-6 py-3 border border-slate-200 text-center">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ledgers as $ledger)
                            @php
                                \Carbon\Carbon::setLocale('id');
                                $tanggal = \Carbon\Carbon::parse($ledger->tanggal_jurnal)->translatedFormat('d F Y');
                            @endphp
                            <tr class="border-b hover:bg-slate-50">
                                <td class="px-6 py-3 border border-slate-200">{{ $ledger->kode_jurnal }}</td>
                                <td class="px-6 py-3 border border-slate-200">{{ $tanggal }}</td>
                                <td class="px-6 py-3 border border-slate-200">{{ $ledger->keterangan_jurnal }}</td>
                                <td class="px-6 py-3 border border-slate-200 text-right">Rp {{ number_format($ledger->debit, 0, ',', '.') }}</td>
                                <td class="px-6 py-3 border border-slate-200 text-right">Rp {{ number_format($ledger->kredit, 0, ',', '.') }}</td>
                                <td class="px-6 py-3 border border-slate-200 text-right font-semibold">Rp {{ number_format($ledger->saldo, 0, ',', '.') }}</td>
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

        {{-- Pagination (Kelas disesuaikan agar lebih terpisah dari tabel) --}}
        <div class="flex justify-center mt-6">
            {{ $ledgers->links() }}
        </div>

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