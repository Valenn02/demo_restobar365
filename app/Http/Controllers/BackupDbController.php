<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Backup\BackupDestination\Backup;
use App\ConfiguracionTrabajo;
use Spatie\Backup\BackupDestination\BackupDestinationFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class BackupDbController extends Controller
{
    public function createBackup()
    {
        // Nombre del archivo de respaldo
        $fileName = 'backup_' . date('Ymd_His') . '.sql';
        $configuracionTrabajo = ConfiguracionTrabajo::first();
        
        // Ruta de almacenamiento del archivo de respaldo
        //$backupPath = 'D:\laravel\Respaldos\sisventas\storage\app\backups\\'.$fileName;
        //$backupPath = 'D:\laravel\backups\\'.$fileName;
        $backupPath = $configuracionTrabajo->rutaBackup . '\\'.$fileName;

        Log::info('configuracion', [
            'ruta' => $backupPath, 
        ]);

        // Comando para generar el archivo de respaldo
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s --port=%s --databases %s > %s 2>&1',
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            env('DB_HOST'),
            env('DB_PORT'),
            env('DB_DATABASE'),
            $backupPath
        );

        // Ejecutar el comando de respaldo
        exec($command);

        // Verificar si se generó el archivo de respaldo
        if (!file_exists($backupPath) || filesize($backupPath) === 0) {
            return ['error' => 'Error al crear el backup'];
        }

        return ['exito' => 'Backup de la base de datos exitoso..!'];
    }
}
