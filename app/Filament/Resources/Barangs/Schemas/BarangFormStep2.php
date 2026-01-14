<?php

namespace App\Filament\Resources\Barangs\Schemas;

use App\Models\Barang;
use App\Models\Lokasi;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BarangFormStep2
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
                    ->placeholder('Otomatis diisi saat pilih lokasi')
                    ->helperText('Format: KATEGORI(3)-NAMA(2)-LOKASI(2)-SEQ(3)'),

                Select::make('lokasi_id')
                    ->label('Lokasi Penyimpanan')
                    ->options(Lokasi::pluck('nama_lokasi', 'id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($get, $set, $state) {
                        // Retrieve barang data from form context
                        $kategoriId = $get('kategori_id');
                        $namaBarang = $get('nama_barang');
                        $lokasiId = $state;

                        if ($kategoriId && $namaBarang && $lokasiId) {
                            $kodeBarang = Barang::generateKodeBarang($kategoriId, $lokasiId, $namaBarang);
                            $set('kode_barang', $kodeBarang);
                        }
                    })
                    ->helperText('Pilih lokasi penyimpanan barang yang sudah dibuat'),
            ]);
    }
}
