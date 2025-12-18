<?php

namespace App\Filament\Pages;

use App\Models\Barang;
use App\Models\Laporan;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;
use UnitEnum;

class LaporanBarangRusak extends Page implements HasForms
{
    use InteractsWithForms;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = 'Laporan Barang Rusak';

    protected static ?string $title = 'Laporan Barang Rusak';

    protected static string|UnitEnum|null $navigationGroup = 'Laporan';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.laporan-barang-rusak';

    public ?string $periode_awal = null;

    public ?string $periode_akhir = null;

    public function mount(): void
    {
        $this->form->fill([
            'periode_awal' => now()->startOfMonth()->format('Y-m-d'),
            'periode_akhir' => now()->endOfMonth()->format('Y-m-d'),
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('periode_awal')
                ->label('Tanggal Mulai')
                ->required()
                ->default(now()->startOfMonth())
                ->maxDate(fn ($get) => $get('periode_akhir')),
            DatePicker::make('periode_akhir')
                ->label('Tanggal Akhir')
                ->required()
                ->default(now()->endOfMonth())
                ->minDate(fn ($get) => $get('periode_awal')),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generate')
                ->label('Generate PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    $data = $this->form->getState();

                    // Query barang rusak
                    $barangs = Barang::with(['kategori', 'lokasi'])
                        ->byStatus('rusak')
                        ->orderBy('nama_barang')
                        ->get();

                    // Generate PDF
                    $pdf = Pdf::loadView('laporan.barang-rusak', [
                        'barangs' => $barangs,
                        'periode_awal' => $data['periode_awal'],
                        'periode_akhir' => $data['periode_akhir'],
                        'user' => auth()->user(),
                    ]);

                    $filename = 'laporan-barang-rusak-'.now()->format('YmdHis').'.pdf';
                    $path = 'laporan/'.$filename;

                    Storage::put($path, $pdf->output());

                    // Simpan record laporan
                    Laporan::create([
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
                }),
        ];
    }
}
