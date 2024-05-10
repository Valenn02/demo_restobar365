<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacturaFueraLinea extends Model
{
    protected $fillable =[
        'idcliente',
        'idventa',
        'numeroFactura',
        'cuf',
        'cufd',
        'codigoControl',
        'correo',
        'fechaEmision',
        'codigoMetodoPago',
        'montoTotal',
        'montoTotalSujetoIva',
        'descuentoAdicional',
        'productos',
        'estado'
    ];

    public function persona(){
        return $this->belongsTo('App\Persona');
    }

    public function venta(){
        return $this->belongsTo('App\Venta');
    }
}
