<?php

namespace App\Filament\Widgets;

use App\Models\TransaksiBarang;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TransaksiHarianChart extends ChartWidget
{
    protected ?string $heading = 'Tren Transaksi Harian (Bulan Ini)';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $data = TransaksiBarang::select(
                DB::raw('DATE(tanggal_transaksi) as date'), 
                DB::raw('COUNT(*) as aggregate')
            )
            ->whereBetween('tanggal_transaksi', [$start, $end])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $values = [];
        
        $current = $start->copy();
        while ($current <= $end) {
            $dateStr = $current->format('Y-m-d');
            $labels[] = $current->format('d M');
            
            $record = $data->firstWhere('date', $dateStr);
            $values[] = $record ? $record->aggregate : 0;
            
            $current->addDay();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Transaksi',
                    'data' => $values,
                    'borderColor' => '#3b82f6',
                    'fill' => true,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
