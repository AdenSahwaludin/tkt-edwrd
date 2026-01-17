<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApprovalTransaksiResource\Pages;
use App\Models\TransaksiBarang;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use UnitEnum;

class ApprovalTransaksiResource extends Resource
{
    protected static ?string $model = TransaksiBarang::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-check-badge';

    protected static ?string $navigationLabel = 'Approval Transaksi';

    protected static ?string $modelLabel = 'Approval';

    protected static ?string $pluralModelLabel = 'Approval Transaksi';

    protected static string|UnitEnum|null $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 2;

    public static function canAccess(): bool
    {
        return auth()->user()?->hasPermissionTo('approve_transaksi_barangs') ?? false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('tipe_transaksi', 'masuk')
            ->where('approval_status', 'pending');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_transaksi')
                    ->label('Tanggal Transaksi')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('barang.nama_barang')
                    ->label('Barang')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('barang.id')
                    ->label('Kode Barang')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('jumlah')
                    ->label('Jumlah')
                    ->color('info'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Diinput Oleh')
                    ->sortable(),

                Tables\Columns\TextColumn::make('penanggung_jawab')
                    ->label('Penanggung Jawab')
                    ->searchable(),

                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(30)
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('approval_status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->formatStateUsing(fn (string $state) => ucfirst($state)),
            ])
            ->defaultSort('tanggal_transaksi', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('barang_id')
                    ->label('Barang')
                    ->relationship('barang', 'nama_barang')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
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
                            // Update approval status (Observer will handle stock update)
                            $record->update([
                                'approval_status' => 'approved',
                                'approved_by' => auth()->id(),
                                'approved_at' => now(),
                                'approval_notes' => $data['approval_notes'] ?? null,
                            ]);
                        });

                        Notification::make()
                            ->success()
                            ->title('Transaksi Disetujui')
                            ->body('Transaksi barang masuk telah disetujui dan stok diperbarui.')
                            ->send();
                    })
                    ->requiresConfirmation(),

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
                            'approval_notes' => $data['approval_notes'] ?? null,
                        ]);

                        Notification::make()
                            ->warning()
                            ->title('Transaksi Ditolak')
                            ->body('Transaksi barang masuk telah ditolak.')
                            ->send();
                    })
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
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
                                        // Update approval status (Observer will handle stock update)
                                        $record->update([
                                            'approval_status' => 'approved',
                                            'approved_by' => auth()->id(),
                                            'approved_at' => now(),
                                            'approval_notes' => $data['approval_notes'] ?? null,
                                        ]);
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
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApprovalTransaksis::route('/'),
        ];
    }
}
