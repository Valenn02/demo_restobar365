<?php
 
namespace App;
 
use Illuminate\Database\Eloquent\Model;
 
class CuotasCredito extends Model
{
    protected $table = 'cuotas_credito';
    protected $fillable = [
        'idcredito', 
        'fecha_pago',
        'fecha_cancelado',
        'precio_cuota',
        'total_cancelado',
        'saldo',
        'estado'
    ];
    public $timestamps = false;
}