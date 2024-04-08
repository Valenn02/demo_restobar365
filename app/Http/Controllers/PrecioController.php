<?php

namespace App\Http\Controllers;

use App\Precio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class PrecioController extends Controller
{
    //lista de precio inacTivos
    public function indexanctivo(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $precio = Precio::where('condicion', 1)->orderBy('id', 'asc')->paginate(8);

        return [
            'precio' => $precio
        ];
    }
    public function indexactivo(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        //$precio = Precio::orderBy('id', 'desc')->paginate(3);
        $precio = Precio::orderBy('id', 'asc')->paginate(8);

        return [
            'precio' => $precio
        ];
    }    
    public function cambiarEstado(Request $request, $id, $accion)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $precio = Precio::findOrFail($id);

        if ($accion === 'desactivar') {
            $precio->condicion = '0';
        } elseif ($accion === 'activar') {
            $precio->condicion = '1';
        }

        $precio->save();
    }
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $precio = new Precio();

        $precio->nombre_precio = $request->nombre_precio;
        $precio->porcentage = $request->porcentage;
        //$precio->condicion = $request->condicion ? '1' : '0';
        $precio->condicion = '1';
        Log::info('DATOS REGISTRO PRECIO:', [
            'nombre_precio' => $request->nombre_precio,
            'porcentage' => $request->porcentage,
            'condicion' => $request->condicion,
        ]);
        $precio->save();
    }

}
