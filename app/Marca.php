<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    //
    
    protected $fillable = ['nombre','condicion'];
    public $timestamps = false;

    public function articulos()
    {
        return $this->hasMany('App\Articulo');
    }
}
