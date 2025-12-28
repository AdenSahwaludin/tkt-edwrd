<?php

namespace App\Http\Controllers;

use App\Models\BackupLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupDownloadController extends Controller
{
    public function download(int $id): BinaryFileResponse
    {
        // Check authorization
        if (! Auth::user()?->hasPermissionTo('backup_system')) {
            abort(403, 'Unauthorized to download backups');
        }

        $backupLog = BackupLog::findOrFail($id);

        if ($backupLog->status !== 'success' || ! $backupLog->filename) {
            abort(404, 'Backup file not found or not ready');
        }

        $backupPath = storage_path('backups/'.$backupLog->filename);

        if (! File::exists($backupPath)) {
            abort(404, 'Backup file not found on server: '.$backupPath);
        }

        return Response::download($backupPath, $backupLog->filename, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="'.$backupLog->filename.'"',
        ]);
    }
}
