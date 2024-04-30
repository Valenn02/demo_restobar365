<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class categoria_menu extends Model
{
    protected $table = 'categoria_menu';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre',
        'descripcion',
        'condicion',
    ];

    public function menu()
    {
        return $this->hasMany('App\menu');
    }
}
