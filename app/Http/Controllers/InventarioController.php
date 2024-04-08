<?php

namespace App\Http\Controllers;

use App\Articulo;
use App\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Exports\ProductosBajoStockExport;
use App\Exports\ProductosPorVencerseExport;
use App\Exports\ProductosVencidosExport;
use Maatwebsite\Excel\Facades\Excel;

class InventarioController extends Controller
{
    //listar almacen 
    // public function index(Request $request)
    // {
    //     if (!$request->ajax())
    //         return redirect('/');

    //     $buscar = $request->buscar;
    //     $criterio = $request->criterio;

    //     if ($buscar == '') {
    //         $inventarios = Inventario::join('almacens', 'inventarios.idalmacen', '=', 'almacens.id')
    //             ->join('articulos', 'inventarios.idarticulo', '=', 'articulos.id')
    //             ->select(
    //                 'inventarios.id',
    //                 'inventarios.fecha_vencimiento',
    //                 'inventarios.saldo_stock',

    //                 'almacens.nombre_almacen',
    //                 'almacens.ubicacion',

    //                 'articulos.nombre as nombre_producto',
    //                 'articulos.unidad_envase',

    //             )
    //             ->orderBy('inventarios.id', 'desc')->paginate(3);
    //     } else {
    //         $inventarios = Inventario::join('almacens', 'inventarios.idalmacen', '=', 'almacens.id')
    //             ->join('articulos', 'inventarios.idarticulo', '=', 'articulos.id')
    //             ->select(

    //                 'inventarios.id',
    //                 'inventarios.fecha_vencimiento',
    //                 'inventarios.saldo_stock',

    //                 'almacens.nombre_almacen',
    //                 'almacens.ubicacion',

    //                 'articulos.nombre as nombre_producto',
    //                 'articulos.precio_costo_unid',
    //             )
    //             ->where('inventarios.' . $criterio, 'like', '%' . $buscar . '%')
    //             ->orderBy('inventarios.id', 'desc')->paginate(3);
    //     }


    //     return [
    //         'pagination' => [
    //             'total' => $inventarios->total(),
    //             'current_page' => $inventarios->currentPage(),
    //             'per_page' => $inventarios->perPage(),
    //             'last_page' => $inventarios->lastPage(),
    //             'from' => $inventarios->firstItem(),
    //             'to' => $inventarios->lastItem(),
    //         ],
    //         'inventarios' => $inventarios
    //     ];
    // }
    public function store(Request $request)
    {
        Log::info("al menos llegaa xddd");

        if ($request->has('inventarios')) {
            $inventarios = $request->input('inventarios');
            Log::info("llego");
            foreach ($inventarios as $inventario) {
                // Verificar si el idarticulo existe en la tabla de articulos
                $articulo = Articulo::find($inventario['idarticulo']);
                Log::info("llego2");

                if ($articulo) {
                    // El idarticulo existe, obtener todos los inventarios con el mismo idarticulo
                    $inventariosExistente = Inventario::where('idarticulo', $inventario['idarticulo'])
                        ->whereDate('fecha_vencimiento', $inventario['fecha_vencimiento'])
                        ->get();
                    Log::info("El articulo existe");

                    $foundInventory = false;
                    foreach ($inventariosExistente as $invExistente) {
                        Log::info("El inventario");
                        $fechaVencimiento = new \DateTime($invExistente->fecha_vencimiento);

                        // Comparar solo la fecha de vencimiento (día, mes y año)
                        if ($fechaVencimiento->format('Y-m-d') === date('Y-m-d', strtotime($inventario['fecha_vencimiento']))) {
                            // Verificar si el índice 'cantidad' está presente antes de usarlo
                            Log::info("se comparo");

                            if (isset($inventario['cantidad'])) {
                                // Si la fecha de vencimiento coincide, sumar el saldo_stock
                                $invExistente->saldo_stock += $inventario['cantidad'];
                                $invExistente->save();
                                $foundInventory = true;
                                break;
                            } else {
                                // Manejar el caso en el que el índice 'cantidad' no está presente
                                Log::info("El índice 'cantidad' no está presente en el array");
                            }
                        }
                    }

                    if (!$foundInventory) {
                        // Si no hay coincidencias de fecha de vencimiento, registrar normalmente
                        $newInventario = new Inventario();
                        $newInventario->idalmacen = $inventario['idalmacen'];
                        $newInventario->idarticulo = $inventario['idarticulo'];
                        $newInventario->fecha_vencimiento = $inventario['fecha_vencimiento'];

                        // Verificar si el índice 'cantidad' está presente antes de usarlo
                        if (isset($inventario['cantidad'])) {
                            $newInventario->saldo_stock = $inventario['cantidad'];
                        } else {
                            // Asignar un valor por defecto en caso de que el índice 'cantidad' no esté presente
                            $newInventario->saldo_stock = 0;
                            Log::info("El índice 'cantidad' no está presente en el array");
                        }

                        $newInventario->save();
                    }
                } else {
                    // El idarticulo no existe, registrar normalmente
                    Log::info("No existe el articulo");
                }
            }

            // Aquí puedes retornar una respuesta al cliente para indicar que los datos fueron guardados exitosamente
            return response()->json(['message' => 'Inventarios guardados exitosamente'], 200);
        }

        // Si no se enviaron inventarios en la solicitud, puedes retornar una respuesta de error
        return response()->json(['error' => 'No se enviaron inventarios'], 400);
    }

