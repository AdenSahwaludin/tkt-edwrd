<?php

use App\Http\Controllers\BackupDownloadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin/login');
});

// Backup download route
Route::get('/admin/backups/{id}/download', [BackupDownloadController::class, 'download'])
    ->middleware('auth')
    ->name('backup.download');

// Route::get('/', function () {
//     return view('welcome');
// });
