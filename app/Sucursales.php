<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sucursales extends Model
{
    //
    protected $fillable =[
        'idempresa','nombre','codigoSucursal','direccion','correo','telefono','departamento','condicion'
    ];
    public function empresa(){
        return $this->belongsTo('App\Empresa');
    }

    public function puntosVenta()
{
    return $this->hasMany('App\PuntoVenta', 'idsucursal', 'id');
}

}
