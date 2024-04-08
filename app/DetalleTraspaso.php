<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleTraspaso extends Model
{
    protected $table = 'detalle_traspasos';
    protected $fillable = [
        'idtraspaso', 
        'idinventario',
        'cantidad_traspaso',
    ];
    public $timestamps = false;
}
