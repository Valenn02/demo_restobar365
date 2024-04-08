<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $fillable =[
        'idcliente',
        'numeroFactura',
        'cuf',
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
}
