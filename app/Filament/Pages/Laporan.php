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
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use UnitEnum;

class Laporan extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Dashboard Laporan';

    protected static ?string $title = 'Dashboard Laporan';

    protected static string|UnitEnum|null $navigationGroup = 'Laporan';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.laporan';

    public function getViewData(): array
    {
        $periodeAwal = now()->startOfMonth();
        $periodeAkhir = now()->endOfMonth();

        $stats = [
            'total_barang' => Barang::count(),
            'barang_baik' => Barang::byStatus('baik')->count(),
            'barang_rusak' => Barang::byStatus('rusak')->count(),
            'barang_hilang' => Barang::byStatus('hilang')->count(),
            'transaksi_bulan_ini' => TransaksiBarang::whereBetween('tanggal_transaksi', [$periodeAwal, $periodeAkhir])->count(),
            'transaksi_masuk' => TransaksiBarang::masuk()->whereBetween('tanggal_transaksi', [$periodeAwal, $periodeAkhir])->count(),
            'transaksi_keluar' => TransaksiBarang::keluar()->whereBetween('tanggal_transaksi', [$periodeAwal, $periodeAkhir])->count(),
        ];

        $laporanTerakhir = LaporanModel::with('pembuatLaporan')
            ->latest()
            ->limit(10)
            ->get();

        return [
            'stats' => $stats,
            'laporanTerakhir' => $laporanTerakhir,
        ];
    }

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

    public function downloadLaporan(int $laporanId): ?StreamedResponse
    {
        $laporan = LaporanModel::find($laporanId);

        if (! $laporan) {
            Notification::make()
                ->title('Laporan tidak ditemukan')
                ->danger()
                ->send();

            return null;
        }

        if (! Storage::exists($laporan->file_path)) {
            Notification::make()
                ->title('File laporan tidak tersedia')
                ->body('File laporan sudah tidak ada di penyimpanan.')
                ->danger()
                ->send();

            return null;
        }

        $filename = Str::slug($laporan->judul_laporan).'-'.$laporan->created_at->format('YmdHis').'.pdf';

        return Storage::download($laporan->file_path, $filename);
    }

    protected function generateLaporanInventaris(array $data): ?StreamedResponse
    {
        $barangs = Barang::with(['kategori', 'lokasi'])
            ->orderBy('kategori_id')
            ->orderBy('nama_barang')
            ->get();

        $transaksiMasuk = TransaksiBarang::masuk()
            ->whereBetween('tanggal_transaksi', [$data['periode_awal'], $data['periode_akhir']])
            ->count();

        $transaksiKeluar = TransaksiBarang::keluar()
            ->whereBetween('tanggal_transaksi', [$data['periode_awal'], $data['periode_akhir']])
            ->count();

        return $this->generateDanDownloadPdf(
            judul: 'Laporan Inventaris Bulanan',
            jenis: 'inventaris_bulanan',
            view: 'laporan.inventaris-bulanan',
            periodeAwal: $data['periode_awal'],
            periodeAkhir: $data['periode_akhir'],
            data: [
                'barangs' => $barangs,
                'periode_awal' => $data['periode_awal'],
                'periode_akhir' => $data['periode_akhir'],
                'transaksi_masuk' => $transaksiMasuk,
                'transaksi_keluar' => $transaksiKeluar,
                'user' => auth()->user(),
            ],
        );
    }

    protected function generateLaporanBarangRusak(array $data): ?StreamedResponse
    {
        $barangs = Barang::with(['kategori', 'lokasi'])
            ->byStatus('rusak')
            ->orderBy('nama_barang')
            ->get();

        return $this->generateDanDownloadPdf(
            judul: 'Laporan Barang Rusak',
            jenis: 'barang_rusak',
            view: 'laporan.barang-rusak',
            periodeAwal: $data['periode_awal'],
            periodeAkhir: $data['periode_akhir'],
            data: [
                'barangs' => $barangs,
                'periode_awal' => $data['periode_awal'],
                'periode_akhir' => $data['periode_akhir'],
                'user' => auth()->user(),
            ],
        );
    }

    protected function generateLaporanBarangHilang(array $data): ?StreamedResponse
    {
        $barangs = Barang::with(['kategori', 'lokasi'])
            ->byStatus('hilang')
            ->orderBy('nama_barang')
            ->get();

        return $this->generateDanDownloadPdf(
            judul: 'Laporan Barang Hilang',
            jenis: 'barang_hilang',
            view: 'laporan.barang-hilang',
            periodeAwal: $data['periode_awal'],
            periodeAkhir: $data['periode_akhir'],
            data: [
                'barangs' => $barangs,
                'periode_awal' => $data['periode_awal'],
                'periode_akhir' => $data['periode_akhir'],
                'user' => auth()->user(),
            ],
        );
    }

    protected function generateDanDownloadPdf(
        string $judul,
        string $jenis,
        string $view,
        mixed $periodeAwal,
        mixed $periodeAkhir,
        array $data
    ): ?StreamedResponse {
        try {
            $pdf = Pdf::loadView($view, $data);

            $filename = Str::slug($judul).'-'.now()->format('YmdHis').'.pdf';
            $path = 'laporan/'.$filename;

            Storage::put($path, $pdf->output());

            LaporanModel::create([
                'judul_laporan' => $judul,
                'jenis_laporan' => $jenis,
                'periode_awal' => $periodeAwal,
                'periode_akhir' => $periodeAkhir,
                'file_path' => $path,
                'dibuat_oleh' => auth()->id(),
            ]);

            Notification::make()
                ->title($judul.' berhasil digenerate!')
                ->success()
                ->send();

            return response()->streamDownload(static function () use ($pdf) {
                echo $pdf->output();
            }, $filename);
        } catch (\Throwable $exception) {
            report($exception);

            Notification::make()
                ->title('Gagal membuat laporan')
                ->body('Silakan coba lagi. '.$exception->getMessage())
                ->danger()
                ->send();

            return null;
        }
    }
}
