<?php

namespace App\Filament\Resources\TransaksiBarangs\Schemas;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\StokLokasi;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransaksiBarangForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('barang_id')
                    ->label('Barang')
                    ->relationship('barang', 'nama_barang')
                    ->getOptionLabelFromRecordUsing(fn (Barang $record) => "{$record->id} - {$record->nama_barang} ({$record->kategori->nama_kategori})")
                    ->required()
                    ->searchable(['nama_barang', 'id'])
                    ->preload()
                    ->live()
                    ->createOptionForm([
                        Select::make('kategori_id')
                            ->label('Kategori')
                            ->options(Kategori::pluck('nama_kategori', 'kode_kategori'))
                            ->required()
                            ->searchable()
                            ->preload(),

                        TextInput::make('nama_barang')
                            ->label('Nama Barang')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Komputer Dell Latitude'),

                        TextInput::make('satuan')
                            ->label('Satuan')
                            ->required()
                            ->maxLength(50)
                            ->default('unit')
                            ->placeholder('Contoh: unit, pcs, box, set'),

                        TextInput::make('merk')
                            ->label('Merk/Brand')
                            ->maxLength(100)
                            ->placeholder('Contoh: Dell, Canon, Faber Castell'),

                        TextInput::make('harga_satuan')
                            ->label('Harga Satuan')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->prefix('Rp')
                            ->placeholder('0'),

                        DatePicker::make('tanggal_pembelian')
                            ->label('Tanggal Pembelian')
                            ->maxDate(now())
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->placeholder('Pilih tanggal'),

                        Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('Keterangan tambahan tentang barang...'),

                        Repeater::make('stok_lokasi')
                            ->label('Stok per Lokasi')
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
                                    ->minValue(0)
                                    ->default(0)
                                    ->suffix('unit'),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->addActionLabel('Tambah Lokasi')
                            ->collapsible(),
                    ])
                    ->createOptionUsing(function (array $data): string {
                        // Check permission
                        if (! auth()->user()?->can('create_barangs')) {
                            abort(403, 'Anda tidak memiliki izin untuk menambah barang baru.');
                        }

                        // Extract stok_lokasi data
                        $stokLokasiData = $data['stok_lokasi'] ?? [];
                        unset($data['stok_lokasi']);

                        // Create barang (ID auto-generated)
                        $barang = Barang::create($data);

                        // Create stok_lokasi entries
                        foreach ($stokLokasiData as $stokData) {
                            if (! empty($stokData['lokasi_id'])) {
                                StokLokasi::create([
                                    'barang_id' => $barang->id,
                                    'lokasi_id' => $stokData['lokasi_id'],
                                    'stok' => $stokData['stok'] ?? 0,
                                ]);
                            }
                        }

                        return $barang->id;
                    })
                    ->createOptionModalHeading('Tambah Barang Baru'),

                Select::make('lokasi_id')
                    ->label('Lokasi Tujuan')
                    ->options(Lokasi::pluck('nama_lokasi', 'kode_lokasi'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->helperText('Lokasi dimana barang akan ditambahkan stoknya'),

                TextInput::make('jumlah')
                    ->label('Jumlah Masuk')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->suffix(fn ($get) => Barang::find($get('barang_id'))?->satuan ?? 'unit'),

                DatePicker::make('tanggal_transaksi')
                    ->label('Tanggal Transaksi')
                    ->required()
                    ->default(now())
                    ->maxDate(now())
                    ->native(false),

                TextInput::make('penanggung_jawab')
                    ->label('Penanggung Jawab')
                    ->maxLength(100)
                    ->placeholder('Nama penanggung jawab (opsional)'),

                Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->maxLength(65535)
                    ->rows(3)
                    ->placeholder('Keterangan transaksi (opsional)')
                    ->columnSpanFull(),
            ]);
    }
}
