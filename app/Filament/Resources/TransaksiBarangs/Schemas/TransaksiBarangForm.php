<?php

namespace App\Filament\Resources\TransaksiBarangs\Schemas;

use App\Models\Barang;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransaksiBarangForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                        Select::make('barang_id')
                            ->label('Barang')
                            ->options(Barang::query()
                                ->with(['kategori', 'lokasi'])
                                ->get()
                                ->mapWithKeys(fn ($barang) => [
                                    $barang->id => "{$barang->nama_barang} ({$barang->kategori->nama_kategori} - {$barang->lokasi->nama_lokasi})",
                                ]))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $barang = Barang::find($state);
                                    if ($barang) {
                                        $set('stok_tersedia', $barang->jumlah);
                                    }
                                }
                            }),

                        Select::make('jenis_transaksi')
                            ->label('Jenis Transaksi')
                            ->options([
                                'masuk' => 'Barang Masuk',
                                'keluar' => 'Barang Keluar',
                            ])
                            ->required()
                            ->native(false)
                            ->live(),

                        TextInput::make('stok_tersedia')
                            ->label('Stok Tersedia')
                            ->disabled()
                            ->suffix('unit')
                            ->dehydrated(false)
                            ->visible(fn ($get) => $get('barang_id') !== null),
                        TextInput::make('jumlah')
                            ->label('Jumlah')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->suffix('unit')
                            ->live()
                            ->helperText(fn ($get) => $get('jenis_transaksi') === 'keluar' && $get('stok_tersedia') !== null
                                ? "Stok tersedia: {$get('stok_tersedia')} unit"
                                : null),

                        DatePicker::make('tanggal_transaksi')
                            ->label('Tanggal Transaksi')
                            ->required()
                            ->default(now())
                            ->maxDate(now())
                            ->native(false),

                        Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->maxLength(65535)
                            ->rows(3)
                            ->placeholder('Keterangan transaksi (opsional)')
                            ->columnSpanFull(),
            ]);
    }
}