    public function productosPorVencer(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $inventarios = Inventario::join('almacens', 'inventarios.idalmacen', '=', 'almacens.id')
                ->join('articulos', 'inventarios.idarticulo', '=', 'articulos.id')
                ->join('proveedores', 'articulos.idproveedor', '=', 'proveedores.id')
                ->join('personas', 'proveedores.id', '=', 'personas.id')
                ->select(
                    'inventarios.id',
                    'inventarios.fecha_vencimiento',
                    'inventarios.saldo_stock',

                    'almacens.nombre_almacen',
                    'almacens.ubicacion',

                    'articulos.codigo',
                    'articulos.nombre as nombre_producto',
                    'articulos.unidad_envase',

                    'personas.nombre as nombre_proveedor',
                )
                ->whereRaw('DATEDIFF(inventarios.fecha_vencimiento, CURDATE()) <= 30')
                ->whereDate('inventarios.fecha_vencimiento', '>', DB::raw('CURDATE()'))
                ->orderBy('inventarios.id', 'desc')->paginate(3);
        } else {
            $inventarios = Inventario::join('almacens', 'inventarios.idalmacen', '=', 'almacens.id')
                ->join('articulos', 'inventarios.idarticulo', '=', 'articulos.id')
                ->join('proveedores', 'articulos.idproveedor', '=', 'proveedores.id')
                ->join('personas', 'proveedores.id', '=', 'personas.id')
                ->select(

                    'inventarios.id',
                    'inventarios.fecha_vencimiento',
                    'inventarios.saldo_stock',

                    'almacens.nombre_almacen',
                    'almacens.ubicacion',

                    'articulos.codigo',
                    'articulos.nombre as nombre_producto',
                    'articulos.precio_costo_unid',

                    'personas.nombre as nombre_proveedor',
                )
                ->whereRaw('DATEDIFF(inventarios.fecha_vencimiento, CURDATE()) <= 30')
                ->whereDate('inventarios.fecha_vencimiento', '>', DB::raw('CURDATE()'))
                ->where('inventarios.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderBy('inventarios.id', 'desc')->paginate(3);
        }


        return [
            'pagination' => [
                'total' => $inventarios->total(),
                'current_page' => $inventarios->currentPage(),
                'per_page' => $inventarios->perPage(),
                'last_page' => $inventarios->lastPage(),
                'from' => $inventarios->firstItem(),
                'to' => $inventarios->lastItem(),
            ],
            'inventarios' => $inventarios
        ];
    }

    public function listarReportePorVencerExcel()
    {
        return Excel::download(new ProductosPorVencerseExport, 'articulosPorVencer.xlsx');
    }

