@extends('layout.main')

@section('title', 'Laporan Laba Rugi')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="bg-white shadow-lg rounded-2xl p-6 border border-slate-200">

        {{-- Header (Hanya penyesuaian warna teks agar kontras dengan gradien) --}}
        <div class="mb-6 flex justify-center">
            <div class="w-full rounded-xl shadow-soft-md bg-gradient-to-tl from-purple-700 to-pink-500 px-8 py-6 flex flex-col items-center" style="max-width: 98vw;">
                
                <span class="inline-block bg-white bg-opacity-20 p-2 rounded-lg mb-2">
                    <i class="fa-solid fa-chart-line text-2xl text-slate-700"></i>
                </span>
                
                <h2 class="text-2xl font-bold text-slate-700 tracking-tight mb-1 drop-shadow">
                    Laporan Laba Rugi
                </h2>

                @if(isset($month) && isset($year))
                    @if($month == 'all')
                    <span class="text-base font-semibold">
                        Periode: Januari – Desember {{ $year }}
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
        <form method="GET" class="flex flex-wrap items-end gap-4 mb-6 justify-between">
            <div class="flex gap-4 items-end flex-wrap">
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">Bulan</label>
                    <select name="month" class="border rounded-lg px-3 py-2 w-40 focus:border-slate-700 focus:ring-slate-700">
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
                    <label class="block text-sm font-medium mb-1 text-gray-700">Tahun</label>
                    <select name="year" class="border rounded-lg px-3 py-2 w-32 focus:border-slate-700 focus:ring-slate-700">
                        <option value="">Pilih Tahun</option>
                        @foreach (range(date('Y')-5, date('Y')) as $y)
                        <option value="{{ $y }}" {{ (isset($year) && $y == $year) ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tombol Filter, Reset, dan Print (Kelas disesuaikan) --}}
                <div class="flex gap-2">
                    <button type="submit"
                        class="bg-slate-700 text-white px-4 py-2 rounded-lg hover:bg-slate-800 transition text-sm font-medium">
                        Filter
                    </button>

                    <a href="{{ route('profit.loss.index') }}"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition text-sm font-medium">
                        Reset
                    </a>

                    <a href="{{ route('profit.loss.print', ['month' => $month ?? '', 'year' => $year ?? '']) }}" target="_blank"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm font-medium">
                        Cetak
                    </a>
                </div>
            </div>
        </form>

        {{-- Table Laporan (Sesuai Acuan: border border-slate-200 shadow rounded-2xl bg-white) --}}
        <div class="border border-slate-200 shadow rounded-2xl bg-white">
            <div class="overflow-x-auto">
                <table class="items-center w-full text-slate-600 border-collapse min-w-[600px]">
                    <tbody>
                        {{-- Pendapatan --}}
                        <tr class="bg-slate-100 font-bold border-b border-slate-200">
                            <td class="px-6 py-3 border-r border-slate-200">Pendapatan</td>
                            <td class="px-6 py-3 border-r border-slate-200 text-right font-normal"></td>
                            <td class="px-6 py-3 text-right">
                                Rp {{ number_format($pendapatan ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>

                        {{-- Biaya-biaya --}}
                        <tr class="bg-gray-50 font-semibold border-b border-slate-200">
                            <td class="px-6 py-3 border-r border-slate-200">Biaya-Biaya:</td>
                            <td class="px-6 py-3 border-r border-slate-200"></td>
                            <td class="px-6 py-3"></td>
                        </tr>
                        
                        {{-- Detail Biaya --}}
                        <tr class="hover:bg-slate-50 border-b border-slate-100">
                            <td class="px-6 py-2"></td>
                            <td class="px-6 py-2 border-r border-slate-200">Biaya ATK</td>
                            <td class="px-6 py-2 text-right">({{ number_format($biaya_atk ?? 0, 0, ',', '.') }})</td>
                        </tr>
                        <tr class="hover:bg-slate-50 border-b border-slate-100">
                            <td class="px-6 py-2"></td>
                            <td class="px-6 py-2 border-r border-slate-200">Biaya Gaji</td>
                            <td class="px-6 py-2 text-right">({{ number_format($biaya_gaji ?? 0, 0, ',', '.') }})</td>
                        </tr>
                        <tr class="hover:bg-slate-50 border-b border-slate-100">
                            <td class="px-6 py-2"></td>
                            <td class="px-6 py-2 border-r border-slate-200">Biaya Transportasi</td>
                            <td class="px-6 py-2 text-right">({{ number_format($biaya_transportasi ?? 0, 0, ',', '.') }})</td>
                        </tr>
                        <tr class="hover:bg-slate-50 border-b border-slate-100">
                            <td class="px-6 py-2"></td>
                            <td class="px-6 py-2 border-r border-slate-200">Biaya Internet</td>
                            <td class="px-6 py-2 text-right">({{ number_format($biaya_internet ?? 0, 0, ',', '.') }})</td>
                        </tr>
                        <tr class="hover:bg-slate-50 border-b border-slate-100">
                            <td class="px-6 py-2"></td>
                            <td class="px-6 py-2 border-r border-slate-200">Biaya Lainnya</td>
                            <td class="px-6 py-2 text-right">({{ number_format($biaya_lainnya ?? 0, 0, ',', '.') }})</td>
                        </tr>
                        <tr class="hover:bg-slate-50 border-b border-slate-100">
                            <td class="px-6 py-2"></td>
                            <td class="px-6 py-2 border-r border-slate-200">Biaya Service Komputer & Aplikasi</td>
                            <td class="px-6 py-2 text-right">({{ number_format($biaya_service ?? 0, 0, ',', '.') }})</td>
                        </tr>

                        {{-- Total Biaya --}}
                        <tr class="bg-gray-100 font-bold border-t border-b border-slate-200">
                            <td class="px-6 py-3 border-r border-slate-200">Total Biaya</td>
                            <td class="px-6 py-3 border-r border-slate-200"></td>
                            <td class="px-6 py-3 text-right">
                                (Rp {{ number_format($total_biaya ?? 0, 0, ',', '.') }})
                            </td>
                        </tr>

                        {{-- Laba/Rugi (Hasil Akhir) --}}
                        @php
                            $laba_rugi_val = $laba_rugi ?? 0;
                            $is_laba = $laba_rugi_val >= 0;
                            $row_class = $is_laba ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                            $text_result = $is_laba ? 'Laba Bersih' : 'Rugi Bersih';
                            $formatted_result = number_format(abs($laba_rugi_val), 0, ',', '.');
                        @endphp
                        <tr class="{{ $row_class }} font-extrabold border-t-2 border-slate-700">
                            <td class="px-6 py-4 border-r border-slate-200 uppercase">{{ $text_result }}</td>
                            <td class="px-6 py-4 border-r border-slate-200 text-right font-normal"></td>
                            <td class="px-6 py-4 text-right">
                                Rp {{ $formatted_result }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection