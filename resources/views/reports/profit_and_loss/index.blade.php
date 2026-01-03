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

        @if(!empty($month) && !empty($year))
        {{-- Table Laporan --}}
        <div class="border border-slate-200 shadow rounded-2xl bg-white">
            <div class="overflow-x-auto">
                <table class="items-center w-full text-slate-600 border-collapse min-w-[600px]">
                    <thead>
                        <tr class="bg-slate-200 text-slate-700 text-sm uppercase font-semibold">
                            <th class="px-6 py-3 text-left border-b border-slate-300">
                                Keterangan
                            </th>
                            <th class="px-6 py-3 text-right border-b border-slate-300">
                                Harga
                            </th>
                            <th class="px-6 py-3 text-center border-b border-slate-300">
                                Jumlah
                            </th>
                        </tr>
                    </thead>


                    <tbody>
                        {{-- Pendapatan --}}
                        <tr class="bg-slate-100 font-bold border-b">
                            <td class="px-6 py-3">Pendapatan</td>
                            <td class="px-6 py-3"></td>
                            <td class="px-6 py-3 text-right">
                                Rp {{ number_format($pendapatan ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>

                        {{-- Biaya-biaya --}}
                        <tr class="bg-gray-50 font-semibold border-b">
                            <td class="px-6 py-3">Biaya-Biaya</td>
                            <td class="px-6 py-3"></td>
                            <td class="px-6 py-3"></td>
                        </tr>

                        @php
                        $total_bahan_habis_pakai =
                        ($biaya_dtf ?? 0) +
                        ($biaya_polyflex_standar ?? 0) +
                        ($biaya_polyflex_stabilo ?? 0) +
                        ($biaya_polyflex_reflective ?? 0) +
                        ($biaya_polyflex_gold ?? 0) +
                        ($biaya_polyflex_silver ?? 0) +
                        ($biaya_kantong_kresek ?? 0);

                        $total_operasional =
                        ($biaya_listrik_internet ?? 0) +
                        ($biaya_tinta_dtf ?? 0);
                        @endphp

                        {{-- Biaya Bahan Habis Pakai --}}
                        <tr class="font-semibold">
                            <td class="px-10 py-2">Biaya Bahan Habis Pakai</td>
                            <td class="px-6 py-2"></td>
                            <td class="px-6 py-2 text-right">
                                Rp {{ number_format($total_bahan_habis_pakai, 0, ',', '.') }}
                            </td>
                        </tr>

                        <tr>
                            <td class="px-14 py-2">DTF</td>
                            <td class="px-6 py-2 text-right">
                                Rp {{ number_format($biaya_dtf ?? 0, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>

                        <tr>
                            <td class="px-14 py-2">Polyflex Standar</td>
                            <td class="px-6 py-2 text-right">
                                Rp {{ number_format($biaya_polyflex_standar ?? 0, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>

                        <tr>
                            <td class="px-14 py-2">Polyflex Stabilo</td>
                            <td class="px-6 py-2 text-right">
                                Rp {{ number_format($biaya_polyflex_stabilo ?? 0, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>

                        <tr>
                            <td class="px-14 py-2">Polyflex Reflective</td>
                            <td class="px-6 py-2 text-right">
                                Rp {{ number_format($biaya_polyflex_reflective ?? 0, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>

                        <tr>
                            <td class="px-14 py-2">Polyflex Gold</td>
                            <td class="px-6 py-2 text-right">
                                Rp {{ number_format($biaya_polyflex_gold ?? 0, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>

                        <tr>
                            <td class="px-14 py-2">Polyflex Silver</td>
                            <td class="px-6 py-2 text-right">
                                Rp {{ number_format($biaya_polyflex_silver ?? 0, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>

                        <tr>
                            <td class="px-14 py-2">Kantong Kresek</td>
                            <td class="px-6 py-2 text-right">
                                Rp {{ number_format($biaya_kantong_kresek ?? 0, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>

                        {{-- Biaya Operasional --}}
                        <tr class="font-semibold">
                            <td class="px-10 py-2">Biaya Operasional</td>
                            <td class="px-6 py-2"></td>
                            <td class="px-6 py-2 text-right">
                                Rp {{ number_format($total_operasional, 0, ',', '.') }}
                            </td>
                        </tr>

                        <tr>
                            <td class="px-14 py-2">Listrik & Internet</td>
                            <td class="px-6 py-2 text-right">
                                Rp {{ number_format($biaya_listrik_internet ?? 0, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>

                        <tr>
                            <td class="px-14 py-2">Tinta Printer DTF</td>
                            <td class="px-6 py-2 text-right">
                                Rp {{ number_format($biaya_tinta_dtf ?? 0, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>

                        {{-- Total Biaya --}}
                        <tr class="bg-gray-100 font-bold border-t">
                            <td class="px-6 py-3">Total Biaya</td>
                            <td class="px-6 py-3"></td>
                            <td class="px-6 py-3 text-right">
                                Rp {{ number_format($total_biaya ?? ($total_bahan_habis_pakai + $total_operasional), 0, ',', '.') }}
                            </td>
                        </tr>

                        {{-- Laba / Rugi --}}
                        @php
                        $hasil = $laba_rugi ?? (($pendapatan ?? 0) - ($total_bahan_habis_pakai + $total_operasional));
                        @endphp
                        <tr class="{{ $hasil >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} font-extrabold border-t-2">
                            <td class="px-6 py-4">
                                {{ $hasil >= 0 ? 'Laba Bersih' : 'Rugi Bersih' }}
                            </td>
                            <td class="px-6 py-4"></td>
                            <td class="px-6 py-4 text-right">
                                Rp {{ number_format(abs($hasil), 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>



                </table>
            </div>
        </div>
        @else
        {{-- Tampilkan pesan jika belum ada filter --}}
        <div class="text-center py-10 text-gray-500 border border-slate-200 rounded-2xl bg-white shadow">
            Silakan pilih <strong>Periode Bulan & Tahun</strong> dan tekan tombol
            <strong>Filter</strong> untuk menampilkan Laporan Laba Rugi.
        </div>
        @endif
    </div>

</div>
</div>
@endsection