    public function productosVencidos(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $inventarios = Inventario::join('almacens', 'inventarios.idalmacen', '=', 'almacens.id')
                ->join('articulos', 'inventarios.idarticulo', '=', 'articulos.id')
                ->join('proveedores', 'articulos.idproveedor', '=', 'proveedores.id')
                ->join('personas', 'proveedores.id', '=', 'personas.id')
                ->select(
                    'inventarios.id',
                    'inventarios.fecha_vencimiento',
                    'inventarios.saldo_stock',

                    'almacens.nombre_almacen',
                    'almacens.ubicacion',

                    'articulos.codigo',
                    'articulos.nombre as nombre_producto',
                    'articulos.unidad_envase',

                    'personas.nombre as nombre_proveedor',

                )
                ->whereDate('inventarios.fecha_vencimiento', '<=', DB::raw('CURDATE()'))
                ->orderBy('inventarios.id', 'desc')->paginate(3);
        } else {
            $inventarios = Inventario::join('almacens', 'inventarios.idalmacen', '=', 'almacens.id')
                ->join('articulos', 'inventarios.idarticulo', '=', 'articulos.id')
                ->join('proveedores', 'articulos.idproveedor', '=', 'proveedores.id')
                ->join('personas', 'proveedores.id', '=', 'personas.id')
                ->select(

                    'inventarios.id',
                    'inventarios.fecha_vencimiento',
                    'inventarios.saldo_stock',

                    'almacens.nombre_almacen',
                    'almacens.ubicacion',

                    'articulos.codigo',
                    'articulos.nombre as nombre_producto',
                    'articulos.precio_costo_unid',

                    'personas.nombre as nombre_proveedor',
                )
                ->whereDate('inventarios.fecha_vencimiento', '<=', DB::raw('CURDATE()'))
                ->where('inventarios.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderBy('inventarios.id', 'desc')->paginate(3);
        }


        return [
            'pagination' => [
                'total' => $inventarios->total(),
                'current_page' => $inventarios->currentPage(),
                'per_page' => $inventarios->perPage(),
                'last_page' => $inventarios->lastPage(),
                'from' => $inventarios->firstItem(),
                'to' => $inventarios->lastItem(),
            ],
            'inventarios' => $inventarios
        ];
    }

    public function listarReporteVencidosExcel()
    {
        return Excel::download(new ProductosVencidosExport, 'articulosVencidos.xlsx');
    }

    public function productosBajoStock(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $inventarios = Inventario::join('almacens', 'inventarios.idalmacen', '=', 'almacens.id')
                ->join('articulos', 'inventarios.idarticulo', '=', 'articulos.id')
                ->join('proveedores', 'articulos.idproveedor', '=', 'proveedores.id')
                ->join('personas', 'proveedores.id', '=', 'personas.id')
                ->select(
                    'inventarios.id',
                    'inventarios.fecha_vencimiento',
                    'inventarios.saldo_stock',

                    'almacens.nombre_almacen',
                    'almacens.ubicacion',

                    'articulos.codigo',
                    'articulos.nombre as nombre_producto',
                    'articulos.unidad_envase',
                    'articulos.stock',

                    'personas.nombre as nombre_proveedor',

                )
                ->whereRaw('articulos.stock > inventarios.saldo_stock')
                ->orderBy('inventarios.id', 'desc')->paginate(3);
        } else {
            $inventarios = Inventario::join('almacens', 'inventarios.idalmacen', '=', 'almacens.id')
                ->join('articulos', 'inventarios.idarticulo', '=', 'articulos.id')
                ->join('proveedores', 'articulos.idproveedor', '=', 'proveedores.id')
                ->join('personas', 'proveedores.id', '=', 'personas.id')
                ->select(

                    'inventarios.id',
                    'inventarios.fecha_vencimiento',
                    'inventarios.saldo_stock',

                    'almacens.nombre_almacen',
                    'almacens.ubicacion',

                    'articulos.codigo',
                    'articulos.nombre as nombre_producto',
                    'articulos.precio_costo_unid',

                    'personas.nombre as nombre_proveedor',
                )
                ->whereRaw('articulos.stock > inventarios.saldo_stock')
                ->where('inventarios.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderBy('inventarios.id', 'desc')->paginate(3);
        }


        return [
            'pagination' => [
                'total' => $inventarios->total(),
                'current_page' => $inventarios->currentPage(),
                'per_page' => $inventarios->perPage(),
                'last_page' => $inventarios->lastPage(),
                'from' => $inventarios->firstItem(),
                'to' => $inventarios->lastItem(),
            ],
            'inventarios' => $inventarios
        ];
    }

