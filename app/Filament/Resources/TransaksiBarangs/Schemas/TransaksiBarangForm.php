<?php

namespace App\Filament\Resources\TransaksiBarangs\Schemas;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
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
                    ->getOptionLabelFromRecordUsing(fn (Barang $record) => "{$record->nama_barang} ({$record->kategori->nama_kategori} - {$record->lokasi->nama_lokasi})")
                    ->required()
                    ->searchable(['nama_barang', 'kode_barang'])
                    ->preload()
                    ->live()
                    ->createOptionForm([
                        TextInput::make('kode_barang')
                            ->label('Kode Barang (Auto-Generate)')
                            ->required()
                            ->unique()
                            ->maxLength(50)
                            ->disabled()
                            ->dehydrated()
                            ->placeholder('Otomatis diisi saat pilih kategori, nama & lokasi')
                            ->helperText('Format: KATEGORI(3)-NAMA(2)-LOKASI(2)-SEQ(3)'),

                        Select::make('kategori_id')
                            ->label('Kategori')
                            ->options(Kategori::pluck('nama_kategori', 'id'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateKodeBarangInModal($get, $set);
                            }),

                        TextInput::make('nama_barang')
                            ->label('Nama Barang')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Komputer Dell Latitude')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateKodeBarangInModal($get, $set);
                            }),

                        Select::make('lokasi_id')
                            ->label('Lokasi')
                            ->options(Lokasi::pluck('nama_lokasi', 'id'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateKodeBarangInModal($get, $set);
                            }),

                        TextInput::make('jumlah_stok')
                            ->label('Jumlah Stok')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->suffix('unit'),

                        TextInput::make('reorder_point')
                            ->label('Reorder Point (ROP)')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(10)
                            ->suffix('unit')
                            ->helperText('Stok minimum sebelum perlu pemesanan ulang'),

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

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'baik' => 'Baik',
                                'rusak' => 'Rusak',
                                'hilang' => 'Hilang',
                            ])
                            ->required()
                            ->default('baik')
                            ->native(false),
                    ])
                    ->createOptionUsing(function (array $data): int {
                        // Check permission
                        if (! auth()->user()?->can('create_barangs')) {
                            abort(403, 'Anda tidak memiliki izin untuk menambah barang baru.');
                        }

                        // Buat barang baru
                        $barang = Barang::create($data);

                        return $barang->id;
                    })
                    ->createOptionModalHeading('Tambah Barang Baru')
                    ->afterStateUpdated(function ($state, callable $set, callable $get, $context) {
                        if ($state) {
                            $barang = Barang::find($state);
                            if ($barang) {
                                // If editing and tipe_transaksi is keluar, add back the original transaction amount
                                // If creating, just use current stock
                                if ($context === 'edit' && $get('tipe_transaksi') === 'keluar' && $get('jumlah')) {
                                    $set('stok_tersedia', $barang->jumlah_stok + (int) $get('jumlah'));
                                } else {
                                    $set('stok_tersedia', $barang->jumlah_stok);
                                }
                            }
                        }
                    }),

                Select::make('tipe_transaksi')
                    ->label('Tipe Transaksi')
                    ->options([
                        'masuk' => 'Barang Masuk',
                        'keluar' => 'Barang Keluar',
                    ])
                    ->required()
                    ->native(false)
                    ->live(),

                TextInput::make('stok_tersedia')
                    ->label('Stok Tersedia')
                    ->disabled()
                    ->suffix('unit')
                    ->dehydrated(false)
                    ->visible(fn ($get) => $get('barang_id') !== null),

                TextInput::make('jumlah')
                    ->label('Jumlah')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->suffix('unit')
                    ->live()
                    ->helperText(fn ($get) => $get('tipe_transaksi') === 'keluar' && $get('stok_tersedia') !== null
                        ? "Stok tersedia: {$get('stok_tersedia')} unit"
                        : null)
                    ->rules([
                        fn ($get) => function (string $attribute, $value, $fail) use ($get) {
                            if ($get('tipe_transaksi') === 'keluar') {
                                $stokTersedia = $get('stok_tersedia');
                                if ($stokTersedia !== null && $value > $stokTersedia) {
                                    $fail("Jumlah tidak boleh melebihi stok tersedia ({$stokTersedia} unit)");
                                }
                            }
                        },
                    ]),

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

    /**
     * Update kode barang saat kategori, nama barang, atau lokasi berubah
     */
    protected static function updateKodeBarangInModal(Get $get, Set $set): void
    {
        $kategoriId = $get('kategori_id');
        $lokasiId = $get('lokasi_id');
        $namaBarang = $get('nama_barang');

        // Generate kode barang jika semua field terisi
        if ($kategoriId && $lokasiId && $namaBarang) {
            $kodeBarang = Barang::generateKodeBarang($kategoriId, $lokasiId, $namaBarang);
            $set('kode_barang', $kodeBarang);
        }
    }
}
