@extends('layout.main')

@section('title', 'Neraca')

@section('content')
<div class="p-6 bg-white shadow rounded-2xl">

    {{-- Header --}}
    <div class="mb-6 flex justify-center">
        <div class="w-full rounded-xl shadow-soft-md bg-gradient-to-tl from-purple-700 to-pink-500 px-8 py-6 flex flex-col items-center" style="max-width: 98vw;">
            <span class="inline-block bg-white bg-opacity-20 p-3 rounded-lg mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2h6v2m3 4H6a2 2 0 
                        01-2-2V5a2 2 0 
                        012-2h12a2 2 0 
                        012 2v14a2 2 0 
                        01-2 2zM9 9h6v2H9V9z" />
                </svg>
            </span>
            <h2 class="text-2xl font-extrabold text-white tracking-tight mb-1 drop-shadow">
                Neraca
            </h2>
            @if(isset($month) && isset($year))
            @if($month == 'all')
            <span class="text-base font-semibold text-white">
                Periode: Januari - Desember {{ $year }}
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
    <form method="GET" class="flex flex-wrap items-end gap-4 mb-4 justify-between no-print">
        <div class="flex gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700">Bulan</label>
                <select name="month" class="border rounded-lg px-3 py-2 w-40">
                    <option value="">-- Pilih Bulan --</option>
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
                <a href="{{ route('balance.sheet.index') }}"
                    class="inline-block px-6 py-3 font-bold text-center bg-gradient-to-tl from-slate-600 to-slate-300 uppercase transition-all rounded-lg text-xs shadow-soft-md hover:scale-105 active:opacity-85 text-white">
                    Reset
                </a>
                <a href="{{ route('balance.sheet.print', ['month' => $month, 'year' => $year]) }}" target="_blank"
                    class="inline-block px-6 py-3 font-bold text-center bg-gradient-to-tl from-green-600 to-lime-400 uppercase transition-all rounded-lg text-xs shadow-soft-md hover:scale-105 active:opacity-85 text-white no-print">
                    Cetak
                </a>
            </div>
        </div>
    </form>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-[800px] w-full text-sm text-left text-gray-700 border">
            <thead class="bg-gray-100 font-bold">
                <tr>
                    <th class="px-4 py-2 w-1/4">Aset</th>
                    <th class="px-4 py-2 text-right w-1/4">Jumlah (Rp)</th>
                    <th class="px-4 py-2 w-1/4">Liabilitas & Ekuitas</th>
                    <th class="px-4 py-2 text-right w-1/4">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                {{-- Aset Lancar & Liabilitas --}}
                <tr class="bg-gray-50 font-semibold">
                    <td colspan="2" class="px-4 py-2">Aset Lancar</td>
                    <td colspan="2" class="px-4 py-2">Liabilitas</td>
                </tr>

                @php
                $maxRows = max(count($aset_lancar), count($liabilitas));
                @endphp

                @for ($i = 0; $i < $maxRows; $i++)
                    <tr>
                    <td class="pl-8 py-2">
                        {{ $aset_lancar[$i]->nama_akun ?? '' }}
                    </td>
                    <td class="text-right px-4">
                        {{ isset($aset_lancar[$i]) ? number_format($aset_lancar[$i]->saldo, 2, ',', '.') : '' }}
                    </td>
                    <td class="pl-8 py-2">
                        {{ $liabilitas[$i]->nama_akun ?? '' }}
                    </td>
                    <td class="text-right px-4">
                        {{ isset($liabilitas[$i]) ? number_format($liabilitas[$i]->saldo, 2, ',', '.') : '' }}
                    </td>
                    </tr>
                    @endfor

                    <tr class="bg-gray-100 font-bold border-t">
                        <td class="px-4 py-2">Total Aset Lancar</td>
                        <td class="text-right px-4">{{ number_format($total_aset_lancar, 2, ',', '.') }}</td>
                        <td class="px-4 py-2">Total Liabilitas</td>
                        <td class="text-right px-4">{{ number_format($total_liabilitas, 2, ',', '.') }}</td>
                    </tr>

                    <tr class="bg-gray-50 font-semibold border-t">
                        <td colspan="2" class="px-4 py-2">Aset Tetap</td>
                        <td colspan="2" class="px-4 py-2">Ekuitas</td>
                    </tr>

                    @php
                    $maxRows2 = max(count($aset_tetap), count($ekuitas));
                    @endphp

                    @for ($i = 0; $i < $maxRows2; $i++)
                        <tr>
                        <td class="pl-8 py-2">{{ $aset_tetap[$i]->nama_akun ?? '' }}</td>
                        <td class="text-right px-4">{{ isset($aset_tetap[$i]) ? number_format($aset_tetap[$i]->saldo, 2, ',', '.') : '' }}</td>
                        <td class="pl-8 py-2">{{ $ekuitas[$i]->nama_akun ?? '' }}</td>
                        <td class="text-right px-4">{{ isset($ekuitas[$i]) ? number_format($ekuitas[$i]->saldo, 2, ',', '.') : '' }}</td>
                        </tr>
                        @endfor

                        <tr class="bg-gray-100 font-bold border-t">
                            <td class="px-4 py-2">Total Aset Tetap</td>
                            <td class="text-right px-4">{{ number_format($total_aset_tetap, 2, ',', '.') }}</td>
                            <td class="px-4 py-2">Total Ekuitas</td>
                            <td class="text-right px-4">{{ number_format($total_ekuitas, 2, ',', '.') }}</td>
                        </tr>

                        <tr class="bg-blue-100 font-bold border-t-2">
                            <td class="px-4 py-2">Total Aset</td>
                            <td class="text-right px-4">{{ number_format($total_aset, 2, ',', '.') }}</td>
                            <td class="px-4 py-2">Total Liabilitas & Ekuitas</td>
                            <td class="text-right px-4">{{ number_format($total_liabilitas_ekuitas, 2, ',', '.') }}</td>
                        </tr>

                        <tr class="{{ $total_aset == $total_liabilitas_ekuitas ? 'bg-green-100' : 'bg-red-100' }} font-extrabold text-center border-t">
                            <td colspan="4" class="py-3">
                                {{ $total_aset == $total_liabilitas_ekuitas ? '✅ Neraca Seimbang' : '⚠️ Neraca Tidak Seimbang' }}
                            </td>
                        </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- CSS agar elemen dengan class no-print tidak ikut tercetak --}}
<style>
    @media print {
        .no-print {
            display: none !important;
        }

        table {
            font-size: 12px;
            width: 100%;
        }

        body {
            background: white !important;
        }
    }
</style>

@endsection