<?php

namespace App\Http\Controllers;

use App\Industria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IndustriaController extends Controller
{
    //listado de industria
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $industrias = Industria::orderBy('id', 'desc')->paginate(3);
        }
        else{
            $industrias = Industria::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(3);
        }
        

        return [
            'pagination' => [
                'total'        => $industrias->total(),
                'current_page' => $industrias->currentPage(),
                'per_page'     => $industrias->perPage(),
                'last_page'    => $industrias->lastPage(),
                'from'         => $industrias->firstItem(),
                'to'           => $industrias->lastItem(),
            ],
            'industrias' => $industrias
        ];
    }
    //refistrar industrias
    public function store(Request $request)
    {
        Log::info('Datos REGISTRAR', [
            'nombre' => $request->nombre, 
            'estado' => $request->estado,            
        ]);
        if (!$request->ajax()) return redirect('/');
        $industria = new Industria();
        $industria->nombre = $request->nombre;
        $industria->estado = $request->estado ? '1' : '0';
        //$industria->estado = '1';

        $industria->save();
    }
    //editar/ actualizar
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $industrial = Industria::findOrFail($request->id);
        $industrial->nombre = $request->nombre;
        $industrial->estado = $request->estado ? '1' : '0';
        //$industrial->estado = '1';
        $industrial->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $industrias = Industria::findOrFail($request->id);
        $industrias->estado = '0';
        $industrias->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $industrias = Industria::findOrFail($request->id);
        $industrias->estado = '1';
        $industrias->save();
    }
}
