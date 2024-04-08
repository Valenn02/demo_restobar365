<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventosSignificativos extends Model
{
    protected $fillable = [
        'idmotivoevento',
        'descripcion',
        'cufdEvento',
        'nit',
        'codigoMotivoEvento',
        'cufd',
        'inicioEvento',
        'finEvento',
        'estado'
    ];

    public function motivoEvento(){
        return $this->belongsTo('App\MotivoEvento');
    }
}
