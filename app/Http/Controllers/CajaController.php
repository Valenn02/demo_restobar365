<?php

namespace App\Http\Controllers;

use App\Caja;
use App\TransaccionesCaja;
use Illuminate\Http\Request;
use App\ArqueoCaja;
use App\User;
use Illuminate\Support\Facades\DB;

class CajaController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $user = \Auth::user();
        $query = Caja::join('sucursales', 'cajas.idsucursal', '=', 'sucursales.id')
                    ->join('users', 'cajas.idusuario', '=', 'users.id')
                    ->select(
                        'cajas.id', 
                        'cajas.idsucursal', 
                        'sucursales.nombre as nombre_sucursal',
                        'cajas.idusuario', 
                        'users.usuario as usuario', 
                        'cajas.fechaApertura', 
                        'cajas.fechaCierre', 
                        'cajas.saldoInicial', 
                        'depositos', 
                        'salidas', 
                        'ventas',
                        'ventasContado',
                        'ventasCredito',
                        'pagosEfectivoVentas', 
                        'pagosEfecivocompras', 
                        'compras', 
                        'comprasContado',
                        'saldoFaltante', 
                        'PagoCuotaEfectivo', 
                        'saldoCaja', 
                        'estado',
                        'cuotasventasCredito'
                    )
                    ->where('cajas.idsucursal', '=', $user->idsucursal);

        if ($user->idrol == 2) {
            $query->where('cajas.idusuario', '=', $user->id);
        }

        if (!empty($buscar)) {
            $query->where('cajas.' . $criterio, 'like', '%' . $buscar . '%');
        }

        $cajas = $query->orderBy('cajas.id', 'desc')->paginate(6);

        return [
            'pagination' => [
                'total'        => $cajas->total(),
                'current_page' => $cajas->currentPage(),
                'per_page'     => $cajas->perPage(),
                'last_page'    => $cajas->lastPage(),
                'from'         => $cajas->firstItem(),
                'to'           => $cajas->lastItem(),
            ],
            'cajas' => $cajas
        ];
    }


    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $caja = new Caja();
        $caja->idsucursal = \Auth::user()->idsucursal;
        $caja->idusuario = \Auth::user()->id;
        $caja->fechaApertura = now()->setTimezone('America/La_Paz');
        $caja->saldoInicial = $request->saldoInicial;
        $caja->saldoCaja = $request->saldoInicial;
        
        $caja->estado = '1';
        $caja->save();
    }

    public function depositar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        DB::beginTransaction();

        try {
            $caja = Caja::findOrFail($request->id);
            $caja->depositos = ($request->depositos)+($caja->depositos);
            $caja->saldoCaja += $request->depositos;

            $transacciones = new TransaccionesCaja();
            $transacciones->idcaja = $request->id;
            $transacciones->idusuario = \Auth::user()->id; 
            $transacciones->fecha = now()->setTimezone('America/La_Paz');
            $transacciones->transaccion = $request->transaccion;
            $transacciones->importe = ($request->depositos);

            $transacciones->save();

            $caja->save();
            DB::commit();

            return response()->json('Retiro realizado correctamente', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json('Error al realizar el deposito: ' . $e->getMessage(), 500);
        }
        

        
    }

    public function retirar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        DB::beginTransaction();

        try {
            $caja = Caja::findOrFail($request->id);
            $caja->salidas = ($request->salidas) + ($caja->salidas);
            $caja->saldoCaja -= $request->salidas;

            $transacciones = new TransaccionesCaja();
            $transacciones->idcaja = $request->id;
            $transacciones->idusuario = \Auth::user()->id;
            $transacciones->fecha = now()->setTimezone('America/La_Paz');
            $transacciones->transaccion = $request->transaccion;
            $transacciones->importe = $request->salidas;

            // Guardar la transacción primero
            $transacciones->save();

            // Si la transacción se guarda correctamente, guardar los cambios en la caja
            $caja->save();

            DB::commit();

            return response()->json('Retiro realizado correctamente', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json('Error al realizar el retiro: ' . $e->getMessage(), 500);
        }
    }


    public function arqueoCaja(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $arqueoCaja = new ArqueoCaja();
        $arqueoCaja->idcaja = $request->idcaja;
        $arqueoCaja->idusuario = \Auth::user()->id; 
        $arqueoCaja->billete200 = $request->billete200;
        $arqueoCaja->billete100 = $request->billete100;
        $arqueoCaja->billete50 = $request->billete50;
        $arqueoCaja->billete20 = $request->billete20;
        $arqueoCaja->billete10 = $request->billete10;
        $arqueoCaja->moneda5 = $request->moneda5;
        $arqueoCaja->moneda2 = $request->moneda2;
        $arqueoCaja->moneda1 = $request->moneda1;
        $arqueoCaja->moneda050 = $request->moneda050;
        $arqueoCaja->moneda020 = $request->moneda020;
        $arqueoCaja->moneda010 = $request->moneda010;

        $arqueoCaja->save();
    }

    public function cerrar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $caja = Caja::findOrFail($request->id);
        $caja->fechaCierre = now()->setTimezone('America/La_Paz');
        $caja->estado = '0';
        $caja->saldoFaltante = ($request->saldoFaltante)-($caja->saldoCaja);
        $caja->save();
    }

}
