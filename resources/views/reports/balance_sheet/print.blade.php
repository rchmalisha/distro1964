<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Neraca {{ $year }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 6px;
        }

        th {
            background: #ddd;
        }

        h2,
        h4 {
            text-align: center;
            margin: 0;
        }
    </style>
</head>

<body onload="window.print()">
    <h2><strong>Neraca</strong></h2>
    @if($month == 'all')
    <h4>Periode: Januari - Desember {{ $year }}</h4>
    @else
    <h4>Periode: {{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }}</h4>
    @endif

    <table>
        <thead>
            <tr>
                <th>Aset</th>
                <th>Jumlah (Rp)</th>
                <th>Liabilitas & Ekuitas</th>
                <th>Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2"><strong>Aset Lancar</strong></td>
                <td colspan="2"><strong>Liabilitas</strong></td>
            </tr>
            @php $max1 = max(count($aset_lancar), count($liabilitas)); @endphp
            @for ($i = 0; $i < $max1; $i++)
                <tr>
                <td>{{ $aset_lancar[$i]->nama_akun ?? '' }}</td>
                <td align="right">{{ isset($aset_lancar[$i]) ? number_format($aset_lancar[$i]->saldo, 2, ',', '.') : '' }}</td>
                <td>{{ $liabilitas[$i]->nama_akun ?? '' }}</td>
                <td align="right">{{ isset($liabilitas[$i]) ? number_format($liabilitas[$i]->saldo, 2, ',', '.') : '' }}</td>
                </tr>
                @endfor
                <tr>
                    <td><strong>Total Aset Lancar</strong></td>
                    <td align="right"><strong>{{ number_format($total_aset_lancar, 2, ',', '.') }}</strong></td>
                    <td><strong>Total Liabilitas</strong></td>
                    <td align="right"><strong>{{ number_format($total_liabilitas, 2, ',', '.') }}</strong></td>
                </tr>

                <tr>
                    <td colspan="2"><strong>Aset Tetap</strong></td>
                    <td colspan="2"><strong>Ekuitas</strong></td>
                </tr>
                @php $max2 = max(count($aset_tetap), count($ekuitas)); @endphp
                @for ($i = 0; $i < $max2; $i++)
                    <tr>
                    <td>{{ $aset_tetap[$i]->nama_akun ?? '' }}</td>
                    <td align="right">{{ isset($aset_tetap[$i]) ? number_format($aset_tetap[$i]->saldo, 2, ',', '.') : '' }}</td>
                    <td>{{ $ekuitas[$i]->nama_akun ?? '' }}</td>
                    <td align="right">{{ isset($ekuitas[$i]) ? number_format($ekuitas[$i]->saldo, 2, ',', '.') : '' }}</td>
                    </tr>
                    @endfor

                    <tr>
                        <td><strong>Total Aset Tetap</strong></td>
                        <td align="right"><strong>{{ number_format($total_aset_tetap, 2, ',', '.') }}</strong></td>
                        <td><strong>Total Ekuitas</strong></td>
                        <td align="right"><strong>{{ number_format($total_ekuitas, 2, ',', '.') }}</strong></td>
                    </tr>

                    <tr>
                        <td><strong>Total Aset</strong></td>
                        <td align="right"><strong>{{ number_format($total_aset, 2, ',', '.') }}</strong></td>
                        <td><strong>Total Liabilitas & Ekuitas</strong></td>
                        <td align="right"><strong>{{ number_format($total_liabilitas_ekuitas, 2, ',', '.') }}</strong></td>
                    </tr>
        </tbody>
    </table>
</body>

</html>