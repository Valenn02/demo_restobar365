<?php

namespace App\Http\Controllers;

use App\CuotasCredito;
use Illuminate\Http\Request;

class CuotasCreditoController extends Controller
{
    
    public function indexPag(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $cuotas_credito = CuotasCredito::orderBy('id', 'desc')->paginate(3);
        }
        else{
            $cuotas_credito = CuotasCredito::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(3);
        }
        

        return [
            'pagination' => [
                'total'        => $cuotas_credito->total(),
                'current_page' => $cuotas_credito->currentPage(),
                'per_page'     => $cuotas_credito->perPage(),
                'last_page'    => $cuotas_credito->lastPage(),
                'from'         => $cuotas_credito->firstItem(),
                'to'           => $cuotas_credito->lastItem(),
            ],
            'cuotas_credito' => $cuotas_credito
        ];
    }
        // Mostrar una lista de las cuotas de crédito
        public function index()
        {
            // devolver todas las cuotas de crédito 
            $cuotas = CuotasCredito::all();
            return $cuotas;
        }
    
        // Almacenar una nueva cuota de crédito en la base de datos
        public function store(Request $request)
        {
            // validar y guardar una nueva cuota de crédito en la base de datos
            $request->validate([
                'idcredito' => 'required|integer|exists:credito_venta,id',
                'fecha_pago' => 'required|date',
                'fecha_cancelado' => 'nullable|date',
                'precio_cuota' => 'required|numeric|min:0',
                'total_cancelado' => 'required|numeric|min:0',
                'saldo' => 'required|numeric|min:0',
                'estado' => 'required|string|in:pendiente,pagado,atrasado'
            ]);
    
            $cuota = new CuotasCredito();
            $cuota->idcredito = $request->idcredito;
            $cuota->fecha_pago = $request->fecha_pago;
            $cuota->fecha_cancelado = $request->fecha_cancelado;
            $cuota->precio_cuota = $request->precio_cuota;
            $cuota->total_cancelado = $request->total_cancelado;
            $cuota->saldo = $request->saldo;
            $cuota->estado = $request->estado;
            $cuota->save();
    
            return $cuota;
        }
    
        // Mostrar los detalles de una cuota de crédito específica
        public function show(CuotasCredito $cuota)
        {
            // devolver la cuota de crédito solicitada 
            return $cuota ;
        }
    
        // Actualizar una cuota de crédito existente en la base de datos
        public function update(Request $request, CuotasCredito $cuota)
        {
            // validar y actualizar la cuota de crédito existente en la base de datos
            $request->validate([
                'idcredito' => 'required|integer|exists:credito_venta,id',
                'fecha_pago' => 'required|date',
                'fecha_cancelado' => 'nullable|date',
                'precio_cuota' => 'required|numeric|min:0',
                'total_cancelado' => 'required|numeric|min:0',
                'saldo' => 'required|numeric|min:0',
                'estado' => 'required|string|in:pendiente,pagado,atrasado'
            ]);
    
            $cuota->idcredito = $request->idcredito;
            $cuota->fecha_pago = $request->fecha_pago;
            $cuota->fecha_cancelado = $request->fecha_cancelado;
            $cuota->precio_cuota = $request->precio_cuota;
            $cuota->total_cancelado = $request->total_cancelado;
            $cuota->saldo = $request->saldo;
            $cuota->estado = $request->estado;
            $cuota->save();
    
            return $cuota ;
        }
    
        // Eliminar una cuota de crédito existente de la base de datos
        public function destroy(CuotasCredito $cuota)
        {
            // eliminar la cuota de crédito existente de la base de datos
            $cuota->delete();
            return $cuota ;
        }
    }
