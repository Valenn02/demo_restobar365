<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ConfiguracionTrabajo;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class ConfiguracionTrabajoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $configuracionTrabajo = ConfiguracionTrabajo::first();

        /*Log::info('coniguracion', [
            'data' => $configuracionTrabajo 
        ]);*/
    
        return ['configuracionTrabajo' => $configuracionTrabajo];
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
        if(!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();
            Log::info('coniguracion', [
                'saldoNegativo' => $request->saldosNegativos
            ]);
            $configuracionTrabajo = ConfiguracionTrabajo::findOrFail($request->id);
            $configuracionTrabajo->gestion = $request->selectedYear;
            $configuracionTrabajo->codigoProductos = $request->codigoProducto;
            $configuracionTrabajo->consultasAlmacenes = $request->consultarAlmacenes;
            $configuracionTrabajo->limiteDescuento = $request->limiteDescuento;
            $configuracionTrabajo->maximoDescuento = $request->maximoDescuento;
            $configuracionTrabajo->valuacionInventario = $request->valuacionInventario;
            $configuracionTrabajo->backupAutomatico = $request->backupAutomatico;
            $configuracionTrabajo->rutaBackup = $request->rutaBackup;
            $configuracionTrabajo->saldosNegativos = $request->saldosNegativos;
            $configuracionTrabajo->monedaTrabajo = $request->monedaTrabajo;
            $configuracionTrabajo->separadorDecimales = $request->separadorDecimales;
            $configuracionTrabajo->mostrarCostos = $request->mostrarCostos;
            $configuracionTrabajo->mostrarProveedores = $request->mostrarProveedor;
            $configuracionTrabajo->mostrarSaldosStock = $request->mostrarSaldosStock;
            $configuracionTrabajo->actualizarIva = $request->actualizarIVA;
            $configuracionTrabajo->vendedorAsignado = $request->vendedorAsignado;
            $configuracionTrabajo->permitirDevolucion = $request->devolucion;
            $configuracionTrabajo->editarNroDoc = $request->editarNroDoc;
            $configuracionTrabajo->registroClienteObligatorio = $request->registroClienteObligatorio;
            $configuracionTrabajo->buscarClientePorCodigo = $request->buscarClientePorCodigo;
        
            $configuracionTrabajo->save();
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }
    }
    public function obtenerSaldosNegativos()
    {
        try {
            $saldosNegativos = ConfiguracionTrabajo::value('saldosNegativos');
            return response()->json(['saldosNegativos' => $saldosNegativos]);
        } catch (\Exception $e) {
            Log::error('Error al obtener saldos negativos: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    public function obtenerIva()
    {
        try {
            $iva = ConfiguracionTrabajo::value('actualizarIva');
            return response()->json(['actualizarIva' => $iva]);
        } catch (\Exception $e) {
            Log::error('Error al obtener iva: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
