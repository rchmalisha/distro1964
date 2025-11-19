@extends('layout.main')

@section('title', 'Neraca Saldo')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="bg-white shadow-lg rounded-2xl p-6 border border-slate-200">

        {{-- Header --}}
        <div class="mb-6 flex justify-center">
            <div class="w-full rounded-xl shadow-soft-md bg-gradient-to-tl from-purple-700 to-pink-500 px-8 py-6 flex flex-col items-center" style="max-width: 98vw;">
                
                <span class="inline-block bg-white bg-opacity-20 p-2 rounded-lg mb-2">
                    <i class="fa-solid fa-table-columns text-2xl text-slate-700"></i>
                </span> 
                <h2 class="text-2xl font-bold text-slate-700 tracking-tight mb-1 drop-shadow">Neraca Saldo</h2>
                
                @if(isset($year))
                <span class="text-base font-semibold">
                    Periode: 
                    @if($month == '0' || !$month)
                        Januari s/d Desember {{ $year }}
                    @else
                        {{ \Carbon\Carbon::createFromDate($year, $month, 1)->locale('id')->translatedFormat('F Y') }}
                    @endif
                </span>
                <span class="text-sm">(dalam Rupiah)</span>
                @endif
            </div>
        </div>

        {{-- Filter --}}
        <form method="GET" class="flex flex-wrap items-end gap-4 mb-6 justify-between">
            <div class="flex gap-4 items-end flex-wrap">
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">Bulan</label>
                    <select name="month" class="border rounded-lg px-3 py-2 w-40 focus:border-slate-700 focus:ring-slate-700">
                        <option value="">Pilih Bulan</option>
                        <option value="0" {{ (isset($month) && $month == '0') ? 'selected' : '' }}>Semua Bulan</option>
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

                <div class="flex gap-2">
                    <button type="submit"
                        class="bg-slate-700 text-white px-4 py-2 rounded-lg hover:bg-slate-800 transition text-sm font-medium">
                        Filter
                    </button>
                    <a href="{{ route('trial-balance.index') }}"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition text-sm font-medium">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        {{-- Table --}}
        <div class="border border-slate-200 shadow rounded-2xl bg-white">
            <div class="overflow-x-auto">
                <table class="items-center w-full text-slate-600 border-collapse min-w-[800px]">
                    <thead class="bg-slate-100 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-3 border border-slate-200 text-left">Kode Akun</th>
                            <th class="px-6 py-3 border border-slate-200 text-left">Nama Akun</th>
                            <th class="px-6 py-3 border border-slate-200 text-center">Debet</th>
                            <th class="px-6 py-3 border border-slate-200 text-center">Kredit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalDebit = 0;
                            $totalKredit = 0;
                        @endphp

                        @forelse ($trialBalance as $row)
                            @php
                                $d = $row->debit ?? 0;
                                $k = $row->kredit ?? 0;

                                $totalDebit += $d;
                                $totalKredit += $k;
                            @endphp
                            <tr class="border-b hover:bg-slate-50">
                                <td class="px-6 py-3 border border-slate-200">{{ $row->kode_akun }}</td>
                                <td class="px-6 py-3 border border-slate-200">{{ $row->nama_akun }}</td>
                                <td class="px-6 py-3 border border-slate-200 text-right">
                                    {{ $d ? 'Rp '.number_format($d, 0, ',', '.') : '' }}
                                </td>
                                <td class="px-6 py-3 border border-slate-200 text-right">
                                    {{ $k ? 'Rp '.number_format($k, 0, ',', '.') : '' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-slate-500">
                                    @if(isset($year))
                                        Tidak ada data untuk periode ini
                                    @else
                                        Silakan pilih Bulan dan Tahun untuk menampilkan Neraca Saldo.
                                    @endif
                                </td>
                            </tr>
                        @endforelse

                        @if ($trialBalance->count() > 0)
                            <tr class="bg-slate-100 font-bold border-t">
                                <td colspan="2" class="px-6 py-3 border border-slate-200 text-right uppercase">Total</td>
                                <td class="px-6 py-3 border border-slate-200 text-right">
                                    Rp {{ number_format($totalDebit, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-3 border border-slate-200 text-right">
                                    Rp {{ number_format($totalKredit, 0, ',', '.') }}
                                </td>
                            </tr>

                            @if($totalDebit !== $totalKredit)
                                <tr class="bg-red-50 text-red-700 font-bold">
                                    <td colspan="4" class="px-6 py-3 text-center">
                                        Neraca tidak seimbang!  
                                        Selisih: Rp {{ number_format(abs($totalDebit - $totalKredit), 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endif
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
