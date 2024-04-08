<?php
 
namespace App;
 
use Illuminate\Database\Eloquent\Model;
 
class CreditoVenta extends Model
{
    protected $table = 'credito_ventas';
    protected $fillable = [
        'idventa', 
        'idpersona',
        'numero_cuotas',
        'tiempo_dias_cuota',
        'estado'
        
    ];
    public $timestamps = false;
}