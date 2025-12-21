<?php

namespace App\Filament\Resources\TransaksiBarangs\Tables;

use App\Models\TransaksiBarang;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

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

                TextColumn::make('approval_status')
                    ->label('Status Approval')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'PENDING',
                        'approved' => 'APPROVED',
                        'rejected' => 'REJECTED',
                    }),

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

                TextColumn::make('approvedBy.name')
                    ->label('Disetujui Oleh')
                    ->sortable()
                    ->toggleable()
                    ->visible(fn (): bool => auth()->user()->hasPermissionTo('approve_transaksi_barangs')),

                TextColumn::make('approved_at')
                    ->label('Waktu Persetujuan')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn (): bool => auth()->user()->hasPermissionTo('approve_transaksi_barangs')),

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

                SelectFilter::make('approval_status')
                    ->label('Status Approval')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->visible(fn (): bool => auth()->user()->hasPermissionTo('approve_transaksi_barangs')),

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
                EditAction::make(),
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->form([
                        Textarea::make('approval_notes')
                            ->label('Catatan Persetujuan')
                            ->rows(3),
                    ])
                    ->action(function (TransaksiBarang $record, array $data): void {
                        DB::transaction(function () use ($record, $data) {
                            // Update approval status
                            $record->update([
                                'approval_status' => 'approved',
                                'approved_by' => auth()->id(),
                                'approved_at' => now(),
                                'approval_notes' => $data['approval_notes'] ?? null,
                            ]);

                            // Update stok barang for incoming transactions
                            if ($record->isMasuk()) {
                                $record->barang->increment('jumlah_stok', $record->jumlah);
                            }
                        });

                        Notification::make()
                            ->success()
                            ->title('Transaksi Disetujui')
                            ->body("Transaksi {$record->kode_transaksi} telah disetujui dan stok diperbarui.")
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->visible(fn (TransaksiBarang $record): bool => $record->isPending() && $record->isMasuk() && auth()->user()->can('approve', $record)),
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Textarea::make('approval_notes')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (TransaksiBarang $record, array $data): void {
                        $record->update([
                            'approval_status' => 'rejected',
                            'approved_by' => auth()->id(),
                            'approved_at' => now(),
                            'approval_notes' => $data['approval_notes'],
                        ]);

                        Notification::make()
                            ->warning()
                            ->title('Transaksi Ditolak')
                            ->body("Transaksi {$record->kode_transaksi} telah ditolak.")
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->visible(fn (TransaksiBarang $record): bool => $record->isPending() && $record->isMasuk() && auth()->user()->can('approve', $record)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('bulk_approve')
                        ->label('Setujui Terpilih')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->form([
                            Textarea::make('approval_notes')
                                ->label('Catatan Persetujuan')
                                ->rows(3),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            DB::transaction(function () use ($records, $data) {
                                foreach ($records as $record) {
                                    if ($record->isPending() && $record->isMasuk()) {
                                        // Update approval status
                                        $record->update([
                                            'approval_status' => 'approved',
                                            'approved_by' => auth()->id(),
                                            'approved_at' => now(),
                                            'approval_notes' => $data['approval_notes'] ?? null,
                                        ]);

                                        // Update stok barang
                                        $record->barang->increment('jumlah_stok', $record->jumlah);
                                    }
                                }
                            });

                            Notification::make()
                                ->success()
                                ->title('Transaksi Disetujui')
                                ->body("{$records->count()} transaksi telah disetujui dan stok diperbarui.")
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation()
                        ->visible(fn (): bool => auth()->user()->hasPermissionTo('approve_transaksi_barangs')),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
