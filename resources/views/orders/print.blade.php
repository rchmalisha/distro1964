<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nota Pesanan</title>
<style>
    body {
        font-family: Arial, sans-serif;
        color: #000;
        margin: 0;
        padding: 0;
    }

    .nota {
        width: 140mm; /* lebar paper size Statement */
        padding: 10px;
        margin: 0 auto;
        box-sizing: border-box;
    }

    .header, .footer {
        text-align: center;
        margin-bottom: 10px;
    }

    .header h2 {
        margin: 0;
    }

    .info {
        width: 100%;
        font-size: 12px;
        margin-bottom: 10px;
        border-collapse: collapse;
    }

    .info td {
        padding: 2px 4px;
        text-align: left;
        vertical-align: top;
    }

    .table-container {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    th, td {
        border: 1px solid #ccc;
        padding: 4px 6px;
        text-align: center;
    }

    th {
        background-color: #f3f3f3;
        text-transform: uppercase;
        font-size: 11px;
    }

    td.text-left {
        text-align: left;
    }

    td.text-right {
        text-align: right;
    }

    tfoot td {
        font-weight: bold;
    }

    .text-green {
        color: green;
    }

    .text-blue {
        color: blue;
    }

    .notes {
        margin-top: 10px;
        font-size: 11px;
    }

    @media print {
        body {
            margin: 0;
            padding: 0;
        }

        .nota {
            width: 100%;
        }
    }
</style>
</head>
<body>
<div class="nota">
    <!-- Header Nota -->
    <div class="header">
        <!-- Nama Brand -->
        <h1 style="margin:0; font-size:18px; font-weight:bold; color:#222;">DISTRO 1964</h1>

        <!-- Tagline atau alamat (opsional) -->
        <p style="margin:2px 0; font-size:11px; color:#555;">Jl. Contoh No. 123, Semarang | Telp: 0812-3456-7890</p>

        <!-- Garis pemisah tipis -->
        <hr style="border:none; border-top:1px solid #000; margin:5px 0;">

        <!-- Judul Nota Pesanan -->
        <h2 style="margin:5px 0; font-size:14px; font-weight:bold; text-decoration:underline;">NOTA PESANAN</h2>
    </div>


    <!-- Informasi Pelanggan -->
    <table class="info">
        <tr>
            <!-- Kolom kiri -->
            <td>
                <p><strong>Kode Pesanan:</strong> {{ $order->kode_pesan }}</p>
                <p><strong>Nama Pelanggan:</strong> {{ $order->customer->nama_cus }}</p>
                <p><strong>No.Telepon:</strong> {{ $order->customer->no_telp }}</p>
            </td>

            <!-- Kolom kanan -->
            <td>
                <p><strong>Tanggal Pesan:</strong> {{ \Carbon\Carbon::parse($order->tgl_pesan)->format('d M Y') }}</p>
                <p><strong>Tanggal Ambil:</strong> {{ \Carbon\Carbon::parse($order->tgl_ambil)->format('d M Y') }}</p>
            </td>
        </tr>
    </table>

    <!-- Tabel Rincian -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th class="text-left">Nama Barang</th>
                    <th>Ukuran</th>
                    <th>Jumlah Pesanan</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->detailOrders as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">{{ $detail->service->nama_jasa ?? $detail->material->nama_material ?? '-' }}</td>
                    <td>
                        @php
                            $p = $detail->ukuran_panjang ?? null;
                            $l = $detail->ukuran_lebar ?? null;
                            $p_display = ($p == floor($p)) ? number_format($p,0,',','.') : rtrim(rtrim(number_format($p,2,',','.'),'0'),',');
                            $l_display = ($l == floor($l)) ? number_format($l,0,',','.') : rtrim(rtrim(number_format($l,2,',','.'),'0'),',');
                        @endphp
                        @if ($p && $l)
                            {{ $p_display }} x {{ $l_display }} cm
                        @elseif ($p)
                            {{ $p_display }} cm
                        @elseif ($l)
                            {{ $l_display }} cm
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $detail->jumlah_bahan }}</td>
                    <td class="text-right">Rp {{ number_format($detail->harga_satuan,0,',','.') }}</td>
                    <td class="text-right">Rp {{ number_format($detail->subtotal,0,',','.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-right">Total Harga</td>
                    <td class="text-right text-green">Rp {{ number_format($order->total_harga,0,',','.') }}</td>
                </tr>
                @if ($order->biaya_lainnya)
                <tr>
                    <td colspan="5" class="text-right">Biaya Lainnya</td>
                    <td class="text-right">Rp {{ number_format($order->biaya_lainnya,0,',','.') }}</td>
                </tr>
                @endif
                @if ($order->potongan_harga)
                <tr>
                    <td colspan="5" class="text-right">Potongan Harga</td>
                    <td class="text-right">Rp {{ number_format($order->potongan_harga,0,',','.') }}</td>
                </tr>
                @endif
                <tr>
                    <td colspan="5" class="text-right">Total Akhir</td>
                    <td class="text-right text-blue">Rp {{ number_format($order->total_akhir,0,',','.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Catatan -->
    <div class="notes">
        <p><strong>Catatan:</strong> Harap diambil sesuai tanggal yang tertera. Terima kasih.</p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>--- Terima Kasih ---</p>
    </div>
</div>
</body>
</html>
