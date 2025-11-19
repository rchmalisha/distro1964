@extends('layout.main')

@section('title', 'Laporan Laba Rugi')

@section('content')
<div class="p-6 bg-white shadow rounded-2xl">

    {{-- Header --}}
    <div class="mb-6 flex justify-center">
        <div class="w-full rounded-xl shadow-soft-md bg-gradient-to-tl from-purple-700 to-pink-500 px-8 py-6 flex flex-col items-center" style="max-width: 98vw;">
            <span class="inline-block bg-white bg-opacity-20 p-3 rounded-lg mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2h6v2m3 4H6a2 2 0 01-2-2V5a2 
                        2 0 012-2h12a2 2 0 
                        012 2v14a2 2 0 
                        01-2 2zM9 9h6v2H9V9z" />
                </svg>
            </span>
            <h2 class="text-2xl font-extrabold text-white tracking-tight mb-1 drop-shadow">
                Laporan Laba Rugi
            </h2>

            @if(isset($month) && isset($year))
            @if($month == 'all')
            <span class="text-base font-semibold text-white">
                Periode: Januari – Desember {{ $year }}
            </span>
            @else
            <span class="text-base font-semibold text-white">
                Periode: {{ \Carbon\Carbon::createFromDate($year, $month, 1)->locale('id')->translatedFormat('F Y') }}
            </span>
            @endif
            @endif
        </div>
    </div>

    {{-- Filter Bulan & Tahun --}}
    <form method="GET" class="flex flex-wrap items-end gap-4 mb-4 justify-between">
        <div class="flex gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700">Bulan</label>
                <select name="month" class="border rounded-lg px-3 py-2 w-40">
                    <option value="">Pilih Bulan</option>
                    <option value="all" {{ (isset($month) && $month == 'all') ? 'selected' : '' }}>Semua Bulan</option>
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
                    <option value="">Pilih Tahun</option>
                    @foreach (range(date('Y')-5, date('Y')) as $y)
                    <option value="{{ $y }}" {{ (isset($year) && $y == $year) ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Tombol Filter, Reset, dan Print --}}
            <div class="flex gap-2">
                <button type="submit"
                    class="inline-block px-6 py-3 font-bold text-center bg-gradient-to-tl from-blue-600 to-cyan-400 uppercase transition-all rounded-lg text-xs shadow-soft-md hover:scale-105 active:opacity-85 text-white">
                    Filter
                </button>

                <a href="{{ route('profit.loss.index') }}"
                    class="inline-block px-6 py-3 font-bold text-center bg-gradient-to-tl from-slate-600 to-slate-300 uppercase transition-all rounded-lg text-xs shadow-soft-md hover:scale-105 active:opacity-85 text-white">
                    Reset
                </a>

                <a href="{{ route('profit.loss.print', ['month' => $month ?? '', 'year' => $year ?? '']) }}" target="_blank"
                    class="inline-block px-6 py-3 font-bold text-center bg-gradient-to-tl from-green-600 to-lime-400 uppercase transition-all rounded-lg text-xs shadow-soft-md hover:scale-105 active:opacity-85 text-white">
                    Cetak
                </a>
            </div>
        </div>
    </form>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-[600px] w-full text-sm text-left text-gray-700 border">
            <tbody>
                {{-- Pendapatan --}}
                <tr class="bg-gray-100 font-bold">
                    <td colspan="2" class="px-4 py-2">Pendapatan</td>
                    <td class="px-4 py-2 text-right">{{ number_format($pendapatan ?? 0, 2, ',', '.') }}</td>
                </tr>

                {{-- Biaya-biaya --}}
                <tr class="bg-gray-50 font-semibold">
                    <td colspan="3" class="px-4 py-2">Biaya-Biaya:</td>
                </tr>
                <tr><td class="pl-8 py-2">Biaya ATK</td><td></td><td class="text-right px-4">{{ number_format($biaya_atk ?? 0, 2, ',', '.') }}</td></tr>
                <tr><td class="pl-8 py-2">Biaya Gaji</td><td></td><td class="text-right px-4">{{ number_format($biaya_gaji ?? 0, 2, ',', '.') }}</td></tr>
                <tr><td class="pl-8 py-2">Biaya Transportasi</td><td></td><td class="text-right px-4">{{ number_format($biaya_transportasi ?? 0, 2, ',', '.') }}</td></tr>
                <tr><td class="pl-8 py-2">Biaya Internet</td><td></td><td class="text-right px-4">{{ number_format($biaya_internet ?? 0, 2, ',', '.') }}</td></tr>
                <tr><td class="pl-8 py-2">Biaya Lainnya</td><td></td><td class="text-right px-4">{{ number_format($biaya_lainnya ?? 0, 2, ',', '.') }}</td></tr>
                <tr><td class="pl-8 py-2">Biaya Service Komputer & Aplikasi</td><td></td><td class="text-right px-4">{{ number_format($biaya_service ?? 0, 2, ',', '.') }}</td></tr>

                {{-- Total Biaya --}}
                <tr class="bg-gray-100 font-bold border-t">
                    <td colspan="2" class="px-4 py-2">Total Biaya</td>
                    <td class="px-4 py-2 text-right">{{ number_format($total_biaya ?? 0, 2, ',', '.') }}</td>
                </tr>

                {{-- Laba/Rugi --}}
                <tr class="bg-blue-100 font-extrabold border-t">
                    <td colspan="2" class="px-4 py-2">Laba (Rugi)</td>
                    <td class="px-4 py-2 text-right">{{ number_format($laba_rugi ?? 0, 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
