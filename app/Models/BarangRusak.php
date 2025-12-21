<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangRusak extends Model
{
    protected $table = 'barang_rusaks';

    protected $fillable = [
        'barang_id',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
