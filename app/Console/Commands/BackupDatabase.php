<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup database ke file SQL';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $backupPath = storage_path('backups');

            // Buat direktori jika belum ada
            if (! File::isDirectory($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }

            $filename = 'backup_'.now()->format('Ymd_His').'.sql';
            $filepath = $backupPath.'/'.$filename;

            // Gunakan mysqldump
            $database = config('database.connections.mysql.database');
            $host = config('database.connections.mysql.host');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');

            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s',
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($host),
                escapeshellarg($database),
                escapeshellarg($filepath)
            );

            exec($command, $output, $status);

            if ($status === 0 && File::exists($filepath)) {
                $size = $this->humanFilesize(File::size($filepath));
                $this->info("✓ Database backup berhasil dibuat: {$filename} ({$size})");

                return self::SUCCESS;
            } else {
                $this->error('✗ Gagal membuat backup database');

                return self::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('✗ Error: '.$e->getMessage());

            return self::FAILURE;
        }
    }

    private function humanFilesize($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision).' '.$units[$i];
    }
}
