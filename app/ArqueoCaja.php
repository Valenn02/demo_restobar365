<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArqueoCaja extends Model
{
    protected $fillable = [
        'idcaja',
        'idusuario',
        'billete200',
        'billete100',
        'billete50',
        'billete20',
        'billete10',
        'moneda5',
        'moneda2',
        'moneda1',
        'moneda050',
        'moneda020',
        'moneda010'
    ];

    public function caja(){
        return $this->belongsTo('App\Caja');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
