<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Precio extends Model
{
    protected $table = 'precios';

    protected $fillable = [
        'nombre_precio',
        'porcentage',
        'condicion',
    ];

    // Relación con el modelo Articulo
    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }
}
