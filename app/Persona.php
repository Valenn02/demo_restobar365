<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable = ['id','nombre','tipo_documento','num_documento','complemento_id','direccion','telefono','email','fotografia','estado'];

    public function provedor()
    {
        return $this->hasOne('App\Proveedor');
    }

    public function user()
    {
        return $this->hasOne('App\User');
    }


}
