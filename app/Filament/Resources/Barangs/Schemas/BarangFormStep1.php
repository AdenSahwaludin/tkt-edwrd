<?php

namespace App\Filament\Resources\Barangs\Schemas;

use App\Models\Kategori;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BarangFormStep1
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('kategori_id')
                    ->label('Kategori')
                    ->options(Kategori::pluck('nama_kategori', 'id'))
                    ->required()
                    ->searchable()
                    ->preload(),

                TextInput::make('nama_barang')
                    ->label('Nama Barang')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Contoh: Komputer Dell Latitude'),

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
}
