<?php

namespace App\Http\Controllers;

use App\CotizacionVenta;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Venta;
use App\DetalleCotizacionVenta;
use App\User;
use App\Empresa;
use App\Caja;
use Illuminate\Support\Facades\Log;
use App\Notifications\NotifyAdmin;
use TheSeer\Tokenizer\Exception;

class CotizacionVentaController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if ($buscar == '') {
            $cotizacion_venta = CotizacionVenta::join('personas', 'cotizacion_venta.idcliente', '=', 'personas.id')
                ->join('users', 'cotizacion_venta.idusuario', '=', 'users.id')
                ->select(
                    'cotizacion_venta.id',
                    'cotizacion_venta.fecha_hora',
                    'cotizacion_venta.impuesto',
                    'cotizacion_venta.total',
                    'cotizacion_venta.nota',
                    'cotizacion_venta.validez',
                    'cotizacion_venta.condicion',


                    'personas.nombre',
                    'personas.num_documento',

                    'users.usuario'
                )
                ->orderBy('cotizacion_venta.id', 'desc')->paginate(10);
        } else {
            $cotizacion_venta = CotizacionVenta::join('personas', 'cotizacion_venta.idcliente', '=', 'personas.id')
                ->join('users', 'cotizacion_venta.idusuario', '=', 'users.id')
                ->select(
                    'cotizacion_venta.id',
                    'cotizacion_venta.fecha_hora',
                    'cotizacion_venta.impuesto',
                    'cotizacion_venta.total',
                    'cotizacion_venta.nota',
                    'cotizacion_venta.validez',
                    'cotizacion_venta.condicion',



                    'personas.nombre',
                    'personas.num_documento',

                    'users.usuario'
                )
                ->where('cotizacion_venta.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderBy('cotizacion_venta.id', 'desc')->paginate(3);
        }

        return [
            'pagination' => [
                'total' => $cotizacion_venta->total(),
                'current_page' => $cotizacion_venta->currentPage(),
                'per_page' => $cotizacion_venta->perPage(),
                'last_page' => $cotizacion_venta->lastPage(),
                'from' => $cotizacion_venta->firstItem(),
                'to' => $cotizacion_venta->lastItem(),
            ],
            'cotizacion_venta' => $cotizacion_venta
        ];
    }
    public function obtenerCabecera(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $id = $request->id;
        $cotizacion = CotizacionVenta::join('personas', 'cotizacion_venta.idcliente', '=', 'personas.id')
            ->join('users', 'cotizacion_venta.idusuario', '=', 'users.id')
            ->select(
                'cotizacion_venta.id',
                'cotizacion_venta.idcliente',
                'cotizacion_venta.tiempo_entrega',


                'cotizacion_venta.fecha_hora',
                'cotizacion_venta.impuesto',
                'cotizacion_venta.total',

                'personas.nombre',


                'users.usuario'
            )
            ->where('cotizacion_venta.id', '=', $id)
            ->orderBy('cotizacion_venta.id', 'desc')->take(1)->get();

        return ['cotizacion' => $cotizacion];
    }
    public function obtenerDetalles(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $id = $request->id;
        $detalles = DetalleCotizacionVenta::join('articulos', 'detalle_cotizacion.idarticulo', '=', 'articulos.id')
            ->select(

                'detalle_cotizacion.cantidad',
                'detalle_cotizacion.precio',
                'detalle_cotizacion.descuento',
                'detalle_cotizacion.idarticulo',

                'articulos.nombre as articulo'
            )
            ->where('detalle_cotizacion.idcotizacion', '=', $id)
            ->orderBy('detalle_cotizacion.id', 'desc')->get();

        return ['detalles' => $detalles];
    }
    public function pdf(Request $request, $id)
    {
        $venta = CotizacionVenta::join('personas', 'cotizacion_venta.idcliente', '=', 'personas.id')
            ->join('users', 'cotizacion_venta.idusuario', '=', 'users.id')
            ->select(
                'cotizacion_venta.id',
                'cotizacion_venta.created_at',
                'cotizacion_venta.impuesto',
                'cotizacion_venta.total',
                'personas.nombre',
                'personas.tipo_documento',
                'personas.num_documento',
                'personas.direccion',
                'personas.email',
                'personas.telefono',
                'users.usuario'
            )
            ->where('cotizacion_venta.id', '=', $id)
            ->orderBy('cotizacion_venta.id', 'desc')->take(1)->get();

        $detalles = DetalleCotizacionVenta::join('articulos', 'detalle_cotizacion.idarticulo', '=', 'articulos.id')
            ->select(
                'detalle_cotizacion.cantidad',
                'detalle_cotizacion.precio',
                'detalle_cotizacion.descuento',
                'articulos.nombre as articulo'
            )
            ->where('detalle_cotizacion.idcotizacion', '=', $id)
            ->orderBy('detalle_cotizacion.id', 'desc')->get();

        // $numventa = Venta::select('num_comprobante')->where('id', $id)->get();

        $pdf = \PDF::loadView('pdf.cotizacionpdf', ['venta' => $venta, 'detalles' => $detalles]);
        return $pdf->download('cotizacion' . '.pdf');

    }
    public function store(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        try {
            DB::beginTransaction();

            $descu = '';
            $valorMaximoDescuentoEmpresa = Empresa::first();
            $valorMaximo = $valorMaximoDescuentoEmpresa->valorMaximoDescuento;
            $detalles = $request->data; //Array de detalles

            foreach ($detalles as $ep => $det) {
                $descu = $det['descuento'];
            }

            if ($descu > $valorMaximoDescuentoEmpresa->valorMaximoDescuento) {
                return [
                    'id' => -1,
                    'valorMaximo' => $valorMaximo
                ];
            } else {
                $fechaActual = now()->setTimezone('America/La_Paz');
                $nuevaFecha = $fechaActual->addDays($request->n_validez);



                $cotizacionventa = new CotizacionVenta();
                $cotizacionventa->idcliente = $request->idcliente;
                $cotizacionventa->idusuario = \Auth::user()->id;

                $cotizacionventa->fecha_hora = now()->setTimezone('America/La_Paz');
                $cotizacionventa->validez = $nuevaFecha;

                $cotizacionventa->lugar_entrega = $request->lugar_entrega;
                $cotizacionventa->forma_pago = $request->forma_pago;
                $cotizacionventa->nota = $request->nota;
                $cotizacionventa->condicion = '1';



                $cotizacionventa->tiempo_entrega = $request->tiempo_entrega;
                $cotizacionventa->impuesto = $request->impuesto;
                $cotizacionventa->total = $request->total;
                $cotizacionventa->save();



                foreach ($detalles as $ep => $det) {
                    $detalle = new DetalleCotizacionVenta();
                    $detalle->idcotizacion = $cotizacionventa->id;
                    $detalle->idarticulo = $det['idarticulo'];
                    $detalle->cantidad = $det['cantidad'];
                    $detalle->precio = $det['precio'];
                    $detalle->descuento = $det['descuento'];
                    $detalle->save();
                }



                DB::commit();
                return [
                    'id' => $cotizacionventa->id
                ];



            }
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');
        $cotizacionventa = CotizacionVenta::findOrFail($request->id);
        $cotizacionventa->condicion = '0';

        $cotizacionventa->save();
    }
    //


    public function activar(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');
        $cotizacionventa = CotizacionVenta::findOrFail($request->id);
        $cotizacionventa->condicion = '1';
        $cotizacionventa->save();
    }

    public function delete(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        try {
            DB::beginTransaction();

            $cotizacionVenta = CotizacionVenta::findOrFail($request->id);
            $cotizacionVenta->delete();

            // También puedes eliminar los detalles relacionados con la cotización de venta si es necesario
            DetalleCotizacionVenta::where('idcotizacion', $request->id)->delete();

            DB::commit();

            return [
                'success' => true,
                'message' => 'Cotización de venta eliminada exitosamente.',
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Ocurrió un error al eliminar la cotización de venta.',
            ];
        }
    }

}