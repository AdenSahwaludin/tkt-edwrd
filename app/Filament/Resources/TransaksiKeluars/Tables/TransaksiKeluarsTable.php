<?php

namespace App\Filament\Resources\TransaksiKeluars\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TransaksiKeluarsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_transaksi')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->copyable()
                    ->tooltip('Klik untuk copy'),

                TextColumn::make('tipe')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pemindahan' => 'info',
                        'peminjaman' => 'warning',
                        'penggunaan' => 'success',
                        'penghapusan' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('barang.nama_barang')
                    ->label('Barang')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('barang.id')
                    ->label('ID Barang')
                    ->badge()
                    ->color('gray')
                    ->toggleable(),

                TextColumn::make('lokasiAsal.nama_lokasi')
                    ->label('Dari')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('lokasiTujuan.nama_lokasi')
                    ->label('Ke')
                    ->badge()
                    ->color('info')
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->alignEnd()
                    ->suffix(fn ($record) => ' '.$record->barang->satuan)
                    ->sortable(),

                TextColumn::make('tanggal_transaksi')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('approval_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    }),

                TextColumn::make('approvedBy.name')
                    ->label('Disetujui Oleh')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('approved_at')
                    ->label('Tgl Approval')
                    ->dateTime('d M Y H:i')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('user.name')
                    ->label('Dibuat Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('tipe')
                    ->label('Tipe Transaksi')
                    ->options([
                        'pemindahan' => 'Pemindahan',
                        'peminjaman' => 'Peminjaman',
                        'penggunaan' => 'Penggunaan',
                        'penghapusan' => 'Penghapusan',
                    ])
                    ->multiple(),

                SelectFilter::make('approval_status')
                    ->label('Status Approval')
                    ->options([
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ])
                    ->multiple(),

                SelectFilter::make('barang_id')
                    ->label('Barang')
                    ->relationship('barang', 'nama_barang')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('lokasi_asal_id')
                    ->label('Lokasi Asal')
                    ->relationship('lokasiAsal', 'nama_lokasi')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
