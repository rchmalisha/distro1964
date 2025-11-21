<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 10px;
            color: #222;
            margin: 0;
            background: #f4f6f8;
        }

        /* Kontainer yang disesuaikan agar pas pada A4 landscape dan berada di tengah */
        .container {
            width: calc(297mm - 40mm); /* A4 landscape width minus page margins */
            max-width: 100%;
            margin: 18mm auto; /* beri jarak dari tepi untuk preview */
            background: #fff;
            padding: 14px 18px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.08);
            border-radius: 6px;
            overflow-x: hidden; /* nonaktifkan scroll horizontal di preview */
        }

        .header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 12px;
        }

        .brand {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .brand h1 {
            margin: 0;
            font-size: 20px;
            letter-spacing: 0.6px;
            color: #111;
        }

        .brand p {
            margin: 2px 0 0 0;
            font-size: 13px;
            color: #555;
        }

        .meta {
            margin-left: auto;
            text-align: right;
            font-size: 13px;
            color: #444;
        }

        .logo {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg,#333 0%,#777 100%);
            border-radius: 6px;
            display: inline-block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 12px;
            table-layout: fixed; /* agar preview layar menyesuaikan tanpa membuat scrollbar */
        }

        table, th, td {
            border: 1px solid #e2e8f0;
        }

        th {
            background: #0f172a;
            color: #fff;
            font-weight: 600;
            padding: 10px 8px;
            text-align: center;
        }

        /* Pada layar: ijinkan pembungkusan supaya preview tidak memiliki scrollbar */
        th, td { white-space: normal; word-break: break-word; }

        td {
            padding: 9px 8px;
            vertical-align: middle;
            color: #111827;
        }

        /* Kolom "No" dibuat lebih kecil */
        th:nth-child(1),
        td:nth-child(1) {
            width: 40px !important;
            max-width: 50px !important;
            /* text-align: center; */
            white-space: nowrap;
        }


        tbody tr:nth-child(odd) td { background: #fbfdff; }
        tbody tr:nth-child(even) td { background: #fff; }

        .text-left { text-align: left; }
        .text-right { text-align: right; }

        tfoot td {
            font-weight: 700;
            background: #f1f5f9;
        }

        /* Hindari pemotongan baris tabel di halaman yang berbeda saat print */
        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }
        tr { page-break-inside: avoid; }

        @media print {
            body { background: #fff; }
            .container { box-shadow: none; margin: 0; padding: 0; width: auto; }
            th { -webkit-print-color-adjust: exact; color-adjust: exact; }
            .logo { display: none; }
            .meta { font-size: 12px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo" aria-hidden="true"></div>
            <div class="brand">
                <h1>LAPORAN PENJUALAN DISTRO 1964</h1>

                @if ($start_date && $end_date)
                    <p>
                        Periode: 
                        {{ \Carbon\Carbon::parse($start_date)->format('d-m-Y') }}
                        s/d
                        {{ \Carbon\Carbon::parse($end_date)->format('d-m-Y') }}
                    </p>
                @else
                    <p>Periode: - </p>
                @endif
            </div>
            <div class="meta">
                <div>Dicetak: {{ date('d-m-Y H:i:s') }}</div>
            </div>
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
    </div>
</body>
</html>
