<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Laba Rugi - DISTRO 1964</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 40px;
            color: #333;
        }
        h2, h3, h4 {
            text-align: center;
            margin: 2px 0; /* Jarak antarjudul jadi lebih rapat */
            line-height: 1.2;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px; /* Jarak antara judul dan tabel sedikit saja */
        }
        th, td {
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
        <tbody>
            {{-- Pendapatan --}}
            <tr class="bg-gray-100">
                <td>Pendapatan</td>
                <td class="text-right">{{ number_format($pendapatan ?? 0, 2, ',', '.') }}</td>
            </tr>

            {{-- Biaya-biaya --}}
            <tr>
                <td colspan="2"><strong>Biaya-Biaya:</strong></td>
            </tr>
            <tr><td class="pl-8">Biaya ATK</td><td class="text-right">{{ number_format($biaya_atk ?? 0, 2, ',', '.') }}</td></tr>
            <tr><td class="pl-8">Biaya Gaji</td><td class="text-right">{{ number_format($biaya_gaji ?? 0, 2, ',', '.') }}</td></tr>
            <tr><td class="pl-8">Biaya Transportasi</td><td class="text-right">{{ number_format($biaya_transportasi ?? 0, 2, ',', '.') }}</td></tr>
            <tr><td class="pl-8">Biaya Internet</td><td class="text-right">{{ number_format($biaya_internet ?? 0, 2, ',', '.') }}</td></tr>
            <tr><td class="pl-8">Biaya Lainnya</td><td class="text-right">{{ number_format($biaya_lainnya ?? 0, 2, ',', '.') }}</td></tr>
            <tr><td class="pl-8">Biaya Service Komputer & Aplikasi</td><td class="text-right">{{ number_format($biaya_service ?? 0, 2, ',', '.') }}</td></tr>

            {{-- Total Biaya --}}
            <tr class="bg-gray-100">
                <td>Total Biaya</td>
                <td class="text-right">{{ number_format($total_biaya ?? 0, 2, ',', '.') }}</td>
            </tr>

            {{-- Laba/Rugi --}}
            <tr class="bg-blue-100">
                <td>Laba (Rugi)</td>
                <td class="text-right">{{ number_format($laba_rugi ?? 0, 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

</body>
</html>
