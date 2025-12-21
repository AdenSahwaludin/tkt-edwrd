<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\LaporanStatsOverview;
use App\Filament\Widgets\TransaksiBulananChart;
use App\Filament\Widgets\TransaksiHarianChart;
use App\Filament\Widgets\TransaksiMingguanChart;
use App\Models\TransaksiBarang;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use UnitEnum;

class LaporanDashboard extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Dashboard Laporan';

    protected static ?string $title = 'Analisis Transaksi';

    protected static string|UnitEnum|null $navigationGroup = 'Laporan';

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        return auth()->user()?->hasPermissionTo('view_laporan') ?? false;
    }

    protected string $view = 'filament.pages.laporan-dashboard';

    public function getHeaderWidgets(): array
    {
        return [
            LaporanStatsOverview::class,
            TransaksiHarianChart::class,
            TransaksiMingguanChart::class,
            TransaksiBulananChart::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return [
            'md' => 2,
            'xl' => 2,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('laporan_harian')
                ->label('Laporan Harian')
                ->icon('heroicon-o-calendar')
                ->action(function () {
                    return response()->streamDownload(function () {
                        $transaksi = TransaksiBarang::with(['barang', 'user'])
                            ->whereDate('tanggal_transaksi', Carbon::today())
                            ->get();

                        $pdf = Pdf::loadView('pdf.laporan-harian', ['data' => $transaksi]);
                        echo $pdf->output();
                    }, 'laporan-harian-'.Carbon::now()->format('Y-m-d').'.pdf');
                }),

            Action::make('laporan_bulanan')
                ->label('Laporan Bulanan')
                ->icon('heroicon-o-calendar-days')
                ->action(function () {
                    return response()->streamDownload(function () {
                        $transaksi = TransaksiBarang::with(['barang', 'user'])
                            ->whereMonth('tanggal_transaksi', Carbon::now()->month)
                            ->whereYear('tanggal_transaksi', Carbon::now()->year)
                            ->get();

                        $pdf = Pdf::loadView('pdf.laporan-bulanan', ['data' => $transaksi]);
                        echo $pdf->output();
                    }, 'laporan-bulanan-'.Carbon::now()->format('Y-m').'.pdf');
                }),

            Action::make('laporan_transaksi')
                ->label('Semua Transaksi')
                ->icon('heroicon-o-clipboard-document-list')
                ->action(function () {
                    return response()->streamDownload(function () {
                        $transaksi = TransaksiBarang::with(['barang', 'user'])->get();

                        $pdf = Pdf::loadView('pdf.laporan-transaksi', ['data' => $transaksi]);
                        echo $pdf->output();
                    }, 'laporan-transaksi-all.pdf');
                }),
        ];
    }
}
