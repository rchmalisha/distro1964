<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Laba Rugi - DISTRO 1964</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h2,
        h3,
        h4 {
            text-align: center;
            margin: 2px 0;
            /* Jarak antarjudul jadi lebih rapat */
            line-height: 1.2;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            /* Jarak antara judul dan tabel sedikit saja */
        }

        th,
        td {
            border: 1px solid #999;
            padding: 8px 10px;
            font-size: 14px;
        }

        th {
            background: #f2f2f2;
        }

        tr.bg-gray-100 {
            background: #f2f2f2;
            font-weight: bold;
        }

        tr.bg-blue-100 {
            background: #dbeafe;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .pl-8 {
            padding-left: 32px;
        }
    </style>
</head>

<body onload="window.print()">

    <h2><strong>DISTRO 1964</strong></h2>
    <h3><strong>Laporan Laba Rugi</strong></h3>

    @if(isset($month) && isset($year))
    @if($month == 'all')
    <h4>Periode: Januari – Desember {{ $year }}</h4>
    @else
    <h4>Periode: {{ \Carbon\Carbon::createFromDate($year, (int)$month, 1)->locale('id')->translatedFormat('F Y') }}</h4>
    @endif
    @endif

    <h4>(dalam Rupiah)</h4>

    <table>
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
            <tr style="font-weight: bold; background: #f2f2f2;">
                <td class="px-6 py-3">Pendapatan</td>
                <td class="px-6 py-3"></td>
                <td class="px-6 py-3 text-right">
                    {{ number_format($pendapatan ?? 0, 0, ',', '.') }}
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
                    {{ number_format($total_bahan_habis_pakai, 0, ',', '.') }}
                </td>
            </tr>

            <tr>
                <td class="px-14 py-2">DTF</td>
                <td class="px-6 py-2 text-right">
                    {{ number_format($biaya_dtf ?? 0, 0, ',', '.') }}
                </td>
                <td></td>
            </tr>

            <tr>
                <td class="px-14 py-2">Polyflex Standar</td>
                <td class="px-6 py-2 text-right">
                    {{ number_format($biaya_polyflex_standar ?? 0, 0, ',', '.') }}
                </td>
                <td></td>
            </tr>

            <tr>
                <td class="px-14 py-2">Polyflex Stabilo</td>
                <td class="px-6 py-2 text-right">
                    {{ number_format($biaya_polyflex_stabilo ?? 0, 0, ',', '.') }}
                </td>
                <td></td>
            </tr>

            <tr>
                <td class="px-14 py-2">Polyflex Reflective</td>
                <td class="px-6 py-2 text-right">
                    {{ number_format($biaya_polyflex_reflective ?? 0, 0, ',', '.') }}
                </td>
                <td></td>
            </tr>

            <tr>
                <td class="px-14 py-2">Polyflex Gold</td>
                <td class="px-6 py-2 text-right">
                    {{ number_format($biaya_polyflex_gold ?? 0, 0, ',', '.') }}
                </td>
                <td></td>
            </tr>

            <tr>
                <td class="px-14 py-2">Polyflex Silver</td>
                <td class="px-6 py-2 text-right">
                    {{ number_format($biaya_polyflex_silver ?? 0, 0, ',', '.') }}
                </td>
                <td></td>
            </tr>

            <tr>
                <td class="px-14 py-2">Kantong Kresek</td>
                <td class="px-6 py-2 text-right">
                    {{ number_format($biaya_kantong_kresek ?? 0, 0, ',', '.') }}
                </td>
                <td></td>
            </tr>

            {{-- Biaya Operasional --}}
            <tr class="font-semibold">
                <td class="px-10 py-2">Biaya Operasional</td>
                <td class="px-6 py-2"></td>
                <td class="px-6 py-2 text-right">
                    {{ number_format($total_operasional, 0, ',', '.') }}
                </td>
            </tr>

            <tr>
                <td class="px-14 py-2">Listrik & Internet</td>
                <td class="px-6 py-2 text-right">
                    {{ number_format($biaya_listrik_internet ?? 0, 0, ',', '.') }}
                </td>
                <td></td>
            </tr>

            <tr>
                <td class="px-14 py-2">Tinta Printer DTF</td>
                <td class="px-6 py-2 text-right">
                    {{ number_format($biaya_tinta_dtf ?? 0, 0, ',', '.') }}
                </td>
                <td></td>
            </tr>

            {{-- Total Biaya --}}
            <tr class="bg-gray-100 font-bold border-t">
                <td class="px-6 py-3">Total Biaya</td>
                <td class="px-6 py-3"></td>
                <td class="px-6 py-3 text-right">
                    {{ number_format($total_biaya ?? ($total_bahan_habis_pakai + $total_operasional), 0, ',', '.') }}
                </td>
            </tr>

            {{-- Laba / Rugi --}}
            @php
            $hasil = $laba_rugi ?? (($pendapatan ?? 0) - ($total_bahan_habis_pakai + $total_operasional));
            @endphp
            <tr style="font-weight: bold; background: #f2f2f2;">
             <td class="px-6 py-4">
                {{ $hasil >= 0 ? 'Laba Bersih' : 'Rugi Bersih' }}
            </td>
            <td class="px-6 py-4"></td>
            <td class="px-6 py-4 text-right">
                {{ number_format(abs($hasil), 0, ',', '.') }}
            </td>
            </tr>
        </tbody>
    </table>

</body>

</html>