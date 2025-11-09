@extends('layout.main')

@section('title', 'Jurnal Umum')

@section('content')
<div class="p-6 bg-white shadow rounded-2xl">

    {{-- Header --}}
    <div class="mb-6 flex justify-center">
        <div class="w-full rounded-xl shadow-soft-md bg-gradient-to-tl from-purple-700 to-pink-500 px-8 py-6 flex flex-col items-center" style="max-width: 98vw;">
            <span class="inline-block bg-white bg-opacity-20 p-2 rounded-lg mb-2">
                <i class="fa-solid fa-file-pen text-white text-2xl"></i>
            </span>
            <h2 class="text-2xl font-extrabold text-white tracking-tight mb-1 drop-shadow">Jurnal Umum</h2>

            @if(request('from') && request('to'))
                <span class="text-base font-semibold text-white">
                    Periode 
                    {{ \Carbon\Carbon::parse(request('from'))->locale('id')->translatedFormat('d F Y') }}
                    s/d 
                    {{ \Carbon\Carbon::parse(request('to'))->locale('id')->translatedFormat('d F Y') }}
                </span>
                <span class="text-sm text-pink-100">(dalam Rupiah)</span>
            @endif
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" class="flex flex-wrap items-end gap-4 mb-2 justify-between">
        <div class="flex gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                <input type="date" name="from" value="{{ request('from') }}" class="border rounded-lg px-3 py-2 w-48">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                <input type="date" name="to" value="{{ request('to') }}" class="border rounded-lg px-3 py-2 w-48">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="mr-3 inline-block px-6 py-3 font-bold text-center bg-gradient-to-tl from-blue-600 to-cyan-400 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                    Filter
                </button>
                <a href="{{ route('journal.index') }}" class="mr-3 inline-block px-6 py-3 font-bold text-center bg-gradient-to-tl from-slate-600 to-slate-300 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
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
        <table class="min-w-[900px] w-full text-sm text-left text-gray-700 border">
            <thead class="bg-gray-100 text-gray-900">
                <tr>
                    <th class="px-4 py-2 border text-center">Kode Jurnal</th>
                    <th class="px-4 py-2 border text-center">No Bukti</th>
                    <th class="px-4 py-2 border text-center">Tanggal</th>
                    <th class="px-4 py-2 border text-center">Nama Akun</th>
                    <th class="px-4 py-2 border text-center">Debet</th>
                    <th class="px-4 py-2 border text-center">Kredit</th>
                    <th class="px-4 py-2 border text-center">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($journals as $journal)
                    @foreach ($journal->details as $detail)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $journal->kode_jurnal }}</td>
                            <td class="px-4 py-2">{{ $journal->no_bukti }}</td>
                            <td class="px-4 py-2">
                                {{ \Carbon\Carbon::parse($journal->tanggal_jurnal)->locale('id')->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-4 py-2">{{ $detail->kode_akun }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($detail->debit, 2, ',', '.') }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($detail->kredit, 2, ',', '.') }}</td>
                            <td class="px-4 py-2">{{ $journal->keterangan_jurnal }}</td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-gray-500">Tidak ada data jurnal</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="flex justify-center mt-6">
        {{ $journals->links() }}
    </div>

</div>
@endsection
