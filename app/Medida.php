<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medida extends Model
{
    //
    protected $fillable =[
        'descripcion_medida','descripcion_corta','estado'
    ];
}
