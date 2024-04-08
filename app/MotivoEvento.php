<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MotivoEvento extends Model
{
    protected $fillable = [
        'codigo',
        'descripcion'
    ];

    public function eventosSignificativos()
    {
        return $this->hasMany('App\EventosSignificativos');
    }
}

