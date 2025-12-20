<?php

namespace App\Filament\Widgets;

use App\Models\TransaksiBarang;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TransaksiBulananChart extends ChartWidget
{
    protected ?string $heading = 'Rekap Bulanan (Tahun Ini)';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $year = Carbon::now()->year;

        $data = TransaksiBarang::select(
                DB::raw('MONTH(tanggal_transaksi) as month'), 
                DB::raw('COUNT(*) as aggregate')
            )
            ->whereYear('tanggal_transaksi', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = [];
        $values = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $labels[] = Carbon::create()->month($i)->format('F');
            $record = $data->firstWhere('month', $i);
            $values[] = $record ? $record->aggregate : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Transaksi',
                    'data' => $values,
                    'backgroundColor' => '#f59e0b',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
