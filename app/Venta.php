<?php
 
namespace App;
 
use Illuminate\Database\Eloquent\Model;
 
class Venta extends Model
{
    protected $fillable =[
        'idcliente', 
        'idusuario',
        'idtipo_pago',
        'idsucursal',
        'iddelivery',
        'cliente',
        'documento',
        'tipo_comprobante',
        'serie_comprobante',
        'num_comprobante',
        'fecha_hora',
        'impuesto',
        'total',
        'tipoEntrega',
        'observacion',
        'estado',
        'idcaja'
    ];

    public function caja(){
        return $this->belongsTo('App\Caja', 'id');
    }

    public function delivery()
    {
        return $this->belongsTo('App\Delivery', 'id');
    }
}