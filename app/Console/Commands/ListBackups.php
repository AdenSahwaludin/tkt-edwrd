<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ListBackups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:list-backups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List semua backup database yang tersedia';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $backupPath = storage_path('backups');

        if (! File::isDirectory($backupPath)) {
            $this->info('Belum ada backup database');

            return self::SUCCESS;
        }

        $files = File::files($backupPath);

        if (count($files) === 0) {
            $this->info('Belum ada backup database');

            return self::SUCCESS;
        }

        $backups = [];
        foreach ($files as $file) {
            $backups[] = [
                'Filename' => $file->getFilename(),
                'Size' => $this->humanFilesize($file->getSize()),
                'Created' => date('d M Y H:i', $file->getMTime()),
            ];
        }

        // Sort by created date (newest first)
        usort($backups, function ($a, $b) {
            return strtotime($b['Created']) <=> strtotime($a['Created']);
        });

        $this->table(['Filename', 'Size', 'Created'], $backups);

        return self::SUCCESS;
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
