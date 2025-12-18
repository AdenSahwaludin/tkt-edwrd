<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Barang Rusak</title>
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
            color: #dc3545;
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
            background-color: #f8d7da;
            font-weight: bold;
            color: #721c24;
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

        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>⚠️ Laporan Barang Rusak</h1>
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
            <td><strong>Total Barang Rusak</strong></td>
            <td>: {{ $barangs->count() }} item</td>
        </tr>
    </table>

    @if ($barangs->count() > 0)
        <div class="alert">
            <strong>Perhatian:</strong> Terdapat {{ $barangs->count() }} item barang dengan status RUSAK yang memerlukan
            perhatian untuk perbaikan atau penggantian.
        </div>

        <h3 style="margin-top: 30px;">Daftar Barang Rusak</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="12%">Kode Barang</th>
                    <th width="25%">Nama Barang</th>
                    <th width="15%">Kategori</th>
                    <th width="15%">Lokasi</th>
                    <th width="10%">Jumlah</th>
                    <th width="12%">Tanggal Beli</th>
                    <th width="12%">Harga Satuan</th>
                </tr>
            </thead>
            <tbody>
                @php $totalNilai = 0; @endphp
                @foreach ($barangs as $index => $barang)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $barang->kode_barang }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>{{ $barang->kategori->nama_kategori }}</td>
                        <td>{{ $barang->lokasi->nama_lokasi }}</td>
                        <td style="text-align: center;">{{ $barang->jumlah_stok }} {{ $barang->satuan }}</td>
                        <td style="text-align: center;">
                            {{ $barang->tanggal_pembelian ? $barang->tanggal_pembelian->format('d/m/Y') : '-' }}</td>
                        <td style="text-align: right;">Rp {{ number_format($barang->harga_satuan ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                    @php $totalNilai += $barang->nilaiTotal(); @endphp
                @endforeach
                <tr style="background-color: #f8d7da; font-weight: bold;">
                    <td colspan="7" style="text-align: right;">Estimasi Total Kerugian:</td>
                    <td style="text-align: right;">Rp {{ number_format($totalNilai, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <h3 style="margin-top: 30px;">Rekomendasi Tindakan</h3>
        <ol>
            <li>Lakukan pemeriksaan detail terhadap setiap barang rusak</li>
            <li>Evaluasi kemungkinan perbaikan vs penggantian baru</li>
            <li>Ajukan anggaran untuk perbaikan atau pengadaan barang pengganti</li>
            <li>Update status barang setelah diperbaiki atau diganti</li>
        </ol>
    @else
        <div class="alert">
            <strong>Informasi:</strong> Tidak ada barang dengan status RUSAK pada periode yang dipilih. Kondisi
            inventaris dalam keadaan baik.
        </div>
    @endif

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
