<?php

namespace App\Filament\Pages;

use App\Models\Barang;
use App\Models\Laporan;
use App\Models\TransaksiBarang;
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

class LaporanInventaris extends Page implements HasForms
{
    use InteractsWithForms;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Laporan Inventaris Bulanan';

    protected static ?string $title = 'Laporan Inventaris Bulanan';

    protected static string|UnitEnum|null $navigationGroup = 'Laporan';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.laporan-inventaris';

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
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {
                    $data = $this->form->getState();

                    // Query barang dengan relasi
                    $barangs = Barang::with(['kategori', 'lokasi'])
                        ->orderBy('kategori_id')
                        ->orderBy('nama_barang')
                        ->get();

                    // Hitung transaksi dalam periode
                    $transaksi_masuk = TransaksiBarang::masuk()
                        ->whereBetween('tanggal_transaksi', [$data['periode_awal'], $data['periode_akhir']])
                        ->count();

                    $transaksi_keluar = TransaksiBarang::keluar()
                        ->whereBetween('tanggal_transaksi', [$data['periode_awal'], $data['periode_akhir']])
                        ->count();

                    // Generate PDF
                    $pdf = Pdf::loadView('laporan.inventaris-bulanan', [
                        'barangs' => $barangs,
                        'periode_awal' => $data['periode_awal'],
                        'periode_akhir' => $data['periode_akhir'],
                        'transaksi_masuk' => $transaksi_masuk,
                        'transaksi_keluar' => $transaksi_keluar,
                        'user' => auth()->user(),
                    ]);

                    $filename = 'laporan-inventaris-'.now()->format('YmdHis').'.pdf';
                    $path = 'laporan/'.$filename;

                    Storage::put($path, $pdf->output());

                    // Simpan record laporan
                    Laporan::create([
                        'judul_laporan' => 'Laporan Inventaris Bulanan',
                        'jenis_laporan' => 'inventaris_bulanan',
                        'periode_awal' => $data['periode_awal'],
                        'periode_akhir' => $data['periode_akhir'],
                        'file_path' => $path,
                        'dibuat_oleh' => auth()->id(),
                    ]);

                    Notification::make()
                        ->title('Laporan berhasil digenerate!')
                        ->success()
                        ->send();

                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, $filename);
                }),
        ];
    }
}
