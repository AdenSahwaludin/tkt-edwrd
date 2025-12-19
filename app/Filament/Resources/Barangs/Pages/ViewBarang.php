<?php

namespace App\Filament\Resources\Barangs\Pages;

use App\Filament\Resources\Barangs\BarangResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewBarang extends ViewRecord
{
    protected static string $resource = BarangResource::class;

    public function getContentSchema(): Schema
    {
        return Schema::make()
            ->schema([
                Section::make('Informasi Barang')
                    ->schema([
                        Group::make()
                            ->schema([
                                Placeholder::make('kode_barang')
                                    ->label('Kode Barang')
                                    ->content(fn ($record) => $record->kode_barang),

                                Placeholder::make('nama_barang')
                                    ->label('Nama Barang')
                                    ->content(fn ($record) => $record->nama_barang),

                                Placeholder::make('kategori')
                                    ->label('Kategori')
                                    ->content(fn ($record) => $record->kategori->nama_kategori),

                                Placeholder::make('lokasi')
                                    ->label('Lokasi')
                                    ->content(fn ($record) => $record->lokasi->nama_lokasi),
                            ])
                            ->columns(2),
                    ]),

                Section::make('Detail Stok & Harga')
                    ->schema([
                        Group::make()
                            ->schema([
                                Placeholder::make('jumlah_stok')
                                    ->label('Jumlah Stok')
                                    ->content(fn ($record) => $record->jumlah_stok.' unit'),

                                Placeholder::make('reorder_point')
                                    ->label('Reorder Point (ROP)')
                                    ->content(fn ($record) => $record->reorder_point.' unit'),

                                Placeholder::make('harga_satuan')
                                    ->label('Harga Satuan')
                                    ->content(fn ($record) => 'Rp '.number_format($record->harga_satuan, 0, ',', '.')),

                                Placeholder::make('nilai_total')
                                    ->label('Nilai Total Inventaris')
                                    ->content(fn ($record) => 'Rp '.number_format($record->nilaiTotal(), 0, ',', '.')),
                            ])
                            ->columns(2),
                    ]),

                Section::make('Informasi Tambahan')
                    ->schema([
                        Group::make()
                            ->schema([
                                Placeholder::make('merk')
                                    ->label('Merk/Brand')
                                    ->content(fn ($record) => $record->merk ?? 'Tidak ada merk'),

                                Placeholder::make('tanggal_pembelian')
                                    ->label('Tanggal Pembelian')
                                    ->content(fn ($record) => $record->tanggal_pembelian?->format('d F Y') ?? 'Tidak diketahui'),

                                Placeholder::make('status')
                                    ->label('Status')
                                    ->content(fn ($record) => ucfirst($record->status)),
                            ])
                            ->columns(3),

                        Placeholder::make('deskripsi')
                            ->label('Deskripsi')
                            ->content(fn ($record) => $record->deskripsi ?? 'Tidak ada deskripsi')
                            ->columnSpanFull(),
                    ]),

                Section::make('Informasi Sistem')
                    ->schema([
                        Group::make()
                            ->schema([
                                Placeholder::make('created_at')
                                    ->label('Dibuat Pada')
                                    ->content(fn ($record) => $record->created_at->format('d F Y, H:i')),

                                Placeholder::make('updated_at')
                                    ->label('Terakhir Diubah')
                                    ->content(fn ($record) => $record->updated_at->format('d F Y, H:i')),

                                Placeholder::make('deleted_at')
                                    ->label('Dihapus Pada')
                                    ->content(fn ($record) => $record->deleted_at?->format('d F Y, H:i') ?? 'Tidak dihapus'),
                            ])
                            ->columns(3),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
