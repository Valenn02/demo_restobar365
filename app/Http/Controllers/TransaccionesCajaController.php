<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Ingreso;
use App\TransaccionesCaja;
use App\Venta;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TransaccionesCajaController extends Controller
{
     public function index(Request $request, $id)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;

        if ($buscar==''){
            $transaccionesCajas = TransaccionesCaja::join('cajas', 'transacciones_cajas.idcaja', '=', 'cajas.id')
                ->join('users', 'transacciones_cajas.idusuario', '=', 'users.id')
                ->select('transacciones_cajas.id', 'transacciones_cajas.idcaja', 'transacciones_cajas.idusuario', 'users.usuario as usuario', 'transacciones_cajas.fecha', 'transacciones_cajas.transaccion', 'transacciones_cajas.importe', 'cajas.fechaApertura')
                ->where('transacciones_cajas.idcaja', '=', $id)
                ->orderBy('transacciones_cajas.id', 'desc')->paginate(6);
        



        }

        $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
        ->join('users','ingresos.idusuario','=','users.id')
        ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
        'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
        'ingresos.estado','personas.nombre','users.usuario')
        ->orderBy('ingresos.id', 'desc')->where('idcaja', $id)->get();
        $ventas = Venta::join('personas', 'ventas.idcliente', '=', 'personas.id')
                ->join('users', 'ventas.idusuario', '=', 'users.id')
                ->select(
                    'ventas.id',
                    'ventas.tipo_comprobante',
                    'ventas.serie_comprobante',
                    'ventas.num_comprobante',
                    'ventas.fecha_hora',
                    'ventas.impuesto',
                    'ventas.total',
                    'ventas.estado',
                    'personas.nombre',
                    'users.usuario'
                )
                ->orderBy('ventas.id', 'desc')->where('idcaja', $id)->paginate(3);

        return [
            'pagination' => [
                'total'        => $transaccionesCajas->total(),
                'current_page' => $transaccionesCajas->currentPage(),
                'per_page'     => $transaccionesCajas->perPage(),
                'last_page'    => $transaccionesCajas->lastPage(),
                'from'         => $transaccionesCajas->firstItem(),
                'to'           => $transaccionesCajas->lastItem(),
            ],
            'transacciones' => $transaccionesCajas,
            'ingresos' =>  $ingresos,
            'ventas'=> $ventas

        ];
    }
}
