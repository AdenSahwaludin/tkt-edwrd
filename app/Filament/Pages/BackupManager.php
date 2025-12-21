<?php

namespace App\Filament\Pages;

use App\Models\BackupLog;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

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

            $this->executeBackupCommand($backupLog);

            Notification::make()
                ->title('Backup Berhasil')
                ->body('Database berhasil di-backup.')
                ->success()
                ->send();

            $this->loadBackups();
        } catch (\Exception $e) {
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

            preg_match('/Backup created: (.+?) \(([0-9.]+\s[A-Z]+)\)/', $output, $matches);

            if (! empty($matches[1])) {
                $filename = basename($matches[1]);
                $fileSize = $this->parseFileSize($matches[2] ?? '0 B');

                $backupLog->update([
                    'filename' => $filename,
                    'file_size' => $fileSize,
                    'status' => 'success',
                ]);
            } else {
                $backupLog->update([
                    'status' => 'failed',
                    'notes' => 'Backup command did not return expected format',
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

    public function downloadBackup(int $id): void
    {
        try {
            $backupLog = BackupLog::findOrFail($id);

            if ($backupLog->status !== 'success') {
                throw new \Exception('File backup belum selesai atau gagal diproses.');
            }

            $backupPath = storage_path('backups/'.$backupLog->filename);

            if (! File::exists($backupPath)) {
                Notification::make()
                    ->title('File Tidak Ditemukan')
                    ->body('File backup tidak ada di server.')
                    ->danger()
                    ->send();

                return;
            }

            // Serve file download via route/controller
            // For now, just notify user to download via browser
            Notification::make()
                ->title('Download Siap')
                ->body('File backup siap diunduh. Gunakan command: php artisan backup:download')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Download Gagal')
                ->body($e->getMessage())
                ->danger()
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

            shell_exec('cd "'.base_path().'" && php artisan db:restore '.escapeshellarg($backupLog->filename).' --no-interaction 2>&1');

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

    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision).' '.$units[$pow];
    }

    private function parseFileSize(string $sizeString): int
    {
        $units = ['B' => 1, 'KB' => 1024, 'MB' => 1024 ** 2, 'GB' => 1024 ** 3];
        preg_match('/([0-9.]+)\s*([A-Z]+)/', $sizeString, $matches);

        if (empty($matches)) {
            return 0;
        }

        $size = (float) $matches[1];
        $unit = $matches[2] ?? 'B';

        return (int) ($size * ($units[$unit] ?? 1));
    }
}
