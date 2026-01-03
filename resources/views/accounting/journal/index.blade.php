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

{{-- Filter Form (Tetap Tampil) --}}
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
                    {{-- Tombol Reset mengarahkan ke URL bersih (tanpa parameter), sehingga tabel tersembunyi --}}
                    <a href="{{ route('journal.index') }}"
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


@if (request('from') && request('to'))

        {{-- Tabel Data --}}
        <div class="border border-slate-200 shadow rounded-2xl bg-white">
            <div class="overflow-x-auto">
                <table class="items-center w-full text-slate-600 border-collapse whitespace-nowrap">
                    <thead class="bg-slate-100 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-3 border border-slate-200 text-left">Kode Jurnal</th>
                            <th class="px-6 py-3 border border-slate-200 text-left">Tanggal</th>
                            <th class="px-6 py-3 border border-slate-200 text-left">Nama Akun</th>
                            <th class="px-6 py-3 border border-slate-200 text-center">Debet</th>
                            <th class="px-6 py-3 border border-slate-200 text-center">Kredit</th>
                            <th class="px-6 py-3 border border-slate-200 text-left">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($journals as $journal)
                            @php
                                $detailCount = $journal->details->count();
                                $detailsDebet = $journal->details->where('debit', '>', 0);
                                $detailsKredit = $journal->details->where('kredit', '>', 0);
                                $sortedDetails = $detailsDebet->merge($detailsKredit);
                                $detailCount = $sortedDetails->count();
                            @endphp
                            
                            @foreach ($sortedDetails as $detail)
                                <tr class="border-b hover:bg-slate-50">
                                    
                                    {{-- Kolom Kode Jurnal (rowspan) --}}
                                    @if ($loop->first)
                                        <td class="px-6 py-3 border border-slate-200" rowspan="{{ $detailCount }}">
                                            {{ $journal->kode_jurnal }}
                                        </td>
                                    @endif
                                    
                                    {{-- Kolom Tanggal (rowspan) --}}
                                    @if ($loop->first)
                                        <td class="px-6 py-3 border border-slate-200" rowspan="{{ $detailCount }}">
                                            {{ \Carbon\Carbon::parse($journal->tanggal_jurnal)->locale('id')->translatedFormat('d F Y') }}
                                        </td>
                                    @endif
                                    
                                    {{-- Kolom Nama Akun --}}
                                    @php
                                        $isKredit = $detail->kredit > 0;
                                    @endphp

                                    <td class="px-6 py-3 border border-slate-200 {{ $isKredit ? 'pl-6' : '' }} font-bold text-slate-700">
                                        {{ $detail->account->nama_akun ?? 'Akun Tidak Ditemukan' }}
                                    </td>
                                    
                                    {{-- Kolom Debet --}}
                                    <td class="px-6 py-3 border border-slate-200 text-right">
                                        @if ($detail->debit > 0)
                                            {{ number_format($detail->debit, 0, ',', '.') }}
                                        @else
                                            &nbsp; 
                                        @endif
                                    </td>
                                    
                                    {{-- Kolom Kredit --}}
                                    <td class="px-6 py-3 border border-slate-200 text-right">
                                        @if ($detail->kredit > 0)
                                            {{ number_format($detail->kredit, 0, ',', '.') }}
                                        @else
                                            &nbsp; 
                                        @endif
                                    </td>
                                    
                                    {{-- Kolom Keterangan (rowspan) --}}
                                    @if ($loop->first)
                                        <td class="px-6 py-3 border border-slate-200 max-w-xs whitespace-normal" rowspan="{{ $detailCount }}">
                                            {{ $journal->keterangan_jurnal }}
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-slate-500">Tidak ada data jurnal</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination & Summary --}}
        <div class="flex justify-between items-center mt-6">
            {{-- Ringkasan Pagination (KIRI) --}}
            <div class="text-sm text-gray-600">
                Menampilkan 
                <span class="font-semibold">{{ $journals->firstItem() }}</span> 
                sampai 
                <span class="font-semibold">{{ $journals->lastItem() }}</span> 
                dari total 
                <span class="font-semibold">{{ $journals->total() }}</span> entri.
            </div>

            {{-- Tautan Pagination (KANAN - Menggunakan tampilan minimalis seperti contoh) --}}
            <div class="flex items-center text-sm">
                {{-- Tombol Prev --}}
                <a href="{{ $journals->previousPageUrl() }}" 
                   class="px-3 py-1 border border-slate-300 rounded-l-lg hover:bg-gray-100 {{ $journals->onFirstPage() ? 'text-gray-400 cursor-default' : 'text-slate-700' }}">
                    Prev
                </a>

                {{-- Halaman Saat Ini (Informasi Hal X dari Y) --}}
                <span class="px-3 py-1 border border-slate-300 border-l-0 border-r-0 text-slate-700 bg-white font-semibold">
                    Hal {{ $journals->currentPage() }} dari {{ $journals->lastPage() }}
                </span>

                {{-- Tombol Next --}}
                <a href="{{ $journals->nextPageUrl() }}" 
                   class="px-3 py-1 border border-slate-300 rounded-r-lg hover:bg-gray-100 {{ $journals->hasMorePages() ? 'text-slate-700' : 'text-gray-400 cursor-default' }}">
                    Next
                </a>
            </div>
        </div>
@else
    {{-- Tampilkan pesan jika belum ada filter --}}
    <div class="text-center py-10 text-gray-500 border border-slate-200 rounded-2xl bg-white shadow">
        Silakan pilih <strong>Periode Tanggal</strong> dan tekan tombol <strong>Filter</strong> untuk menampilkan Jurnal Umum.
    </div>
@endif

    </div>
</div>
@endsection