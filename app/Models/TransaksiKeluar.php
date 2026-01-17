<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiKeluar extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel di database.
     */
    protected $table = 'transaksi_keluar';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_transaksi',
        'tipe',
        'barang_id',
        'lokasi_asal_id',
        'lokasi_tujuan_id',
        'jumlah',
        'tanggal_transaksi',
        'penanggung_jawab',
        'keterangan',
        'approval_status',
        'approved_by',
        'approved_at',
        'user_id',
    ];

    /**
     * Bootstrap model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaksi) {
            // Auto-generate kode_transaksi
            if (empty($transaksi->kode_transaksi) && ! empty($transaksi->tipe)) {
                $transaksi->kode_transaksi = static::generateKodeTransaksi($transaksi->tipe);
            }

            // Set default approval status
            if (empty($transaksi->approval_status)) {
                $transaksi->approval_status = 'pending';
            }
        });
    }

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
     * Relasi ke barang.
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    /**
     * Relasi ke lokasi asal.
     */
    public function lokasiAsal(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_asal_id');
    }

    /**
     * Relasi ke lokasi tujuan (optional untuk pemindahan).
     */
    public function lokasiTujuan(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_tujuan_id');
    }

    /**
     * Relasi ke user yang input transaksi.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke user yang approve.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Generate kode transaksi otomatis.
     */
    public static function generateKodeTransaksi(string $tipe): string
    {
        $prefix = match ($tipe) {
            'pemindahan' => 'PMD',
            'peminjaman' => 'PJM',
            'penggunaan' => 'PGN',
            'penghapusan' => 'PHP',
            default => 'TRX',
        };

        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', now())->count() + 1;

        return sprintf('%s-%s-%03d', $prefix, $date, $count);
    }

    /**
     * Approve transaksi keluar.
     */
    public function approve(int $userId): void
    {
        $this->update([
            'approval_status' => 'approved',
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);
    }

    /**
     * Reject transaksi keluar.
     */
    public function reject(int $userId): void
    {
        $this->update([
            'approval_status' => 'rejected',
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);
    }
}
