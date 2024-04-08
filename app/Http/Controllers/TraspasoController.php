<?php

namespace App\Http\Controllers;

use App\DetalleTraspaso;
use App\Inventario;
use App\Traspaso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TraspasoController extends Controller
{
    //----lisTado or filtro de fecha--
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;

        $traspasos = Traspaso::select('id', 'almacen_origen', 'almacen_destino', 'fecha_traspaso', 'created_at')
        ->whereBetween('fecha_traspaso', [$fechaInicio, $fechaFin])
            ->paginate(4); // Mover el paginate(4) aquí

        return [
            'pagination' => [
                'total'        => $traspasos->total(),
                'current_page' => $traspasos->currentPage(),
                'per_page'     => $traspasos->perPage(),
                'last_page'    => $traspasos->lastPage(),
                'from'         => $traspasos->firstItem(),
                'to'           => $traspasos->lastItem(),
            ],
            'traspasos' => $traspasos // Corregir 'tras$traspasos' por 'traspasos'
        ];
    }
    //--registrando datos de Trapaso luego pasando datosa  inventario para registrar---
    public function store(Request $request)
    {
        try{
            DB::beginTransaction();

            $traspasos = new Traspaso();
            //$traspasos->id = $request->idproveedor;
            
            $traspasos->tipo_traspaso = $request->tipo_traspaso;
            $traspasos->idusuario = \Auth::user()->id;
            $traspasos->almacen_origen = $request->almacen_origen;
            $traspasos->almacen_destino = $request->almacen_destino;
            $traspasos-> fecha_traspaso = $request->fecha_traspaso;
            Log::info('DATOS REGISTRO TRASPASO:', [
                'tipo_traspaso' => $request->tipo_traspaso,
                'idusuario' => $request->idusuario,
                'almacen_origen' => $request->almacen_origen,
                'almacen_destino' => $request->almacen_destino,
                'fecha_traspaso' => $request->fecha_traspaso,
    
            ]);
            $traspasos->save();
            
            $detalles = $request->data;//Array de detalles
            //Recorro todos los elementos
            Log::info('PRODUCTOS:', [
                'DATA' => $detalles,
            ]);
            // foreach($detalles as $ep=>$det)
            foreach ($detalles as $detalle) 
            {
                 // Encuentra el inventario correspondiente al artículo y almacén de origen
                $inventarioOrigen = Inventario::where('idalmacen', $detalle['idalmacen'])
                                    ->where('idarticulo', $detalle['idarticulo'])
                ->first();
                if ($inventarioOrigen) {
                    // Actualiza el saldo_stock restando la cantidad transferida
                    $inventarioOrigen->saldo_stock -= $detalle['cantidad_traspaso'];
                    $inventarioOrigen->save();
                }

                //------prueva registro de inventario -----
                $inventario = new Inventario();
                //$inventario->idalmacen = $detalle['idalmacen'];
                $inventario->idalmacen = $detalle['idalmacendes'];
                //$inventario->idalmacen = $detalle['idinventario'];
                $inventario->idarticulo = $detalle['idarticulo'];
                $inventario->fecha_vencimiento = $detalle['fecha_vencimiento'];
                $inventario->saldo_stock = $detalle['cantidad_traspaso'];
                // Agrega aquí los demás campos necesarios para la tabla de inventarios.
                $inventario->save();
                //-----hasta aqui---

                $detalletraspaso = new DetalleTraspaso();
                $detalletraspaso->idtraspaso = $traspasos->id;
                $detalletraspaso->idinventario = $detalle['idinventario'];
                $detalletraspaso->cantidad_traspaso = $detalle['cantidad_traspaso'];
                // $detalle->idinventario = $det['idinventario'];
                // $detalle->cantidad_traspaso = $det['cantidad_traspaso'];
                $detalletraspaso->save();
            }
            DB::commit(); // Confirmar los cambios en la base de datos

        } catch (Exception $e){
            DB::rollBack();
            }
    }
    //---listado por id lo que se traspaso--
    public function indexPorID(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $idtraspaso = $request->idtraspaso;
        $detalletrasp = DetalleTraspaso::join('inventarios', 'detalle_traspasos.idinventario', '=', 'inventarios.id') 
            ->join('traspasos as t', 'detalle_traspasos.idtraspaso', '=', 't.id')
            ->join('articulos', 'inventarios.idarticulo', '=', 'articulos.id')
        ->select(
            'detalle_traspasos.id',
            'detalle_traspasos.cantidad_traspaso',
            'inventarios.saldo_stock',
            'inventarios.fecha_vencimiento',
            'articulos.nombre as nombre_producto',
            'articulos.unidad_envase',
            'articulos.precio_costo_unid',
        )
        ->where('detalle_traspasos.idtraspaso','=',$idtraspaso)->get();   
        return [ 'detalletrasp' => $detalletrasp];
    }
    
}
