<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogAktivitas extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     */
    protected $table = 'log_aktivitas';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'jenis_aktivitas',
        'nama_tabel',
        'record_id',
        'deskripsi',
        'perubahan_data',
        'ip_address',
        'user_agent',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu.
     */
    protected function casts(): array
    {
        return [
            'record_id' => 'integer',
            'perubahan_data' => 'array',
        ];
    }

    /**
     * Relasi ke pengguna yang melakukan aktivitas.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope untuk filter berdasarkan jenis aktivitas.
     */
    public function scopeJenisAktivitas(Builder $query, string $jenis): Builder
    {
        return $query->where('jenis_aktivitas', $jenis);
    }

    /**
     * Scope untuk filter berdasarkan tabel.
     */
    public function scopeTabel(Builder $query, string $tabel): Builder
    {
        return $query->where('nama_tabel', $tabel);
    }

    /**
     * Scope untuk filter berdasarkan periode tanggal.
     */
    public function scopePeriode(Builder $query, string $dari, string $sampai): Builder
    {
        return $query->whereBetween('created_at', [$dari, $sampai]);
    }
}
