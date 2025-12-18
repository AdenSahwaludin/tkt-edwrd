<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Laporan extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     */
    protected $table = 'laporan';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'judul_laporan',
        'jenis_laporan',
        'periode_awal',
        'periode_akhir',
        'file_path',
        'dibuat_oleh',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu.
     */
    protected function casts(): array
    {
        return [
            'periode_awal' => 'date',
            'periode_akhir' => 'date',
        ];
    }

    /**
     * Relasi ke pengguna yang membuat laporan.
     */
    public function pembuatLaporan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    /**
     * Scope untuk filter berdasarkan jenis laporan.
     */
    public function scopeJenis(Builder $query, string $jenis): Builder
    {
        return $query->where('jenis_laporan', $jenis);
    }
}
