<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kategori extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel di database.
     */
    protected $table = 'kategori';

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
    protected $primaryKey = 'kode_kategori';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_kategori',
        'nama_kategori',
        'deskripsi',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kategori) {
            if (empty($kategori->kode_kategori)) {
                $kategori->kode_kategori = self::generateKodeKategori($kategori->nama_kategori);
            }
        });
    }

    /**
     * Generate kode kategori from nama (first 3 letters, uppercase).
     * Example: "Elektronik" -> "ELE", "Furniture" -> "FUR"
     */
    public static function generateKodeKategori(string $namaKategori): string
    {
        // Remove non-letters and take first 3 chars
        $clean = preg_replace('/[^A-Za-z]/', '', $namaKategori);
        $prefix = strtoupper(substr($clean, 0, 3));

        // Check for duplicates
        $counter = 1;
        $kode = $prefix;
        while (self::where('kode_kategori', $kode)->exists()) {
            $kode = $prefix.$counter;
            $counter++;
        }

        return $kode;
    }

    /**
     * Relasi ke barang yang memiliki kategori ini.
     */
    public function barang(): HasMany
    {
        return $this->hasMany(Barang::class, 'kategori_id', 'kode_kategori');
    }
}
