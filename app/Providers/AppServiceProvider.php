<?php

namespace App\Providers;

use App\Models\Barang;
use App\Models\BarangRusak;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\TransaksiBarang;
use App\Models\TransaksiKeluar;
use App\Observers\BarangObserver;
use App\Observers\BarangRusakObserver;
use App\Observers\KategoriObserver;
use App\Observers\LokasiObserver;
use App\Observers\TransaksiBarangObserver;
use App\Observers\TransaksiKeluarObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Eloquent model observers untuk auto-update stok dan log aktivitas
        TransaksiBarang::observe(TransaksiBarangObserver::class);
        TransaksiKeluar::observe(TransaksiKeluarObserver::class);
        Barang::observe(BarangObserver::class);
        BarangRusak::observe(BarangRusakObserver::class);
        Kategori::observe(KategoriObserver::class);
        Lokasi::observe(LokasiObserver::class);
    }
}
