<?php

namespace App\Filament\Resources\TransaksiKeluars\Pages;

use App\Filament\Resources\TransaksiKeluars\TransaksiKeluarResource;
use App\Models\TransaksiKeluar;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaksiKeluar extends CreateRecord
{
    protected static string $resource = TransaksiKeluarResource::class;

    protected ?string $heading = 'Tambah Transaksi Keluar';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generate kode transaksi
        $data['kode_transaksi'] = TransaksiKeluar::generateKodeTransaksi($data['tipe']);

        // Set user_id
        $data['user_id'] = auth()->id();

        // Set default approval status
        $data['approval_status'] = 'pending';

        return $data;
    }
}
