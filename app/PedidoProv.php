<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidoProv extends Model
{
    protected $table = 'pedido_proveedors';
    protected $fillable =
        [
            'idusuario',
            'idproveedor',
            'idalmacen',
            'fecha_pedido',
            'fecha_entrega',
            'forma_pago',
            'observacion',
            'total',
            'estado'
        ];
}