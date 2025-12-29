<?php

namespace App\Filament\Pages;

use App\Models\BackupLog;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BackupManager extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-up-tray';

    protected string $view = 'filament.pages.backup-manager';

    protected static ?string $title = 'Backup & Restore Data';

    public ?array $backups = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->hasPermissionTo('backup_system') ?? false;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Sistem';
    }

    public static function getNavigationSort(): ?int
    {
        return 100;
    }

    public function mount(): void
    {
        $this->loadBackups();
    }

    public function loadBackups(): void
    {
        $this->backups = BackupLog::latest()->get()->toArray();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createBackup')
                ->label('Buat Backup Baru')
                ->icon('heroicon-o-plus')
                ->color('success')
                ->action(fn () => $this->createBackup())
                ->requiresConfirmation()
                ->modalHeading('Buat Backup Baru')
                ->modalDescription('Ini akan membuat backup database baru. Proses ini mungkin memerlukan beberapa menit untuk database besar.')
                ->modalSubmitActionLabel('Ya, Buat Backup'),

            Action::make('restoreDatabase')
                ->label('Restore Database')
                ->icon('heroicon-o-arrow-path')
                ->color('danger')
                ->visible(fn () => Auth::user()?->hasPermissionTo('restore_system') ?? false)
                ->form([
                    FileUpload::make('sql_file')
                        ->label('Upload File Backup (.sql)')
                        ->maxSize(102400)
                        ->required()
                        ->rules([
                            function () {
                                return function (string $attribute, $value, \Closure $fail) {
                                    $extension = strtolower(pathinfo($value->getClientOriginalName(), PATHINFO_EXTENSION));
                                    if ($extension !== 'sql') {
                                        $fail('File harus berformat .sql');
                                    }
                                };
                            },
                        ])
                        ->helperText('Pilih file backup .sql yang ingin direstore. Proses ini akan menghapus semua data yang ada!')
                        ->disk('local')
                        ->directory('temp-restores')
                        ->visibility('private')
                        ->storeFiles(false)
                        ->getUploadedFileNameForStorageUsing(
                            fn ($file): string => 'restore_'.time().'.'.$file->getClientOriginalExtension()
                        ),
                ])
                ->action(function (array $data) {
                    // Data dari FileUpload adalah UploadedFile object
                    $uploadedFile = $data['sql_file'];

                    // Jika array (multiple files), ambil yang pertama
                    if (is_array($uploadedFile)) {
                        $uploadedFile = $uploadedFile[0];
                    }

                    $this->restoreFromUpload($uploadedFile);
                })
                ->requiresConfirmation()
                ->modalHeading('Restore Database dari File')
                ->modalDescription('⚠️ PERINGATAN: Proses ini akan menghapus SEMUA data yang ada di database dan menggantinya dengan data dari file backup. Pastikan Anda sudah membuat backup terlebih dahulu!')
                ->modalSubmitActionLabel('Ya, Restore Database')
                ->modalIcon('heroicon-o-exclamation-triangle'),
        ];
    }

    public function createBackup(): void
    {
        try {
            $backupLog = BackupLog::create([
                'user_id' => Auth::id(),
                'status' => 'pending',
                'format' => 'sql',
            ]);

            // Execute backup menggunakan Artisan call (synchronous)
            $exitCode = Artisan::call('db:backup');
            $output = Artisan::output();

            Log::info('Backup command executed', ['exit_code' => $exitCode, 'output' => $output]);

            // Get latest backup file
            $backupDir = storage_path('backups');
            $files = File::files($backupDir);

            if (empty($files)) {
                throw new \Exception('Tidak ada file backup yang ditemukan.');
            }

            // Sort by modification time, get newest
            usort($files, function ($a, $b) {
                return $b->getMTime() - $a->getMTime();
            });

            $latestFile = $files[0];
            $filename = basename($latestFile->getPathname());
            $fileSize = $latestFile->getSize();

            Log::info('Updating backup log', ['id' => $backupLog->id, 'filename' => $filename, 'file_size' => $fileSize]);

            // Update backup log
            $updated = $backupLog->update([
                'filename' => $filename,
                'file_size' => $fileSize,
                'status' => 'success',
            ]);

            Log::info('Backup log updated', ['success' => $updated, 'model' => $backupLog->fresh()->toArray()]);

            Notification::make()
                ->title('Backup Berhasil')
                ->body("Database berhasil di-backup: {$filename}")
                ->success()
                ->send();

            $this->loadBackups();
        } catch (\Exception $e) {
            Log::error('Backup failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            if (isset($backupLog)) {
                $backupLog->update([
                    'status' => 'failed',
                    'notes' => $e->getMessage(),
                ]);
            }

            Notification::make()
                ->title('Backup Gagal')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    private function executeBackupCommand(BackupLog $backupLog): void
    {
        try {
            $output = shell_exec('cd "'.base_path().'" && php artisan db:backup 2>&1');

            // Check if backup was created successfully
            if (strpos($output, 'Backup created') !== false || strpos($output, 'Database backup') !== false) {
                // Get latest backup file from storage
                $backupDir = storage_path('backups');
                $files = File::files($backupDir);

                if (empty($files)) {
                    throw new \Exception('No backup files found in storage/backups');
                }

                // Sort by modification time, get newest
                usort($files, function ($a, $b) {
                    return $b->getMTime() - $a->getMTime();
                });

                $latestFile = $files[0];
                $filename = basename($latestFile->getPathname());
                $fileSize = $latestFile->getSize();

                $backupLog->update([
                    'filename' => $filename,
                    'file_size' => $fileSize,
                    'status' => 'success',
                ]);
            } else {
                $backupLog->update([
                    'status' => 'failed',
                    'notes' => 'Backup command did not return expected format. Output: '.$output,
                ]);
            }
        } catch (\Exception $e) {
            $backupLog->update([
                'status' => 'failed',
                'notes' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function restoreFromUpload($uploadedFile): void
    {
        try {
            if (! Auth::user()->hasPermissionTo('restore_system')) {
                throw new \Exception('Anda tidak memiliki izin untuk restore database.');
            }

            // Jika string, anggap sebagai path
            if (is_string($uploadedFile)) {
                $tempPath = $uploadedFile;
                $originalName = basename($uploadedFile);
            } else {
                // Jika UploadedFile object, ambil path real-nya
                $tempPath = $uploadedFile->getRealPath();
                $originalName = $uploadedFile->getClientOriginalName();
            }

            if (! File::exists($tempPath)) {
                throw new \Exception('File backup tidak ditemukan.');
            }

            $fileSize = File::size($tempPath);
            $userId = Auth::id();

            // Baca file SQL dari temporary location
            $sql = File::get($tempPath);

            // Pastikan SQL file tidak kosong
            if (empty(trim($sql))) {
                throw new \Exception('File backup kosong atau tidak valid.');
            }

            // Disable foreign key checks dan execute
            DB::unprepared('SET FOREIGN_KEY_CHECKS=0');
            DB::unprepared($sql);
            DB::unprepared('SET FOREIGN_KEY_CHECKS=1');

            // Buat log restore SETELAH restore selesai (karena restore menimpa database)
            BackupLog::create([
                'user_id' => $userId,
                'filename' => $originalName,
                'file_size' => $fileSize,
                'format' => 'sql',
                'status' => 'success',
                'notes' => 'Restore berhasil dari file upload: '.$originalName,
            ]);

            // Clear cache Laravel
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');

            Notification::make()
                ->title('Restore Berhasil')
                ->body('Database berhasil di-restore dari file: '.$originalName)
                ->success()
                ->duration(3000)
                ->send();

            // Reload data
            $this->loadBackups();

            // Redirect untuk refresh halaman
            $this->redirect(route('filament.admin.pages.backup-manager'), navigate: false);

        } catch (\Exception $e) {
            // Pastikan foreign key checks enabled kembali
            try {
                DB::unprepared('SET FOREIGN_KEY_CHECKS=1');
            } catch (\Exception $ignored) {
            }

            Notification::make()
                ->title('Restore Gagal')
                ->body('Error: '.$e->getMessage())
                ->danger()
                ->duration(10000)
                ->send();
        }
    }

    public function restoreBackup(int $id): void
    {
        try {
            $backupLog = BackupLog::findOrFail($id);

            if (! Auth::user()->hasPermissionTo('restore_system')) {
                throw new \Exception('Anda tidak memiliki izin untuk restore database.');
            }

            if ($backupLog->status !== 'success') {
                throw new \Exception('File backup belum selesai atau gagal diproses.');
            }

            // Use Artisan::call for proper restoration with force flag
            \Illuminate\Support\Facades\Artisan::call('db:restore', [
                'file' => $backupLog->filename,
                '--force' => true,
            ]);

            Notification::make()
                ->title('Restore Berhasil')
                ->body('Database berhasil di-restore dari backup.')
                ->success()
                ->send();

            $this->loadBackups();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Restore Gagal')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function deleteBackup(int $id): void
    {
        try {
            $backupLog = BackupLog::findOrFail($id);
            $backupLog->delete();

            Notification::make()
                ->title('Backup Dihapus')
                ->body('File backup berhasil dihapus dari database.')
                ->success()
                ->send();

            $this->loadBackups();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Penghapusan Gagal')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
