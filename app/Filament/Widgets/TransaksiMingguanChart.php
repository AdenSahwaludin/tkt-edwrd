<?php

namespace App\Filament\Widgets;

use App\Models\TransaksiBarang;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TransaksiMingguanChart extends ChartWidget
{
    protected ?string $heading = 'Transaksi Mingguan';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $data = TransaksiBarang::select(
                DB::raw('WEEK(tanggal_transaksi) as week'), 
                DB::raw('COUNT(*) as aggregate')
            )
            ->whereBetween('tanggal_transaksi', [$start, $end])
            ->groupBy('week')
            ->orderBy('week')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Transaksi',
                    'data' => $data->pluck('aggregate'),
                    'backgroundColor' => '#10b981',
                ],
            ],
            'labels' => $data->map(fn ($item) => 'Minggu ke-' . $item->week),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
