<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StokLokasi extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel di database.
     */
    protected $table = 'stok_lokasi';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'barang_id',
        'lokasi_id',
        'stok',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu.
     */
    protected function casts(): array
    {
        return [
            'stok' => 'integer',
        ];
    }

    /**
     * Relasi ke barang.
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    /**
     * Relasi ke lokasi.
     */
    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }

    /**
     * Tambah stok di lokasi ini.
     */
    public function tambahStok(int $jumlah): void
    {
        $this->increment('stok', $jumlah);
    }

    /**
     * Kurangi stok di lokasi ini.
     */
    public function kurangiStok(int $jumlah): void
    {
        if ($this->stok < $jumlah) {
            throw new \Exception("Stok tidak mencukupi. Stok tersedia: {$this->stok}, diminta: {$jumlah}");
        }

        $this->decrement('stok', $jumlah);
    }

    /**
     * Cek apakah stok mencukupi.
     */
    public function cukupStok(int $jumlah): bool
    {
        return $this->stok >= $jumlah;
    }
}
