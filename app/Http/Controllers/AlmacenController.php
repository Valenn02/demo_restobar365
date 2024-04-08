<?php

namespace App\Http\Controllers;

use App\Almacen;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AlmacenController extends Controller
{
    //listas almacen
    public function index(Request $request)
    {

        if (!$request->ajax())
            return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $almacenes = Almacen::orderBy('id', 'desc')->paginate(3);
        } else {
            $almacenes = Almacen::where($criterio, 'like', '%' . $buscar . '%')->orderBy('id', 'desc')->paginate(3);
        }


        return [
            'pagination' => [
                'total' => $almacenes->total(),
                'current_page' => $almacenes->currentPage(),
                'per_page' => $almacenes->perPage(),
                'last_page' => $almacenes->lastPage(),
                'from' => $almacenes->firstItem(),
                'to' => $almacenes->lastItem(),
            ],
            'almacenes' => $almacenes
        ];
    }
    //registrar almacen
    public function store(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');
        $almacenes = new Almacen();
        $almacenes->nombre_almacen = $request->nombre_almacen;
        $almacenes->ubicacion = $request->ubicacion;
        $almacenes->encargado = $request->encargado;
        $almacenes->telefono = $request->telefono;
        $almacenes->lugar = $request->lugar;
        $almacenes->observacion = $request->observacion;
        Log::info('DATOS REGISTRO ALMACEN:', [
            'nombre_almacen' => $request->nombre_almacen,
            'ubicacion' => $request->ubicacion,
            'encargado' => $request->encargado,
            'telefono' => $request->telefono,
            'lugar' => $request->lugar,
            'observacion' => $request->observacion,
        ]);
        $almacenes->save();
    }
    //editar
    public function update(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $almacenes = Almacen::findOrFail($request->id);

        $almacenes->nombre_almacen = $request->nombre_almacen;
        $almacenes->ubicacion = $request->ubicacion;
        $almacenes->encargado = $request->encargado;
        $almacenes->telefono = $request->telefono;
        $almacenes->lugar = $request->lugar;
        $almacenes->observacion = $request->observacion;
        Log::info('ACTUALIZAR ALMACEN:', [
            'nombre_almacen' => $request->nombre_almacen,
            'ubicacion' => $request->ubicacion,
            'encargado' => $request->encargado,
            'telefono' => $request->telefono,
            'lugar' => $request->lugar,
            'observacion' => $request->observacion,
        ]);
        $almacenes->save();
    }
    public function selectAlmacen(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');
        $almacenes = Almacen::where('condicion', '=', '1')
            ->select('id', 'nombre_almacen')->orderBy('nombre_almacen', 'asc')->get();
        return ['almacenes' => $almacenes];
    }
    public function selectAlmacenDestino(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');
        $almacenes = Almacen::where('condicion', '=', '1')
            ->select('id', 'nombre_almacen')->orderBy('nombre_almacen', 'asc')->get();
        return ['almacenes' => $almacenes];
    }
}