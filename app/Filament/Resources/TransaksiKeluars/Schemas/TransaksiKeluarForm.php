<?php

namespace App\Filament\Resources\TransaksiKeluars\Schemas;

use App\Models\Barang;
use App\Models\Lokasi;
use App\Models\StokLokasi;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Schemas\Schema;

class TransaksiKeluarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('kode_transaksi')
                    ->label('Kode Transaksi')
                    ->disabled()
                    ->dehydrated(false)
                    ->placeholder('Auto-generate: PMD-20260117-001')
                    ->columnSpan(1)
                    ->visible(fn ($record) => $record !== null),

                Select::make('tipe')
                    ->label('Tipe Transaksi')
                    ->options([
                        'pemindahan' => 'Pemindahan Lokasi',
                        'peminjaman' => 'Peminjaman',
                        'penggunaan' => 'Penggunaan',
                        'penghapusan' => 'Penghapusan',
                    ])
                    ->required()
                    ->native(false)
                    ->live()
                    ->columnSpan(1),

                Select::make('barang_id')
                    ->label('Barang')
                    ->options(function () {
                        return Barang::with('kategori')->get()->mapWithKeys(function ($barang) {
                            return [$barang->id => "{$barang->nama_barang} ({$barang->id}) - {$barang->kategori->nama_kategori}"];
                        });
                    })
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('lokasi_asal_id', null);
                        $set('stok_tersedia', null);
                    })
                    ->columnSpan(2),

                Select::make('lokasi_asal_id')
                    ->label('Lokasi Asal')
                    ->options(function (Get $get) {
                        $barangId = $get('barang_id');
                        if (! $barangId) {
                            return [];
                        }

                        return StokLokasi::where('barang_id', $barangId)
                            ->with('lokasi')
                            ->get()
                            ->mapWithKeys(function ($stokLokasi) {
                                return [
                                    $stokLokasi->lokasi_id => "{$stokLokasi->lokasi->nama_lokasi} (Stok: {$stokLokasi->stok})",
                                ];
                            });
                    })
                    ->required()
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function ($state, Get $get, callable $set) {
                        if ($state && $get('barang_id')) {
                            $stokLokasi = StokLokasi::where('barang_id', $get('barang_id'))
                                ->where('lokasi_id', $state)
                                ->first();

                            $set('stok_tersedia', $stokLokasi ? $stokLokasi->stok : 0);
                        }
                    })
                    ->columnSpan(1),

                Select::make('lokasi_tujuan_id')
                    ->label('Lokasi Tujuan')
                    ->options(Lokasi::pluck('nama_lokasi', 'kode_lokasi'))
                    ->searchable()
                    ->preload()
                    ->required(fn (Get $get) => $get('tipe') === 'pemindahan')
                    ->visible(fn (Get $get) => $get('tipe') === 'pemindahan')
                    ->columnSpan(1),

                TextInput::make('stok_tersedia')
                    ->label('Stok Tersedia')
                    ->disabled()
                    ->suffix('unit')
                    ->dehydrated(false)
                    ->visible(fn (Get $get) => $get('lokasi_asal_id') !== null)
                    ->columnSpan(1),

                TextInput::make('jumlah')
                    ->label('Jumlah')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->suffix('unit')
                    ->live()
                    ->helperText(fn (Get $get) => $get('stok_tersedia') !== null
                        ? "Stok tersedia: {$get('stok_tersedia')} unit"
                        : null)
                    ->rules([
                        fn (Get $get) => function (string $attribute, $value, $fail) use ($get) {
                            $stokTersedia = $get('stok_tersedia');
                            if ($stokTersedia !== null && $value > $stokTersedia) {
                                $fail("Jumlah tidak boleh melebihi stok tersedia ({$stokTersedia} unit)");
                            }
                        },
                    ])
                    ->columnSpan(1),

                DatePicker::make('tanggal_transaksi')
                    ->label('Tanggal Transaksi')
                    ->required()
                    ->default(now())
                    ->maxDate(now())
                    ->native(false)
                    ->columnSpan(1),

                TextInput::make('penanggung_jawab')
                    ->label('Penanggung Jawab')
                    ->maxLength(100)
                    ->placeholder('Nama penanggung jawab')
                    ->columnSpan(1),

                Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->rows(3)
                    ->maxLength(65535)
                    ->placeholder('Keterangan transaksi...')
                    ->columnSpan(2),
            ]);
    }
}
