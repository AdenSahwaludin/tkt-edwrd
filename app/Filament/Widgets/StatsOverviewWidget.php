<?php

namespace App\Filament\Widgets;

use App\Models\Barang;
use App\Models\TransaksiBarang;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected static ?int $sort = 1;

    /**
     * Statistik overview untuk dashboard inventaris.
     */
    protected function getStats(): array
    {
        $totalBarang = Barang::count();
        $barangStokRendah = Barang::stokRendah()->count();
        $transaksiMasukBulanIni = TransaksiBarang::masuk()
            ->whereMonth('tanggal_transaksi', now()->month)
            ->whereYear('tanggal_transaksi', now()->year)
            ->count();
        $transaksiKeluarBulanIni = TransaksiBarang::keluar()
            ->whereMonth('tanggal_transaksi', now()->month)
            ->whereYear('tanggal_transaksi', now()->year)
            ->count();

        return [
            Stat::make('Total Jenis Barang', $totalBarang)
                ->description('Total jenis barang dalam inventaris')
                ->descriptionIcon('heroicon-o-cube')
                ->color('success')
                ->chart([7, 12, 5, 18, 22, 15, $totalBarang]),

            Stat::make('Barang Stok Rendah', $barangStokRendah)
                ->description('Barang dengan stok ≤ ROP')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color('danger')
                ->chart([3, 5, 2, 8, 6, 4, $barangStokRendah]),

            Stat::make('Transaksi Masuk', $transaksiMasukBulanIni)
                ->description('Transaksi masuk bulan ini')
                ->descriptionIcon('heroicon-o-arrow-down-tray')
                ->color('info')
                ->chart([12, 18, 15, 22, 19, 25, $transaksiMasukBulanIni]),

            Stat::make('Transaksi Keluar', $transaksiKeluarBulanIni)
                ->description('Transaksi keluar bulan ini')
                ->descriptionIcon('heroicon-o-arrow-up-tray')
                ->color('warning')
                ->chart([8, 12, 10, 15, 13, 18, $transaksiKeluarBulanIni]),
        ];
    }
}
