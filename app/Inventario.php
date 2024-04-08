<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $fillable = ['idalmacen', 'idarticulo', 'fecha_vencimiento', 'saldo_stock'];

    public function almacen()
    {
        return $this->belongsTo('App\Almacen');
    }
    public function articulo()
    {
        return $this->belongsTo('App\Articulo');
    }
}