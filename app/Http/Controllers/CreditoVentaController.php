<?php

namespace App\Http\Controllers;

use App\CreditoVenta;
use Illuminate\Http\Request;

class CreditoVentaController extends Controller
{
    // Mostrar una lista de los créditos de venta
    public function index()
    {
        //  devolver todos los créditos de venta 
        $creditos = CreditoVenta::all();
        return $creditos ;
    }

    // Almacenar un nuevo crédito de venta en la base de datos
    public function store(Request $request)
    {
        //  validar y guardar un nuevo crédito de venta en la base de datos
        $request->validate([
            'idventa' => 'required|integer|exists:ventas,id',
            'idpersona' => 'required|integer|exists:personas,id',
            'numero_cuotas' => 'required|integer|min:1',
            'tiempo_dias_cuota' => 'required|integer|min:1',
            'estado' => 'required|string|in:activo,inactivo'
        ]);

        $credito = new CreditoVenta();
        $credito->idventa = $request->idventa;
        $credito->idpersona = $request->idpersona;
        $credito->numero_cuotas = $request->numero_cuotas;
        $credito->tiempo_dias_cuota = $request->tiempo_dias_cuota;
        $credito->estado = $request->estado;
        $credito->save();

        return $credito ;
    }

    // Mostrar los detalles de un crédito de venta específico
    public function show(CreditoVenta $credito)
    {
        //  devolver el crédito de venta solicitado 
        return $credito;
    }

    // Actualizar un crédito de venta existente en la base de datos
    public function update(Request $request, CreditoVenta $credito)
    {
        //  validar y actualizar el crédito de venta existente en la base de datos
        $request->validate([
            'idventa' => 'required|integer|exists:ventas,id',
            'idpersona' => 'required|integer|exists:personas,id',
            'numero_cuotas' => 'required|integer|min:1',
            'tiempo_dias_cuota' => 'required|integer|min:1',
            'estado' => 'required|string|in:activo,inactivo'
        ]);

        $credito->idventa = $request->idventa;
        $credito->idpersona = $request->idpersona;
        $credito->numero_cuotas = $request->numero_cuotas;
        $credito->tiempo_dias_cuota = $request->tiempo_dias_cuota;
        $credito->estado = $request->estado;
        $credito->save();

        return $credito;
    }

    // Eliminar un crédito de venta existente de la base de datos
    public function destroy(CreditoVenta $credito)
    {
        //  eliminar el crédito de venta existente de la base de datos
        $credito->delete();
        return $credito;
    }
}
