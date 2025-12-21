<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RestoreDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:restore {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore database dari file SQL backup';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $filename = $this->argument('file');
            $backupPath = storage_path('backups');
            $filepath = $backupPath.'/'.$filename;

            if (! File::exists($filepath)) {
                $this->error("✗ File backup tidak ditemukan: {$filename}");

                return self::FAILURE;
            }

            if (! $this->confirm('Restore database akan menghapus semua data saat ini. Lanjutkan?')) {
                $this->info('✗ Restore dibatalkan');

                return self::FAILURE;
            }

            // Gunakan mysql
            $database = config('database.connections.mysql.database');
            $host = config('database.connections.mysql.host');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');

            $command = sprintf(
                'mysql --user=%s --password=%s --host=%s %s < %s',
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($host),
                escapeshellarg($database),
                escapeshellarg($filepath)
            );

            exec($command, $output, $status);

            if ($status === 0) {
                $this->info("✓ Database restore berhasil dari: {$filename}");

                return self::SUCCESS;
            } else {
                $this->error('✗ Gagal melakukan restore database');

                return self::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('✗ Error: '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
