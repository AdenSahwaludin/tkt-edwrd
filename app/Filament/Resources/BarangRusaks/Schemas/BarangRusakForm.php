<?php

namespace App\Filament\Resources\BarangRusaks\Schemas;

use App\Models\Barang;
use App\Models\Lokasi;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BarangRusakForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('barang_id')
                    ->label('Barang')
                    ->relationship('barang', 'nama_barang')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->getOptionLabelFromRecordUsing(fn (Barang $record) => "{$record->id} - {$record->nama_barang} (Total Stok: {$record->total_stok})"
                    )
                    ->helperText('Pilih barang yang rusak'),

                Select::make('lokasi_id')
                    ->label('Lokasi')
                    ->options(Lokasi::pluck('nama_lokasi', 'kode_lokasi'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Lokasi dimana barang rusak berada'),

                TextInput::make('jumlah')
                    ->label('Jumlah Rusak')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->helperText('Masukkan jumlah barang yang rusak'),

                DatePicker::make('tanggal_kejadian')
                    ->label('Tanggal Kejadian')
                    ->required()
                    ->default(now())
                    ->maxDate(now())
                    ->helperText('Tanggal terjadinya kerusakan'),

                TextInput::make('penanggung_jawab')
                    ->label('Penanggung Jawab')
                    ->required()
                    ->maxLength(100)
                    ->helperText('Nama penanggung jawab saat kejadian'),

                Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->rows(4)
                    ->maxLength(65535)
                    ->helperText('Deskripsi kerusakan (opsional)')
                    ->columnSpanFull(),
            ]);
    }
}
