<!DOCTYPE html>
<html>

<head>
    <title>Laporan Bulanan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Laporan Transaksi Bulanan</h2>
        <p>Periode: {{ \Carbon\Carbon::now()->format('F Y') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th>Tanggal</th>
                <th>Kode Transaksi</th>
                <th>Barang</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Penanggung Jawab</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->tanggal_transaksi->format('d/m/Y') }}</td>
                    <td>{{ $item->kode_transaksi }}</td>
                    <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                    <td>{{ ucfirst($item->tipe_transaksi) }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ $item->penanggung_jawab }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center">Tidak ada data transaksi bulan ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
