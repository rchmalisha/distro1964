@extends('layout.main')

@section('title', 'Buku Besar')

@section('content')
<div class="p-6 bg-white shadow rounded-2xl">

    {{-- Header --}}
    <div class="mb-6 flex justify-center">
        <div class="w-full rounded-xl shadow-soft-md bg-gradient-to-tl from-purple-700 to-pink-500 px-8 py-6 flex flex-col items-center" style="max-width: 98vw;">
            <span class="inline-block bg-white bg-opacity-20 p-3 rounded-lg mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6.75c-2.25-1.5-5.25-1.5-7.5 0V18c2.25-1.5 5.25-1.5 7.5 0m0-11.25v11.25m0-11.25c2.25-1.5 5.25-1.5 7.5 0V18c-2.25-1.5-5.25-1.5-7.5 0" />
                </svg>
            </span>
            <h2 class="text-2xl font-extrabold text-white tracking-tight mb-1 drop-shadow">Buku Besar</h2>
            @if(request('from') && request('to'))
                @php
                    \Carbon\Carbon::setLocale('id');
                    $from = \Carbon\Carbon::parse(request('from'))->translatedFormat('d F Y');
                    $to = \Carbon\Carbon::parse(request('to'))->translatedFormat('d F Y');
                @endphp
                <span class="text-base font-semibold text-white">
                    Periode {{ $from }} s/d {{ $to }}
                </span>
                <span class="text-sm text-pink-100">(dalam Rupiah)</span>
                @if(request('account'))
                    <span class="text-sm text-pink-100">Akun: {{ request('account') }}</span>
                @endif
            @endif
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" class="flex flex-wrap items-end gap-4 mb-2 justify-between">
        <div class="flex gap-4 items-end flex-wrap">
            <div>
                <label class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                <input type="date" name="from" value="{{ request('from') }}" class="border rounded-lg px-3 py-2 w-48">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                <input type="date" name="to" value="{{ request('to') }}" class="border rounded-lg px-3 py-2 w-48">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Akun</label>
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
                    class="mr-3 inline-block px-6 py-3 font-bold text-center bg-gradient-to-tl from-blue-600 to-cyan-400 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                    Filter
                </button>
                <a href="{{ route('ledger.index') }}"
                    class="mr-3 inline-block px-6 py-3 font-bold text-center bg-gradient-to-tl from-slate-600 to-slate-300 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                    Reset
                </a>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <label class="text-sm font-medium text-gray-700 mr-2">Show Entries</label>
            <select name="entries" class="border rounded-lg px-3 py-2 w-32">
                @foreach ([10, 25, 50, 100] as $value)
                    <option value="{{ $value }}" {{ request('entries', 10) == $value ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-[800px] w-full text-sm text-left text-gray-700 border">
            <thead class="bg-gray-100 text-gray-900">
                <tr>
                    <th class="px-4 py-2 border text-center">Kode Jurnal</th>
                    <th class="px-4 py-2 border text-center">Tanggal</th>
                    <th class="px-4 py-2 border text-center">Keterangan</th>
                    <th class="px-4 py-2 border text-center">Debit</th>
                    <th class="px-4 py-2 border text-center">Kredit</th>
                    <th class="px-4 py-2 border text-center">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ledgers as $ledger)
                    @php
                        \Carbon\Carbon::setLocale('id');
                        $tanggal = \Carbon\Carbon::parse($ledger->tanggal_jurnal)->translatedFormat('d F Y');
                    @endphp
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2 text-center">{{ $ledger->kode_jurnal }}</td>
                        <td class="px-4 py-2 text-center">{{ $tanggal }}</td>
                        <td class="px-4 py-2">{{ $ledger->keterangan_jurnal }}</td>
                        <td class="px-4 py-2 text-right">{{ number_format($ledger->debit, 2, ',', '.') }}</td>
                        <td class="px-4 py-2 text-right">{{ number_format($ledger->kredit, 2, ',', '.') }}</td>
                        <td class="px-4 py-2 text-right font-semibold">{{ number_format($ledger->saldo, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">Tidak ada data buku besar</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="flex justify-center mt-6">
        {{ $ledgers->links() }}
    </div>

</div>

{{-- Tambahkan Select2 langsung di halaman --}}
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
