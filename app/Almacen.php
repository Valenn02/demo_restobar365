<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    //
    protected $fillable = ['nombre_almacen', 'ubicacion', 'encargado', 'telefono', 'lugar', 'observacion', 'condicion'];

    public function inventario()
    {
        return $this->hasMany('App\Inventario');
    }
}