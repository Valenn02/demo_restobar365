<?php

namespace App\Http\Controllers;

use App\PuntoVenta;
use App\Sucursales;
use App\TipoPuntoVenta;
use Illuminate\Http\Request;

class PuntoVentaController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            $punto_ventas = PuntoVenta::join('tipo_punto_ventas', 'punto_ventas.idtipopuntoventa', '=', 'tipo_punto_ventas.id')
            ->join('sucursales', 'punto_ventas.idsucursal', '=', 'sucursales.id')
            ->select('punto_ventas.*', 'tipo_punto_ventas.codigo as codigotipo', 'tipo_punto_ventas.descripcion as descripcionTipo', 'sucursales.nombre as nombreSucursal')
            ->orderBy('punto_ventas.id', 'desc')->paginate(3);
        }
        else{
            $punto_ventas = PuntoVenta::join('tipo_punto_ventas', 'punto_ventas.idtipopuntoventa', '=', 'tipo_punto_ventas.id')
            ->join('sucursales', 'punto_ventas.idsucursal', '=', 'sucursales.id')
            ->select('punto_ventas.*', 'tipo_punto_ventas.codigo as codigotipo', 'tipo_punto_ventas.descripcion as descripcionTipo', 'sucursales.nombre as nombreSucursal')
            ->where('punto_ventas.' . $criterio, 'like', '%' . $buscar . '%')
            ->orderBy('punto_ventas.id', 'desc')->paginate(3);
        }

        return [
            'pagination' => [
                'total'        => $punto_ventas->total(),
                'current_page' => $punto_ventas->currentPage(),
                'per_page'     => $punto_ventas->perPage(),
                'last_page'    => $punto_ventas->lastPage(),
                'from'         => $punto_ventas->firstItem(),
                'to'           => $punto_ventas->lastItem(),
            ],
            'punto_ventas' => $punto_ventas
        ];
    }

    public function obtenerDatosTipoPuntoVenta(Request $request){
        if (!$request->ajax()) return redirect('/');

        $tipo_punto_ventas = TipoPuntoVenta::select('id', 'descripcion', 'codigo')
        ->orderBy('id', 'desc')
        ->get();

        return ['tipo_punto_ventas' => $tipo_punto_ventas];
    }

    public function obtenerDatosSucursal(Request $request){
        if (!$request->ajax()) return redirect('/');

        $sucursales = Sucursales::select('id', 'nombre')
        ->orderBy('id', 'desc')
        ->get();

        return ['sucursales' => $sucursales];
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $punto_ventas = new PuntoVenta();
        $punto_ventas->nombre = $request->nombre;
        $punto_ventas->descripcion = $request->descripcion;
        $punto_ventas->nit = $request->nit;
        $punto_ventas->idtipopuntoventa = $request->idtipopuntoventa;
        $punto_ventas->idsucursal = $request->idsucursal;
        $punto_ventas->estado = '1';

        $punto_ventas->save();
    }

    public function cambioEstado(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $punto_ventas = PuntoVenta::findOrFail($request->id);
        $punto_ventas->estado = '0';
        $punto_ventas->save();
    }
}
