<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoPago extends Model
{
    //
    protected $table = 'tipo_pagos';
    //protected $fillable = ['id', 'nombre_tipo_pago', 'descripcion_tipo_pago', 'condicion'];
    protected $fillable = ['id', 'nombre_tipo_pago'];
}
