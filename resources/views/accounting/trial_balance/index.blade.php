@extends('layout.main')

@section('title', 'Neraca Saldo')

@section('content')
<div class="p-6 bg-white shadow rounded-2xl">

    {{-- Header --}}
    <div class="mb-6 flex justify-center">
        <div class="w-full rounded-xl shadow-soft-md bg-gradient-to-tl from-purple-700 to-pink-500 px-8 py-6 flex flex-col items-center" style="max-width: 98vw;">
            <span class="inline-block bg-white bg-opacity-20 p-3 rounded-lg mb-2">
                {{-- SVG icon tabel putih --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 10h16M4 14h16M4 18h16M4 6v12M12 6v12M20 6v12" />
                </svg>
            </span>
            <h2 class="text-2xl font-extrabold text-white tracking-tight mb-1 drop-shadow">Neraca Saldo</h2>
            @if(isset($month) && isset($year))
            <span class="text-base font-semibold text-white">
                Periode: {{ \Carbon\Carbon::createFromDate($year, $month, 1)->locale('id')->translatedFormat('F Y') }}
            </span>
            @endif
        </div>
    </div>

    {{-- Filter Bulan & Tahun --}}
    <form method="GET" class="flex flex-wrap items-end gap-4 mb-4 justify-between">
        <div class="flex gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700">Bulan</label>
                <select name="month" class="border rounded-lg px-3 py-2 w-40">
                    <option value="">-- Pilih Bulan --</option>
                    @foreach (range(1,12) as $m)
                    <option value="{{ $m }}" {{ (isset($month) && $m == $month) ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::createFromDate(null, $m, 1)->locale('id')->translatedFormat('F') }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tahun</label>
                <select name="year" class="border rounded-lg px-3 py-2 w-32">
                    <option value="">-- Pilih Tahun --</option>
                    @foreach (range(date('Y')-5, date('Y')) as $y)
                    <option value="{{ $y }}" {{ (isset($year) && $y == $year) ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="inline-block px-6 py-3 font-bold text-center bg-gradient-to-tl from-blue-600 to-cyan-400 uppercase transition-all rounded-lg text-xs shadow-soft-md hover:scale-105 active:opacity-85 text-white">
                    Filter
                </button>
                <a href="{{ route('trial-balance.index') }}"
                    class="inline-block px-6 py-3 font-bold text-center bg-gradient-to-tl from-slate-600 to-slate-300 uppercase transition-all rounded-lg text-xs shadow-soft-md hover:scale-105 active:opacity-85 text-white">
                    Reset
                </a>
            </div>
        </div>
    </form>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-[800px] w-full text-sm text-left text-gray-700 border">
            <thead class="bg-gray-100 text-gray-900">
                <tr>
                    <th class="px-4 py-2 border text-center">Kode Akun</th>
                    <th class="px-4 py-2 border text-center">Nama Akun</th>
                    <th class="px-4 py-2 border text-center">Debet</th>
                    <th class="px-4 py-2 border text-center">Kredit</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalDebit = 0;
                    $totalKredit = 0;
                @endphp
                @forelse ($trialBalance as $row)
                    @php
                        $totalDebit += $row->debit;
                        $totalKredit += $row->kredit;
                    @endphp
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2 text-center">{{ $row->kode_akun }}</td>
                        <td class="px-4 py-2">{{ $row->nama_akun }}</td>
                        <td class="px-4 py-2 text-right">{{ number_format($row->debit, 2, ',', '.') }}</td>
                        <td class="px-4 py-2 text-right">{{ number_format($row->kredit, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500">Tidak ada data untuk periode ini</td>
                    </tr>
                @endforelse

                @if ($trialBalance->count())
                    <tr class="bg-gray-100 font-bold border-t">
                        <td colspan="2" class="px-4 py-2 text-right">Total</td>
                        <td class="px-4 py-2 text-right">{{ number_format($totalDebit, 2, ',', '.') }}</td>
                        <td class="px-4 py-2 text-right">{{ number_format($totalKredit, 2, ',', '.') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
