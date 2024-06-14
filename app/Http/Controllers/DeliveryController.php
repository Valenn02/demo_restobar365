<?php

namespace App\Http\Controllers;

use App\Delivery;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $deliverys = Delivery::orderBy('id', 'desc')
                        ->paginate(3);
        }
        else{
            $deliverys = Delivery::where($criterio, 'like', '%'. $buscar . '%')
                ->orderBy('id', 'desc')
                ->paginate(3);
        }
        

        return [
            'pagination' => [
                'total'        => $deliverys->total(),
                'current_page' => $deliverys->currentPage(),
                'per_page'     => $deliverys->perPage(),
                'last_page'    => $deliverys->lastPage(),
                'from'         => $deliverys->firstItem(),
                'to'           => $deliverys->lastItem(),
            ],
            'deliverys' => $deliverys
        ];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $delivery = new Delivery();
        $delivery->nombre = $request->nombre;
        $delivery->telefono = $request->telefono;
        $delivery->estado = true;

        $delivery->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $delivery = Delivery::findOrFail($request->id);
        $delivery->nombre = $request->nombre;
        $delivery->telefono = $request->telefono;

        $delivery->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $delivery = Delivery::findOrFail($request->id);
        $delivery->estado = '0';
        $delivery->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $delivery = Delivery::findOrFail($request->id);
        $delivery->estado = '1';
        $delivery->save();
    }

    public function selectDelivery(Request $request)
    {
        $deliverys = Delivery::where('estado', '=', '1')
        ->select('id','nombre')
        ->orderBy('nombre', 'asc')->get();

        return ['deliverys' => $deliverys];
    } 

    public function recuperarTelf(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        $id = $request->input('id');

        $delivery = Delivery::find($id);

        if (!$delivery) {
            return response()->json(['error' => 'Registro no encontrado'], 404);
        }

        return response()->json(['telefono' => $delivery->telefono]);
    }
}
