<?php

namespace App\Http\Controllers;

use App\Empresa;
use Illuminate\Http\Request;
use App\Sucursales;
use Illuminate\Support\Facades\DB;

class SucursalController extends Controller
{
    //listar datos de sucursales
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $sucursales = Sucursales::join('empresas','sucursales.idempresa','=','empresas.id')
            ->select('sucursales.id','sucursales.idempresa','empresas.nombre as nombre_empresa','sucursales.nombre','sucursales.direccion','sucursales.correo','sucursales.telefono','sucursales.departamento', 'sucursales.codigoSucursal','sucursales.condicion')
            ->orderBy('sucursales.id', 'desc')->paginate(3);
        }
        else{
            $sucursales = Sucursales::join('empresas','sucursales.idempresa','=','empresas.id')
            ->select('sucursales.id','sucursales.idempresa','empresas.nombre as nombre_empresa','sucursales.nombre','sucursales.direccion','sucursales.correo','sucursales.telefono','sucursales.departamento','sucursales.codigoSucursal','sucursales.condicion')
            ->where('sucursales.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('sucursales.id', 'desc')->paginate(3);
        }
        
        return [
            'pagination' => [
                'total'        => $sucursales->total(),
                'current_page' => $sucursales->currentPage(),
                'per_page'     => $sucursales->perPage(),
                'last_page'    => $sucursales->lastPage(),
                'from'         => $sucursales->firstItem(),
                'to'           => $sucursales->lastItem(),
            ],
            'sucursales' => $sucursales
        ];
    }

    public function obtenerUltimoCodigoSucursal() {
        $ultimoCodigo = DB::table('sucursales')
          ->select('codigoSucursal')
          ->orderBy('codigoSucursal', 'desc')
          ->first();
      
        return response()->json(['ultimoCodigo' => $ultimoCodigo->codigoSucursal]);
      }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $sucursal = new Sucursales();
        //$sucursal->idempresa = $request->idempresa;
        $sucursal->idempresa = Empresa::first()->id;
        $sucursal->nombre = $request->nombre;
        $sucursal->direccion = $request->direccion;
        $sucursal->correo = $request->correo;
        $sucursal->telefono = $request->telefono;
        $sucursal->departamento = $request->departamento;
        $sucursal->codigoSucursal = $request->codigoSucursal;

        $sucursal->condicion = '1';
        $sucursal->save();
    }
    //---------hasa qui
    //-------actualizar datos
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $sucursal = Sucursales::findOrFail($request->id);
        $sucursal->idempresa = $request->idempresa;
        $sucursal->nombre = $request->nombre;
        $sucursal->direccion = $request->direccion;
        $sucursal->correo = $request->correo;
        $sucursal->telefono = $request->telefono;
        $sucursal->departamento = $request->departamento;
        $sucursal->codigoSucursal = $request->codigoSucursal;
        $sucursal->condicion = '1';
        $sucursal->save();
    }
    //-----------hasta aqui
    //---desactivar registro

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $sucursal = Sucursales::findOrFail($request->id);
        $sucursal->condicion = '0';
        $sucursal->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $sucursal = Sucursales::findOrFail($request->id);
        $sucursal->condicion = '1';
        $sucursal->save();
    }
    //--------hasta aqui

    public function selectSucursal(Request $request)
    {
        $sucursales = Sucursales::where('condicion', '=', '1')
        ->select('id','nombre')
        ->orderBy('nombre', 'asc')->get();

        return ['sucursales' => $sucursales];
    } 
}
