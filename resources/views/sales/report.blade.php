<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        /* Ukuran kertas A4 */
        @page {
            size: A4;
            margin: 20mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
            margin: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 2px 0 0 0;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 6px 8px;
            text-align: center;
        }

        tfoot td {
            font-weight: bold;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PENJUALAN DISTRO 1964</h1>
        <p>Periode: {{ $start_date }} s/d {{ $end_date }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode Jual</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Total Harga</th>
                <th>Biaya Lainnya</th>
                <th>Potongan Harga</th>
                <th>Total Akhir</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $totalHarga = 0;
                $totalBiayaLain = 0;
                $totalPotongan = 0;
                $totalAkhir = 0;
            @endphp

            @foreach($sales as $sale)
                @php
                    $detailCount = $sale->detailOrders->count(); // jumlah baris untuk rowspan
                @endphp

                @foreach($sale->detailOrders as $index => $detail)
                    @php
                        // Hitung total
                        $totalHarga += $detail->subtotal;
                        if($index === 0){
                            $totalBiayaLain += $sale->order->biaya_lainnya ?? 0;
                            $totalPotongan += $sale->order->potongan_harga ?? 0;
                            $totalAkhir += $sale->total_akhir;
                        }
                    @endphp

                    <tr>
                        @if($index === 0)
                            <td rowspan="{{ $detailCount }}">{{ $no++ }}</td>
                            <td rowspan="{{ $detailCount }}">{{ date('d-m-Y', strtotime($sale->tgl_transaksi)) }}</td>
                            <td rowspan="{{ $detailCount }}">{{ $sale->kode_jual }}</td>
                        @endif

                        <td class="text-left">{{ $detail->service->nama_barang }}</td>
                        <td>{{ $detail->jumlah_pesan }}</td>
                        <td class="text-right">Rp {{ number_format($detail->subtotal,0,',','.') }}</td>

                        @if($index === 0)
                            <td rowspan="{{ $detailCount }}" class="text-right">Rp {{ number_format($sale->order->biaya_lainnya ?? 0,0,',','.') }}</td>
                            <td rowspan="{{ $detailCount }}" class="text-right">Rp {{ number_format($sale->order->potongan_harga ?? 0,0,',','.') }}</td>
                            <td rowspan="{{ $detailCount }}" class="text-right">Rp {{ number_format($sale->total_akhir,0,',','.') }}</td>
                        @endif
                    </tr>
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right">Jumlah</td>
                <td class="text-right">Rp {{ number_format($totalHarga,0,',','.') }}</td>
                <td class="text-right">Rp {{ number_format($totalBiayaLain,0,',','.') }}</td>
                <td class="text-right">Rp {{ number_format($totalPotongan,0,',','.') }}</td>
                <td class="text-right">Rp {{ number_format($totalAkhir,0,',','.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
