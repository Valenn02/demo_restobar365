<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleCotizacionVenta extends Model
{
    protected $table = 'detalle_cotizacion';
    protected $fillable = [
        'idventa',
        'idarticulo',
        'cantidad',
        'precio',
        'descuento'
    ];
    public $timestamps = false;
}