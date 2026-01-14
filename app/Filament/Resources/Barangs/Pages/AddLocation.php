<?php

namespace App\Filament\Resources\Barangs\Pages;

use App\Filament\Resources\Barangs\BarangResource;
use App\Filament\Resources\Barangs\Schemas\BarangFormStep2;
use App\Models\Barang;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;

class AddLocation extends EditRecord
{
    protected static string $resource = BarangResource::class;

    protected ?string $heading = 'Tambah Barang - Langkah 2: Lokasi Penyimpanan';

    public function form(Schema $schema): Schema
    {
        return BarangFormStep2::configure($schema);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Generate kode barang setelah lokasi dipilih
        if (isset($data['kategori_id'], $data['nama_barang'], $data['lokasi_id'])) {
            $data['kode_barang'] = Barang::generateKodeBarang(
                $data['kategori_id'],
                $data['lokasi_id'],
                $data['nama_barang']
            );
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan & Selesai')
                ->submit('save'),
        ];
    }
}
