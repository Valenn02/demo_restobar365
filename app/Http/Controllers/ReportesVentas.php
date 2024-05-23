<?php

namespace App\Http\Controllers;

use App\DetalleVenta;
use App\Moneda;
use App\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportesVentas extends Controller
{
    public function ResumenVentasPorDocumento(Request $request) {
        $fechaInicio = $request->fechaInicio . ' 00:00:00';
        $fechaFin = $request->fechaFin . ' 23:59:59';
    
        $ventas = Venta::join('personas', 'ventas.idcliente', '=', 'personas.id')
            ->join('users', 'ventas.idusuario', '=', 'users.id')
            ->join('roles', 'users.idrol', '=', 'roles.id')
            ->join('sucursales', 'users.idsucursal', '=', 'sucursales.id')
            ->select('ventas.num_comprobante as Factura',
                    'ventas.id',
                    'ventas.tipoEntrega as Tipo_entrega',
                    'ventas.idtipo_pago as Tipo_venta',
                    'sucursales.nombre as Nombre_sucursal',
                    'ventas.fecha_hora',
                    'roles.nombre AS nombre_rol',
                    'users.usuario',
                    'personas.nombre',
                    'ventas.total AS importe_BS',)
            ->whereBetween('fecha_hora', [$fechaInicio, $fechaFin])
            ->orderBy('ventas.fecha_hora', 'asc');
    
        if ($request->has('estadoVenta')) {
            $estado_venta = $request->estadoVenta;
            if ($estado_venta !== 'Todos') {
                $ventas->where('ventas.estado', '=', $estado_venta);
            }
        }
    
        if ($request->has('sucursal') && $request->sucursal !== 'undefined') {
            $sucursal = $request->sucursal;
            $ventas->where('sucursales.id', $sucursal);
        }
    
        if ($request->has('ejecutivoCuentas') && $request->ejecutivoCuentas !== 'undefined') {
            $ejecutivoCuentas = $request->ejecutivoCuentas;
            $ventas->where('ventas.idusuario', $ejecutivoCuentas);
        }
    
        if ($request->has('idcliente') && $request->idcliente !== 'undefined') {
            $cliente = $request->idcliente;
            $ventas->where('ventas.idcliente', $cliente);
        }
    
        $ventas = $ventas->get();
    
        $total_importeBs = 0;
        foreach ($ventas as &$venta) {
            $total_importeBs += $venta->importe_BS;
    
            // Mapeo para tipo_entrega
            switch ($venta->Tipo_entrega) {
                case 'L':
                    $venta->Tipo_entrega = 'Llevar';
                    break;
                case 'D':
                    $venta->Tipo_entrega = 'Delivery';
                    break;
                default:
                    $venta->Tipo_entrega = 'Mesa';
                    break;
            }
    
            // Mapeo para tipo_venta
            switch ($venta->Tipo_venta) {
                case 1:
                    $venta->Tipo_venta = 'EFECTIVO';
                    break;
                case 7:
                    $venta->Tipo_venta = 'QR';
                    break;
                default:
                    $venta->Tipo_venta = 'OTRO';
                    break;
            }
        }
    
        return [
            'ventas' => $ventas,
            'total_BS' => $total_importeBs,
        ];
    }

    public function ResumenVentasPorDocumentoDetallado(Request $request) {
        $fechaInicio = $request->fechaInicio . ' 00:00:00';
        $fechaFin = $request->fechaFin . ' 23:59:59';
    
        $ventas = DetalleVenta::select(
                'detalle_ventas.cantidad',
                'detalle_ventas.precio',
                'detalle_ventas.descuento',
                'detalle_ventas.codigoComida',
                'ventas.num_comprobante as Factura',
                'ventas.id',
                'ventas.total as Importe_Bs',
                'ventas.fecha_hora as Fecha',
                'personas.id as id_cliente',
                'personas.nombre as Cliente',
                'users.usuario as Vendedor',
                'roles.nombre as Ejecutivo_de_Venta',
                'sucursales.nombre as Sucursal',
                'ventas.tipoEntrega as Tipo_entrega',
                'ventas.idtipo_pago as Tipo_venta',
                'categoria_menu.nombre as nombre_categoria',
                'medidas.descripcion_medida as medida',
                'personas.num_documento as nit',
                DB::raw("ROUND((detalle_ventas.precio / detalle_ventas.cantidad), 2) AS precio_unitario"),
                'articulos.nombre as articulo_nombre',
                'menu.nombre as menu_nombre'
            )
            ->join('ventas', 'detalle_ventas.idventa', '=', 'ventas.id')
            ->join('personas', 'ventas.idcliente', '=', 'personas.id')
            ->join('users', 'ventas.idusuario', '=', 'users.id')
            ->join('roles', 'users.idrol', '=', 'roles.id')
            ->join('sucursales', 'users.idsucursal', '=', 'sucursales.id')
            ->leftJoin('articulos', 'detalle_ventas.codigoComida', '=', 'articulos.codigo')
            ->leftJoin('menu', 'detalle_ventas.codigoComida', '=', 'menu.codigo')
            ->leftJoin('categoria_menu','articulos.idcategoria_producto','=','categoria_menu.id')
            ->leftJoin('medidas','articulos.idmedida','=','medidas.id')
            ->whereBetween('ventas.fecha_hora', [$fechaInicio, $fechaFin])
            ->orderBy('personas.nombre')
            ->orderBy('ventas.fecha_hora');
    
        if ($request->has('estadoVenta')) {
            $estado_venta = $request->estadoVenta;
            if ($estado_venta !== 'Todos') {
                $ventas->where('ventas.estado', '=', $estado_venta);
            }
        }
    
        if ($request->has('sucursal') && $request->sucursal !== 'undefined') {
            $sucursal = $request->sucursal;
            $ventas->where('sucursales.id', $sucursal);
        }
    
        if ($request->has('ejecutivoCuentas') && $request->ejecutivoCuentas !== 'undefined') {
            $ejecutivoCuentas = $request->ejecutivoCuentas;
            $ventas->where('ventas.idusuario', $ejecutivoCuentas);
        }
    
        if ($request->has('idcliente') && $request->idcliente !== 'undefined') {
            $cliente = $request->idcliente;
            $ventas->where('ventas.idcliente', $cliente);
        }
    
        $ventas = $ventas->get();
    
        $totalVentasPorCliente = [];
    
        foreach ($ventas as $venta) {
            $idCliente = $venta->id_cliente;
            $cantidadVenta = $venta->cantidad;
            $precioVenta = $venta->precio;
    
            if (!isset($totalVentasPorCliente[$idCliente])) {
                $totalVentasPorCliente[$idCliente] = [
                    'total_cantidad' => 0,
                    'total_precio' => 0,
                    'index' => null,
                ];
            }
    
            $totalVentasPorCliente[$idCliente]['total_cantidad'] += $cantidadVenta;
            $totalVentasPorCliente[$idCliente]['total_precio'] += $precioVenta;
            $totalVentasPorCliente[$idCliente]['index'] = $venta->id;
        }
    
        foreach ($ventas as &$venta) {
            $idCliente = $venta->id_cliente;
    
            if (isset($totalVentasPorCliente[$idCliente]) && $venta->id == $totalVentasPorCliente[$idCliente]['index']) {
                $venta->total_cantidad_cliente = $totalVentasPorCliente[$idCliente]['total_cantidad'];
                $venta->total_precio_cliente = $totalVentasPorCliente[$idCliente]['total_precio'];
            }
    
            // Mapeo para tipo_entrega
            switch ($venta->Tipo_entrega) {
                case 'L':
                    $venta->Tipo_entrega = 'Llevar';
                    break;
                case 'D':
                    $venta->Tipo_entrega = 'Delivery';
                    break;
                default:
                    $venta->Tipo_entrega = 'Mesa';
                    break;
            }
    
            // Mapeo para tipo_venta
            switch ($venta->Tipo_venta) {
                case 1:
                    $venta->Tipo_venta = 'EFECTIVO';
                    break;
                case 7:
                    $venta->Tipo_venta = 'QR';
                    break;
                default:
                    $venta->Tipo_venta = 'OTRO';
                    break;
            }
    
            // Verificar si el código pertenece a artículos o menú y asignar los valores correspondientes
            if ($venta->articulo_nombre !== null) {
                $venta->nombre = $venta->articulo_nombre;
            } elseif ($venta->menu_nombre !== null) {
                $venta->nombre = $venta->menu_nombre;
            } else {
                $venta->nombre = 'Sin nombre';
            }
            // Eliminar los campos no necesarios después de la asignación
            unset($venta->articulo_nombre);
            unset($venta->menu_nombre);
        }
    
        return [
            'ventas' => $ventas,
        ];
    }
    
    

    public function ventasPorProducto(Request $request){
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;
        $fechaInicio = $fechaInicio . ' 00:00:00';
        $fechaFin = $fechaFin . ' 23:59:59';
        $ventas = Venta::join('detalle_ventas','ventas.id','detalle_ventas.idventa')
        ->join('personas','personas.id','=','ventas.idcliente')
        ->join('articulos','detalle_ventas.idarticulo','=','articulos.id')
        ->join('categorias','articulos.idcategoria','=','categorias.id')
        ->join('marcas','articulos.idmarca','=','marcas.id')
        ->join('industrias','articulos.idindustria','=','industrias.id')
        ->join('medidas','articulos.idmedida','=','medidas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->join('sucursales','users.idsucursal','=','sucursales.id')
        ->select('ventas.fecha_hora',
                'personas.nombre',
            'detalle_ventas.*',
            'articulos.codigo',
            'articulos.descripcion',
            'categorias.nombre as nombre_categoria',
            'marcas.nombre as nombre_marca',
            'industrias.nombre as nombre_industria',
            'medidas.descripcion_medida as medida')
        ->whereBetween('fecha_hora', [$fechaInicio, $fechaFin]);

        if ($request->has('sucursal') && $request->sucursal !== 'undefined') {
                $sucursal = $request->sucursal;
                $ventas->where('sucursales.id', $sucursal);
            }

        if ($request->has('idcliente') && $request->idcliente !== 'undefined') {
                $cliente = $request->idcliente;
                $ventas->where('ventas.idcliente' , $cliente);
            }
        if ($request->has('articulo') && $request->articulo !== 'undefined') {
                $articulo = $request->articulo;
                $ventas->where('detalle_ventas.idarticulo' , $articulo);
            }
        if ($request->has('marca') && $request->marca !== 'undefined') {
                $idmarca = $request->marca;
                $ventas->where('articulos.idmarca' , $idmarca);
                
            }
        if ($request->has('linea') && $request->linea !== 'undefined') {
                $idlinea = $request->linea;
                $ventas->where('articulos.idcategoria' , $idlinea);
                
            }
        if ($request->has('industria') && $request->industria !== 'undefined') {
                $idindustria = $request->industria;
                $ventas->where('articulos.idindustria' , $idindustria);
                
            }
        $ventas = $ventas->get();
        return ['resultados' =>$ventas];
    }
    
    

}