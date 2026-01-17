<?php

namespace App\Filament\Resources\Barangs\Pages;

use App\Filament\Resources\Barangs\BarangResource;
use App\Models\Barang;
use App\Models\Lokasi;
use App\Models\StokLokasi;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;

class CreateBarang extends CreateRecord
{
    protected static string $resource = BarangResource::class;

    protected ?string $heading = 'Tambah Barang';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Barang')
                    ->schema(BarangResource::form($schema)->getComponents()),

                Section::make('Stok per Lokasi')
                    ->description('Tentukan lokasi dan jumlah stok untuk barang ini')
                    ->schema([
                        Repeater::make('stok_lokasi')
                            ->label('Stok Lokasi')
                            ->schema([
                                Select::make('lokasi_id')
                                    ->label('Lokasi')
                                    ->options(Lokasi::pluck('nama_lokasi', 'kode_lokasi'))
                                    ->required()
                                    ->searchable()
                                    ->distinct()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems(),

                                TextInput::make('stok')
                                    ->label('Jumlah Stok')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->default(1)
                                    ->suffix('unit'),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->addActionLabel('Tambah Lokasi')
                            ->collapsible()
                            ->cloneable()
                            ->reorderable(false),
                    ]),
            ]);
    }

    protected function afterCreate(): void
    {
        $barang = $this->record;
        $stokLokasiData = $this->data['stok_lokasi'] ?? [];

        $unitLocations = [];
        foreach ($stokLokasiData as $stokData) {
            $lokasiId = $stokData['lokasi_id'] ?? null;
            $stok = (int) ($stokData['stok'] ?? 0);

            if (! $lokasiId || $stok < 1) {
                continue;
            }

            for ($i = 0; $i < $stok; $i++) {
                $unitLocations[] = $lokasiId;
            }
        }

        if ($unitLocations === []) {
            return;
        }

        $barangAttributes = $barang->only([
            'nama_barang',
            'kategori_id',
            'satuan',
            'status',
            'deskripsi',
            'harga_satuan',
            'merk',
            'tanggal_pembelian',
        ]);

        if (empty($barangAttributes['status'])) {
            $barangAttributes['status'] = 'baik';
        }

        if (empty($barangAttributes['satuan'])) {
            $barangAttributes['satuan'] = 'pcs';
        }

        DB::transaction(function () use ($barang, $barangAttributes, $unitLocations): void {
            StokLokasi::create([
                'barang_id' => $barang->id,
                'lokasi_id' => $unitLocations[0],
                'stok' => 1,
            ]);

            for ($i = 1; $i < count($unitLocations); $i++) {
                $newBarang = Barang::create($barangAttributes);

                StokLokasi::create([
                    'barang_id' => $newBarang->id,
                    'lokasi_id' => $unitLocations[$i],
                    'stok' => 1,
                ]);
            }
        });

        Notification::make()
            ->success()
            ->title('Barang berhasil ditambahkan!')
            ->body('Total unit dibuat: '.count($unitLocations))
            ->send();
    }
}
