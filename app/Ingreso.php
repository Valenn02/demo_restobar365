<?php
 
namespace App;
 
use Illuminate\Database\Eloquent\Model;
 
class Ingreso extends Model
{
    protected $fillable = [
        'idproveedor', 
        'idusuario',
        'tipo_comprobante',
        'serie_comprobante',
        'num_comprobante',
        'fecha_hora',
        'impuesto',
        'total',
        'estado',
        'idcaja'
     ];
     public function usuario()
     {
         return $this->belongsTo('App\User');
     }
     public function proveedor()
     {
         return $this->belongsTo('App\Proveedor');
     }
     
     public function caja(){
        return $this->belongsTo('App\Caja', 'id');
    }
  
}