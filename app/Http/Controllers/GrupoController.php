<?php

namespace App\Http\Controllers;

use App\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GrupoController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        //$criterio = $request->criterio;
        
        if ($buscar == '') {
            $grupos = Grupo::orderBy('id', 'desc')->paginate(3);
        } else {
            $grupos = Grupo::where('nombre_grupo', 'like', '%' . $buscar . '%')->orderBy('id', 'desc')->paginate(3);
        }
        

        return [
            'pagination' => [
                'total'        => $grupos->total(),
                'current_page' => $grupos->currentPage(),
                'per_page'     => $grupos->perPage(),
                'last_page'    => $grupos->lastPage(),
                'from'         => $grupos->firstItem(),
                'to'           => $grupos->lastItem(),
            ],
            'grupos' => $grupos
        ];
    }
    //registrar proveedor
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
         
        $grupos = new Grupo();
        $grupos->nombre_grupo = $request->nombre_grupo;
        Log::info('DATOS REGISTRO GRUPO:', [
            'nombre_grupo' => $request->nombre_grupo,
        ]);
        $grupos->save();
    }
    //editar grupo
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $grupos = Grupo::findOrFail($request->id);
        $grupos->nombre_grupo = $request->nombre_grupo;
        Log::info('DATOS EDITAR GRUPO:', [
            'nombre_grupo' => $request->nombre_grupo,
        ]);
        $grupos->save();
    }
}
