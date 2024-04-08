<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionTrabajo extends Model
{
    //
    protected $table = 'configuracion_trabajos';

    protected $fillable = [
        'id','gestion','codigoProductos','consultasAlmacenes','limiteDescuento','maximoDescuento','monedaPrincipal','valuacionInventario','backupAutomatico',
        'rutaBackup','saldosNegativos','monedaTrabajo','separadorDecimales','mostrarCostos','mostrarProveedores',
        'mostrarSaldosStock','actualizarIva','vendedorAsignado','permitirDevolucion','editarNroDoc',
        'registroClienteObligatorio','buscarClientePorCodigo'
    ];
}
