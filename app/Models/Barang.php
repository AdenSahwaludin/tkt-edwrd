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
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori_id',
        'lokasi_id',
        'jumlah_stok',
        'reorder_point',
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
            'jumlah_stok' => 'integer',
            'reorder_point' => 'integer',
            'harga_satuan' => 'decimal:2',
            'tanggal_pembelian' => 'date',
        ];
    }

    /**
     * Relasi ke kategori barang.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * Relasi ke lokasi penyimpanan barang.
     */
    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }

    /**
     * Relasi ke transaksi barang.
     */
    public function transaksiBarang(): HasMany
    {
        return $this->hasMany(TransaksiBarang::class, 'barang_id');
    }

    /**
     * Scope untuk barang dengan stok rendah (stok <= reorder point).
     */
    public function scopeStokRendah(Builder $query): Builder
    {
        return $query->whereColumn('jumlah_stok', '<=', 'reorder_point');
    }

    /**
     * Scope untuk barang dengan status tertentu.
     */
    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Cek apakah stok barang rendah.
     */
    public function isStokRendah(): bool
    {
        return $this->jumlah_stok <= $this->reorder_point;
    }

    /**
     * Hitung nilai total inventaris barang ini.
     */
    public function nilaiTotal(): float
    {
        return $this->jumlah_stok * ($this->harga_satuan ?? 0);
    }

    /**
     * Generate kode barang otomatis dengan format: KATEGORI(3)-NAMABARANG(2)-LOKASI(2)-SEQ(3)
     * Contoh: ELE-KL-RG-001 (Elektronik - Kursi Lipat - Ruang Guru - 001)
     */
    public static function generateKodeBarang(?int $kategoriId, ?int $lokasiId, ?string $namaBarang): string
    {
        // Default values jika parameter null
        $kategoriCode = 'XXX';
        $lokasiCode = 'XX';
        $namaCode = 'XX';

        // Ambil 3 huruf pertama dari nama kategori
        if ($kategoriId) {
            $kategori = Kategori::find($kategoriId);
            if ($kategori) {
                $kategoriCode = strtoupper(substr($kategori->nama_kategori, 0, 3));
            }
        }

        // Ambil 2 huruf dari nama lokasi
        if ($lokasiId) {
            $lokasi = Lokasi::find($lokasiId);
            if ($lokasi) {
                // Ambil inisial dari setiap kata, maksimal 2 huruf
                $words = explode(' ', $lokasi->nama_lokasi);
                $initials = '';
                foreach ($words as $word) {
                    if (strlen($initials) < 2 && ! empty($word)) {
                        $initials .= strtoupper(substr($word, 0, 1));
                    }
                }
                // Jika hanya 1 kata atau 1 inisial, ambil 2 huruf pertama
                $lokasiCode = strlen($initials) >= 2 ? $initials : strtoupper(substr($lokasi->nama_lokasi, 0, 2));
            }
        }

        // Ambil 2 huruf inisial dari nama barang (per kata)
        if ($namaBarang) {
            $words = explode(' ', $namaBarang);
            $initials = '';
            foreach ($words as $word) {
                if (strlen($initials) < 2 && ! empty($word)) {
                    $initials .= strtoupper(substr($word, 0, 1));
                }
            }
            // Jika hanya 1 kata atau 1 inisial, ambil 2 huruf pertama
            $namaCode = strlen($initials) >= 2 ? $initials : strtoupper(substr($namaBarang, 0, 2));
        }

        // Generate prefix
        $prefix = "{$kategoriCode}-{$namaCode}-{$lokasiCode}";

        // Cari nomor urut terakhir dengan prefix yang sama
        $lastBarang = self::withTrashed()
            ->where('kode_barang', 'LIKE', "{$prefix}-%")
            ->orderBy('kode_barang', 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastBarang) {
            // Extract nomor dari kode terakhir (ambil 3 digit terakhir)
            $lastNumber = (int) substr($lastBarang->kode_barang, -3);
            $nextNumber = $lastNumber + 1;
        }

        // Format nomor dengan 3 digit (001, 002, dst)
        $sequenceNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return "{$prefix}-{$sequenceNumber}";
    }
}
