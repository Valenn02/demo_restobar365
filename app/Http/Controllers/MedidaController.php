<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Medida;
use Illuminate\Support\Facades\Log;

class MedidaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $medidas = Medida::orderBy('id', 'desc')->paginate(5);
        }
        else{
            $medidas = Medida::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(5);
        }
        

        return [
            'paginationMedida' => [
                'total'        => $medidas->total(),
                'current_page' => $medidas->currentPage(),
                'per_page'     => $medidas->perPage(),
                'last_page'    => $medidas->lastPage(),
                'from'         => $medidas->firstItem(),
                'to'           => $medidas->lastItem(),
            ],
            'medidas' => $medidas
        ];
    }

    public function selectMedida(Request $request){
        if (!$request->ajax()) return redirect('/');
        $medidas = Medida::where('estado','=','1')
        ->select('id','descripcion_medida')->orderBy('descripcion_medida', 'asc')->get();
        return ['medidas' => $medidas];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $medida = new Medida();
        $medida->descripcion_medida = $request->descripcion_medida;
        $medida->descripcion_corta = $request->descripcion_corta;
        $medida->estado = $request->estado ? '1' : '0';
        Log::info('DATOS ACTUALIZADOS DE ARTICULO:', [
            'descripcion_medida' => $request->descripcion_medida,
            'descripcion_corta' => $request->descripcion_corta,
            'estado' => $request->estado,

        ]);
        //$medida->save();
    }
  

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $medida = Medida::findOrFail($request->id);
        $medida->descripcion_medida = $request->descripcion_medida;
        $medida->descripcion_corta = $request->descripcion_corta;
        $medida->estado = 1;

        $medida->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $medida = Medida::findOrFail($request->id);
        $medida->estado = '0';
        $medida->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $medida = Medida::findOrFail($request->id);
        $medida->estado = '1';
        $medida->save();
    }
}
