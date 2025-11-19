@extends('layout.main')

@section('title', 'Neraca')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="bg-white shadow-lg rounded-2xl p-6 border border-slate-200">

        {{-- Header --}}
        <div class="mb-6 flex justify-center">
            <div class="w-full rounded-xl shadow-soft-md bg-gradient-to-tl from-purple-700 to-pink-500 px-8 py-6 flex flex-col items-center" style="max-width: 98vw;">
                <span class="inline-block bg-white bg-opacity-20 p-2 rounded-lg mb-2">
                    <i class="fa-solid fa-balance-scale text-2xl text-slate-700"></i>
                </span>
                
                <h2 class="text-2xl font-bold text-slate-700 tracking-tight mb-1 drop-shadow">
                    Neraca
                </h2>
                
                @if(isset($month) && isset($year))
                @if($month == 'all')
                <span class="text-base font-semibold ">
                    Periode: Januari - Desember {{ $year }}
                </span>
                @else
                <span class="text-base font-semibold ">
                    Periode: {{ \Carbon\Carbon::createFromDate($year, $month, 1)->locale('id')->translatedFormat('F Y') }}
                </span>
                @endif
                <span class="text-sm">(dalam Rupiah)</span>
                @endif
            </div>
        </div>

        {{-- Filter Bulan & Tahun (Kelas disesuaikan) --}}
        <form method="GET" class="flex flex-wrap items-end gap-4 mb-6 justify-between no-print">
            <div class="flex gap-4 items-end flex-wrap">
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">Bulan</label>
                    <select name="month" class="border rounded-lg px-3 py-2 w-40 focus:border-slate-700 focus:ring-slate-700">
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
                    <label class="block text-sm font-medium mb-1 text-gray-700">Tahun</label>
                    <select name="year" class="border rounded-lg px-3 py-2 w-32 focus:border-slate-700 focus:ring-slate-700">
                        <option value="">-- Pilih Tahun --</option>
                        @foreach (range(date('Y')-5, date('Y')) as $y)
                        <option value="{{ $y }}" {{ (isset($year) && $y == $year) ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit"
                        class="bg-slate-700 text-white px-4 py-2 rounded-lg hover:bg-slate-800 transition text-sm font-medium">
                        Filter
                    </button>
                    <a href="{{ route('balance.sheet.index') }}"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition text-sm font-medium">
                        Reset
                    </a>
                    <a href="{{ route('balance.sheet.print', ['month' => $month ?? '', 'year' => $year ?? '']) }}" target="_blank"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm font-medium no-print">
                        Cetak
                    </a>
                </div>
            </div>
        </form>

        {{-- Table Laporan (Sesuai Acuan: border border-slate-200 shadow rounded-2xl bg-white) --}}
        <div class="border border-slate-200 shadow rounded-2xl bg-white">
            <div class="overflow-x-auto">
                <table class="items-center w-full text-slate-600 border-collapse min-w-[800px]">
                    <thead class="bg-slate-100 text-sm uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-3 border border-slate-200 text-left w-1/4">Aset</th>
                            <th class="px-6 py-3 border border-slate-200 text-right w-1/4">Jumlah (Rp)</th>
                            <th class="px-6 py-3 border border-slate-200 text-left w-1/4">Liabilitas & Ekuitas</th>
                            <th class="px-6 py-3 border border-slate-200 text-right w-1/4">Jumlah (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Aset Lancar & Liabilitas --}}
                        <tr class="bg-gray-50 font-semibold border-b border-slate-200">
                            <td colspan="2" class="px-6 py-3 border-r border-slate-200">Aset Lancar</td>
                            <td colspan="2" class="px-6 py-3">Liabilitas</td>
                        </tr>

                        @php
                        $maxRows = max(count($aset_lancar), count($liabilitas));
                        @endphp

                        @for ($i = 0; $i < $maxRows; $i++)
                            <tr class="border-b border-slate-100 hover:bg-slate-50">
                                <td class="pl-10 py-2 border-r border-slate-200">
                                    {{ $aset_lancar[$i]->nama_akun ?? '' }}
                                </td>
                                <td class="text-right px-6 py-2 border-r border-slate-200">
                                    {{ isset($aset_lancar[$i]) ? 'Rp ' . number_format($aset_lancar[$i]->saldo, 0, ',', '.') : '' }}
                                </td>
                                <td class="pl-10 py-2 border-r border-slate-200">
                                    {{ $liabilitas[$i]->nama_akun ?? '' }}
                                </td>
                                <td class="text-right px-6 py-2">
                                    {{ isset($liabilitas[$i]) ? 'Rp ' . number_format($liabilitas[$i]->saldo, 0, ',', '.') : '' }}
                                </td>
                            </tr>
                        @endfor

                        <tr class="bg-slate-100 font-bold border-t border-b border-slate-200">
                            <td class="px-6 py-3 border-r border-slate-200">Total Aset Lancar</td>
                            <td class="text-right px-6 py-3 border-r border-slate-200">Rp {{ number_format($total_aset_lancar, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 border-r border-slate-200">Total Liabilitas</td>
                            <td class="text-right px-6 py-3">Rp {{ number_format($total_liabilitas, 0, ',', '.') }}</td>
                        </tr>

                        <tr class="bg-gray-50 font-semibold border-t border-slate-200">
                            <td colspan="2" class="px-6 py-3 border-r border-slate-200">Aset Tetap</td>
                            <td colspan="2" class="px-6 py-3">Ekuitas</td>
                        </tr>

                        @php
                        $maxRows2 = max(count($aset_tetap), count($ekuitas));
                        @endphp

                        @for ($i = 0; $i < $maxRows2; $i++)
                            <tr class="border-b border-slate-100 hover:bg-slate-50">
                                <td class="pl-10 py-2 border-r border-slate-200">{{ $aset_tetap[$i]->nama_akun ?? '' }}</td>
                                <td class="text-right px-6 py-2 border-r border-slate-200">{{ isset($aset_tetap[$i]) ? 'Rp ' . number_format($aset_tetap[$i]->saldo, 0, ',', '.') : '' }}</td>
                                <td class="pl-10 py-2 border-r border-slate-200">{{ $ekuitas[$i]->nama_akun ?? '' }}</td>
                                <td class="text-right px-6 py-2">{{ isset($ekuitas[$i]) ? 'Rp ' . number_format($ekuitas[$i]->saldo, 0, ',', '.') : '' }}</td>
                            </tr>
                        @endfor

                        <tr class="bg-slate-100 font-bold border-t border-b border-slate-200">
                            <td class="px-6 py-3 border-r border-slate-200">Total Aset Tetap</td>
                            <td class="text-right px-6 py-3 border-r border-slate-200">Rp {{ number_format($total_aset_tetap, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 border-r border-slate-200">Total Ekuitas</td>
                            <td class="text-right px-6 py-3">Rp {{ number_format($total_ekuitas, 0, ',', '.') }}</td>
                        </tr>

                        <tr class="bg-blue-100 font-extrabold border-t-2 border-slate-700">
                            <td class="px-6 py-4 border-r border-slate-200 uppercase">Total Aset</td>
                            <td class="text-right px-6 py-4 border-r border-slate-200">Rp {{ number_format($total_aset, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 border-r border-slate-200 uppercase">Total Liabilitas & Ekuitas</td>
                            <td class="text-right px-6 py-4">Rp {{ number_format($total_liabilitas_ekuitas, 0, ',', '.') }}</td>
                        </tr>

                        <tr class="{{ $total_aset == $total_liabilitas_ekuitas ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} font-extrabold text-center border-t-2 border-slate-700">
                            <td colspan="4" class="py-4">
                                {{ $total_aset == $total_liabilitas_ekuitas ? '✅ Neraca Seimbang' : '⚠️ Neraca Tidak Seimbang. Selisih: Rp ' . number_format(abs($total_aset - $total_liabilitas_ekuitas), 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

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