    public function listarReporteBajoStockExcel()
    {
        return Excel::download(new ProductosBajoStockExport, 'articulosBajoStock.xlsx');
    }
    //-------------------aumente el listado mejorado------
    public function indextraspaso(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        Log::info('Data', [
            'idAlmacen' => $request->idAlmacen,
            'buscar' => $request->buscar,
            'criterio' => $request->criterio,
        ]);

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $idAlmacen = $request->idAlmacen;

            $inventarios = Inventario::join('almacens', 'inventarios.idalmacen', '=', 'almacens.id')
                ->join('articulos', 'inventarios.idarticulo', '=', 'articulos.id')
                ->join('proveedores', 'articulos.idproveedor', '=', 'proveedores.id') // Agregar esta línea para unir la tabla de proveedores
                ->join('personas', 'proveedores.id', '=', 'personas.id')
                ->select(
                    'inventarios.id',
                    'inventarios.idarticulo',

                    'articulos.nombre as nombre_producto',
                    'articulos.codigo',
                    'articulos.precio_costo_unid',
                    'articulos.precio_costo_paq',
                    'articulos.unidad_envase',

                    'inventarios.saldo_stock',
                    'inventarios.fecha_vencimiento',
                    'articulos.precio_venta',

                    'almacens.ubicacion',
                    'personas.nombre as nombre_proveedor',
                    'articulos.fotografia',                    
                )
                ->where('inventarios.idalmacen', '=', $idAlmacen);
                //->orderBy('inventarios.id', 'desc')->paginate(5);

                if (!empty($buscar)) {
                    $inventarios = $inventarios->where(function ($query) use ($criterio, $buscar) {
                        $query->where('articulos.' . $criterio, 'like', '%' . $buscar . '%');
                    });
                }
                $inventarios = $inventarios->orderBy('inventarios.id', 'desc')->paginate(4);
        return [
            'pagination' => [
                'total' => $inventarios->total(),
                'current_page' => $inventarios->currentPage(),
                'per_page' => $inventarios->perPage(),
                'last_page' => $inventarios->lastPage(),
                'from' => $inventarios->firstItem(),
                'to' => $inventarios->lastItem(),
            ],
            'inventarios' => $inventarios
        ];
    }
       ////////////////--Lista or item y lotes-/////////////////
    public function indexItemLote(Request $request,$tipo)
    {
        if (!$request->ajax())
            return redirect('/');
        $idAlmacen = $request->idAlmacen;
        $criterio = $request->criterio;
        $buscar = $request->buscar;

        if ($tipo === 'item') {
            $inventarios = Inventario::join('almacens', 'inventarios.idalmacen', '=', 'almacens.id')
            ->join('articulos', 'inventarios.idarticulo', '=', 'articulos.id')
            ->join('proveedores', 'articulos.idproveedor', '=', 'proveedores.id')
            ->join('personas', 'proveedores.id', '=', 'personas.id')
            ->select(
                'articulos.nombre as nombre_producto',
                'articulos.unidad_envase',
                'almacens.nombre_almacen',
                DB::raw('SUM(inventarios.saldo_stock) as saldo_stock_total')
            )
            ->where('inventarios.idalmacen', '=', $idAlmacen)
            ->groupBy('articulos.nombre', 'almacens.nombre_almacen','articulos.unidad_envase')
            ->orderBy('articulos.nombre')
            ->orderBy('almacens.nombre_almacen');
            //->get();

        }elseif ($tipo === 'lote') {
            $inventarios = Inventario::join('almacens', 'inventarios.idalmacen', '=', 'almacens.id')
            ->join('articulos', 'inventarios.idarticulo', '=', 'articulos.id')
            ->join('detalle_ingresos', 'detalle_ingresos.idarticulo', '=', 'articulos.id')
            ->join('ingresos', 'detalle_ingresos.idingreso', '=', 'ingresos.id')
            ->join('proveedores', 'articulos.idproveedor', '=', 'proveedores.id')
            ->join('personas', 'proveedores.id', '=', 'personas.id')
            ->select(
                'articulos.nombre as nombre_producto',
                'articulos.unidad_envase',
                'articulos.precio_costo_unid',
                'inventarios.saldo_stock',
                \DB::raw('DATE_FORMAT(ingresos.fecha_hora, "%d-%m-%Y") as fecha_ingreso'),
                'inventarios.fecha_vencimiento',
                'almacens.nombre_almacen',
            )
            ->where('inventarios.idalmacen', '=', $idAlmacen)
            ->orderBy('almacens.nombre_almacen');
            //->get();
        }
        //---------------------------------------
        if (!empty($buscar)) {
            $inventarios = $inventarios->where(function ($query) use ($criterio, $buscar, $tipo) {
                $query->where('articulos.' . $criterio, 'like', '%' . $buscar . '%');
                if ($tipo === 'lote') {
                    $query->orWhere('articulos.' . $criterio, 'like', '%' . $buscar . '%');
                }
            });
        }
        $inventarios = $inventarios->get();
        //---------------------------------
        return ['inventarios' => $inventarios];
    }
    //LISTA PARA OBTENER EL SALDO_STOCK POR ITEM POR EL ALMACEN,NOMBRE Y ID DE ARTICULO
    public function indexsaldostock(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        $idAlmacen = $request->idAlmacen;
        $idArticulo = $request->idArticulo;
        if ($idAlmacen !== null && $idArticulo !== null) {
            $invenstock = Inventario::join('almacens', 'inventarios.idalmacen', '=', 'almacens.id')
            ->join('articulos', 'inventarios.idarticulo', '=', 'articulos.id')
            ->join('proveedores', 'articulos.idproveedor', '=', 'proveedores.id')
            ->join('personas', 'proveedores.id', '=', 'personas.id')
            ->select(
                'articulos.nombre as nombre_producto',
                //'articulos.unidad_envase',
                'almacens.nombre_almacen',
                DB::raw('SUM(inventarios.saldo_stock) as saldo_stock_totaldos')
            )
            ->where('inventarios.idalmacen', '=', $idAlmacen)
            ->where('inventarios.idarticulo', '=', $idArticulo)
            ->groupBy('articulos.nombre', 'almacens.nombre_almacen')
            ->orderBy('articulos.nombre')
            ->orderBy('almacens.nombre_almacen')->get();

            return ['invenstock' => $invenstock];
        }else {
            // Si falta alguno de los valores, regresar respuesta vacía
            return ['invenstock' => []];
        }
    }
    // public function store(Request $request)
    // {
    //     if (!$request->ajax()) {
    //         return redirect('/');
    //     }

