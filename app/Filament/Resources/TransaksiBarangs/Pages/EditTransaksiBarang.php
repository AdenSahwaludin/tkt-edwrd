<?php

namespace App\Filament\Resources\TransaksiBarangs\Pages;

use App\Filament\Resources\TransaksiBarangs\TransaksiBarangResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTransaksiBarang extends EditRecord
{
    protected static string $resource = TransaksiBarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * Mutate data before filling the form.
     * Set stok_tersedia with the current stock at the location.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $transaksi = $this->record;

        // Get current stock at the location (excluding this transaction)
        $stokLokasi = \App\Models\StokLokasi::where('barang_id', $transaksi->barang_id)
            ->where('lokasi_id', $transaksi->lokasi_id)
            ->first();

        $data['stok_tersedia'] = $stokLokasi ? $stokLokasi->stok - $transaksi->jumlah : 0;

        return $data;
    }

    /**
     * Mutate data before saving.
     * Clean up stok_tersedia field before saving.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Remove stok_tersedia as it's not a database column
        unset($data['stok_tersedia']);

        return $data;
    }
}
