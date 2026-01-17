<?php

namespace App\Filament\Widgets;

use App\Models\TransaksiBarang;
use App\Models\TransaksiKeluar;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class TransaksiChartWidget extends ChartWidget
{
    protected ?string $heading = 'Tren Transaksi Barang (7 Hari Terakhir)';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    /**
     * Data chart untuk transaksi masuk dan keluar 7 hari terakhir.
     */
    protected function getData(): array
    {
        $days = collect();
        $transaksiMasuk = [];
        $transaksiKeluar = [];

        // Generate data untuk 7 hari terakhir
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $days->push($date->format('d M'));

            $transaksiMasuk[] = TransaksiBarang::masuk()
                ->whereDate('tanggal_transaksi', $date)
                ->count();

            $transaksiKeluar[] = TransaksiKeluar::whereDate('tanggal_transaksi', $date)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Barang Masuk',
                    'data' => $transaksiMasuk,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'Barang Keluar',
                    'data' => $transaksiKeluar,
                    'borderColor' => 'rgb(251, 146, 60)',
                    'backgroundColor' => 'rgba(251, 146, 60, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $days->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