    //     // Verificar si el idarticulo existe en la tabla de articulos
    //     $articulo = Articulo::find($request->idarticulo);

    //     if ($articulo) {
    //         // El idarticulo existe, obtener todos los inventarios con el mismo idarticulo
    //         $inventarios = Inventario::where('idarticulo', $request->idarticulo)->get();

    //         $foundInventory = false;
    //         foreach ($inventarios as $inventario) {
    //             // Comparar solo la fecha de vencimiento (día, mes y año)
    //             if ($inventario->fecha_vencimiento->format('Y-m-d') === date('Y-m-d', strtotime($request->fecha_vencimiento))) {
    //                 // Si la fecha de vencimiento coincide, sumar el saldo_stock
    //                 $inventario->saldo_stock += $request->saldo_stock;
    //                 $inventario->save();
    //                 $foundInventory = true;
    //                 break;
    //             }
    //         }

    //         if (!$foundInventory) {
    //             // Si no hay coincidencias de fecha de vencimiento, registrar normalmente
    //             $inventario = new Inventario();
    //             $inventario->idalmacen = $request->idalmacen;
    //             $inventario->idarticulo = $request->idarticulo;
    //             $inventario->fecha_vencimiento = $request->fecha_vencimiento;
    //             $inventario->saldo_stock = $request->saldo_stock;
    //             $inventario->save();
    //         }
    //     } else {
    //         // El idarticulo no existe, registrar normalmente
    //         Log::info("No existe el articulo");
    //     }
    // }

}