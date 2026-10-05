<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\Process\Process;

class BackupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Open database backup page.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('database_backup', 'view') == true) {
            activityLog('Admin', 'Opened Database Backup Menu');
            return view('user::admin.backup.index');
        } else {
            return abort(404);
        }
    }

    /* Download the database */
    public function download(Request $request)
    {
        if (checkRole('database_backup', 'add') == true) {
            $backupFileName = 'database_backup_' . date('Y-m-d_H-i-s') . '.sql';
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s',
                env('DB_USERNAME'),
                env('DB_PASSWORD'),
                env('DB_HOST'),
                env('DB_DATABASE'),
                storage_path('app/' . $backupFileName)
            );
    
            $process = Process::fromShellCommandline($command);
            $process->run();
    
            // Download the backup file
            return response()->download(storage_path('app/' . $backupFileName))->deleteFileAfterSend(true);
        } else {
            return redirect()->back()->with('failure', 'You do not have permission to download backup');
        }
    }
}
