<?php

namespace App\Filament\Resources\TransaksiBarangs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TransaksiBarangsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal_transaksi')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable()
                    ->weight(FontWeight::Bold),

                TextColumn::make('kode_transaksi')
                    ->label('Kode Transaksi')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->copyable(),

                TextColumn::make('barang.kode_barang')
                    ->label('Kode Barang')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('barang.nama_barang')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('tipe_transaksi')
                    ->label('Tipe')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'masuk' => 'success',
                        'keluar' => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => strtoupper($state)),

                TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->sortable()
                    ->alignEnd()
                    ->suffix(' unit')
                    ->weight(FontWeight::Bold),

                TextColumn::make('penanggung_jawab')
                    ->label('Penanggung Jawab')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('user.name')
                    ->label('Diinput Oleh')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(30)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('tanggal_transaksi', 'desc')
            ->filters([
                SelectFilter::make('tipe_transaksi')
                    ->label('Tipe Transaksi')
                    ->options([
                        'masuk' => 'Masuk',
                        'keluar' => 'Keluar',
                    ]),

                SelectFilter::make('barang_id')
                    ->label('Barang')
                    ->relationship('barang', 'nama_barang')
                    ->searchable()
                    ->preload(),

                Filter::make('tanggal_transaksi')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('dari')
                            ->label('Dari Tanggal'),
                        \Filament\Forms\Components\DatePicker::make('sampai')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_transaksi', '>=', $date),
                            )
                            ->when(
                                $data['sampai'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_transaksi', '<=', $date),
                            );
                    }),
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
