<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CotizacionVenta extends Model
{
    protected $table = 'cotizacion_venta';
    //
    protected $fillable = [
        'idcliente',
        'idusuario',
        'fecha_hora',
        'impuesto',
        'total',
        'idcaja'
    ];

    public function caja()
    {
        return $this->belongsTo('App\Caja', 'id');
    }
}