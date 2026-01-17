<?php

namespace App\Filament\Resources\Barangs\Schemas;

use App\Models\Kategori;
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
                TextInput::make('id')
                    ->label('ID Barang (Auto-Generate)')
                    ->disabled()
                    ->dehydrated(false)
                    ->placeholder('Otomatis diisi: HPE-ELE-001')
                    ->helperText('Format: NAMA(3)-KATEGORI(3)-SEQ(3)')
                    ->visible(fn ($record) => $record !== null),

                Select::make('kategori_id')
                    ->label('Kategori')
                    ->options(Kategori::pluck('nama_kategori', 'kode_kategori'))
                    ->required()
                    ->searchable()
                    ->preload(),

                TextInput::make('nama_barang')
                    ->label('Nama Barang')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Contoh: Komputer Dell Latitude'),

                TextInput::make('satuan')
                    ->label('Satuan')
                    ->required()
                    ->maxLength(50)
                    ->default('unit')
                    ->placeholder('Contoh: unit, pcs, box, set'),

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
            ]);
    }
}
