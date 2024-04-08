<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoPuntoVenta extends Model
{
    protected $fillable = [
        'codigo',
        'descripcion'
    ];

    public function puntoVenta()
    {
        return $this->hasOne('App\PuntoVenta');
    }
}
