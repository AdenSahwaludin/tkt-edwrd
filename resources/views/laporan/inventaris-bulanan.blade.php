<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inventaris Bulanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0;
            font-size: 11px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 3px 0;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table.data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        table.data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            margin-top: 40px;
            font-size: 10px;
            text-align: center;
            color: #666;
        }

        .signature {
            margin-top: 50px;
            text-align: right;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Laporan Inventaris Bulanan</h1>
        <p>Sistem Informasi Manajemen Inventaris Barang</p>
        <p>Periode: {{ \Carbon\Carbon::parse($periode_awal)->format('d F Y') }} -
            {{ \Carbon\Carbon::parse($periode_akhir)->format('d F Y') }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="150"><strong>Tanggal Cetak</strong></td>
            <td>: {{ now()->format('d F Y H:i') }} WIB</td>
        </tr>
        <tr>
            <td><strong>Dicetak Oleh</strong></td>
            <td>: {{ $user->name }} ({{ ucfirst(str_replace('_', ' ', $user->role)) }})</td>
        </tr>
        <tr>
            <td><strong>Total Jenis Barang</strong></td>
            <td>: {{ $barangs->count() }} item</td>
        </tr>
    </table>

    <h3 style="margin-top: 30px;">Daftar Barang Inventaris</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Kode Barang</th>
                <th width="25%">Nama Barang</th>
                <th width="15%">Kategori</th>
                <th width="15%">Lokasi</th>
                <th width="10%">Stok</th>
                <th width="8%">ROP</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($barangs as $index => $barang)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $barang->kode_barang }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->kategori->nama_kategori }}</td>
                    <td>{{ $barang->lokasi->nama_lokasi }}</td>
                    <td style="text-align: center;">
                        {{ $barang->jumlah_stok }} {{ $barang->satuan }}
                        @if ($barang->isStokRendah())
                            <br><span class="badge badge-danger">LOW</span>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $barang->reorder_point }}</td>
                    <td style="text-align: center;">
                        <span
                            class="badge badge-{{ $barang->status == 'baik' ? 'success' : ($barang->status == 'rusak' ? 'danger' : 'warning') }}">
                            {{ strtoupper($barang->status) }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3 style="margin-top: 30px;">Ringkasan Transaksi Periode</h3>
    <table class="info-table">
        <tr>
            <td width="200"><strong>Total Transaksi Masuk</strong></td>
            <td>: {{ $transaksi_masuk }} transaksi</td>
        </tr>
        <tr>
            <td><strong>Total Transaksi Keluar</strong></td>
            <td>: {{ $transaksi_keluar }} transaksi</td>
        </tr>
        <tr>
            <td><strong>Barang Stok Rendah (≤ ROP)</strong></td>
            <td>: {{ $barangs->filter(fn($b) => $b->isStokRendah())->count() }} item</td>
        </tr>
    </table>

    <div class="signature">
        <p>{{ now()->format('d F Y') }}</p>
        <p style="margin-top: 60px;">
            <strong>{{ $user->name }}</strong><br>
            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
        </p>
    </div>

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh Sistem Informasi Manajemen Inventaris Barang</p>
        <p>Halaman 1 dari 1</p>
    </div>
</body>

</html>
