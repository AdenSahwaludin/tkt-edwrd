<?php

namespace App\Filament\Resources\Barangs\Schemas;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BarangForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode_barang')
                    ->label('Kode Barang (Auto-Generate)')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50)
                    ->disabled()
                    ->dehydrated()
                    ->placeholder('Otomatis diisi saat pilih kategori, nama & lokasi')
                    ->helperText('Format: KATEGORI(3)-NAMA(2)-LOKASI(2)-SEQ(3)'),

                Select::make('kategori_id')
                    ->label('Kategori')
                    ->options(Kategori::pluck('nama_kategori', 'id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($get, $set, $state) {
                        self::updateKodeBarang($get, $set);
                    }),

                TextInput::make('nama_barang')
                    ->label('Nama Barang')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Contoh: Komputer Dell Latitude')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($get, $set, $state) {
                        self::updateKodeBarang($get, $set);
                    }),

                Select::make('lokasi_id')
                    ->label('Lokasi')
                    ->options(Lokasi::pluck('nama_lokasi', 'id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($get, $set, $state) {
                        self::updateKodeBarang($get, $set);
                    }),

                TextInput::make('jumlah_stok')
                    ->label('Jumlah Stok')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->suffix('unit'),

                TextInput::make('reorder_point')
                    ->label('Reorder Point (ROP)')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(10)
                    ->suffix('unit')
                    ->helperText('Stok minimum sebelum perlu pemesanan ulang'),

                TextInput::make('merk')
                    ->label('Merk/Brand')
                    ->maxLength(100)
                    ->placeholder('Contoh: Dell, Canon, Faber Castell'),

                TextInput::make('harga_satuan')
                    ->label('Harga Satuan')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix('Rp')
                    ->placeholder('0'),

                DatePicker::make('tanggal_pembelian')
                    ->label('Tanggal Pembelian')
                    ->maxDate(now())
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->placeholder('Pilih tanggal'),

                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->maxLength(500)
                    ->placeholder('Keterangan tambahan tentang barang...'),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'baik' => 'Baik',
                        'rusak' => 'Rusak',
                        'hilang' => 'Hilang',
                    ])
                    ->required()
                    ->default('baik')
                    ->native(false),
            ]);
    }

    /**
     * Update kode barang saat kategori, nama barang, atau lokasi berubah
     */
    protected static function updateKodeBarang($get, $set): void
    {
        $kategoriId = $get('kategori_id');
        $lokasiId = $get('lokasi_id');
        $namaBarang = $get('nama_barang');

        // Generate kode barang jika semua field terisi
        if ($kategoriId && $lokasiId && $namaBarang) {
            $kodeBarang = Barang::generateKodeBarang($kategoriId, $lokasiId, $namaBarang);
            $set('kode_barang', $kodeBarang);
        }
    }
}
