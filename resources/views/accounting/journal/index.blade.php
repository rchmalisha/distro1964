@extends('layout.main')

@section('title', 'Jurnal Umum')

@section('content')
<div class="p-6 bg-white shadow rounded-2xl">

    {{-- Header --}}
    <div class="mb-6 flex justify-center">
        <div class="w-full rounded-xl shadow-soft-md bg-gradient-to-tl from-purple-700 to-pink-500 px-8 py-6 flex flex-col items-center" style="max-width: 98vw;">
            <span class="inline-block bg-white bg-opacity-20 p-2 rounded-lg mb-2">
                <i class="fa-solid fa-file-pen text-2xl"></i>
            </span>
            <h2 class="text-2xl font-bold text-slate-700 tracking-tight mb-1 drop-shadow">Jurnal Umum</h2>

            @if(request('from') && request('to'))
                <span class="text-base font-semibold">
                    Periode 
                    {{ \Carbon\Carbon::parse(request('from'))->locale('id')->translatedFormat('d F Y') }}
                    s/d 
                    {{ \Carbon\Carbon::parse(request('to'))->locale('id')->translatedFormat('d F Y') }}
                </span>
                <span class="text-sm">(dalam Rupiah)</span>
            @endif
        </div>
    </div>

{{-- Filter --}}
        <form method="GET" class="flex flex-wrap items-end gap-4 mb-6 justify-between">
            <div class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">Dari Tanggal</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="border rounded-lg px-3 py-2 w-48 focus:border-slate-700 focus:ring-slate-700">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">Sampai Tanggal</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="border rounded-lg px-3 py-2 w-48 focus:border-slate-700 focus:ring-slate-700">
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="bg-slate-700 text-white px-4 py-2 rounded-lg hover:bg-slate-800 transition text-sm font-medium">
                        Filter
                    </button>
                    <a href="{{ route('journal.index') }}"
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
                <table class="items-center w-full text-slate-600 border-collapse min-w-[900px]">
                    <thead class="bg-slate-100 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-3 border border-slate-200 text-left">Kode Jurnal</th>
                            <th class="px-6 py-3 border border-slate-200 text-left">No Bukti</th>
                            <th class="px-6 py-3 border border-slate-200 text-left">Tanggal</th>
                            <th class="px-6 py-3 border border-slate-200 text-left">Kode Akun</th>
                            <th class="px-6 py-3 border border-slate-200 text-center">Debet</th>
                            <th class="px-6 py-3 border border-slate-200 text-center">Kredit</th>
                            <th class="px-6 py-3 border border-slate-200 text-left">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($journals as $journal)
                            @foreach ($journal->details as $detail)
                                <tr class="border-b hover:bg-slate-50">
                                    <td class="px-6 py-3 border border-slate-200">{{ $journal->kode_jurnal }}</td>
                                    <td class="px-6 py-3 border border-slate-200">{{ $journal->no_bukti }}</td>
                                    <td class="px-6 py-3 border border-slate-200">
                                        {{ \Carbon\Carbon::parse($journal->tanggal_jurnal)->locale('id')->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="px-6 py-3 border border-slate-200">{{ $detail->kode_akun }}</td>
                                    <td class="px-6 py-3 border border-slate-200 text-right">Rp {{ number_format($detail->debit, 0, ',', '.') }}</td>
                                    <td class="px-6 py-3 border border-slate-200 text-right">Rp {{ number_format($detail->kredit, 0, ',', '.') }}</td>
                                    <td class="px-6 py-3 border border-slate-200">{{ $journal->keterangan_jurnal }}</td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-slate-500">Tidak ada data jurnal</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination (Kelas disesuaikan agar lebih terpisah dari tabel) --}}
        <div class="flex justify-center mt-6">
            {{ $journals->links() }}
        </div>

    </div>
</div>
@endsection