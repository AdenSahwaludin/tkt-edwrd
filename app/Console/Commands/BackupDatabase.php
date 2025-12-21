<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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

            // Get database configuration
            $database = config('database.connections.mysql.database');
            $host = config('database.connections.mysql.host');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');

            // Use pure PHP to create backup (works without mysqldump)
            $pdo = DB::connection()->getPdo();

            // Start SQL dump
            $sql = "-- MySQL Dump\n";
            $sql .= "-- Generated at: ".now()->toDateTimeString()."\n";
            $sql .= "-- Database: {$database}\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            // Get all tables
            $tables = DB::select('SHOW TABLES');
            $tableKey = "Tables_in_{$database}";

            foreach ($tables as $table) {
                $tableName = $table->$tableKey;

                // Get table structure
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`")[0];
                $sql .= "-- Structure for table `{$tableName}`\n";
                $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sql .= $createTable->{'Create Table'}.";\n\n";

                // Get table data
                $rows = DB::table($tableName)->get();
                if ($rows->count() > 0) {
                    $sql .= "-- Data for table `{$tableName}`\n";

                    foreach ($rows as $row) {
                        $values = [];
                        foreach ((array) $row as $value) {
                            if (is_null($value)) {
                                $values[] = 'NULL';
                            } else {
                                $values[] = "'".addslashes($value)."'";
                            }
                        }
                        $sql .= 'INSERT INTO `'.$tableName.'` VALUES ('.implode(', ', $values).");\n";
                    }
                    $sql .= "\n";
                }
            }

            $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

            // Write to file
            File::put($filepath, $sql);

            if (File::exists($filepath)) {
                $size = $this->humanFilesize(File::size($filepath));
                $this->info("Backup created: {$filepath} ({$size})");

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
