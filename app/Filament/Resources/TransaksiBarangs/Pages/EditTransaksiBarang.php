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
     * Set stok_tersedia with the current stock considering the transaction being edited.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $transaksi = $this->record;
        $barang = $transaksi->barang;

        // For keluar transactions, we need to add back the current transaction amount
        // to show the actual available stock if this transaction was cancelled
        if ($transaksi->tipe_transaksi === 'keluar') {
            $data['stok_tersedia'] = $barang->jumlah_stok + $transaksi->jumlah;
        } else {
            // For masuk transactions, subtract the current transaction amount
            $data['stok_tersedia'] = $barang->jumlah_stok - $transaksi->jumlah;
        }

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
