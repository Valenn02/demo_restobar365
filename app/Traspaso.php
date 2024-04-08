<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Traspaso extends Model
{
    protected $table = 'traspasos';
    protected $fillable = 
    ['tipo_traspaso','idusuario','almacen_origen','almacen_destino','fecha_traspaso'
    ];
    public function usuario()
    {
        return $this->belongsTo('App\User');
    }
}
