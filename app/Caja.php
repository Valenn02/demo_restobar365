<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $fillable =[
        'id',
        'idsucursal', 
        'idusuario', 
        'fechaApertura', 
        'fechaCierre', 
        'saldoInicial', 
        'depositos', 
        'salidas', 
        'ventasContado', 
        'ventasCredito', 
        'comprasContado', 
        'comprasCredito', 
        'saldoFaltante', 
        'saldoSobrante', 
        'saldoCaja', 
        'estado'
    ];

    public function sucursal(){
        return $this->belongsTo('App\Sucursales');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function venta()
    {
        return $this->hasOne('App\Venta');
    }

    public function ingreso()
    {
        return $this->hasOne('App\Ingreso');
    }
}
