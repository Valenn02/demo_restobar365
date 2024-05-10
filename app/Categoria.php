<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categoria_producto';
    protected $primaryKey = 'id';
    protected $fillable = ['nombre','descripcion','codigo','condicion'];

    public function articulos()
    {
        return $this->hasMany('App\Articulo');
    }
}
