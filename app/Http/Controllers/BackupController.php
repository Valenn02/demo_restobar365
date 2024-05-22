<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Logging\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function runBackup()
    {
        \Log::info('PATH actual: ' . getenv('PATH'));

        Artisan::call('backup:run');
        


        $output = Artisan::output();

        \Log::info('Backup Output: ' . $output);

        if (strpos($output, 'Backup completed!') !== false) {
            return response()->json(['success' => true, 'message' => 'Backup realizado exitosamente.']);
        }

        return response()->json(['success' => false, 'message' => 'Hubo un problema al realizar el backup.', 'output' => $output]);
    }

    /*public function backupDatabase()
    {
        $databaseName = config('database.connections.mysql.database');

        $backupFile = str_replace('/', '\\', storage_path('app/backup/' . $databaseName . '_' . date('Ymd_His') . '.sql'));
        $command = sprintf('mysqldump -u %s -p %s %s > %s', config('database.connections.mysql.username'), config('database.connections.mysql.password'), $databaseName, $backupFile);
        //dd($command);
        exec($command);

        if (file_exists($backupFile)) {
            Storage::disk('local')->put('backups/' . basename($backupFile), file_get_contents($backupFile));
            unlink($backupFile);
            return response()->json(['success' => true, 'message' => 'Backup realizado exitosamente.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Hubo un problema al realizar el backup.']);
        }
    }*/

    public function backupDatabase()
    {
        $databaseName = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $backupFileName = $databaseName . '_' . date('Ymd_His') . '.sql';
        $backupFile = storage_path('app/backup/' . $backupFileName);

        $command = sprintf(
            'mysqldump -u %s -p %s %s > %s',
            //escapeshellarg($host),
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($databaseName),
            escapeshellarg($backupFile)
        );
        /*$command = sprintf('mysqldump -u %s -p %s %s > %s', config('database.connections.mysql.username'), 
        config('database.connections.mysql.password'), $databaseName, $backupFile);*/
        dd($command);

        $output = [];
        $returnVar = null;
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            return response()->json(['success' => false, 'message' => 'Hubo un problema al realizar el backup.']);
        }

        if (file_exists($backupFile)) {
            Storage::disk('local')->put('backups/' . basename($backupFile), file_get_contents($backupFile));
            unlink($backupFile);
            return response()->json(['success' => true, 'message' => 'Backup realizado exitosamente.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Hubo un problema al realizar el backup.']);
        }
    }

}
