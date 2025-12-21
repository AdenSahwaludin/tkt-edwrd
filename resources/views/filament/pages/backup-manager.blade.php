<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Backups List -->
        @if (count($this->backups) > 0)
            <div
                class="overflow-x-auto rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Nama File
                            </th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Format
                            </th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Ukuran
                            </th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Status
                            </th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Dibuat
                                Oleh</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Waktu
                                Backup</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($this->backups as $backup)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $backup['filename'] ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span
                                        class="inline-block px-2 py-1 text-xs font-medium rounded-full
                                        @if ($backup['format'] === 'sql') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                        @elseif ($backup['format'] === 'json')
                                            bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @else
                                            bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif
                                    ">
                                        {{ strtoupper($backup['format']) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    @if ($backup['file_size'])
                                        {{ number_format($backup['file_size'] / 1024 / 1024, 2) }} MB
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span
                                        class="inline-block px-2 py-1 text-xs font-medium rounded-full
                                        @if ($backup['status'] === 'success') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif ($backup['status'] === 'failed')
                                            bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @elseif ($backup['status'] === 'pending')
                                            bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @else
                                            bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif
                                    ">
                                        {{ ucfirst($backup['status']) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $backup['user_id'] ? \App\Models\User::find($backup['user_id'])?->name : '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($backup['created_at'])->format('d M Y H:i:s') }}
                                </td>
                                <td class="px-6 py-4 text-sm space-x-2">
                                    @if ($backup['status'] === 'success')
                                        <a href="{{ route('backup.download', ['id' => $backup['id']]) }}"
                                            class="inline-flex items-center px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 16.5V9m0 0l-3 3m3-3l3 3M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z">
                                                </path>
                                            </svg>
                                            Download
                                        </a>
                                        @if (auth()->user()?->hasPermissionTo('restore_system'))
                                            <button wire:click="restoreBackup({{ $backup['id'] }})"
                                                class="inline-flex items-center px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                    </path>
                                                </svg>
                                                Restore
                                            </button>
                                        @endif
                                    @endif
                                    <button wire:click="deleteBackup({{ $backup['id'] }})"
                                        class="inline-flex items-center px-3 py-1 bg-gray-500 text-white text-xs rounded hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div
                class="rounded-lg border border-gray-200 bg-white p-6 text-center dark:border-gray-700 dark:bg-gray-900">
                <p class="text-sm text-gray-600 dark:text-gray-400">Belum ada backup. Klik tombol "Buat Backup Baru"
                    untuk membuat backup pertama Anda.</p>
            </div>
        @endif
    </div>
</x-filament-panels::page>
