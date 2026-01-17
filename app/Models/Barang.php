<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel di database.
     */
    protected $table = 'barang';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = true;

    /**
     * The "type" of the auto-incrementing ID.
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'id';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'nama_barang',
        'kategori_id',
        'satuan',
        'status',
        'deskripsi',
        'harga_satuan',
        'merk',
        'tanggal_pembelian',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu.
     */
    protected function casts(): array
    {
        return [
            'harga_satuan' => 'decimal:2',
            'tanggal_pembelian' => 'date',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($barang) {
            if (empty($barang->id)) {
                $barang->id = self::generateId($barang->kategori_id, $barang->nama_barang);
            }
        });
    }

    /**
     * Relasi ke kategori barang.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * Relasi ke stok lokasi (barang bisa ada di multiple lokasi).
     */
    public function stokLokasi(): HasMany
    {
        return $this->hasMany(StokLokasi::class, 'barang_id');
    }

    /**
     * Relasi ke transaksi barang.
     */
    public function transaksiBarang(): HasMany
    {
        return $this->hasMany(TransaksiBarang::class, 'barang_id');
    }

    /**
     * Relasi ke transaksi keluar.
     */
    public function transaksiKeluar(): HasMany
    {
        return $this->hasMany(TransaksiKeluar::class, 'barang_id');
    }

    /**
     * Relasi ke barang rusak.
     */
    public function barangRusak(): HasMany
    {
        return $this->hasMany(BarangRusak::class, 'barang_id');
    }

    /**
     * Get total stok barang di semua lokasi.
     */
    public function getTotalStokAttribute(): int
    {
        return $this->stokLokasi()->sum('stok');
    }

    /**
     * Get list lokasi dimana barang ini tersimpan.
     */
    public function getLokasiListAttribute(): string
    {
        return $this->stokLokasi()
            ->with('lokasi')
            ->get()
            ->pluck('lokasi.nama_lokasi')
            ->join(', ');
    }

    /**
     * Scope untuk barang dengan status tertentu.
     */
    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Generate ID barang dengan format: HPE-ELE-001
     * Format: 3 huruf nama + 3 huruf kategori + nomor urut (001, 002, dst)
     */
    public static function generateId(?string $kategoriId, ?string $namaBarang): string
    {
        if (! $kategoriId || ! $namaBarang) {
            throw new \Exception('Kategori dan Nama Barang harus diisi untuk generate ID');
        }

        $kategori = Kategori::find($kategoriId);
        if (! $kategori) {
            throw new \Exception('Kategori tidak ditemukan');
        }

        // Ambil 3 huruf pertama dari nama barang (hapus spasi, ambil huruf saja)
        $cleanNama = preg_replace('/[^A-Za-z]/', '', $namaBarang);
        $namaCode = strtoupper(substr($cleanNama, 0, 3));

        // Ambil 3 huruf pertama dari kategori
        $cleanKategori = preg_replace('/[^A-Za-z]/', '', $kategori->nama_kategori);
        $kategoriCode = strtoupper(substr($cleanKategori, 0, 3));

        // Generate prefix
        $prefix = "{$namaCode}-{$kategoriCode}";

        // Cari nomor urut terakhir dengan prefix yang sama
        $lastBarang = self::where('id', 'LIKE', "{$prefix}-%")
            ->orderBy('id', 'desc')
            ->first();

        $sequence = 1;
        if ($lastBarang) {
            // Extract nomor dari ID terakhir (HPE-ELE-001 -> 001)
            preg_match('/-(\d+)$/', $lastBarang->id, $matches);
            if (isset($matches[1])) {
                $sequence = (int) $matches[1] + 1;
            }
        }

        return sprintf('%s-%03d', $prefix, $sequence);
    }

    /**
     * Generate kode barang otomatis (DEPRECATED - gunakan generateId).
     * Tetap dipertahankan untuk backward compatibility.
     */
    public static function generateKodeBarang(?int $kategoriId, ?int $lokasiId, ?string $namaBarang): string
    {
        return self::generateId($kategoriId, $namaBarang);
    }
}
