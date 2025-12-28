<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it('can create and restore from backup', function () {
    // Get initial user count from seeded database
    $initialUserCount = User::count();
    expect($initialUserCount)->toBeGreaterThan(0);

    // Create a backup
    Artisan::call('db:backup');
    $backupFiles = File::files(storage_path('backups'));
    expect(count($backupFiles))->toBeGreaterThan(0);

    // Find the latest backup file
    $latestBackup = collect($backupFiles)->sortBy(function ($file) {
        return $file->getMTime();
    })->last();

    $backupFilename = basename($latestBackup);

    // Create a new user to test restoration
    User::create([
        'name' => 'Test User For Restore',
        'email' => 'test-restore-'.time().'@test.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
    ]);

    $userCountAfterCreate = User::count();
    expect($userCountAfterCreate)->toBe($initialUserCount + 1);

    // Restore from backup
    Artisan::call('db:restore', ['file' => $backupFilename, '--force' => true]);

    // Verify user count is back to initial
    $userCountAfterRestore = User::count();
    expect($userCountAfterRestore)->toBe($initialUserCount);
});

it('backup file is properly created with correct format', function () {
    Artisan::call('db:backup');

    $backupFiles = File::files(storage_path('backups'));
    $latestBackup = collect($backupFiles)->sortBy(function ($file) {
        return $file->getMTime();
    })->last();

    $content = File::get($latestBackup);

    // Verify SQL file structure
    expect($content)->toContain('-- MySQL Dump');
    expect($content)->toContain('SET FOREIGN_KEY_CHECKS=0');
    expect($content)->toContain('CREATE TABLE');
    expect($content)->toContain('SET FOREIGN_KEY_CHECKS=1');
});
