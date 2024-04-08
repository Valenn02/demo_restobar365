<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = [
        'id', 'nombre', 'direccion', 'telefono', 'email', 'monedaPrincipal', 'valorMaximoDescuento', 'tipoCambio1', 'tipoCambio2', 'tipoCambio3', 'licencia'
    ];
    public function sucursal(){
        return $this->belongsTo('App\Sucursales');
    }
}
