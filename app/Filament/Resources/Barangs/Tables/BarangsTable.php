<?php

namespace App\Filament\Resources\Barangs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class BarangsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID Barang')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->copyable()
                    ->copyMessage('ID Copied!')
                    ->tooltip('Klik untuk copy'),

                TextColumn::make('nama_barang')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('kategori.nama_kategori')
                    ->label('Kategori')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('total_stok')
                    ->label('Total Stok')
                    ->state(fn ($record) => $record->getTotalStokAttribute())
                    ->sortable()
                    ->alignEnd()
                    ->suffix(fn ($record) => ' '.$record->satuan)
                    ->badge()
                    ->color(fn ($record) => $record->getTotalStokAttribute() <= 10 ? 'danger' : 'success'),

                TextColumn::make('stokLokasi.stok')
                    ->label('Lokasi Stok')
                    ->badge()
                    ->separator(', ')
                    ->formatStateUsing(fn ($record, $state) => $record->stokLokasi->map(function ($sl) {
                        return $sl->lokasi->nama_lokasi.': '.$sl->stok;
                    })->join(', '))
                    ->wrap()
                    ->toggleable(),

                TextColumn::make('satuan')
                    ->label('Satuan')
                    ->badge()
                    ->color('gray')
                    ->toggleable(),

                TextColumn::make('merk')
                    ->label('Merk')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('harga_satuan')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable()
                    ->alignEnd()
                    ->toggleable(),

                TextColumn::make('tanggal_pembelian')
                    ->label('Tgl Beli')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('nilai_total')
                    ->label('Nilai Total')
                    ->state(fn ($record) => $record->getTotalStokAttribute() * $record->harga_satuan)
                    ->money('IDR')
                    ->alignEnd()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diubah')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('kategori_id')
                    ->label('Kategori')
                    ->relationship('kategori', 'nama_kategori')
                    ->multiple()
                    ->preload(),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
