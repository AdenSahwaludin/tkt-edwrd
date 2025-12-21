<?php

namespace App\Filament\Resources\TransaksiBarangs\Pages;

use App\Filament\Resources\TransaksiBarangs\TransaksiBarangResource;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewTransaksiBarang extends ViewRecord
{
    protected static string $resource = TransaksiBarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Transaksi')
                    ->description('Detail lengkap transaksi barang')
                    ->icon('heroicon-o-document-text')
                    ->columns(2)
                    ->collapsible()
                    ->components([
                        Placeholder::make('kode_transaksi')
                            ->label('Kode Transaksi')
                            ->content(fn ($record) => $record->kode_transaksi)
                            ->columnSpan(1),

                        Placeholder::make('tipe_transaksi')
                            ->label('Tipe Transaksi')
                            ->content(fn ($record) => ucfirst($record->tipe_transaksi))
                            ->columnSpan(1),

                        Placeholder::make('tanggal_transaksi')
                            ->label('Tanggal Transaksi')
                            ->content(fn ($record) => $record->tanggal_transaksi->format('d F Y'))
                            ->columnSpan(1),

                        Placeholder::make('penanggung_jawab')
                            ->label('Penanggung Jawab')
                            ->content(fn ($record) => $record->penanggung_jawab ?? '-')
                            ->columnSpan(1),
                    ]),

                Section::make('Informasi Barang')
                    ->description('Detail barang yang ditransaksikan')
                    ->icon('heroicon-o-cube')
                    ->columns(2)
                    ->collapsible()
                    ->components([
                        Placeholder::make('nama_barang')
                            ->label('Nama Barang')
                            ->content(fn ($record) => $record->barang->nama_barang)
                            ->columnSpan(2),

                        Placeholder::make('kode_barang')
                            ->label('Kode Barang')
                            ->content(fn ($record) => $record->barang->kode_barang)
                            ->columnSpan(1),

                        Placeholder::make('jumlah')
                            ->label('Jumlah')
                            ->content(fn ($record) => $record->jumlah.' '.$record->barang->satuan)
                            ->columnSpan(1),

                        Placeholder::make('kategori')
                            ->label('Kategori')
                            ->content(fn ($record) => $record->barang->kategori->nama_kategori)
                            ->columnSpan(1),

                        Placeholder::make('lokasi')
                            ->label('Lokasi')
                            ->content(fn ($record) => $record->barang->lokasi->nama_lokasi)
                            ->columnSpan(1),
                    ]),

                Section::make('Keterangan & Audit')
                    ->description('Catatan dan informasi pencatatan')
                    ->icon('heroicon-o-information-circle')
                    ->columns(2)
                    ->collapsible()
                    ->components([
                        Placeholder::make('keterangan')
                            ->label('Keterangan')
                            ->content(fn ($record) => $record->keterangan ?? '-')
                            ->columnSpanFull(),

                        Placeholder::make('user')
                            ->label('Diinput Oleh')
                            ->content(fn ($record) => $record->user->name)
                            ->columnSpan(1),

                        Placeholder::make('email')
                            ->label('Email')
                            ->content(fn ($record) => $record->user->email)
                            ->columnSpan(1),

                        Placeholder::make('created_at')
                            ->label('Dibuat Pada')
                            ->content(fn ($record) => $record->created_at->format('d F Y H:i'))
                            ->columnSpan(1),

                        Placeholder::make('updated_at')
                            ->label('Diupdate Pada')
                            ->content(fn ($record) => $record->updated_at->format('d F Y H:i'))
                            ->columnSpan(1),
                    ]),
            ]);
    }
}
