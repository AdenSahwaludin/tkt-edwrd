<?php

namespace App\Filament\Resources\TransaksiBarangs\Pages;

use App\Filament\Resources\TransaksiBarangs\TransaksiBarangResource;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Placeholder;
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
                Placeholder::make('kode_transaksi')
                    ->label('Kode Transaksi')
                    ->content(fn ($record) => $record->kode_transaksi),

                Placeholder::make('tipe_transaksi')
                    ->label('Tipe Transaksi')
                    ->content(fn ($record) => ucfirst($record->tipe_transaksi)),

                Placeholder::make('barang')
                    ->label('Barang')
                    ->content(fn ($record) => $record->barang->nama_barang.' ('.$record->barang->kode_barang.')'),

                Placeholder::make('jumlah')
                    ->label('Jumlah')
                    ->content(fn ($record) => $record->jumlah.' '.$record->barang->satuan),

                Placeholder::make('tanggal_transaksi')
                    ->label('Tanggal Transaksi')
                    ->content(fn ($record) => $record->tanggal_transaksi->format('d F Y')),

                Placeholder::make('penanggung_jawab')
                    ->label('Penanggung Jawab')
                    ->content(fn ($record) => $record->penanggung_jawab ?? '-')
                    ->visible(fn ($record) => $record->penanggung_jawab !== null),

                Placeholder::make('keterangan')
                    ->label('Keterangan')
                    ->content(fn ($record) => $record->keterangan ?? '-')
                    ->columnSpanFull(),

                Placeholder::make('user')
                    ->label('Diinput Oleh')
                    ->content(fn ($record) => $record->user->name.' ('.$record->user->email.')'),

                Placeholder::make('created_at')
                    ->label('Dibuat Pada')
                    ->content(fn ($record) => $record->created_at->format('d F Y H:i')),

                Placeholder::make('updated_at')
                    ->label('Diupdate Pada')
                    ->content(fn ($record) => $record->updated_at->format('d F Y H:i')),
            ]);
    }
}
