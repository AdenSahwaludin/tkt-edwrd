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
                TextColumn::make('kode_barang')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold),

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

                TextColumn::make('lokasi.nama_lokasi')
                    ->label('Lokasi')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('jumlah')
                    ->label('Stok')
                    ->sortable()
                    ->alignEnd()
                    ->suffix(' unit')
                    ->badge()
                    ->color(fn ($record) => $record->isStokRendah() ? 'danger' : 'success'),

                TextColumn::make('reorder_point')
                    ->label('ROP')
                    ->sortable()
                    ->alignEnd()
                    ->suffix(' unit')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('harga_satuan')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable()
                    ->alignEnd()
                    ->toggleable(),

                TextColumn::make('nilaiTotal')
                    ->label('Nilai Total')
                    ->state(fn ($record) => $record->nilaiTotal())
                    ->money('IDR')
                    ->sortable(query: function ($query, $direction) {
                        return $query->orderByRaw("(jumlah * harga_satuan) {$direction}");
                    })
                    ->alignEnd()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'baik' => 'success',
                        'rusak' => 'danger',
                        'hilang' => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

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

                SelectFilter::make('lokasi_id')
                    ->label('Lokasi')
                    ->relationship('lokasi', 'nama_lokasi')
                    ->multiple()
                    ->preload(),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'baik' => 'Baik',
                        'rusak' => 'Rusak',
                        'hilang' => 'Hilang',
                    ])
                    ->multiple(),

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
