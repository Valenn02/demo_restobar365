<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetallePedidoProv extends Model
{
    protected $table = 'detalle_pedidprov';
    protected $fillable = [
        'idpedidoprov', 
        'idarticulo',
        'cantidad_pedidoprov',
    ];
    public $timestamps = false;
}
