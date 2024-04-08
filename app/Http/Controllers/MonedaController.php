<?php

namespace App\Http\Controllers;

use App\Empresa;
use Illuminate\Http\Request;
use App\Moneda;

class MonedaController extends Controller
{
    //Listar monedas
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            $monedas = Moneda::join('empresas', 'monedas.idempresa', '=', 'empresas.id')
            ->select('monedas.id', 'monedas.idempresa', 'empresas.nombre as nombre_empresa', 'monedas.nombre', 'monedas.pais', 'monedas.simbolo', 'monedas.compra', 'monedas.venta', 'monedas.activo')
            ->orderBy('monedas.id', 'desc')->paginate(3);        
        }
        else{
            $monedas = Moneda::join('empresas', 'monedas.idempresa', '=', 'empresas.id')
            ->select('monedas.id', 'monedas.idempresa','empresas.nombre as nombre_empresa', 'monedas.nombre', 'monedas.pais', 'monedas.simbolo', 'monedas.compra', 'monedas.venta', 'monedas.activo')
            ->where('monedas.'.$criterio, 'like', '%'.$buscar . '%')
            ->orderBy('monedas.id', 'desc')->paginate(3);  
        }

        return [
            'pagination' => [
                'total' => $monedas->total(),
                'current_page' => $monedas->currentPage(),
                'per_page' => $monedas->perPage(),
                'last_page' => $monedas->lastPage(),
                'from' => $monedas->firstItem(),
                'to' => $monedas->lastItem(),
            ],
            'monedas' => $monedas
        ];

    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $moneda = new Moneda();
        $moneda->idempresa = Empresa::first()->id;
        $moneda->nombre = $request->nombre;
        $moneda->pais = $request->pais;
        $moneda->simbolo = $request->simbolo;
        $moneda->compra = $request->compra;
        $moneda->venta = $request->venta;

        $moneda->activo = '1';
        $moneda->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $moneda = Moneda::findOrFail($request->id);
        $moneda->idempresa = $request->idempresa;
        $moneda->nombre = $request->nombre;
        $moneda->pais = $request->pais;
        $moneda->simbolo = $request->simbolo;
        $moneda->compra = $request->compra;
        $moneda->venta = $request->venta;
        $moneda->activo = '1';
        $moneda->save();
    }
    //-----------hasta aqui
    //---desactivar registro

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $moneda = Moneda::findOrFail($request->id);
        $moneda->activo = '0';
        $moneda->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $moneda = Moneda::findOrFail($request->id);
        $moneda->activo = '1';
        $moneda->save();
    }
    //--------hasta aqui
}
