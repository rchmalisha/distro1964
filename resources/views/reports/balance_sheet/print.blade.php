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

        h1, h2, h4 {
            text-align: center;
            margin: 0;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>

<body onload="window.print()">
    <h1><strong>DISTRO 1964</strong></h1>
    <h2><strong>Neraca</strong></h2>

    @php
        \Carbon\Carbon::setLocale('id'); // pastikan locale Indonesia
    @endphp

    @if($month == 'all')
        <h4>Periode: Januari - Desember {{ $year }}</h4>
    @else
        @php
            $monthName = \Carbon\Carbon::createFromDate($year, $month, 1)
                ->locale('id')
                ->translatedFormat('F Y'); // menampilkan bulan dalam bahasa Indonesia
        @endphp
        <h4>Periode: {{ $monthName }}</h4>
    @endif

    <table>
        <thead>
            <tr>
                <th>Aset</th>
                <th class="text-right">Jumlah (Rp)</th>
                <th>Liabilitas & Ekuitas</th>
                <th class="text-right">Jumlah (Rp)</th>
            </tr>
        </thead>

        <tbody>
            {{-- ================= ASET LANCAR & LIABILITAS ================= --}}
            <tr class="bold">
                <td colspan="2">Aset Lancar</td>
                <td colspan="2">Liabilitas</td>
            </tr>

            @php
            $maxRows = max(count($aset_lancar), count($liabilitas));
            @endphp

            @for ($i = 0; $i < $maxRows; $i++)
                <tr>
                    <td class="pl-10">{{ $aset_lancar[$i]->nama_akun ?? '' }}</td>
                    <td class="text-right">
                        @if(isset($aset_lancar[$i]))
                            @php $saldo = $aset_lancar[$i]->saldo; @endphp
                            @if($saldo < 0)
                                (Rp {{ number_format(abs($saldo), 0, ',', '.') }})
                            @else
                                Rp {{ number_format($saldo, 0, ',', '.') }}
                            @endif
                        @endif
                    </td>
                    <td class="pl-10">{{ $liabilitas[$i]->nama_akun ?? '' }}</td>
                    <td class="text-right">
                        @if(isset($liabilitas[$i]))
                            @php $saldo = $liabilitas[$i]->saldo; @endphp
                            @if($saldo < 0)
                                (Rp {{ number_format(abs($saldo), 0, ',', '.') }})
                            @else
                                Rp {{ number_format($saldo, 0, ',', '.') }}
                            @endif
                        @endif
                    </td>
                </tr>
            @endfor

            {{-- Total Aset Lancar & Liabilitas --}}
            <tr class="bold">
                <td>Total Aset Lancar</td>
                <td class="text-right">Rp {{ number_format($total_aset_lancar, 0, ',', '.') }}</td>
                <td>Total Liabilitas</td>
                <td class="text-right">Rp {{ number_format($total_liabilitas, 0, ',', '.') }}</td>
            </tr>

            {{-- ================= ASET TETAP & EKUITAS ================= --}}
            <tr class="bold">
                <td colspan="2">Aset Tetap</td>
                <td colspan="2">Ekuitas</td>
            </tr>

            @php
            $maxRows2 = max(count($aset_tetap), count($ekuitas));
            @endphp

            @for ($i = 0; $i < $maxRows2; $i++)
                <tr>
                    <td class="pl-10">{{ $aset_tetap[$i]->nama_akun ?? '' }}</td>
                    <td class="text-right">
                        @if(isset($aset_tetap[$i]))
                            @php
                            $saldo = $aset_tetap[$i]->saldo;
                            $isKontraAset = in_array($aset_tetap[$i]->kode_akun, ['1301']); 
                            if($isKontraAset && $saldo > 0){
                                $saldo = -1 * $saldo;
                            }
                            @endphp
                            @if($saldo < 0)
                                (Rp {{ number_format(abs($saldo), 0, ',', '.') }})
                            @else
                                Rp {{ number_format($saldo, 0, ',', '.') }}
                            @endif
                        @endif
                    </td>
                    <td class="pl-10">{{ $ekuitas[$i]->nama_akun ?? '' }}</td>
                    <td class="text-right">
                        @if(isset($ekuitas[$i]))
                            @php
                            $saldo = $ekuitas[$i]->saldo;
                            $isPrive = $ekuitas[$i]->kode_akun === '3103';
                            if($isPrive && $saldo > 0){
                                $saldo = -1 * $saldo;
                            }
                            @endphp
                            @if($saldo < 0)
                                (Rp {{ number_format(abs($saldo), 0, ',', '.') }})
                            @else
                                Rp {{ number_format($saldo, 0, ',', '.') }}
                            @endif
                        @endif
                    </td>
                </tr>
            @endfor

            {{-- Total Aset Tetap & Ekuitas --}}
            <tr class="bold">
                <td>Total Aset Tetap</td>
                <td class="text-right">Rp {{ number_format($total_aset_tetap, 0, ',', '.') }}</td>
                <td>Total Ekuitas</td>
                <td class="text-right">Rp {{ number_format($total_ekuitas, 0, ',', '.') }}</td>
            </tr>

            {{-- ================= TOTAL ================= --}}
            <tr class="bold">
                <td>Total Aset</td>
                <td class="text-right">Rp {{ number_format($total_aset, 0, ',', '.') }}</td>
                <td>Total Liabilitas & Ekuitas</td>
                <td class="text-right">Rp {{ number_format($total_liabilitas_ekuitas, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
