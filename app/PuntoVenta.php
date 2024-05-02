<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PuntoVenta extends Model
{
    protected $fillable = [
        'idtipopuntoventa',
        'idsucursal',
        'codigoPuntoVenta',
        'nombre',
        'descripcion',
        'nit',
        'estado'
    ];

    public function sucursales(){
        return $this->belongsTo('App\Sucursales');
    }
}
