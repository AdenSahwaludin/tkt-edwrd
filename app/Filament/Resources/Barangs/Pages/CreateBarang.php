<?php

namespace App\Filament\Resources\Barangs\Pages;

use App\Filament\Resources\Barangs\BarangResource;
use App\Filament\Resources\Barangs\Schemas\BarangFormStep1;
use App\Models\Barang;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;

class CreateBarang extends CreateRecord
{
    protected static string $resource = BarangResource::class;

    protected ?string $heading = 'Tambah Barang - Langkah 1: Data Barang';

    public function form(Schema $schema): Schema
    {
        return BarangFormStep1::configure($schema);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Pada step 1, kita simpan tanpa lokasi dan kode barang
        $data['lokasi_id'] = null;
        $data['kode_barang'] = null;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        // Redirect ke halaman khusus untuk menambahkan lokasi
        return $this->getResource()::getUrl('add-location', ['record' => $this->getRecord()]);
    }
}
