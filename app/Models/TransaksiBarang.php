<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiBarang extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     */
    protected $table = 'transaksi_barang';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_transaksi',
        'barang_id',
        'tipe_transaksi',
        'jumlah',
        'tanggal_transaksi',
        'penanggung_jawab',
        'keterangan',
        'user_id',
        'approved_by',
        'approved_at',
        'approval_status',
        'approval_notes',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu.
     */
    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
            'tanggal_transaksi' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    /**
     * Relasi ke barang yang ditransaksikan.
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    /**
     * Relasi ke pengguna yang melakukan transaksi.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke pengguna yang menyetujui transaksi.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope untuk transaksi masuk.
     */
    public function scopeMasuk(Builder $query): Builder
    {
        return $query->where('tipe_transaksi', 'masuk');
    }

    /**
     * Scope untuk transaksi keluar.
     */
    public function scopeKeluar(Builder $query): Builder
    {
        return $query->where('tipe_transaksi', 'keluar');
    }

    /**
     * Scope untuk transaksi pending approval.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('approval_status', 'pending');
    }

    /**
     * Scope untuk transaksi yang sudah disetujui.
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('approval_status', 'approved');
    }

    /**
     * Scope untuk transaksi yang ditolak.
     */
    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('approval_status', 'rejected');
    }

    /**
     * Scope untuk filter berdasarkan periode tanggal.
     */
    public function scopePeriode(Builder $query, string $dari, string $sampai): Builder
    {
        return $query->whereBetween('tanggal_transaksi', [$dari, $sampai]);
    }

    /**
     * Cek apakah transaksi ini adalah transaksi masuk.
     */
    public function isMasuk(): bool
    {
        return $this->tipe_transaksi === 'masuk';
    }

    /**
     * Cek apakah transaksi ini adalah transaksi keluar.
     */
    public function isKeluar(): bool
    {
        return $this->tipe_transaksi === 'keluar';
    }

    /**
     * Cek apakah transaksi ini menunggu persetujuan.
     */
    public function isPending(): bool
    {
        return $this->approval_status === 'pending';
    }

    /**
     * Cek apakah transaksi ini sudah disetujui.
     */
    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    /**
     * Cek apakah transaksi ini ditolak.
     */
    public function isRejected(): bool
    {
        return $this->approval_status === 'rejected';
    }
}
