<x-filament-panels::page>
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
        {{-- Total Barang Card --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900/50">
                            <x-heroicon-o-cube class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Barang</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                    {{ \App\Models\Barang::count() }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 dark:bg-gray-900/50">
                <div class="text-sm">
                    <span class="font-medium text-gray-900 dark:text-gray-100">
                        {{ \App\Models\Barang::where('status', 'baik')->count() }}
                    </span>
                    <span class="text-gray-500 dark:text-gray-400">dalam kondisi baik</span>
                </div>
            </div>
        </div>

        {{-- Barang Rusak Card --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-full bg-red-100 p-3 dark:bg-red-900/50">
                            <x-heroicon-o-wrench-screwdriver class="h-6 w-6 text-red-600 dark:text-red-400" />
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Barang Rusak</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                    {{ \App\Models\Barang::where('status', 'rusak')->count() }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 dark:bg-gray-900/50">
                <div class="text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Perlu perbaikan atau penggantian</span>
                </div>
            </div>
        </div>

        {{-- Barang Hilang Card --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-full bg-yellow-100 p-3 dark:bg-yellow-900/50">
                            <x-heroicon-o-magnifying-glass class="h-6 w-6 text-yellow-600 dark:text-yellow-400" />
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Barang Hilang</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                    {{ \App\Models\Barang::where('status', 'hilang')->count() }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 dark:bg-gray-900/50">
                <div class="text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Dalam proses pencarian</span>
                </div>
            </div>
        </div>

        {{-- Total Transaksi Bulan Ini Card --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-full bg-green-100 p-3 dark:bg-green-900/50">
                            <x-heroicon-o-arrow-path class="h-6 w-6 text-green-600 dark:text-green-400" />
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Transaksi Bulan Ini</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                    {{ \App\Models\TransaksiBarang::whereBetween('tanggal_transaksi', [
                                        now()->startOfMonth(),
                                        now()->endOfMonth()
                                    ])->count() }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 dark:bg-gray-900/50">
                <div class="text-sm">
                    <span class="font-medium text-green-600 dark:text-green-400">
                        {{ \App\Models\TransaksiBarang::where('tipe_transaksi', 'masuk')
                            ->whereBetween('tanggal_transaksi', [now()->startOfMonth(), now()->endOfMonth()])
                            ->count() }}
                    </span>
                    <span class="text-gray-500 dark:text-gray-400">masuk</span>
                    <span class="mx-1 text-gray-400">•</span>
                    <span class="font-medium text-red-600 dark:text-red-400">
                        {{ \App\Models\TransaksiBarang::where('tipe_transaksi', 'keluar')
                            ->whereBetween('tanggal_transaksi', [now()->startOfMonth(), now()->endOfMonth()])
                            ->count() }}
                    </span>
                    <span class="text-gray-500 dark:text-gray-400">keluar</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Additional Info Section --}}
    <div class="mt-6 rounded-lg bg-white p-6 shadow dark:bg-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informasi Laporan</h3>
        <div class="mt-4 space-y-3 text-sm text-gray-600 dark:text-gray-400">
            <div class="flex items-start gap-3">
                <x-heroicon-o-document-text class="mt-0.5 h-5 w-5 flex-shrink-0 text-green-500" />
                <div>
                    <strong class="text-gray-900 dark:text-gray-100">Laporan Inventaris Bulanan:</strong>
                    Menampilkan daftar seluruh barang inventaris beserta detail kategori, lokasi, stok, dan ringkasan transaksi masuk/keluar dalam periode tertentu.
                </div>
            </div>
            <div class="flex items-start gap-3">
                <x-heroicon-o-wrench-screwdriver class="mt-0.5 h-5 w-5 flex-shrink-0 text-red-500" />
                <div>
                    <strong class="text-gray-900 dark:text-gray-100">Laporan Barang Rusak:</strong>
                    Menampilkan daftar barang dengan status rusak yang memerlukan perbaikan atau penggantian.
                </div>
            </div>
            <div class="flex items-start gap-3">
                <x-heroicon-o-magnifying-glass class="mt-0.5 h-5 w-5 flex-shrink-0 text-yellow-500" />
                <div>
                    <strong class="text-gray-900 dark:text-gray-100">Laporan Barang Hilang:</strong>
                    Menampilkan daftar barang dengan status hilang untuk keperluan tracking dan investigasi.
                </div>
            </div>
        </div>
        <div class="mt-6 rounded-lg bg-blue-50 p-4 dark:bg-blue-900/20">
            <div class="flex items-start gap-3">
                <x-heroicon-o-information-circle class="mt-0.5 h-5 w-5 flex-shrink-0 text-blue-500" />
                <p class="text-sm text-blue-800 dark:text-blue-300">
                    Klik tombol di pojok kanan atas untuk generate dan download laporan dalam format PDF. Semua laporan yang dihasilkan akan tersimpan otomatis dalam sistem.
                </p>
            </div>
        </div>
    </div>
</x-filament-panels::page>
