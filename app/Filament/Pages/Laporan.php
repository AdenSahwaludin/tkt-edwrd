<?php

namespace App\Filament\Pages;

use App\Models\Barang;
use App\Models\Laporan as LaporanModel;
use App\Models\TransaksiBarang;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;
use UnitEnum;

class Laporan extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Laporan';

    protected static ?string $title = 'Laporan';

    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 0;

    protected string $view = 'filament.pages.laporan';

    public function getHeading(): string
    {
        return 'Laporan Inventaris';
    }

    public function getSubheading(): ?string
    {
        return 'Generate dan download laporan inventaris dalam format PDF';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('inventaris_bulanan')
                ->label('Laporan Inventaris Bulanan')
                ->icon('heroicon-o-document-text')
                ->color('success')
                ->form([
                    DatePicker::make('periode_awal')
                        ->label('Tanggal Mulai')
                        ->required()
                        ->default(now()->startOfMonth())
                        ->maxDate(fn ($get) => $get('periode_akhir'))
                        ->native(false),
                    DatePicker::make('periode_akhir')
                        ->label('Tanggal Akhir')
                        ->required()
                        ->default(now()->endOfMonth())
                        ->minDate(fn ($get) => $get('periode_awal'))
                        ->native(false),
                ])
                ->action(function (array $data) {
                    return $this->generateLaporanInventaris($data);
                }),

            Action::make('barang_rusak')
                ->label('Laporan Barang Rusak')
                ->icon('heroicon-o-wrench-screwdriver')
                ->color('danger')
                ->form([
                    DatePicker::make('periode_awal')
                        ->label('Tanggal Mulai')
                        ->required()
                        ->default(now()->startOfMonth())
                        ->maxDate(fn ($get) => $get('periode_akhir'))
                        ->native(false),
                    DatePicker::make('periode_akhir')
                        ->label('Tanggal Akhir')
                        ->required()
                        ->default(now()->endOfMonth())
                        ->minDate(fn ($get) => $get('periode_awal'))
                        ->native(false),
                ])
                ->action(function (array $data) {
                    return $this->generateLaporanBarangRusak($data);
                }),

            Action::make('barang_hilang')
                ->label('Laporan Barang Hilang')
                ->icon('heroicon-o-magnifying-glass')
                ->color('warning')
                ->form([
                    DatePicker::make('periode_awal')
                        ->label('Tanggal Mulai')
                        ->required()
                        ->default(now()->startOfMonth())
                        ->maxDate(fn ($get) => $get('periode_akhir'))
                        ->native(false),
                    DatePicker::make('periode_akhir')
                        ->label('Tanggal Akhir')
                        ->required()
                        ->default(now()->endOfMonth())
                        ->minDate(fn ($get) => $get('periode_awal'))
                        ->native(false),
                ])
                ->action(function (array $data) {
                    return $this->generateLaporanBarangHilang($data);
                }),
        ];
    }

    protected function generateLaporanInventaris(array $data): mixed
    {
        $barangs = Barang::with(['kategori', 'lokasi'])
            ->orderBy('kategori_id')
            ->orderBy('nama_barang')
            ->get();

        $transaksi_masuk = TransaksiBarang::masuk()
            ->whereBetween('tanggal_transaksi', [$data['periode_awal'], $data['periode_akhir']])
            ->count();

        $transaksi_keluar = TransaksiBarang::keluar()
            ->whereBetween('tanggal_transaksi', [$data['periode_awal'], $data['periode_akhir']])
            ->count();

        $pdf = Pdf::loadView('laporan.inventaris-bulanan', [
            'barangs' => $barangs,
            'periode_awal' => $data['periode_awal'],
            'periode_akhir' => $data['periode_akhir'],
            'transaksi_masuk' => $transaksi_masuk,
            'transaksi_keluar' => $transaksi_keluar,
            'user' => auth()->user(),
        ]);

        $filename = 'laporan-inventaris-'.now()->format('YmdHis').'.pdf';
        $path = 'laporan/'.$filename;

        Storage::put($path, $pdf->output());

        LaporanModel::create([
            'judul_laporan' => 'Laporan Inventaris Bulanan',
            'jenis_laporan' => 'inventaris_bulanan',
            'periode_awal' => $data['periode_awal'],
            'periode_akhir' => $data['periode_akhir'],
            'file_path' => $path,
            'dibuat_oleh' => auth()->id(),
        ]);

        Notification::make()
            ->title('Laporan berhasil digenerate!')
            ->success()
            ->send();

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    protected function generateLaporanBarangRusak(array $data): mixed
    {
        $barangs = Barang::with(['kategori', 'lokasi'])
            ->byStatus('rusak')
            ->orderBy('nama_barang')
            ->get();

        $pdf = Pdf::loadView('laporan.barang-rusak', [
            'barangs' => $barangs,
            'periode_awal' => $data['periode_awal'],
            'periode_akhir' => $data['periode_akhir'],
            'user' => auth()->user(),
        ]);

        $filename = 'laporan-barang-rusak-'.now()->format('YmdHis').'.pdf';
        $path = 'laporan/'.$filename;

        Storage::put($path, $pdf->output());

        LaporanModel::create([
            'judul_laporan' => 'Laporan Barang Rusak',
            'jenis_laporan' => 'barang_rusak',
            'periode_awal' => $data['periode_awal'],
            'periode_akhir' => $data['periode_akhir'],
            'file_path' => $path,
            'dibuat_oleh' => auth()->id(),
        ]);

        Notification::make()
            ->title('Laporan barang rusak berhasil digenerate!')
            ->success()
            ->send();

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    protected function generateLaporanBarangHilang(array $data): mixed
    {
        $barangs = Barang::with(['kategori', 'lokasi'])
            ->byStatus('hilang')
            ->orderBy('nama_barang')
            ->get();

        $pdf = Pdf::loadView('laporan.barang-hilang', [
            'barangs' => $barangs,
            'periode_awal' => $data['periode_awal'],
            'periode_akhir' => $data['periode_akhir'],
            'user' => auth()->user(),
        ]);

        $filename = 'laporan-barang-hilang-'.now()->format('YmdHis').'.pdf';
        $path = 'laporan/'.$filename;

        Storage::put($path, $pdf->output());

        LaporanModel::create([
            'judul_laporan' => 'Laporan Barang Hilang',
            'jenis_laporan' => 'barang_hilang',
            'periode_awal' => $data['periode_awal'],
            'periode_akhir' => $data['periode_akhir'],
            'file_path' => $path,
            'dibuat_oleh' => auth()->id(),
        ]);

        Notification::make()
            ->title('Laporan barang hilang berhasil digenerate!')
            ->success()
            ->send();

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }
}
