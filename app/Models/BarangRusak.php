<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BarangRusak extends Model
{
    use SoftDeletes;

    protected $table = 'barang_rusaks';

    protected $fillable = [
        'barang_id',
        'lokasi_id',
        'jumlah',
        'tanggal_kejadian',
        'keterangan',
        'penanggung_jawab',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
            'tanggal_kejadian' => 'date',
        ];
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get stok lokasi yang terkait dengan barang rusak ini.
     */
    public function stokLokasi(): BelongsTo
    {
        return $this->belongsTo(StokLokasi::class, null, 'barang_id', 'barang_id')
            ->where('lokasi_id', $this->lokasi_id);
    }
}
