<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable =[
        'nombre',
        'telfono',
        'estado'
    ];

    public function venta()
    {
        return $this->hasOne('App\Venta');
    }
}
