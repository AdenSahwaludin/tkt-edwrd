<x-filament-panels::page>
    <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-4">
        {{-- Total Barang Card --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <div class="rounded-full bg-blue-100 p-2.5 dark:bg-blue-900/50">
                            <x-heroicon-o-cube class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                        </div>
                    </div>
                    <div class="ml-4 w-0 flex-1">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Barang</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ $stats['total_barang'] }}</p>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $stats['barang_baik'] }} dalam
                            kondisi baik</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Barang Rusak Card --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <div class="rounded-full bg-red-100 p-2.5 dark:bg-red-900/50">
                            <x-heroicon-o-wrench-screwdriver class="h-5 w-5 text-red-600 dark:text-red-400" />
                        </div>
                    </div>
                    <div class="ml-4 w-0 flex-1">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Barang Rusak</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ $stats['barang_rusak'] }}</p>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Perlu perbaikan atau penggantian</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Barang Hilang Card --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <div class="rounded-full bg-yellow-100 p-2.5 dark:bg-yellow-900/50">
                            <x-heroicon-o-magnifying-glass class="h-5 w-5 text-yellow-600 dark:text-yellow-400" />
                        </div>
                    </div>
                    <div class="ml-4 w-0 flex-1">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Barang Hilang</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ $stats['barang_hilang'] }}</p>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Dalam proses pencarian</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Transaksi Bulan Ini Card --}}
        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <div class="rounded-full bg-green-100 p-2.5 dark:bg-green-900/50">
                            <x-heroicon-o-arrow-path class="h-5 w-5 text-green-600 dark:text-green-400" />
                        </div>
                    </div>
                    <div class="ml-4 w-0 flex-1">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Transaksi Bulan Ini</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ $stats['transaksi_bulan_ini'] }}</p>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            <span
                                class="font-medium text-green-600 dark:text-green-400">{{ $stats['transaksi_masuk'] }}</span>
                            <span class="text-gray-500 dark:text-gray-400"> masuk</span>
                            <span class="mx-1 text-gray-400">•</span>
                            <span
                                class="font-medium text-red-600 dark:text-red-400">{{ $stats['transaksi_keluar'] }}</span>
                            <span class="text-gray-500 dark:text-gray-400"> keluar</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Additional Info Section --}}
    <div class="mt-6 rounded-lg bg-white p-6 shadow dark:bg-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informasi Laporan</h3>
        <div class="mt-4 space-y-3 text-sm text-gray-600 dark:text-gray-400">
            <div class="flex items-start gap-3">
                <x-heroicon-o-document-text class="mt-0.5 h-5 w-5 shrink-0 text-green-500" />
                <div>
                    <strong class="text-gray-900 dark:text-gray-100">Laporan Inventaris Bulanan:</strong>
                    Menampilkan daftar seluruh barang inventaris beserta detail kategori, lokasi, stok, dan ringkasan
                    transaksi masuk/keluar dalam periode tertentu.
                </div>
            </div>
            <div class="flex items-start gap-3">
                <x-heroicon-o-wrench-screwdriver class="mt-0.5 h-5 w-5 shrink-0 text-red-500" />
                <div>
                    <strong class="text-gray-900 dark:text-gray-100">Laporan Barang Rusak:</strong>
                    Menampilkan daftar barang dengan status rusak yang memerlukan perbaikan atau penggantian.
                </div>
            </div>
            <div class="flex items-start gap-3">
                <x-heroicon-o-magnifying-glass class="mt-0.5 h-5 w-5 shrink-0 text-yellow-500" />
                <div>
                    <strong class="text-gray-900 dark:text-gray-100">Laporan Barang Hilang:</strong>
                    Menampilkan daftar barang dengan status hilang untuk keperluan tracking dan investigasi.
                </div>
            </div>
        </div>
        <div class="mt-6 rounded-lg bg-blue-50 p-4 dark:bg-blue-900/20">
            <div class="flex items-start gap-3">
                <x-heroicon-o-information-circle class="mt-0.5 h-5 w-5 shrink-0 text-blue-500" />
                <p class="text-sm text-blue-800 dark:text-blue-300">
                    Klik tombol di pojok kanan atas untuk generate dan download laporan dalam format PDF. Semua laporan
                    yang dihasilkan akan tersimpan otomatis dalam sistem.
                </p>
            </div>
        </div>
    </div>

    {{-- Riwayat Laporan --}}
    <div class="mt-6 rounded-lg bg-white p-6 shadow dark:bg-gray-800">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Riwayat Laporan</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Semua laporan yang sudah digenerate bisa diunduh
                    ulang.</p>
            </div>
            <span
                class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600 dark:bg-gray-700 dark:text-gray-200">Auto-save</span>
        </div>

        @if ($laporanTerakhir->isEmpty())
            <div
                class="mt-4 rounded-lg border border-dashed border-gray-200 bg-gray-50 p-6 text-center text-sm text-gray-600 dark:border-gray-700 dark:bg-gray-900/30 dark:text-gray-300">
                Belum ada laporan yang tersimpan. Silakan generate laporan terlebih dahulu.
            </div>
        @else
            <div class="mt-4 overflow-hidden rounded-lg border border-gray-100 shadow-sm dark:border-gray-800">
                <table class="min-w-full divide-y divide-gray-100 text-sm dark:divide-gray-800">
                    <thead
                        class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-900/40 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3">Judul</th>
                            <th class="px-4 py-3">Jenis</th>
                            <th class="px-4 py-3">Periode</th>
                            <th class="px-4 py-3">Dibuat Oleh</th>
                            <th class="px-4 py-3">Dibuat</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white dark:divide-gray-800 dark:bg-gray-900/30">
                        @foreach ($laporanTerakhir as $laporan)
                            @php
                                $label = match ($laporan->jenis_laporan) {
                                    'inventaris_bulanan' => 'Inventaris Bulanan',
                                    'barang_rusak' => 'Barang Rusak',
                                    'barang_hilang' => 'Barang Hilang',
                                    default => ucfirst($laporan->jenis_laporan),
                                };
                            @endphp
                            <tr>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $laporan->judul_laporan }}
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-200">{{ $label }}</span>
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                    {{ $laporan->periode_awal->format('d M Y') }} -
                                    {{ $laporan->periode_akhir->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                    {{ $laporan->pembuatLaporan?->name ?? 'Tidak diketahui' }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                    {{ $laporan->created_at->format('d M Y H:i') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <button type="button" wire:click="downloadLaporan({{ $laporan->id }})"
                                        class="inline-flex items-center gap-1 rounded-md bg-primary-50 px-3 py-1.5 text-xs font-semibold text-primary-700 transition hover:bg-primary-100 dark:bg-primary-900/30 dark:text-primary-200 dark:hover:bg-primary-900/50">
                                        <x-heroicon-o-arrow-down-tray class="h-4 w-4" />
                                        Download
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-filament-panels::page>
