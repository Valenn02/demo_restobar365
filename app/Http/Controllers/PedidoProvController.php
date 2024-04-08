<?php

namespace App\Http\Controllers;

use App\DetallePedidoProv;
use App\PedidoProv;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PedidoProvController extends Controller
{
    //registrado de Pedido A proveedor
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $pedidoProv = new PedidoProv();

            $pedidoProv->idusuario = \Auth::user()->id;
            $pedidoProv->idproveedor = $request->idproveedor;
            $pedidoProv->idalmacen = $request->idalmacen;
            $pedidoProv->fecha_pedido = $request->fecha_pedido;
            $pedidoProv->fecha_entrega = $request->fecha_entrega;
            $pedidoProv->forma_pago = $request->forma_pago;
            $pedidoProv->observacion = $request->observacion;
            $pedidoProv->total = $request->total;
            Log::info('DATOS REGISTRO PEDIDO_PROVEEDOR:', [
                'idusuario' => $request->idusuario,
                'idproveedor' => $request->idproveedor,
                'idalmacen' => $request->idalmacen,
                'fecha_pedido' => $request->fecha_pedido,
                'fecha_entrega' => $request->fecha_entrega,
                'forma_pago' => $request->forma_pago,
                'observacion' => $request->observacion,
                'total' => $request->total,
            ]);
            $pedidoProv->save();

            $detalles = $request->data; //Array de detalles
            //Recorro todos los elementos
            Log::info('PRODUCTOS ARTICULOS PEDIDO PROVEEDOR:', [
                'DATA' => $detalles,
            ]);
            foreach ($detalles as $detalle) {
                $detallepediprov = new DetallePedidoProv();
                $detallepediprov->idpedidoprov = $pedidoProv->id;
                $detallepediprov->idarticulo = $detalle['idarticulo'];
                $detallepediprov->cantidad_pedidoprov = $detalle['cantidad'];
                // $detalle->idinventario = $det['idinventario'];
                // $detalle->cantidad_traspaso = $det['cantidad_traspaso'];
                $detallepediprov->save();
            }
            DB::commit(); // Confirmar los cambios en la base de datos
        } catch (Exception $e) {
            Log::error("Error al registrar el pedido: " . $e->getMessage());

            DB::rollBack();
        }
    }
    public function editar(Request $request)
    {
        try {
            DB::beginTransaction();

            // Encuentra el pedido de proveedor por su ID
            $pedidoProv = PedidoProv::find($request->idpedido);
            Log::info($request->forma_pago);

            // Actualiza los campos del pedido de proveedor con los nuevos valores
            $pedidoProv->idusuario = \Auth::user()->id;
            $pedidoProv->idproveedor = $request->idproveedor;
            $pedidoProv->idalmacen = $request->idalmacen;
            $pedidoProv->fecha_pedido = $request->fecha_pedido;
            $pedidoProv->fecha_entrega = $request->fecha_entrega;
            $pedidoProv->forma_pago = $request->forma_pago;
            $pedidoProv->observacion = $request->observacion;
            $pedidoProv->total = $request->total;
            $pedidoProv->estado = $request->estado;


            // Guarda los cambios en el pedido de proveedor
            $pedidoProv->save();

            // Elimina los detalles del pedido existentes
            DetallePedidoProv::where('idpedidoprov', $pedidoProv->id)->delete();

            // Agrega los nuevos detalles del pedido
            $detalles = $request->data; // Array de detalles
            foreach ($detalles as $detalle) {
                $detallepediprov = new DetallePedidoProv();
                $detallepediprov->idpedidoprov = $pedidoProv->id;
                $detallepediprov->idarticulo = $detalle['idarticulo'];
                $detallepediprov->cantidad_pedidoprov = $detalle['cantidad'];
                $detallepediprov->save();
            }

            DB::commit(); // Confirmar los cambios en la base de datos
        } catch (Exception $e) {
            Log::error("Error al editar el pedido: " . $e->getMessage());
            DB::rollBack();
        }
    }

    public function obtenerDetalles(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $id = $request->id;
        $detalles = DetallePedidoProv::join('articulos', 'detalle_pedidprov.idarticulo', '=', 'articulos.id')
            ->select(

                'detalle_pedidprov.cantidad_pedidoprov',
                'detalle_pedidprov.precio',
                'detalle_pedidprov.idarticulo',

                'articulos.nombre as articulo'
            )
            ->where('detalle_pedidprov.idcotizacion', '=', $id)
            ->orderBy('detalle_pedidprov.id', 'desc')->get();

        return ['detalles' => $detalles];
    }
    //----lstado de PEDIDO--
    public function indexpedido(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');
        //$fechaInicio = $request->fechaInicio;
        $perPage = $request->input('per_page', 10);
        $pedidoprov = PedidoProv::join('almacens', 'pedido_proveedors.idalmacen', '=', 'almacens.id')
            //join('articulos', 'pedido_proveedors.idproveedor', '=', 'articulos.id') 
            ->join('proveedores', 'pedido_proveedors.idproveedor', '=', 'proveedores.id')
            ->join('personas', 'proveedores.id', '=', 'personas.id')
            //->join('users', 'personas.id', '=', 'users.id')
            ->select(
                'pedido_proveedors.id',
                'pedido_proveedors.idproveedor',
                'pedido_proveedors.idalmacen',
                'pedido_proveedors.fecha_pedido',
                'pedido_proveedors.fecha_entrega',
                'pedido_proveedors.forma_pago',
                'pedido_proveedors.estado',


                'personas.nombre as nombre_proveedor',
                'almacens.nombre_almacen',
                'pedido_proveedors.total',
                //'users.usuario',
            )
            //->whereBetween('fecha_pedido', [$fechaInicio])
            ->paginate($perPage); // Mover el paginate(4) aquí

        return [
            'pagination' => [
                'total' => $pedidoprov->total(),
                'current_page' => $pedidoprov->currentPage(),
                'per_page' => $pedidoprov->perPage(),
                'last_page' => $pedidoprov->lastPage(),
                'from' => $pedidoprov->firstItem(),
                'to' => $pedidoprov->lastItem(),
            ],
            'pedidoprov' => $pedidoprov // Corregir 'tras$traspasos' por 'traspasos'
        ];
    }
    public function eliminar(Request $request)
    {
        try {
            DB::beginTransaction();
            Log::info($request->id);

            // Encuentra el pedido de proveedor por su ID
            $pedidoProv = PedidoProv::find($request->id);
            Log::info("Pedido: ", [$pedidoProv]);


            if (!$pedidoProv) {
                throw new Exception('Pedido no encontrado');
            }

            // Elimina los detalles del pedido existentes
            DetallePedidoProv::where('idpedidoprov', $pedidoProv->id)->delete();

            // Elimina el pedido de proveedor
            $pedidoProv->delete();

            DB::commit(); // Confirmar los cambios en la base de datos

            return response()->json(['message' => 'Pedido eliminado correctamente']);
        } catch (Exception $e) {
            Log::error("Error al eliminar el pedido: " . $e->getMessage());
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    //---listado por id lo que se pidio de PEDIDO--
    public function indexPedProv(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $idpedido = $request->idpedido;
        $pedidoprov = DetallePedidoProv::join('articulos', 'detalle_pedidprov.idarticulo', '=', 'articulos.id')
            ->join('pedido_proveedors', 'detalle_pedidprov.idpedidoprov', '=', 'pedido_proveedors.id')
            // ->join('articulos', 'inventarios.idarticulo', '=', 'articulos.id')
            ->select(
                'articulos.id as idarticulo',

                'articulos.codigo',
                'articulos.nombre as articulo',
                'articulos.unidad_envase as unidad_x_paquete',
                'detalle_pedidprov.cantidad_pedidoprov as cantidad',
                'articulos.precio_costo_unid as precio',
                'pedido_proveedors.total',
            )
            ->where('detalle_pedidprov.idpedidoprov', '=', $idpedido)->get();
        return ['pedidoprov' => $pedidoprov];
    }
}