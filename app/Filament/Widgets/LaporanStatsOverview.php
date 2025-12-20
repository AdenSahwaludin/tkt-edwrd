<?php

namespace App\Filament\Widgets;

use App\Models\TransaksiBarang;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class LaporanStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->month;
        $thisYear = Carbon::now()->year;

        return [
            Stat::make('Total Hari Ini', 
                TransaksiBarang::whereDate('tanggal_transaksi', $today)->count() . ' Transaksi')
                ->description('Transaksi tanggal ' . $today->format('d M'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Bulan Ini', 
                TransaksiBarang::whereMonth('tanggal_transaksi', $thisMonth)->whereYear('tanggal_transaksi', $thisYear)->count() . ' Transaksi')
                ->description('Bulan ' . Carbon::now()->format('F'))
                ->color('primary'),

            Stat::make('Total Transaksi', 
                TransaksiBarang::count() . ' Transaksi')
                ->description('Seumur hidup')
                ->color('gray'),
        ];
    }
}
