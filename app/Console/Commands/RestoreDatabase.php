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
    protected $signature = 'db:restore {file} {--force : Skip confirmation}';

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

            if (! $this->option('force') && ! $this->confirm('Restore database akan menghapus semua data saat ini. Lanjutkan?')) {
                $this->info('✗ Restore dibatalkan');

                return self::FAILURE;
            }

            // Baca SQL file dan execute queries
            $sql = File::get($filepath);

            // Split by semicolon and filter empty queries
            $queries = array_filter(
                array_map('trim', explode(';', $sql)),
                fn ($query) => ! empty($query)
            );

            try {
                // Disable foreign key checks temporarily
                \DB::statement('SET FOREIGN_KEY_CHECKS=0');

                // Execute setiap query
                // Note: DDL statements (CREATE/DROP TABLE) will auto-commit, so we don't wrap in transaction
                foreach ($queries as $query) {
                    try {
                        \DB::statement($query);
                    } catch (\Exception $e) {
                        // If a query fails, log it but continue
                        $this->warn('Query error: '.$e->getMessage());
                        throw $e;
                    }
                }

                // Re-enable foreign key checks
                \DB::statement('SET FOREIGN_KEY_CHECKS=1');

                $this->info("✓ Database restore berhasil dari: {$filename}");

                return self::SUCCESS;
            } catch (\Exception $e) {
                // Try to re-enable foreign key checks on error
                try {
                    \DB::statement('SET FOREIGN_KEY_CHECKS=1');
                } catch (\Exception $ignored) {
                }

                $this->error('✗ Error saat restore: '.$e->getMessage());

                return self::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('✗ Error: '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
