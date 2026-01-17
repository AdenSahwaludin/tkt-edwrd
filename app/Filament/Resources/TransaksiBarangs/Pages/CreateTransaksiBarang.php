<?php

namespace App\Filament\Resources\TransaksiBarangs\Pages;

use App\Filament\Resources\TransaksiBarangs\TransaksiBarangResource;
use App\Models\TransaksiBarang;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateTransaksiBarang extends CreateRecord
{
    protected static string $resource = TransaksiBarangResource::class;

    /**
     * Mutate data before creating the record.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set user_id from authenticated user
        $data['user_id'] = Auth::id();

        // Default tipe_transaksi for transaksi barang (only masuk)
        if (empty($data['tipe_transaksi'])) {
            $data['tipe_transaksi'] = 'masuk';
        }

        // Generate kode_transaksi
        $data['kode_transaksi'] = $this->generateKodeTransaksi($data['tipe_transaksi']);

        return $data;
    }

    /**
     * Generate unique transaction code.
     */
    private function generateKodeTransaksi(string $tipe): string
    {
        $prefix = $tipe === 'masuk' ? 'TM' : 'TK';
        $date = now()->format('Ymd');

        // Get last transaction code for today
        $lastTransaction = TransaksiBarang::where('kode_transaksi', 'like', "{$prefix}-{$date}-%")
            ->orderBy('kode_transaksi', 'desc')
            ->first();

        if ($lastTransaction) {
            // Extract the sequence number and increment
            $lastSequence = (int) substr($lastTransaction->kode_transaksi, -3);
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }

        return sprintf('%s-%s-%03d', $prefix, $date, $newSequence);
    }
}
