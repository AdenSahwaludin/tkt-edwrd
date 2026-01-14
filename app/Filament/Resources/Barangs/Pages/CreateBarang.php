<?php

namespace App\Filament\Resources\Barangs\Pages;

use App\Filament\Resources\Barangs\BarangResource;
use App\Filament\Resources\Barangs\Schemas\BarangFormStep1;
use App\Filament\Resources\Barangs\Schemas\BarangFormStep2;
use App\Models\Barang;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;

class CreateBarang extends CreateRecord
{
    protected static string $resource = BarangResource::class;

    protected ?string $heading = 'Tambah Barang - Langkah 1 dari 2';

    public static function getNavigationLabel(): string
    {
        return 'Tambah Barang';
    }

    public function form(Schema $schema): Schema
    {
        // Jika sudah ada data tahap 1, tampilkan form tahap 2
        if ($this->data && isset($this->data['lokasi_id']) && ! isset($this->data['kode_barang'])) {
            $this->heading = 'Tambah Barang - Langkah 2 dari 2';

            return BarangFormStep2::configure($schema);
        }

        // Default: tampilkan form tahap 1
        return BarangFormStep1::configure($schema);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Generate kode barang sebelum disimpan
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
}
