<?php

namespace App\Filament\Widgets;

use App\Models\Barang;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class BarangStokRendahWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    /**
     * Judul widget untuk peringatan stok rendah.
     */
    public function getHeading(): string
    {
        return '⚠️ Peringatan Stok Rendah (ROP)';
    }

    /**
     * Query barang dengan stok rendah (jumlah_stok <= reorder_point).
     */
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Barang::query()
                    ->stokRendah()
                    ->with(['kategori', 'lokasi'])
                    ->orderBy('jumlah_stok', 'asc')
            )
            ->columns([
                TextColumn::make('kode_barang')
                    ->label('Kode Barang')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_barang')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('kategori.nama_kategori')
                    ->label('Kategori')
                    ->sortable(),
                TextColumn::make('lokasi.nama_lokasi')
                    ->label('Lokasi')
                    ->sortable(),
                TextColumn::make('jumlah_stok')
                    ->label('Stok Saat Ini')
                    ->badge()
                    ->color('danger')
                    ->suffix(fn (Barang $record) => " {$record->satuan}"),
                TextColumn::make('reorder_point')
                    ->label('Batas Minimum (ROP)')
                    ->badge()
                    ->color('warning')
                    ->suffix(fn (Barang $record) => " {$record->satuan}"),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'baik' => 'success',
                        'rusak' => 'danger',
                        'hilang' => 'warning',
                    }),
            ])
            ->defaultPaginationPageOption(10);
    }
}
