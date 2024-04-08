<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $fillable = ['nombre_grupo'];

    public function articulos()
    {
        return $this->hasMany('App\Articulo');
    }
}
