<?php

namespace App\Filament\Widgets;

use App\Models\StokLokasi;
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
        return '⚠️ Peringatan Stok Rendah per Lokasi';
    }

    /**
     * Query stok lokasi dengan stok rendah (stok <= 5).
     */
    public function table(Table $table): Table
    {
        return $table
            ->query(
                StokLokasi::query()
                    ->with(['barang.kategori', 'lokasi'])
                    ->where('stok', '<=', 5)
                    ->where('stok', '>', 0)
                    ->orderBy('stok', 'asc')
            )
            ->columns([
                TextColumn::make('barang.id')
                    ->label('Kode Barang')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('barang.nama_barang')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('barang.kategori.nama_kategori')
                    ->label('Kategori')
                    ->sortable(),
                TextColumn::make('lokasi.nama_lokasi')
                    ->label('Lokasi')
                    ->sortable(),
                TextColumn::make('stok')
                    ->label('Stok Saat Ini')
                    ->badge()
                    ->color(fn ($state) => $state <= 2 ? 'danger' : 'warning')
                    ->suffix(fn ($record) => " {$record->barang->satuan}"),
                TextColumn::make('barang.status')
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
