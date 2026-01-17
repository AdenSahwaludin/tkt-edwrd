<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lokasi extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel di database.
     */
    protected $table = 'lokasi';

    /**
     * The primary key type.
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'kode_lokasi';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_lokasi',
        'nama_lokasi',
        'gedung',
        'lantai',
        'keterangan',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($lokasi) {
            if (empty($lokasi->kode_lokasi)) {
                $lokasi->kode_lokasi = self::generateKodeLokasi($lokasi->nama_lokasi);
            }
        });
    }

    /**
     * Generate kode lokasi from nama (first 2-3 letters, uppercase).
     * Example: "Gudang" -> "GD", "Lab Komputer" -> "LK"
     */
    public static function generateKodeLokasi(string $namaLokasi): string
    {
        // Split by space and take first letter of each word (max 3)
        $words = explode(' ', $namaLokasi);
        $kode = '';
        foreach (array_slice($words, 0, 3) as $word) {
            $clean = preg_replace('/[^A-Za-z]/', '', $word);
            if (! empty($clean)) {
                $kode .= strtoupper(substr($clean, 0, 1));
            }
        }

        // If less than 2 chars, take first 2-3 letters from full name
        if (strlen($kode) < 2) {
            $clean = preg_replace('/[^A-Za-z]/', '', $namaLokasi);
            $kode = strtoupper(substr($clean, 0, min(3, strlen($clean))));
        }

        // Check for duplicates
        $counter = 1;
        $baseKode = $kode;
        while (self::where('kode_lokasi', $kode)->exists()) {
            $kode = $baseKode.$counter;
            $counter++;
        }

        return $kode;
    }

    /**
     * Relasi ke stok barang di lokasi ini.
     */
    public function stokLokasi(): HasMany
    {
        return $this->hasMany(StokLokasi::class, 'lokasi_id', 'kode_lokasi');
    }
}
