<?php

namespace App\Filament\Pages;

use App\Models\TransaksiBarang;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use BackedEnum;
use UnitEnum;

class Laporan extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected string $view = 'filament.pages.laporan';

    protected static ?string $navigationLabel = 'Laporan';

    protected static ?string $title = 'Dashboard Laporan';

    protected static string|UnitEnum|null $navigationGroup = 'Laporan';
    
    protected static ?int $navigationSort = 1;

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
                    }, 'laporan-harian-' . Carbon::now()->format('Y-m-d') . '.pdf');
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
                    }, 'laporan-bulanan-' . Carbon::now()->format('Y-m') . '.pdf');
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
