<?php

namespace App\Http\Controllers;

use App\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportesInventariosController extends Controller
{
    public function inventarioFisicoValorado(Request $request,$tipo){
        
        
        
       
        $fechaVencimiento = $request->fecha_vencimiento;
        if ($tipo === 'item'){
            $resultados = DB::table('inventarios')
                ->join('almacens', 'inventarios.idalmacen', '=', 'almacens.id')
                ->join('articulos', 'inventarios.idarticulo', '=', 'articulos.id')
                ->join('categoria_producto', 'articulos.idcategoria_producto', '=', 'categoria_producto.id')
                ->select(
                    'articulos.nombre AS nombre_producto',
                    'articulos.unidad_paquete',
                    'almacens.nombre_almacen',
                    DB::raw('SUM(inventarios.saldo_stock) AS saldo_stock_total'),
                    DB::raw('(SUM(inventarios.saldo_stock) * articulos.precio_costo_unid) AS costo_total'), 
                    'categoria_producto.nombre AS nombre_categoria',
                )
                ->where('inventarios.fecha_vencimiento','<=', $fechaVencimiento)
                ->groupBy(
                    'articulos.nombre',
                    'articulos.unidad_paquete',
                    'almacens.nombre_almacen',
                    'categoria_producto.nombre',
                    'articulos.precio_costo_unid' 
                )
                ->orderBy('articulos.nombre')
                ->orderBy('almacens.nombre_almacen');

        }
        else if ($tipo === 'lote'){
            $resultados = DB::table('inventarios')
                ->join('almacens', 'inventarios.idalmacen', '=', 'almacens.id')
                ->join('articulos', 'inventarios.idarticulo', '=', 'articulos.id')
                ->join('categoria_producto', 'articulos.idcategoria_producto', '=', 'categoria_producto.id')

                ->select(
                    'articulos.nombre AS nombre_producto',
                    'articulos.unidad_paquete',
                    'articulos.precio_costo_unid',
                    'inventarios.saldo_stock',
                    DB::raw('(inventarios.saldo_stock * articulos.precio_costo_unid) AS costo_total'), 
                    DB::raw('DATE_FORMAT(inventarios.created_at, "%Y-%m-%d") AS fecha_ingreso'),
                    'inventarios.fecha_vencimiento',
                    'almacens.nombre_almacen',
                    'categoria_producto.nombre AS nombre_categoria',

                )
                ->where('inventarios.fecha_vencimiento','<=',$fechaVencimiento)
                ->orderBy('articulos.nombre');

        }
        if ($request->has('idAlmacen') && $request->idAlmacen !== 'undefined') {
            $idAlmacen = $request->idAlmacen;
            $resultados->where('almacens.id', $idAlmacen);
        }
        if ($request->has('idArticulo') && $request->idArticulo !== 'undefined') {
            $idArticulo = $request->idArticulo;
            $resultados->where('articulos.id' , $idArticulo);
        }

        if ($request->has('idLinea') && $request->idLinea !== 'undefined') {
            $idLinea = $request->idLinea;
            $resultados->where('articulos.idcategoria_producto' , $idLinea);
            
        }

        $resultados=$resultados->get();
        return ['inventarios_valorado' => $resultados];

    }
    public function resumenFisicoMovimientos(Request $request){
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;

        $fechaInicio = $fechaInicio . ' 00:00:00';
        $fechaFin = $fechaFin . ' 23:59:59';
        $productos = DB::table('articulos')
        ->select(
            'articulos.id',
            'articulos.nombre',
            'articulos.codigo',
            'articulos.descripcion',
            'categorias.nombre as nombre_categoria',
            'marcas.nombre as nombre_marca',
            'industrias.nombre as nombre_industria',
            'medidas.descripcion_medida as medida',
            'almacens.sucursal as idSucursal'
        )
        ->join('categorias', 'articulos.idcategoria_producto', '=', 'categorias.id')
        ->join('marcas', 'articulos.idmarca', '=', 'marcas.id')
        ->join('industrias', 'articulos.idindustria', '=', 'industrias.id')
        ->join('medidas', 'articulos.idmedida', '=', 'medidas.id')
        ->join('inventarios','inventarios.idarticulo','=','articulos.id')
        ->join('almacens','inventarios.idalmacen','=','almacens.id')
        ->join('sucursales','almacens.sucursal','=','sucursales.id')
        ->groupBy('articulos.id', 'articulos.nombre', 'articulos.codigo', 'articulos.descripcion', 'categorias.nombre', 'marcas.nombre', 'industrias.nombre', 'medidas.descripcion_medida','almacens.sucursal');

        
        if ($request->has('articulo') && $request->articulo !== 'undefined') {
            $idarticulo = $request->articulo;
            $productos->where('articulos.id' , $idarticulo);
        }
        if ($request->has('sucursal') && $request->sucursal !== 'undefined') {
            $sucursal = $request->sucursal;
            $productos->where('sucursales.id', $sucursal);
        }
        // Agregar filtros opcionales si se proporcionan otros parámetros
        if ($request->has('marca') && $request->marca !== 'undefined') {
            $idmarca = $request->marca;
            $productos->where('articulos.idmarca' , $idmarca);
        }
        if ($request->has('linea') && $request->linea !== 'undefined') {
            $idlinea = $request->linea;
            $productos->where('articulos.idcategoria_producto' , $idlinea);
        }
    $productos=$productos->get();
    
    $resultados = [];
    
    foreach ($productos as $producto) {
        $traspasos_ingreso = DB::table('detalle_traspasos')
            ->join('traspasos', 'detalle_traspasos.idtraspaso', '=', 'traspasos.id')
            ->join('inventarios','detalle_traspasos.idinventario','=','inventarios.id')
            ->join('almacens','inventarios.idalmacen','=','almacens.id')
            ->where('inventarios.idarticulo', $producto->id)
            ->where('traspasos.tipo_traspaso', 'Entrada')
            ->whereBetween('traspasos.fecha_traspaso', [$fechaInicio, $fechaFin])
            ->sum('detalle_traspasos.cantidad_traspaso');
        $traspasos_salida = DB::table('detalle_traspasos')
            ->join('traspasos', 'detalle_traspasos.idtraspaso', '=', 'traspasos.id')
            ->join('inventarios','detalle_traspasos.idinventario','=','inventarios.id')
            ->join('almacens','inventarios.idalmacen','=','almacens.id')
            ->where('inventarios.idarticulo', $producto->id)
            ->where('traspasos.tipo_traspaso', 'Salida')
            ->whereBetween('traspasos.fecha_traspaso', [$fechaInicio, $fechaFin])
            ->sum('detalle_traspasos.cantidad_traspaso');

        $saldoAnterior = DB::table('detalle_ingresos')
            ->join('ingresos', 'detalle_ingresos.idingreso', '=', 'ingresos.id')
            ->join('users','ingresos.idusuario','=','users.id')
            ->where('users.idsucursal',$producto->idSucursal)
            ->where('detalle_ingresos.idarticulo', $producto->id)
            ->where('ingresos.fecha_hora', '<', $fechaInicio)
            ->sum('detalle_ingresos.cantidad');
        
        $egresosAnteriores = DB::table('ventas')
            ->join('detalle_ventas', 'detalle_ventas.idventa', '=', 'ventas.id')
            ->where('detalle_ventas.idarticulo', $producto->id)
            ->where('ventas.fecha_hora', '<', $fechaInicio)
            ->sum('detalle_ventas.cantidad');
        $saldoAnterior -= $egresosAnteriores;
    
        $ingresos = DB::table('detalle_ingresos')
            ->join('ingresos', 'detalle_ingresos.idingreso', '=', 'ingresos.id')
            ->join('users','ingresos.idusuario','=','users.id')
            ->where('users.idsucursal',$producto->idSucursal)
            ->where('detalle_ingresos.idarticulo', $producto->id)
            ->where('ingresos.fecha_hora', '>=', $fechaInicio)
            ->where('ingresos.fecha_hora', '<=', $fechaFin)
            ->sum('detalle_ingresos.cantidad');
        $ingresos+=$traspasos_ingreso;
        $ventas = DB::table('ventas')
            ->join('detalle_ventas', 'detalle_ventas.idventa', '=', 'ventas.id')
            ->join('users','ventas.idusuario','=','users.id')
            ->where('users.idsucursal',$producto->idSucursal)
            ->where('detalle_ventas.idarticulo', $producto->id)
            ->where('ventas.fecha_hora', '>=', $fechaInicio)
            ->where('ventas.fecha_hora', '<=', $fechaFin)
            ->sum('detalle_ventas.cantidad');
        $ventas +=$traspasos_salida;
        $saldoActual = $saldoAnterior + $ingresos - $ventas;
    
        $resultado = [
            
            'nombre_producto' => $producto->nombre,
            'codigo' => $producto->codigo,
            'descripcion' => $producto->descripcion,
            'nombre_categoria' => $producto->nombre_categoria,
            'nombre_marca' => $producto->nombre_marca,
            'nombre_industria' => $producto->nombre_industria,
            'medida' => $producto->medida,
            'saldo_anterior' => $saldoAnterior,
            'ingresos' => $ingresos,
            'ventas' => $ventas,
            'saldo_actual' => $saldoActual,
            'traspasos_entrada' =>$traspasos_ingreso,
            'traspasos_salida' =>$traspasos_salida
        ];
    
        $resultados[] = $resultado;
    }
    
        return ['resultados' => $resultados,'productos'=>$productos];
    
    }
    public function resumenFisicoMovimientosDetallado(Request $request){
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;

        $fechaInicio = $fechaInicio . ' 00:00:00';
        $fechaFin = $fechaFin . ' 23:59:59';
        $productos = DB::table('articulos')
        ->select(
            'articulos.id',
            'articulos.nombre',
            'articulos.codigo',
            'articulos.descripcion',
            'categorias.nombre as nombre_categoria',
            'marcas.nombre as nombre_marca',
            'industrias.nombre as nombre_industria',
            'medidas.descripcion_medida as medida',
            'almacens.sucursal as idSucursal'
        )
        ->join('categorias', 'articulos.idcategoria_producto', '=', 'categorias.id')
        ->join('marcas', 'articulos.idmarca', '=', 'marcas.id')
        ->join('industrias', 'articulos.idindustria', '=', 'industrias.id')
        ->join('medidas', 'articulos.idmedida', '=', 'medidas.id')
        ->join('inventarios','inventarios.idarticulo','=','articulos.id')
        ->join('almacens','inventarios.idalmacen','=','almacens.id')
        ->groupBy('articulos.id', 'articulos.nombre', 'articulos.codigo', 'articulos.descripcion', 'categorias.nombre', 'marcas.nombre', 'industrias.nombre', 'medidas.descripcion_medida','almacens.sucursal');

        
        if ($request->has('articulo') && $request->articulo !== 'undefined') {
            $idarticulo = $request->articulo;
            $productos->where('articulos.id' , $idarticulo);
        }
        if ($request->has('sucursal') && $request->sucursal !== 'undefined') {
            $sucursal = $request->sucursal;
            $productos->where('almacens.sucursal', $sucursal);
        }
        // Agregar filtros opcionales si se proporcionan otros parámetros
        if ($request->has('marca') && $request->marca !== 'undefined') {
            $idmarca = $request->marca;
            $productos->where('articulos.idmarca' , $idmarca);
        }
        if ($request->has('linea') && $request->linea !== 'undefined') {
            $idlinea = $request->linea;
            $productos->where('articulos.idcategoria_producto' , $idlinea);
        }
    $productos=$productos->get();
    
    $resultados = [];
    
    foreach ($productos as $producto) {
        $traspasos_ingreso = DB::table('detalle_traspasos')
            ->join('traspasos', 'detalle_traspasos.idtraspaso', '=', 'traspasos.id')
            ->join('inventarios','detalle_traspasos.idinventario','=','inventarios.id')
            ->join('almacens','inventarios.idalmacen','=','almacens.id')
            ->where('inventarios.idarticulo', $producto->id)
            ->where('traspasos.tipo_traspaso', 'Entrada')
            ->whereBetween('traspasos.fecha_traspaso', [$fechaInicio, $fechaFin])
            ->sum('detalle_traspasos.cantidad_traspaso');
        $traspasos_salida = DB::table('detalle_traspasos')
            ->join('traspasos', 'detalle_traspasos.idtraspaso', '=', 'traspasos.id')
            ->join('inventarios','detalle_traspasos.idinventario','=','inventarios.id')
            ->join('almacens','inventarios.idalmacen','=','almacens.id')
            ->where('inventarios.idarticulo', $producto->id)
            ->where('traspasos.tipo_traspaso', 'Salida')
            ->whereBetween('traspasos.fecha_traspaso', [$fechaInicio, $fechaFin])
            ->sum('detalle_traspasos.cantidad_traspaso');

        $saldoAnterior = DB::table('detalle_ingresos')
            ->join('ingresos', 'detalle_ingresos.idingreso', '=', 'ingresos.id')
            ->join('users','ingresos.idusuario','=','users.id')
            ->where('users.idsucursal',$producto->idSucursal)
            ->where('detalle_ingresos.idarticulo', $producto->id)
            ->where('ingresos.fecha_hora', '<', $fechaInicio)
            ->sum('detalle_ingresos.cantidad');
        
        $egresosAnteriores = DB::table('ventas')
            ->join('detalle_ventas', 'detalle_ventas.idventa', '=', 'ventas.id')
            ->where('detalle_ventas.idarticulo', $producto->id)
            ->where('ventas.fecha_hora', '<', $fechaInicio)
            ->sum('detalle_ventas.cantidad');
        $saldoAnterior -= $egresosAnteriores;
    
        $ingresos = DB::table('detalle_ingresos')
            ->join('ingresos', 'detalle_ingresos.idingreso', '=', 'ingresos.id')
            ->join('users','ingresos.idusuario','=','users.id')
            ->where('users.idsucursal',$producto->idSucursal)
            ->where('detalle_ingresos.idarticulo', $producto->id)
            ->where('ingresos.fecha_hora', '>=', $fechaInicio)
            ->where('ingresos.fecha_hora', '<=', $fechaFin)
            ->sum('detalle_ingresos.cantidad');
        $ingresos+=$traspasos_ingreso;
        $ventas = DB::table('ventas')
            ->join('detalle_ventas', 'detalle_ventas.idventa', '=', 'ventas.id')
            ->join('users','ventas.idusuario','=','users.id')
            ->where('users.idsucursal',$producto->idSucursal)
            ->where('detalle_ventas.idarticulo', $producto->id)
            ->where('ventas.fecha_hora', '>=', $fechaInicio)
            ->where('ventas.fecha_hora', '<=', $fechaFin)
            ->sum('detalle_ventas.cantidad');
        $ventas +=$traspasos_salida;
        $saldoActual = $saldoAnterior + $ingresos - $ventas;
    
        $resultado = [
            
            'nombre_producto' => $producto->nombre,
            'codigo' => $producto->codigo,
            'descripcion' => $producto->descripcion,
            'nombre_categoria' => $producto->nombre_categoria,
            'nombre_marca' => $producto->nombre_marca,
            'nombre_industria' => $producto->nombre_industria,
            'medida' => $producto->medida,
            'saldo_anterior' => $saldoAnterior,
            'ingresos' => $ingresos,
            'ventas' => $ventas,
            'saldo_actual' => $saldoActual,
            'traspasos_entrada' =>$traspasos_ingreso,
            'traspasos_salida' =>$traspasos_salida
        ];
    
        $resultados[] = $resultado;
    }
    
        return ['resultados' => $resultados,'productos'=>$productos];
    
    }
}
