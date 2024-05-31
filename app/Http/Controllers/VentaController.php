<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use App\Venta;
use App\Articulo;
use App\Inventario;
use App\DetalleVenta;
use App\User;
use App\CreditoVenta;
use App\CuotasCredito;
use App\Empresa;
use App\Caja;
use App\Factura;
use App\FacturaFueraLinea;
use App\Menu;
use App\Http\Controllers\CifrasEnLetrasController;
use Illuminate\Support\Facades\Log;
use App\Notifications\NotifyAdmin;
use FPDF;
use chillerlan\QRCode\{QRCode, QROptions};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SebastianBergmann\Environment\Console;
use SimpleXMLElement;
use SoapClient;
use TheSeer\Tokenizer\Exception;
use App\Helpers\CustomHelpers;
use App\Medida;
use App\MotivoAnulacion;
use App\PuntoVenta;
use App\Rol;
use Illuminate\Support\Facades\File;
use Phar;
use PharData;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;

use function Ramsey\Uuid\v1;

class VentaController extends Controller
{
    private $fecha_formato;

    public function __construct()
    {
        session_start();
    }

    public function index(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $usuario = \Auth::user();
        $idrol = $usuario->idrol;
        $idsucursal = $usuario->idsucursal;

        $codigoPuntoVenta = '';
        if (!empty($usuario->idpuntoventa)) {
            $puntoVenta = PuntoVenta::find($usuario->idpuntoventa);
            if ($puntoVenta) {
                $codigoPuntoVenta = $puntoVenta->codigoPuntoVenta;
            }
        }

        if ($idrol == 1) {
            // Mostrar todas las ventas de la sucursal del administrador
            if ($buscar == '') {
                $ventas = Factura::join('ventas', 'facturas.idventa', '=', 'ventas.id')
                    ->join('users', 'ventas.idusuario', '=', 'users.id')
                    ->select(
                        'facturas.*',
                        'facturas.correo as correo',
                        'ventas.tipo_comprobante as tipo_comprobante',
                        'ventas.serie_comprobante',
                        'ventas.num_comprobante as num_comprobante',
                        'ventas.fecha_hora as fecha_hora',
                        'ventas.impuesto as impuesto',
                        'ventas.total as total',
                        'ventas.idtipo_pago',
                        'ventas.estado as estado',
                        'ventas.cliente as razonSocial',
                        'ventas.documento as documentoid',
                        'users.usuario as usuario'
                    )
                    ->where('users.idsucursal', '=', $idsucursal)
                    ->orderBy('facturas.id', 'desc')->paginate(3);
            } else {
                $ventas = Factura::join('ventas', 'facturas.idventa', '=', 'ventas.id')
                    ->join('users', 'ventas.idusuario', '=', 'users.id')
                    ->select(
                        'facturas.*',
                        'facturas.correo as correo',
                        'ventas.tipo_comprobante as tipo_comprobante',
                        'ventas.serie_comprobante',
                        'ventas.num_comprobante as num_comprobante',
                        'ventas.fecha_hora as fecha_hora',
                        'ventas.impuesto as impuesto',
                        'ventas.total as total',
                        'ventas.idtipo_venta',
                        'ventas.estado as estado',
                        'ventas.cliente as razonSocial',
                        'ventas.documento as documentoid',
                        'users.usuario as usuario'
                    )
                    ->where('users.idsucursal', '=', $idsucursal)
                    ->where('ventas.' . $criterio, 'like', '%' . $buscar . '%')
                    ->orderBy('facturas.id', 'desc')->paginate(3);
            }
        } else if ($idrol == 2) {
            // Mostrar solo las ventas del usuario logueado
            if ($buscar == '') {
                $ventas = Factura::join('ventas', 'facturas.idventa', '=', 'ventas.id')
                    ->join('users', 'ventas.idusuario', '=', 'users.id')
                    ->select(
                        'facturas.*',
                        'facturas.correo as correo',
                        'ventas.tipo_comprobante as tipo_comprobante',
                        'ventas.serie_comprobante',
                        'ventas.num_comprobante as num_comprobante',
                        'ventas.fecha_hora as fecha_hora',
                        'ventas.impuesto as impuesto',
                        'ventas.total as total',
                        'ventas.idtipo_pago',
                        'ventas.estado as estado',
                        'ventas.cliente as razonSocial',
                        'ventas.documento as documentoid',
                        'users.usuario as usuario'
                    )
                    ->where('ventas.idusuario', '=', $usuario->id)
                    ->orderBy('facturas.id', 'desc')->paginate(3);
            } else {
                $ventas = Factura::join('ventas', 'facturas.idventa', '=', 'ventas.id')
                    ->join('users', 'ventas.idusuario', '=', 'users.id')
                    ->select(
                        'facturas.*',
                        'facturas.correo as correo',
                        'ventas.tipo_comprobante as tipo_comprobante',
                        'ventas.serie_comprobante',
                        'ventas.num_comprobante as num_comprobante',
                        'ventas.fecha_hora as fecha_hora',
                        'ventas.impuesto as impuesto',
                        'ventas.total as total',
                        'ventas.idtipo_venta',
                        'ventas.estado as estado',
                        'ventas.cliente as razonSocial',
                        'ventas.documento as documentoid',
                        'users.usuario as usuario'
                    )
                    ->where('ventas.idusuario', '=', $usuario->id)
                    ->where('ventas.' . $criterio, 'like', '%' . $buscar . '%')
                    ->orderBy('facturas.id', 'desc')->paginate(3);
            }
        }

        return [
            'pagination' => [
                'total' => $ventas->total(),
                'current_page' => $ventas->currentPage(),
                'per_page' => $ventas->perPage(),
                'last_page' => $ventas->lastPage(),
                'from' => $ventas->firstItem(),
                'to' => $ventas->lastItem(),
            ],
            'ventas' => $ventas,
            'usuario' => $usuario,
            'codigoPuntoVenta' => $codigoPuntoVenta
        ];
    }


    public function ventaOffline(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $usuario = \Auth::user();
        $codigoPuntoVenta = '';
        if (!empty($usuario->idpuntoventa)) {
            $puntoVenta = PuntoVenta::find($usuario->idpuntoventa);
            if ($puntoVenta) {
                $codigoPuntoVenta = $puntoVenta->codigoPuntoVenta;
            }
        }

        if ($buscar == '') {
            $facturasOffline = FacturaFueraLinea::join('ventas', 'factura_fuera_lineas.idventa', '=', 'ventas.id')
                ->join('users', 'ventas.idusuario', '=', 'users.id')
                ->select(
                    'factura_fuera_lineas.*',
                    'factura_fuera_lineas.correo as correo',
                    'ventas.tipo_comprobante as tipo_comprobante',
                    'ventas.serie_comprobante',
                    'ventas.num_comprobante as num_comprobante',
                    'ventas.fecha_hora as fecha_hora',
                    'ventas.impuesto as impuesto',
                    'ventas.total as total',
                    'ventas.idtipo_pago',
                    'ventas.estado as estado',
                    'ventas.cliente as razonSocial',
                    'ventas.documento as documentoid',
                    'users.usuario as usuario'
                )
                ->orderBy('factura_fuera_lineas.id', 'desc')->paginate(3);
        } else {
            $facturasOffline = FacturaFueraLinea::join('ventas', 'factura_fuera_lineas.idventa', '=', 'ventas.id')
                ->join('users', 'ventas.idusuario', '=', 'users.id')
                ->select(
                    'factura_fuera_lineas.*',
                    'factura_fuera_lineas.correo as correo',
                    'ventas.tipo_comprobante as tipo_comprobante',
                    'ventas.serie_comprobante',
                    'ventas.num_comprobante as num_comprobante',
                    'ventas.fecha_hora as fecha_hora',
                    'ventas.impuesto as impuesto',
                    'ventas.total as total',
                    'ventas.idtipo_pago',
                    'ventas.estado as estado',
                    'ventas.cliente as razonSocial',
                    'ventas.documento as documentoid',
                    'users.usuario as usuario'
                )
                ->where('factura_fuera_lineas.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderBy('factura_fuera_lineas.id', 'desc')->paginate(3);
        }

        return [
            'pagination' => [
                'total' => $facturasOffline->total(),
                'current_page' => $facturasOffline->currentPage(),
                'per_page' => $facturasOffline->perPage(),
                'last_page' => $facturasOffline->lastPage(),
                'from' => $facturasOffline->firstItem(),
                'to' => $facturasOffline->lastItem(),
            ],
            'facturasOffline' => $facturasOffline,
            'usuario' => $usuario,
            'codigoPuntoVenta' => $codigoPuntoVenta
        ];
    }
    
    public function indexBuscar(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $usuario = \Auth::user();
        $idRoles = $request->idRoles;
        $idAlmacen = $request->idAlmacen;
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;
        $idRoles = ($idRoles == 0) ? null : $idRoles;
        $idAlmacen = ($idAlmacen == 0) ? null : $idAlmacen;

        if ($buscar == '') {
            $ventas = Venta::join('personas', 'ventas.idcliente', '=', 'personas.id')
                ->join('users', 'ventas.idusuario', '=', 'users.id')
                ->join('detalle_ventas', 'ventas.id', '=', 'detalle_ventas.idventa')
                ->join('articulos', 'detalle_ventas.idarticulo', '=', 'articulos.id') 
                ->join('inventarios', 'articulos.id', '=', 'inventarios.idarticulo')
                ->select(
                    'ventas.id',
                    'ventas.tipo_comprobante',
                    'ventas.serie_comprobante',
                    'ventas.num_comprobante',
                    'ventas.fecha_hora',
                    'ventas.impuesto',
                    'ventas.total',
                    'ventas.estado',
                    'personas.nombre',
                    'users.usuario',
                    'users.idrol',
                    'detalle_ventas.idarticulo'
                )
                ->distinct()
                ->where(function($query) use ($idRoles) {
                    if ($idRoles !== null) {
                        $query->where('users.idrol', $idRoles);
                    }
                })
                ->where(function($query) use ($idAlmacen) {
                    if ($idAlmacen !== null) {
                        $query->where('inventarios.idalmacen', $idAlmacen);
                    }
                });

                // Filtrar por fechas solo si se proporcionan fechas distintas de la actual
                if ($fechaInicio !== now()->toDateString() || $fechaFin !== now()->addDay()->toDateString()) {
                    $ventas->whereBetween('ventas.fecha_hora', [$fechaInicio, $fechaFin]);
                }

            $ventas = $ventas->orderBy('ventas.id', 'desc')->paginate(3);
        } else {
            $ventas = Venta::join('personas', 'ventas.idcliente', '=', 'personas.id')
                ->join('users', 'ventas.idusuario', '=', 'users.id')
                ->join('detalle_ventas', 'ventas.id', '=', 'detalle_ventas.idventa')
                ->join('articulos', 'detalle_ventas.idarticulo', '=', 'articulos.id') 
                ->join('inventarios', 'articulos.id', '=', 'inventarios.idarticulo')
                ->select(
                    'ventas.id',
                    'ventas.tipo_comprobante',
                    'ventas.serie_comprobante',
                    'ventas.num_comprobante',
                    'ventas.fecha_hora',
                    'ventas.impuesto',
                    'ventas.total',
                    'ventas.estado',
                    'personas.nombre',
                    'users.usuario',
                    'users.idrol',
                    'detalle_ventas.idarticulo'
                )
                ->distinct()
                ->where(function($query) use ($idRoles) {
                    if ($idRoles !== null) {
                        $query->where('users.idrol', $idRoles);
                    }
                })
                ->where(function($query) use ($idAlmacen) {
                    if ($idAlmacen !== null) {
                        $query->where('inventarios.idalmacen', $idAlmacen);
                    }
                })
                ->where('personas.' . $criterio, 'like', '%' . $buscar . '%');

            // Filtrar por fechas
            if ($fechaInicio !== now()->toDateString() || $fechaFin !== now()->addDay()->toDateString()) {
                $ventas->whereBetween('ventas.fecha_hora', [$fechaInicio, $fechaFin]);
            }

            $ventas = $ventas->orderBy('ventas.id', 'desc')->paginate(3);
        }
        return [
            'pagination' => [
                'total' => $ventas->total(),
                'current_page' => $ventas->currentPage(),
                'per_page' => $ventas->perPage(),
                'last_page' => $ventas->lastPage(),
                'from' => $ventas->firstItem(),
                'to' => $ventas->lastItem(),
            ],
            'ventas' => $ventas,
            'usuario' => $usuario
        ];
    }

    public function obtenerUltimoComprobante(Request $request)
    {
        $ultimoComprobante = DB::table('ventas')
            ->select('num_comprobante')
            ->orderBy('num_comprobante', 'desc')
            ->limit(1)
            ->first();

        if ($ultimoComprobante) {
            $lastComprobante = $ultimoComprobante->num_comprobante;
        } else {
            $lastComprobante = 1;
        }

        return response()->json(['last_comprobante' => $lastComprobante]);
    }

    public function obtenerCabecera(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $id = $request->id;
        $venta = Venta::join('personas', 'ventas.idcliente', '=', 'personas.id')
            ->join('users', 'ventas.idusuario', '=', 'users.id')
            ->select(
                'ventas.id',
                'ventas.tipo_comprobante',
                'ventas.serie_comprobante',
                'ventas.num_comprobante',
                'ventas.fecha_hora',
                'ventas.impuesto',
                'ventas.total',
                'ventas.estado',
                'personas.nombre',
                'users.usuario'
            )
            ->where('ventas.id', '=', $id)
            ->orderBy('ventas.id', 'desc')->take(1)->get();

        return ['venta' => $venta];
    }

    
    public function obtenerDetalles(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $id = $request->id;
        $detalles = DetalleVenta::leftJoin('articulos', 'detalle_ventas.codigoComida', '=', 'articulos.codigo')
            ->leftJoin('menu', 'detalle_ventas.codigoComida', '=', 'menu.codigo')
            ->select(
                'detalle_ventas.cantidad',
                'detalle_ventas.precio',
                'detalle_ventas.descuento',
                'articulos.nombre as articulo_nombre',
                'menu.nombre as menu_nombre',
            )
            ->where('detalle_ventas.idventa', '=', $id)
            ->orderBy('detalle_ventas.id', 'desc')
            ->get();

        $detalles = $detalles->map(function ($detalle) {
            // Verificar si el código pertenece a artículos o menú y asignar los valores correspondientes
            if ($detalle->articulo_nombre) {
                $detalle->nombre = $detalle->articulo_nombre;
            } else {
                $detalle->nombre = $detalle->menu_nombre;
            }

            // Eliminar los campos no necesarios después de la asignación
            unset($detalle->articulo_nombre);
            unset($detalle->menu_nombre);

            return $detalle;
        });

        return ['detalles' => $detalles];
    }


    public function pdf(Request $request, $id)
    {
        $venta = Venta::join('personas', 'ventas.idcliente', '=', 'personas.id')
            ->join('users', 'ventas.idusuario', '=', 'users.id')
            ->select(
                'ventas.id',
                'ventas.tipo_comprobante',
                'ventas.serie_comprobante',
                'ventas.num_comprobante',
                'ventas.created_at',
                'ventas.impuesto',
                'ventas.total',
                'ventas.estado',
                'personas.nombre',
                'personas.tipo_documento',
                'personas.num_documento',
                'personas.direccion',
                'personas.email',
                'personas.telefono',
                'users.usuario'
            )
            ->where('ventas.id', '=', $id)
            ->orderBy('ventas.id', 'desc')->take(1)->get();

        $detalles = DetalleVenta::join('articulos', 'detalle_ventas.idarticulo', '=', 'articulos.id')
            ->select(
                'detalle_ventas.cantidad',
                'detalle_ventas.precio',
                'detalle_ventas.descuento',
                'articulos.nombre as articulo'
            )
            ->where('detalle_ventas.idventa', '=', $id)
            ->orderBy('detalle_ventas.id', 'desc')->get();

        $numventa = Venta::select('num_comprobante')->where('id', $id)->get();

        $pdf = \PDF::loadView('pdf.venta', ['venta' => $venta, 'detalles' => $detalles]);
        return $pdf->setPaper('a4', 'landscape')->download('venta-' . $numventa[0]->num_comprobante . '.pdf');

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
            $detalles = $request->data; // Array de detalles
            $idAlmacen = $request->idAlmacen;

            foreach ($detalles as $ep => $det) {
                $descu = $det['descuento'];
            }

            if ($descu > $valorMaximoDescuentoEmpresa->valorMaximoDescuento) {
                return [
                    'id' => -1,
                    'valorMaximo' => $valorMaximo
                ];
            } else {

                $ultimaCaja = Caja::where('idusuario', \Auth::user()->id)->latest()->first();
                //dd($ultimaCaja);

                if ($ultimaCaja) {
                    if ($ultimaCaja->estado == '1') {

                        foreach ($detalles as $det) {
                            $enMenu = Menu::where('codigo', $det['codigoComida'])->exists();
                            $enInventario = Inventario::join('articulos', 'inventarios.idarticulo', '=', 'articulos.id')
                                                    ->where('inventarios.idalmacen', $idAlmacen)
                                                    ->where('articulos.codigo', $det['codigoComida'])
                                                    ->exists();

                            if (!$enMenu && !$enInventario) {
                                return [
                                    'id' => -1,
                                    'error' => 'El artículo ' . $det['codigoComida'] . ' no está en el inventario ni en el menú'
                                ];
                            }

                            if ($enInventario) {
                                $stockArticulo = Inventario::join('articulos', 'inventarios.idarticulo', '=', 'articulos.id')
                                                        ->where('inventarios.idalmacen', $idAlmacen)
                                                        ->where('articulos.codigo', $det['codigoComida'])
                                                        ->first();

                                if ($stockArticulo->saldo_stock < $det['cantidad']) {
                                    return [
                                        'id' => -1,
                                        'error' => 'Stock insuficiente para el artículo ' . $det['codigoComida']
                                    ];
                                }
                            }
                        }

                        $venta = new Venta();
                        $venta->idcliente = $request->idcliente;
                        $venta->idusuario = \Auth::user()->id;
                        $venta->idtipo_pago = $request->idtipo_pago;
                        $venta->idsucursal = $request->idsucursal;
                        $venta->cliente = $request->cliente;
                        $venta->documento = $request->documento;
                        $venta->tipo_comprobante = $request->tipo_comprobante;
                        $venta->serie_comprobante = $request->serie_comprobante;
                        $venta->num_comprobante = $request->num_comprobante;
                        $venta->fecha_hora = now()->setTimezone('America/La_Paz');
                        $venta->impuesto = $request->impuesto;
                        $venta->total = $request->total;
                        $venta->tipoEntrega = $request->tipoEntrega;
                        $venta->observacion = $request->observacion;
                        $venta->estado = 'Registrado';
                        $venta->idcaja = $ultimaCaja->id;
                        Log::info('DATOS REGISTRO ARTICULO VENTA:', [
                            'idcliente' => $request->idcliente,
                            'idusuario' => $request->id,
                            'idtipo_pago' => $request->idtipo_pago,
                            'tipo_comprobante' => $request->tipo_comprobante,
                            'serie_comprobante' => $request->serie_comprobante,
                            'num_comprobante' => $request->num_comprobante,
                            'fecha_hora' => $request->fecha_hora,
                            'impuesto' => $request->impuesto,
                            'total' => $request->total,
                            'idcaja' => $request->id,
                        ]);
                        $venta->save();

                        $ultimaCaja->ventasContado = ($request->total) + ($ultimaCaja->ventasContado);
                        $ultimaCaja->saldoCaja += $request->total;
                        $ultimaCaja->save();

                        Log::info('venta', [
                            'data' => $ultimaCaja,
                            'idalmacen' => $idAlmacen,
                        ]);

                        foreach ($detalles as $ep => $det) {
                            $enMenu = Menu::where('codigo', $det['codigoComida'])->exists();
                            $enInventario = Inventario::join('articulos', 'inventarios.idarticulo', '=', 'articulos.id')
                                                    ->where('inventarios.idalmacen', $idAlmacen)
                                                    ->where('articulos.codigo', $det['codigoComida'])
                                                    ->exists();

                            if ($enMenu || $enInventario) {
                                $detalle = new DetalleVenta();
                                $detalle->idventa = $venta->id;
                                $detalle->codigoComida = $det['codigoComida'];
                                $detalle->cantidad = $det['cantidad'];
                                $detalle->precio = $det['precio'];
                                $detalle->descuento = $det['descuento'];
                                $detalle->save();

                                $_SESSION['sidAlmacen'] = $idAlmacen;
                                $_SESSION['sdetalle'] = $detalles;

                                if ($enInventario) {
                                    $disminuirStock = Inventario::join('articulos', 'inventarios.idarticulo', '=', 'articulos.id')
                                                                ->where('inventarios.idalmacen', $idAlmacen)
                                                                ->where('articulos.codigo', $det['codigoComida'])
                                                                ->firstOrFail();
                                    $disminuirStock->saldo_stock -= $det['cantidad'];
                                    $disminuirStock->save();
                                }
                            }
                        }

                        $fechaActual = date('Y-m-d');
                        $numVentas = DB::table('ventas')->whereDate('created_at', $fechaActual)->count();
                        $numIngresos = DB::table('ingresos')->whereDate('created_at', $fechaActual)->count();

                        $arreglosDatos = [
                            'ventas' => [
                                'numero' => $numVentas,
                                'msj' => 'Ventas'
                            ],
                            'ingresos' => [
                                'numero' => $numIngresos,
                                'msj' => 'Ingresos'
                            ]
                        ];
                        $allUsers = User::all();

                        foreach ($allUsers as $notificar) {
                            User::findOrFail($notificar->id)->notify(new NotifyAdmin($arreglosDatos));
                        }
                        DB::commit();
                        return [
                            'id' => $venta->id
                        ];
                    } else {
                        return [
                            'id' => -1,
                            'caja_validado' => 'Debe tener una caja abierta'
                        ];
                    }
                } else {
                    return [
                        'id' => -1,
                        'caja_validado' => 'Debe crear primero una apertura de caja'
                    ];
                }

            }
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'id' => -1,
                'error' => 'Ha ocurrido un error al registrar la venta'
            ];
        }
    }


    public function revertirInventario($id)
    {
        $ultimaCaja = Caja::latest()->first();

        $idAlmacen = $_SESSION['sidAlmacen'];
        $detalles = $_SESSION['sdetalle'];
    
        foreach ($detalles as $ep => $det) {
            // Verificar si el código de comida está en la tabla Menu
            $enMenu = Menu::where('codigo', $det['codigoComida'])->exists();
            
            // Verificar si el código de comida está en la tabla Inventario
            $enInventario = Inventario::join('articulos', 'inventarios.idarticulo', '=', 'articulos.id')
                                       ->where('inventarios.idalmacen', $idAlmacen)
                                       ->where('articulos.codigo', $det['codigoComida'])
                                       ->exists();
        
            // Si el código de comida está en alguna de las dos tablas, guardar el detalle de venta
            if ($enMenu || $enInventario) {
                $detalle = new DetalleVenta();
                $detalle->idventa = $id;
                $detalle->codigoComida = $det['codigoComida'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $det['precio'];
                $detalle->descuento = $det['descuento'];
                $detalle->save();

        
                // Si el código de comida está en la tabla Inventario, disminuir el stock
                if ($enInventario) {
                    $disminuirStock = Inventario::join('articulos', 'inventarios.idarticulo', '=', 'articulos.id')
                                                 ->where('inventarios.idalmacen', $idAlmacen)
                                                 ->where('articulos.codigo', $det['codigoComida'])
                                                 ->firstOrFail();
                    $disminuirStock->saldo_stock += $det['cantidad'];
                    $disminuirStock->save();
                }
            }
        }

        try {
            $venta = Venta::findOrFail($id);
            $ultimaCaja->saldoCaja -= $venta->total;
            $ultimaCaja->ventasContado -= $venta->total;
            $ultimaCaja->save(); 
            $venta->delete();
            return response()->json('Venta eliminada correctamente', 200);
        } catch (\Exception $e) {
            return response()->json('Error al eliminar la venta: ' . $e->getMessage(), 500);
        }
    }
    
    public function eliminarVenta($id)
    {
        try {
            $venta = Venta::findOrFail($id);
            $venta->delete();
            $this->revertirInventario($id);
            return response()->json('Venta eliminada correctamente', 200);
        } catch (\Exception $e) {
            return response()->json('Error al eliminar la venta: ' . $e->getMessage(), 500);
        }
    }

    public function eliminarVentaFalloSiat($id)
    {
        $ultimaCaja = Caja::latest()->first();

        try {
            DB::beginTransaction();
            // Eliminar todas las facturas relacionadas con la venta
            $facturas = Factura::where('idventa', $id)->get();
            foreach ($facturas as $factura) {
                $factura->delete();
            }

            // Eliminar todos los detalles de la venta
            $detallesVenta = DetalleVenta::where('idventa', $id)->get();
            foreach ($detallesVenta as $detalle) {
                $detalle->delete();
            }

            // Eliminar la venta
            $venta = Venta::findOrFail($id);
            $ultimaCaja->saldoCaja -= $venta->total;
            $ultimaCaja->ventasContado -= $venta->total;
            $ultimaCaja->save();  
            $venta->delete();

            DB::commit();
            return response()->json('Venta eliminada correctamente', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json('Error al eliminar la venta: ' . $e->getMessage(), 500);
        }
    }


    public function desactivar(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');
        $venta = Venta::findOrFail($request->id);
        $venta->estado = 'Anulado';
        $venta->save();
    }

    public function verificarComunicacion()
    {
        require "SiatController.php";
        $siat = new SiatController();
        $res = $siat->verificarComunicacion();
        if ($res->RespuestaComunicacion->transaccion == true) {
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
        } else {
            $msg = "Falló la comunicación";
            echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        }
    }

    public function cuis()
    {
        $user = Auth::user();

        $codigoPuntoVenta = '';
        if (!empty($user->idpuntoventa)) {
            $puntoVenta = PuntoVenta::find($user->idpuntoventa);
            if ($puntoVenta) {
                $codigoPuntoVenta = $puntoVenta->codigoPuntoVenta;
            }
        }
        //$puntoVenta = $user->idpuntoventa;
        $puntoVenta = $codigoPuntoVenta;
        $sucursal = $user->sucursal;
        $empresa = $sucursal->empresas;
        $codnit = $empresa->nit;
        $codSucursal = $sucursal->codigoSucursal;


        require "SiatController.php";
        $siat = new SiatController();
        $res = $siat->cuis($puntoVenta, $codSucursal, $codnit);
        $res->RespuestaCuis->codigo;
        $_SESSION['scuis'] = $res->RespuestaCuis->codigo;
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    public function nuevoCufd()
    {
        $user = Auth::user();
        $codigoPuntoVenta = '';
        if (!empty($user->idpuntoventa)) {
            $puntoVenta = PuntoVenta::find($user->idpuntoventa);
            if ($puntoVenta) {
                $codigoPuntoVenta = $puntoVenta->codigoPuntoVenta;
            }
        }
        //$puntoVenta = $user->idpuntoventa;
        $puntoVenta = $codigoPuntoVenta;
        $sucursal = $user->sucursal;
        $empresa = $sucursal->empresas;
        $codnit = $empresa->nit;
        $codSucursal = $sucursal->codigoSucursal;

        require "SiatController.php";
        $siat = new SiatController();
        $res = $siat->cufd($puntoVenta, $codSucursal, $codnit);
        //dd($res);
        
        if ($res->RespuestaCufd->transaccion == true) {
            $cufd = $res->RespuestaCufd->codigo;
            $codigoControl = $res->RespuestaCufd->codigoControl;
            $direccion = $res->RespuestaCufd->direccion;
            $fechaVigencia = $res->RespuestaCufd->fechaVigencia;
            
            $_SESSION['scufd'] = $cufd;
            $_SESSION['scodigoControl'] = $codigoControl;
            $_SESSION['sdireccion'] = $direccion;
            $_SESSION['sfechaVigenciaCufd'] = $fechaVigencia;

            $res = $res->RespuestaCufd;

            /*$res['transaccion'] = $res->RespuestaCufd->transaccion;
            $res['codigo'] = $_SESSION['scufd'];
            $res['fechaVigencia'] = $_SESSION['sfechaVigenciaCufd'];
            $res['direccion'] = $_SESSION['sdireccion'];
            $res['codigoControl'] = $_SESSION['scodigoControl'];*/
        } else {
            $res = false;
        }

        echo json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    public function cufd()
    {
        $user = Auth::user();
        $codigoPuntoVenta = '';
        if (!empty($user->idpuntoventa)) {
            $puntoVenta = PuntoVenta::find($user->idpuntoventa);
            if ($puntoVenta) {
                $codigoPuntoVenta = $puntoVenta->codigoPuntoVenta;
            }
        }
        //$puntoVenta = $user->idpuntoventa;
        $puntoVenta = $codigoPuntoVenta;
        $sucursal = $user->sucursal;
        $empresa = $sucursal->empresas;
        $codnit = $empresa->nit;
        $codSucursal = $sucursal->codigoSucursal;

        if (!isset ($_SESSION['scufd'])) {
            require "SiatController.php";
            $siat = new SiatController();
            $res = $siat->cufd($puntoVenta, $codSucursal, $codnit);
            if ($res->RespuestaCufd->transaccion == true) {
                $cufd = $res->RespuestaCufd->codigo;
                $codigoControl = $res->RespuestaCufd->codigoControl;
                $direccion = $res->RespuestaCufd->direccion;
                $fechaVigencia = $res->RespuestaCufd->fechaVigencia;
                $_SESSION['scufd'] = $cufd;
                $_SESSION['scodigoControl'] = $codigoControl;
                $_SESSION['sdireccion'] = $direccion;
                $_SESSION['sfechaVigenciaCufd'] = $fechaVigencia;
            } else {
                $res = false;
            }
        } else {
            $fechaVigencia = substr($_SESSION['sfechaVigenciaCufd'], 0, 16);
            $fechaVigencia = str_replace("T", " ", $fechaVigencia);
            if ($fechaVigencia < date('Y-m-d H:i')) {
                require "SiatController.php";
                $siat = new SiatController();
                $res = $siat->cufd($puntoVenta, $codSucursal);
                if ($res->RespuestaCufd->transaccion == true) {
                    $cufd = $res->RespuestaCufd->codigo;
                    $codigoControl = $res->RespuestaCufd->codigoControl;
                    $direccion = $res->RespuestaCufd->direccion;
                    $fechaVigencia = $res->RespuestaCufd->fechaVigencia;
                    $_SESSION['scufd'] = $cufd;
                    $_SESSION['scodigoControl'] = $codigoControl;
                    $_SESSION['sdireccion'] = $direccion;
                    $_SESSION['sfechaVigenciaCufd'] = $fechaVigencia;
                } else {
                    $res = false;
                }
            } else {
                $res['transaccion'] = true;
                $res['codigo'] = $_SESSION['scufd'];
                $res['fechaVigencia'] = $_SESSION['sfechaVigenciaCufd'];
                $res['direccion'] = $_SESSION['sdireccion'];
                $res['codigoControl'] = $_SESSION['scodigoControl'];
            }
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    public function sincronizarActividades()
    {
        $user = Auth::user();
        $codigoPuntoVenta = '';
        if (!empty($user->idpuntoventa)) {
            $puntoVenta = PuntoVenta::find($user->idpuntoventa);
            if ($puntoVenta) {
                $codigoPuntoVenta = $puntoVenta->codigoPuntoVenta;
            }
        }
        //$puntoVenta = $user->idpuntoventa;
        $puntoVenta = $codigoPuntoVenta;
        $sucursal = $user->sucursal;
        $empresa = $sucursal->empresas;
        $codnit = $empresa->nit;
        $codSucursal = $sucursal->codigoSucursal;

        require "SiatController.php";
        $siat = new SiatController();
        $res = $siat->sincronizarActividades($puntoVenta, $codSucursal, $codnit);
        //$mensaje = $res->RespuestaListaActividades;
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    public function sincronizarParametricaTiposFactura()
    {
        $user = Auth::user();
        $codigoPuntoVenta = '';
        if (!empty($user->idpuntoventa)) {
            $puntoVenta = PuntoVenta::find($user->idpuntoventa);
            if ($puntoVenta) {
                $codigoPuntoVenta = $puntoVenta->codigoPuntoVenta;
            }
        }
        //$puntoVenta = $user->idpuntoventa;
        $puntoVenta = $codigoPuntoVenta;
        $sucursal = $user->sucursal;
        $empresa = $sucursal->empresas;
        $codnit = $empresa->nit;
        $codSucursal = $sucursal->codigoSucursal;

        require "SiatController.php";
        $siat = new SiatController();
        $res = $siat->sincronizarParametricaTiposFactura($puntoVenta, $codSucursal, $codnit);
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    public function sincronizarListaProductosServicios()
    {
        $user = Auth::user();
        $codigoPuntoVenta = '';
        if (!empty($user->idpuntoventa)) {
            $puntoVenta = PuntoVenta::find($user->idpuntoventa);
            if ($puntoVenta) {
                $codigoPuntoVenta = $puntoVenta->codigoPuntoVenta;
            }
        }
        //$puntoVenta = $user->idpuntoventa;
        $puntoVenta = $codigoPuntoVenta;
        $sucursal = $user->sucursal;
        $empresa = $sucursal->empresas;
        $codnit = $empresa->nit;
        $codSucursal = $sucursal->codigoSucursal;

        require "SiatController.php";
        $siat = new SiatController();
        $res = $siat->sincronizarListaProductosServicios($puntoVenta, $codSucursal, $codnit);
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    public function sincronizarParametricaMotivoAnulacion()
    {
        $user = Auth::user();
        $codigoPuntoVenta = '';
        if (!empty($user->idpuntoventa)) {
            $puntoVenta = PuntoVenta::find($user->idpuntoventa);
            if ($puntoVenta) {
                $codigoPuntoVenta = $puntoVenta->codigoPuntoVenta;
            }
        }
        //$puntoVenta = $user->idpuntoventa;
        $puntoVenta = $codigoPuntoVenta;
        $sucursal = $user->sucursal;
        $empresa = $sucursal->empresas;
        $codnit = $empresa->nit;
        $codSucursal = $sucursal->codigoSucursal;

        require "SiatController.php";
        $siat = new SiatController();
        $res = $siat->sincronizarParametricaMotivoAnulacion($puntoVenta, $codSucursal, $codnit);
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    public function sincronizarParametricaEventosSignificativos()
    {
        $user = Auth::user();
        $codigoPuntoVenta = '';
        if (!empty($user->idpuntoventa)) {
            $puntoVenta = PuntoVenta::find($user->idpuntoventa);
            if ($puntoVenta) {
                $codigoPuntoVenta = $puntoVenta->codigoPuntoVenta;
            }
        }
        //$puntoVenta = $user->idpuntoventa;
        $puntoVenta = $codigoPuntoVenta;
        $sucursal = $user->sucursal;
        $empresa = $sucursal->empresas;
        $codnit = $empresa->nit;
        $codSucursal = $sucursal->codigoSucursal;

        require "SiatController.php";
        $siat = new SiatController();
        $res = $siat->sincronizarParametricaEventosSignificativos($puntoVenta, $codSucursal, $codnit);
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    public function sincronizarListaLeyendasFactura()
    {
        $user = Auth::user();
        $codigoPuntoVenta = '';
        if (!empty($user->idpuntoventa)) {
            $puntoVenta = PuntoVenta::find($user->idpuntoventa);
            if ($puntoVenta) {
                $codigoPuntoVenta = $puntoVenta->codigoPuntoVenta;
            }
        }
        //$puntoVenta = $user->idpuntoventa;
        $puntoVenta = $codigoPuntoVenta;
        $sucursal = $user->sucursal;
        $empresa = $sucursal->empresas;
        $codnit = $empresa->nit;
        $codSucursal = $sucursal->codigoSucursal;

        require "SiatController.php";
        $siat = new SiatController();
        $res = $siat->sincronizarListaLeyendasFactura($puntoVenta, $codSucursal, $codnit);
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    public function sincronizarParametricaUnidadMedida()
    {
        $user = Auth::user();
        $codigoPuntoVenta = '';
        if (!empty($user->idpuntoventa)) {
            $puntoVenta = PuntoVenta::find($user->idpuntoventa);
            if ($puntoVenta) {
                $codigoPuntoVenta = $puntoVenta->codigoPuntoVenta;
            }
        }
        //$puntoVenta = $user->idpuntoventa;
        $puntoVenta = $codigoPuntoVenta;
        $sucursal = $user->sucursal;
        $empresa = $sucursal->empresas;
        $codnit = $empresa->nit;
        $codSucursal = $sucursal->codigoSucursal;

        require "SiatController.php";
        $siat = new SiatController();
        $res = $siat->sincronizarParametricaUnidadMedida($puntoVenta, $codSucursal, $codnit);
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    public function verificarNit($numeroDocumento)
    {
        $user = Auth::user();
        $sucursal = $user->sucursal;
        $empresa = $sucursal->empresas;
        $codnit = $empresa->nit;
        $codSucursal = $sucursal->codigoSucursal;

        require "SiatController.php";
        $siat = new SiatController();
        $res = $siat->verificarNit($codSucursal, $numeroDocumento, $codnit);
        if ($res->RespuestaVerificarNit->transaccion === true) {
            $mensaje = $res->RespuestaVerificarNit->mensajesList->descripcion;
        } else if ($res->RespuestaVerificarNit->transaccion === false) {
            $mensaje = $res->RespuestaVerificarNit->transaccion;
        }

        echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
    }

    public function verificacionEstadoFactura($cuf)
    {
        $user = Auth::user();
        $codigoPuntoVenta = '';
        if (!empty($user->idpuntoventa)) {
            $puntoVenta = PuntoVenta::find($user->idpuntoventa);
            if ($puntoVenta) {
                $codigoPuntoVenta = $puntoVenta->codigoPuntoVenta;
            }
        }
        //$puntoVenta = $user->idpuntoventa;
        $puntoVenta = $codigoPuntoVenta;
        $sucursal = $user->sucursal;
        $empresa = $sucursal->empresas;
        $codnit = $empresa->nit;
        $codSucursal = $sucursal->codigoSucursal;

        require "SiatController.php";
        $siat = new SiatController();
        $res = $siat->verificacionEstadoFactura($cuf, $puntoVenta, $codSucursal, $codnit);
        $mensaje = $res->RespuestaServicioFacturacion->codigoDescripcion;

        echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
        //var_dump($res);
    }

    public function emitirFactura(Request $request){    

        $user = Auth::user();
        $codigoPuntoVenta = '';
        if (!empty($user->idpuntoventa)) {
            $puntoVenta = PuntoVenta::find($user->idpuntoventa);
            if ($puntoVenta) {
                $codigoPuntoVenta = $puntoVenta->codigoPuntoVenta;
            }
        }

        //$puntoVenta = $user->idpuntoventa;
        $puntoVenta = $codigoPuntoVenta;
        $sucursal = $user->sucursal;
        $empresa = $sucursal->empresas;
        $codnit = $empresa->nit;
        $codSucursal = $sucursal->codigoSucursal;

        $datos = $request->input('factura');
        //$id_cliente = $request->input('id_cliente');
        $idventa = $request->input('idventa');
        $correo = $request->input('correo');
        $cufd = $request->input('cufd');
            
        $valores = $datos['factura'][0]['cabecera'];
        $nitEmisor = str_pad($valores['nitEmisor'], 13, "0", STR_PAD_LEFT);
            
        $fechaEmision = $valores['fechaEmision'];
        $fecha_formato = str_replace("T", "", $fechaEmision);
        $fecha_formato = str_replace("-", "", $fecha_formato);
        $fecha_formato = str_replace(":", "", $fecha_formato);
        $fecha_formato = str_replace(".", "", $fecha_formato);
        $sucursal = str_pad($codSucursal, 4, "0", STR_PAD_LEFT);
        $modalidad = 2;
        $tipoEmision = 1;
        $tipoFactura = 1;
        $tipoDocSector = str_pad(1, 2, "0", STR_PAD_LEFT);
        $numeroFactura = str_pad($valores['numeroFactura'], 10, "0", STR_PAD_LEFT);
        $puntoVentaCuf = str_pad($puntoVenta, 4, "0", STR_PAD_LEFT);
        $codigoControl = $_SESSION['scodigoControl'];
        $cadena = $nitEmisor . $fecha_formato . $sucursal . $modalidad . $tipoEmision . $tipoFactura . $tipoDocSector . $numeroFactura . $puntoVentaCuf;
        $numDig = 1;
        $limMult = 9;
        $x10 = false;
        $mod11 = CustomHelpers::calculaDigitoMod11($cadena, $numDig, $limMult, $x10);
        $cadena2 = $cadena . $mod11;
        
        $pString = $cadena2;
        $bas16 = CustomHelpers::base16($pString);
        
        $cuf = strtoupper($bas16) . $codigoControl;
            
        $datos['factura'][0]['cabecera']['cuf'] = $cuf;
            
        $temporal = $datos['factura'];
        //dd($temporal);
        $xml_temporal = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><facturaComputarizadaCompraVenta xsi:noNamespaceSchemaLocation=\"facturaComputarizadaCompraVenta.xsd\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"></facturaComputarizadaCompraVenta>");

        $this->formato_xml($temporal, $xml_temporal);
        $xml_temporal->asXML(public_path("docs/facturaxml.xml"));
        $gzdata = gzencode(file_get_contents(public_path("docs/facturaxml.xml")), 9);
        $fp = fopen(public_path("docs/facturaxml.xml.zip"), "w");
        fwrite($fp, $gzdata);
        fclose($fp);
        $archivo = $gzdata;
        $hashArchivo = hash("sha256", file_get_contents(public_path("docs/facturaxml.xml")));
            
        $numeroFactura = $valores['numeroFactura'];
        $codigoMetodoPago = $valores['codigoMetodoPago'];
        $montoTotal = $valores['montoTotal'];
        $montoTotalSujetoIva = $valores['montoTotalSujetoIva'];
        $descuentoAdicional = $valores['descuentoAdicional'];
        $productos = file_get_contents(public_path("docs/facturaxml.xml"));

        require "SiatController.php";
        $siat = new SiatController();
        $resFactura = $siat->recepcionFactura($archivo, $fechaEmision, $hashArchivo, $puntoVenta, $codSucursal, $codnit);
        //dd($resFactura);
        if ($resFactura->RespuestaServicioFacturacion->codigoDescripcion === "VALIDADA"){
            $mensaje = $resFactura->RespuestaServicioFacturacion->codigoDescripcion;
            $this->insertarFactura($request, $idventa, $numeroFactura, $cuf, $cufd, $codigoControl, $correo, $fechaEmision, $codigoMetodoPago, $montoTotal, $montoTotalSujetoIva, $descuentoAdicional, $productos);
        }else if($resFactura->RespuestaServicioFacturacion->codigoDescripcion === "RECHAZADA"){
            $mensaje = $resFactura->RespuestaServicioFacturacion->mensajesList->descripcion;
        }
        
        echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
            
    }

    public function paqueteFactura(Request $request)
    {
        $user = Auth::user();
        $codigoPuntoVenta = '';
        if (!empty($user->idpuntoventa)) {
            $puntoVenta = PuntoVenta::find($user->idpuntoventa);
            if ($puntoVenta) {
                $codigoPuntoVenta = $puntoVenta->codigoPuntoVenta;
            }
        }
        //$puntoVenta = $user->idpuntoventa;
        $puntoVenta = $codigoPuntoVenta;
        $sucursal = $user->sucursal;
        $codSucursal = $sucursal->codigoSucursal;

        $datos = $request->input('factura');
        $id_cliente = $request->input('id_cliente');
        $cafc = $request->input('cafc');
        $idventa = $request->input('idventa');
        $correo = $request->input('correo');
        $cufd = $request->input('cufd');
        $codigoControl = $request->input('codigoControl');
        $_SESSION['scafc'] = $cafc;

        $valores = $datos['factura'][0]['cabecera'];
        $nitEmisor = str_pad($valores['nitEmisor'], 13, "0", STR_PAD_LEFT);

        $fechaEmision = $valores['fechaEmision'];
        $fecha_formato = str_replace("T", "", $fechaEmision);
        $fecha_formato = str_replace("-", "", $fecha_formato);
        $fecha_formato = str_replace(":", "", $fecha_formato);
        $fecha_formato = str_replace(".", "", $fecha_formato);
        $sucursal = str_pad($codSucursal, 4, "0", STR_PAD_LEFT);
        $modalidad = 2;
        $tipoEmision = 2;
        $tipoFactura = 1;
        $tipoDocSector = str_pad(1, 2, "0", STR_PAD_LEFT);
        $numeroFactura = str_pad($valores['numeroFactura'], 10, "0", STR_PAD_LEFT);
        $puntoVentaCuf = str_pad($puntoVenta, 4, "0", STR_PAD_LEFT);
        //$codigoControl = $_SESSION['scodigoControl'];
        $cadena = $nitEmisor . $fecha_formato . $sucursal . $modalidad . $tipoEmision . $tipoFactura . $tipoDocSector . $numeroFactura . $puntoVentaCuf;
        $numDig = 1;
        $limMult = 9;
        $x10 = false;
        $mod11 = CustomHelpers::calculaDigitoMod11($cadena, $numDig, $limMult, $x10);
        $cadena2 = $cadena . $mod11;

        $pString = $cadena2;
        $bas16 = CustomHelpers::base16($pString);

        $cuf = strtoupper($bas16) . $codigoControl;

        $datos['factura'][0]['cabecera']['cuf'] = $cuf;


        // Crear una carpeta temporal
        $carpetaTemporal = public_path("docs/temporal/");
        if (!file_exists($carpetaTemporal)) {
            mkdir($carpetaTemporal, 0777, true);
            chmod($carpetaTemporal, 0777);
        }

        // Guardar el archivo XML en la carpeta temporal
        $temporal = $datos['factura'];
        //dd($temporal);
        $xml_temporal = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><facturaComputarizadaCompraVenta xsi:noNamespaceSchemaLocation=\"facturaComputarizadaCompraVenta.xsd\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"></facturaComputarizadaCompraVenta>");
        $this->formato_xml($temporal, $xml_temporal);
        $nombreArchivo = "facturaxml" . $fecha_formato . ".xml";
        $xml_temporal->asXML(public_path("docs/temporal/" . $nombreArchivo));

        $numeroFactura = $valores['numeroFactura'];
        $codigoMetodoPago = $valores['codigoMetodoPago'];
        $montoTotal = $valores['montoTotal'];
        $montoTotalSujetoIva = $valores['montoTotalSujetoIva'];
        $descuentoAdicional = $valores['descuentoAdicional'];
        $productos = file_get_contents(public_path("docs/temporal/" . $nombreArchivo));

        $data = $this->insertarFacturaOffline($request, $idventa, $numeroFactura, $cuf, $cufd, $codigoControl, $correo, $fechaEmision, $codigoMetodoPago, $montoTotal, $montoTotalSujetoIva, $descuentoAdicional, $productos);
        if ($data === true) {
            return response()->json(['message' => 'Factura registrada correctamente']);
        } else {
            return response()->json(['message' => 'Error al registrar la factura'], 500); 
        }
    }


    public function enviarPaquete(Request $request)
    {
        $user = Auth::user();
        $codigoPuntoVenta = '';
        if (!empty($user->idpuntoventa)) {
            $puntoVenta = PuntoVenta::find($user->idpuntoventa);
            if ($puntoVenta) {
                $codigoPuntoVenta = $puntoVenta->codigoPuntoVenta;
            }
        }
        //$puntoVenta = $user->idpuntoventa;
        $puntoVenta = $codigoPuntoVenta;
        $sucursal = $user->sucursal;
        $empresa = $sucursal->empresas;
        $codnit = $empresa->nit;
        $codSucursal = $sucursal->codigoSucursal;
        // Ruta al directorio que deseas comprimir en el archivo TAR
        $carpetaFuente = public_path("docs/temporal");
        // Nombre del archivo TAR resultante
        $nombreArchivoTAR = 'docs/temporal.tar';

        try {
            // Obtener la lista de archivos en el directorio
            $archivosEnDirectorio = scandir($carpetaFuente);

            $archivos = array_diff($archivosEnDirectorio, array('.', '..'));

            // Obtener el número de archivos en la carpeta
            $numeroFacturas = count($archivos);

            // Verificar si la cantidad de archivos excede 500
            if ($numeroFacturas > 500) {
                // Si supera el límite, muestra un mensaje de error
                echo 'La cantidad de archivos excede el límite de 500.';
                return;
            }

            // Crear un objeto PharData para el archivo TAR
            $tar = new PharData($nombreArchivoTAR);

            // Agregar el contenido del directorio al archivo TAR
            $tar->buildFromDirectory($carpetaFuente);

            // Comprimir el archivo TAR utilizando Gzip
            $gzdata = gzencode(file_get_contents(public_path($nombreArchivoTAR)), 9);
            $fp = fopen(public_path("docs/temporal.tar.zip"), "w");
            fwrite($fp, $gzdata);
            fclose($fp);
            $archivo = $gzdata;
            $hashArchivo = hash("sha256", file_get_contents(public_path("docs/temporal.tar.zip")));
            $nombreArchivoZIP = public_path("docs/temporal.tar.zip");

            require "SiatController.php";
            $siat = new SiatController();
            $res = $siat->recepcionPaqueteFactura($archivo, $request->fechaEmision, $hashArchivo, $numeroFacturas, $puntoVenta, $codSucursal, $codnit);
            // Verificar el valor de transacción y asignar el mensaje correspondiente
            //dd($res);
            if ($res->RespuestaServicioFacturacion->codigoDescripcion === "PENDIENTE") {
                $mensaje = $res->RespuestaServicioFacturacion->codigoDescripcion;
                $_SESSION['scodigorecepcion'] = $res->RespuestaServicioFacturacion->codigoRecepcion;

                // Eliminar el archivo TAR si existe
                if (file_exists($nombreArchivoTAR)) {
                    unlink($nombreArchivoTAR);
                }
                // Eliminar el archivo ZIP si existe
                if (file_exists($nombreArchivoZIP)) {
                    unlink($nombreArchivoZIP);
                }
                // Eliminar la carpeta temporal si existe y está vacía
                if (is_dir($carpetaFuente)) {
                    $this->eliminarDirectorio($carpetaFuente);
                }

            } else if ($res->RespuestaServicioFacturacion->codigoDescripcion === "RECHAZADA") {
                $mensaje = $res->RespuestaServicioFacturacion->mensajesList->descripcion;

                /*if (is_array($mensajes)) {
                    $descripciones = array_map(function ($mensaje) {
                        return $mensaje->descripcion;
                    }, $mensajes);
                    $mensaje = $descripciones;
                }*/
            }
            echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
            //var_dump($res);

        } catch (Exception $e) {
            echo "Error al crear el archivo TAR comprimido o al enviarlo al servicio: " . $e->getMessage();
        }
    }

    public function validacionRecepcionPaqueteFactura()
    {
        $user = Auth::user();
        $codigoPuntoVenta = '';
        if (!empty($user->idpuntoventa)) {
            $puntoVenta = PuntoVenta::find($user->idpuntoventa);
            if ($puntoVenta) {
                $codigoPuntoVenta = $puntoVenta->codigoPuntoVenta;
            }
        }
        //$puntoVenta = $user->idpuntoventa;
        $puntoVenta = $codigoPuntoVenta;
        $sucursal = $user->sucursal;
        $codSucursal = $sucursal->codigoSucursal;
        $empresa = $sucursal->empresas;
        $codnit = $empresa->nit;

        require "SiatController.php";
        $siat = new SiatController();
        $res = $siat->validacionRecepcionPaqueteFactura($puntoVenta, $codSucursal, $codnit);
        //dd($res);
        if ($res->RespuestaServicioFacturacion->codigoDescripcion === "VALIDADA") {
            $mensaje = $res->RespuestaServicioFacturacion->codigoDescripcion;
        } else if ($res->RespuestaServicioFacturacion->codigoDescripcion === "OBSERVADA") {
            $mensajes = $res->RespuestaServicioFacturacion->mensajesList;

            if (is_array($mensajes)) {
                $descripciones = array_map(function ($mensaje) {
                    return $mensaje->descripcion;
                }, $mensajes);
                $mensaje = $descripciones;
            }
        }
        echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
        //var_dump($res);
    }


    public function eliminarDirectorio($directorio)
    {
        if (!is_dir($directorio)) {
            return;
        }

        $archivos = glob($directorio . '/*');
        foreach ($archivos as $archivo) {
            is_dir($archivo) ? $this->eliminarDirectorio($archivo) : unlink($archivo);
        }

        rmdir($directorio);
    }

    public function insertarFactura(Request $request, $idventa, $numeroFactura, $cuf, $cufd, $codigoControl, $correo, $fechaEmision, $codigoMetodoPago, $montoTotal, $montoTotalSujetoIva, $descuentoAdicional, $productos){
        if (!$request->ajax()) {
            return response()->json(['error' => 'Acceso no autorizado'], 401);
        }

        $factura = new Factura();
        $factura->idventa = $idventa;
        //$factura->idcliente = $id_cliente;
        $factura->numeroFactura = $numeroFactura;
        $factura->cuf = $cuf;
        $factura->cufd = $cufd;
        $factura->codigoControl = $codigoControl;
        $factura->correo = $correo;
        $factura->fechaEmision = $fechaEmision;
        $factura->codigoMetodoPago = $codigoMetodoPago;
        $factura->montoTotal = $montoTotal;
        $factura->montoTotalSujetoIva = $montoTotalSujetoIva;
        $factura->descuentoAdicional = $descuentoAdicional;
        $factura->productos = $productos;
        $factura->estado = 1;

        $success = $factura->save();

        return $success;
    }

    public function insertarFacturaOffline(Request $request, $idventa, $numeroFactura, $cuf, $cufd, $codigoControl, $correo, $fechaEmision, $codigoMetodoPago, $montoTotal, $montoTotalSujetoIva, $descuentoAdicional, $productos)
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Acceso no autorizado'], 401);
        }

        $facturaOff = new FacturaFueraLinea();
        $facturaOff->idventa = $idventa;
        //$factura->idcliente = $id_cliente;
        $facturaOff->numeroFactura = $numeroFactura;
        $facturaOff->cuf = $cuf;
        $facturaOff->cufd = $cufd;
        $facturaOff->codigoControl = $codigoControl;
        $facturaOff->correo = $correo;
        $facturaOff->fechaEmision = $fechaEmision;
        $facturaOff->codigoMetodoPago = $codigoMetodoPago;
        $facturaOff->montoTotal = $montoTotal;
        $facturaOff->montoTotalSujetoIva = $montoTotalSujetoIva;
        $facturaOff->descuentoAdicional = $descuentoAdicional;
        $facturaOff->productos = $productos;
        $facturaOff->estado = 1;

         $success = $facturaOff->save();

         return $success;
     }

    public function formato_xml($temporal, $xml_temporal)
    {
        $ns_xsi = "http://www.w3.org/2001/XMLSchema-instance";
        foreach ($temporal as $key => $value) {
            if (is_array($value)) {
                if (!is_numeric($key)) {
                    $subnodo = $xml_temporal->addChild("$key");
                    $this->formato_xml($value, $subnodo);
                } else {
                    $this->formato_xml($value, $xml_temporal);
                }
            } else {
                // Escapar el valor antes de agregarlo al XML
                $escapedValue = htmlspecialchars($value, ENT_XML1);
                if ($escapedValue == null && $escapedValue <> '0') {
                    $hijo = $xml_temporal->addChild("$key", "$escapedValue");
                    $hijo->addAttribute('xsi:nil', 'true', $ns_xsi);
                } else {
                    $xml_temporal->addChild("$key", "$escapedValue");
                }
            }
        }
    }

    public function anulacionFactura($cuf, $motivoSeleccionado)
    {
        $user = Auth::user();
        $codigoPuntoVenta = '';
        if (!empty($user->idpuntoventa)) {
            $puntoVenta = PuntoVenta::find($user->idpuntoventa);
            if ($puntoVenta) {
                $codigoPuntoVenta = $puntoVenta->codigoPuntoVenta;
            }
        }

        //$puntoVenta = $user->idpuntoventa;
        $puntoVenta = $codigoPuntoVenta;
        $sucursal = $user->sucursal;
        $empresa = $sucursal->empresas;
        $codnit = $empresa->nit;
        $codSucursal = $sucursal->codigoSucursal;

        require "SiatController.php";
        $siat = new SiatController();
        $res = $siat->anulacionFactura($cuf, $motivoSeleccionado, $puntoVenta, $codSucursal, $codnit);
        if ($res->RespuestaServicioFacturacion->transaccion === true) {
            $mensaje = $res->RespuestaServicioFacturacion->codigoDescripcion;
        } else {
            $mensaje = $res->RespuestaServicioFacturacion->mensajesList->descripcion;
        }
        echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
        //var_dump($res);

        if ($res->RespuestaServicioFacturacion->transaccion === true) {
            $pdfPath = public_path('docs/facturaCarta.pdf');
            $descripcionMotivo = MotivoAnulacion::where('codigo', $motivoSeleccionado)->value('descripcion');
            $numeroFactura = Factura::where('cuf', $cuf)->value('numeroFactura');
            $correo = Factura::where('cuf', $cuf)->value('correo');
            $numFactura = str_pad($numeroFactura, 5, "0", STR_PAD_LEFT);
            \Mail::to($correo)->send(new \App\Mail\AnulaciónFactura($pdfPath, $descripcionMotivo, $numFactura));
        }

    }

    public function anulacionFacturaOffline($cuf, $motivoSeleccionado)
    {
        $user = Auth::user();
        $codigoPuntoVenta = '';
        if (!empty($user->idpuntoventa)) {
            $puntoVenta = PuntoVenta::find($user->idpuntoventa);
            if ($puntoVenta) {
                $codigoPuntoVenta = $puntoVenta->codigoPuntoVenta;
            }
        }

        //$puntoVenta = $user->idpuntoventa;
        $puntoVenta = $codigoPuntoVenta;
        $sucursal = $user->sucursal;
        $empresa = $sucursal->empresas;
        $codnit = $empresa->nit;
        $codSucursal = $sucursal->codigoSucursal;

        require "SiatController.php";
        $siat = new SiatController();
        $res = $siat->anulacionFactura($cuf, $motivoSeleccionado, $puntoVenta, $codSucursal, $codnit);
        if ($res->RespuestaServicioFacturacion->transaccion === true) {
            $mensaje = $res->RespuestaServicioFacturacion->codigoDescripcion;
        } else {
            $mensaje = $res->RespuestaServicioFacturacion->mensajesList->descripcion;
        }
        echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
        //var_dump($res);

        if ($res->RespuestaServicioFacturacion->transaccion === true) {
            $pdfPath = public_path('docs/facturaCarta.pdf');
            $descripcionMotivo = MotivoAnulacion::where('codigo', $motivoSeleccionado)->value('descripcion');
            $numeroFactura = FacturaFueraLinea::where('cuf', $cuf)->value('numeroFactura');
            $correo = FacturaFueraLinea::where('cuf', $cuf)->value('correo');
            $numFactura = str_pad($numeroFactura, 5, "0", STR_PAD_LEFT);
            \Mail::to($correo)->send(new \App\Mail\AnulaciónFactura($pdfPath, $descripcionMotivo, $numFactura));
        }

    }

    public function registroEventoSignificativo(Request $request)
    {
        $user = Auth::user();
        $codigoPuntoVenta = '';
        if (!empty($user->idpuntoventa)) {
            $puntoVenta = PuntoVenta::find($user->idpuntoventa);
            if ($puntoVenta) {
                $codigoPuntoVenta = $puntoVenta->codigoPuntoVenta;
            }
        }
        //$puntoVenta = $user->idpuntoventa;
        $puntoVenta = $codigoPuntoVenta;
        $sucursal = $user->sucursal;
        $codSucursal = $sucursal->codigoSucursal;
        $empresa = $sucursal->empresas;
        $codnit = $empresa->nit;

        $descripcion = $request->descripcion;
        $cufdEvento = $request->cufdEvento;
        $codigoMotivoEvento = $request->codigoMotivoEvento;
        $inicioEvento = $request->inicioEvento;
        $finEvento = $request->finEvento;

        require "SiatController.php";
        $siat = new SiatController();
        $res = $siat->registroEventoSignificativo($descripcion, $cufdEvento, $codigoMotivoEvento, $inicioEvento, $finEvento, $puntoVenta, $codSucursal, $codnit);
        //dd($res);
        // Verificar el valor de transacción y asignar el mensaje correspondiente
        if ($res->RespuestaListaEventos->transaccion === true) {
            $mensaje = $res->RespuestaListaEventos->codigoRecepcionEventoSignificativo;
            $_SESSION['scodigoevento'] = $res->RespuestaListaEventos->codigoRecepcionEventoSignificativo;
        } else {
            $mensaje = $res->RespuestaListaEventos->mensajesList->descripcion;
        }

        // Imprimir o retornar el mensaje, o realizar otras acciones según tu necesidad
        echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
        //var_dump($res);
    }

    public function registroPuntoVenta(Request $request)
    {
        $user = Auth::user();
        $codigoPuntoVenta = '';
        if (!empty($user->idpuntoventa)) {
            $puntoVenta = PuntoVenta::find($user->idpuntoventa);
            if ($puntoVenta) {
                $codigoPuntoVenta = $puntoVenta->codigoPuntoVenta;
            }
        }
        //$puntoVenta = $user->idpuntoventa;
        $puntoVenta = $codigoPuntoVenta;
        $sucursal = $user->sucursal;
        $codSucursal = $sucursal->codigoSucursal;

        $nombre = $request->nombre;
        $descripcion = $request->descripcion;
        $nit = $request->nit;
        $idtipopuntoventa = $request->idtipopuntoventa;
        $idsucursal = $request->idsucursal;

        require "SiatController.php";
        $siat = new SiatController();
        $res = $siat->registroPuntoVenta($nombre, $descripcion, $nit, $idtipopuntoventa, $idsucursal, $puntoVenta, $codSucursal);
        // Verificar el valor de transacción y asignar el mensaje correspondiente
        if ($res->RespuestaRegistroPuntoVenta->transaccion === true) {
            $mensaje = $res->RespuestaRegistroPuntoVenta->codigoPuntoVenta;
        } else {
            $mensaje = $res->RespuestaRegistroPuntoVenta->mensajesList->descripcion;
        }

        // Imprimir o retornar el mensaje, o realizar otras acciones según tu necesidad
        echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
        //var_dump($res);
    }

    public function cierrePuntoVenta(Request $request)
    {
        $user = Auth::user();
        $sucursal = $user->sucursal;
        $codSucursal = $sucursal->codigoSucursal;
        $codigoPuntoVenta = $request->codigoPuntoVenta;
        $codigoSucursal = $request->codigoSucursal;
        $nit = $request->nit;

        require "SiatController.php";
        $siat = new SiatController();
        $res = $siat->cierrePuntoVenta($codigoPuntoVenta, $nit, $codSucursal);
        //dd($res);
        // Verificar el valor de transacción y asignar el mensaje correspondiente
        if ($res->RespuestaCierrePuntoVenta->transaccion === true) {
            $mensaje = $res->RespuestaCierrePuntoVenta->codigoPuntoVenta;
        } else {
            $mensaje = $res->RespuestaCierrePuntoVenta->mensajesList->descripcion;
        }

        echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
        //var_dump($res);
    }

    public function consultaPuntoVenta(Request $request)
    {
        $user = Auth::user();
        $sucursal = $user->sucursal;
        $codSucursal = $sucursal->codigoSucursal;
        $empresa = $sucursal->empresas;
        $codnit = $empresa->nit;
        //VERIFICAR SI ESTA BIEN
        $nombreSucursal = $sucursal->nombre;
        //$nit = "5153610012";

        require "SiatController.php";
        $siat = new SiatController();
        $res = $siat->consultaPuntoVenta($codnit);
        //dd($res);
        if ($res->RespuestaConsultaPuntoVenta->transaccion === true) {
            $mensaje = $res;
        } else {
            $mensaje = $res->RespuestaCierrePuntoVenta->mensajesList->descripcion;
        }

        //echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);

        return response()->json([
            'mensaje' => $mensaje,
            'codSucursal' => $codSucursal,
            'nombreSucursal' => $nombreSucursal,
            'nit2' => $codnit
        ], 200);
    }

    public function imprimirTicket($id)
    {
        $venta = Venta::join('users', 'ventas.idusuario', '=', 'users.id')
            ->select(
                'ventas.id',
                'ventas.tipo_comprobante',
                'ventas.cliente',
                'ventas.documento',
                'ventas.num_comprobante',
                'ventas.created_at',
                'ventas.impuesto',
                'ventas.total',
                'ventas.estado',
                'users.usuario'
            )
            ->where('ventas.id', '=', $id)
            ->orderBy('ventas.id', 'desc')
            ->take(1)
            ->first();
            
        $comprobante = $venta->num_comprobante;

        $detalles = DetalleVenta::leftJoin('articulos', 'detalle_ventas.codigoComida', '=', 'articulos.codigo')
                    ->leftJoin('menu', 'detalle_ventas.codigoComida', '=', 'menu.codigo')
                    ->select(
                        'detalle_ventas.cantidad',
                        'detalle_ventas.precio',
                        'detalle_ventas.descuento',
                        DB::raw('CASE WHEN detalle_ventas.codigoComida IN (SELECT codigo FROM articulos) THEN articulos.nombre 
                                    WHEN detalle_ventas.codigoComida IN (SELECT codigo FROM menu) THEN menu.nombre 
                                    ELSE NULL 
                            END AS comida_nombre')
                    )
                    ->where('detalle_ventas.idventa', '=', $id)
                    ->orderBy('detalle_ventas.id', 'desc')
                    ->get();

        
        $pdf = new FPDF();

        $comprobante = $venta->num_comprobante;
        $montoTotal = $venta->total;
        $cliente = $venta->cliente;

        $pdf->AddPage('P', array(70, 150));
        $pdf->SetMargins(0, 0); 
        $pdf->SetAutoPageBreak(false);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, "TICKET DE COMPRA", 0, 1, 'C');
        $pdf->Cell(0, 10, "Cliente: $cliente", 0, 1, 'C');
        $pdf->Cell(0, 10, "Num. Compra: $comprobante", 0, 1, 'C');

        $anchoCelda = $pdf->GetPageWidth() / 3;
        $tamañoTexto = 10;
        foreach ($detalles as $detalle) {
            $cantidad = $detalle->cantidad;
            $precio = $detalle->precio;
            $descuento = $detalle->descuento;
            $nombreArticulo = $detalle->comida_nombre;
            $total = $precio * $cantidad;

            $pdf->SetFontSize($tamañoTexto - 2);
            // Utilizar los datos en la creación del PDF
            $pdf->Cell($anchoCelda, 10, "$cantidad", 0, 0, 'C');
            $pdf->Cell($anchoCelda, 10, "$nombreArticulo", 0, 0, 'C');
            $pdf->Cell($anchoCelda, 10, "Bs. $total", 0, 1, 'C');
        }

        $pdf->Cell(63, 10, "Total: Bs. $montoTotal", 0, 1, 'R');
        $pdf->Cell(55, 10, "***Gracias por su Compra***", 0, 1, 'R');


        

        $pdfPath = public_path('docs/ticket.pdf');
        $printerName = "POS-80";
        $pdf->Output($pdfPath, 'F');
    
        $cutCommand = "\x1B" . "m";
        file_put_contents($pdfPath, $cutCommand, FILE_APPEND);

        // Imprimir el PDF con Adobe Reader
        $escapedPdfPath = escapeshellarg($pdfPath);
        $escapedPrinterName = escapeshellarg($printerName);
        $acrobatPath = '"C:\\Program Files\\Adobe\\Acrobat Reader DC\\Reader\\AcroRd32.exe"';
        $command = "$acrobatPath /t $escapedPdfPath $escapedPrinterName";
        $output = shell_exec($command);

        // Verificar la salida o manejar errores si es necesario
        if ($output === null) {
            return response()->json(['error' => 'Error al imprimir el archivo PDF.']);
        } else {
            return response()->json(['message' => 'Archivo PDF enviado a la impresora.']);
        }

        return response()->download($pdfPath);
    }


    public function imprimirFactura($id, $correo){

        $user = Auth::user();
        $codigoPuntoVenta = '';
        if (!empty($user->idpuntoventa)) {
            $puntoVenta = PuntoVenta::find($user->idpuntoventa);
            if ($puntoVenta) {
                $codigoPuntoVenta = $puntoVenta->codigoPuntoVenta;
            }
        }
        //$puntoVenta = $user->idpuntoventa;
        $puntoVenta = $codigoPuntoVenta;

        /*$facturas = Factura::join('personas', 'facturas.idcliente', '=', 'personas.id')
        ->select('facturas.*','personas.nombre as razonSocial', 'personas.email as email', 'personas.num_documento as documentoid', 'personas.complemento_id as complementoid')
        ->where('facturas.id', '=', $id)
        ->orderBy('facturas.id', 'desc')->paginate(3);*/

        $facturas = Factura::join('ventas', 'facturas.idventa', '=', 'ventas.id')
        ->select('facturas.*','ventas.cliente as razonSocial', 'ventas.documento as documentoid')
        ->where('facturas.id', '=', $id)
        ->orderBy('facturas.id', 'desc')->paginate(3);
        
        Log::info('Resultado', [
            //'facturas' => $facturas,
            'idFactura' => $id,
        ]);

        $xml = $facturas[0]->productos;
        $archivoXML = new SimpleXMLElement($xml);
        $nitEmisor = $archivoXML->cabecera[0]->nitEmisor;
        $numeroFactura = str_pad($archivoXML->cabecera[0]->numeroFactura, 5, "0", STR_PAD_LEFT);
        $cuf = $archivoXML->cabecera[0]->cuf;
        $direccion = $archivoXML->cabecera[0]->direccion;
        $telefono = $archivoXML->cabecera[0]->telefono;
        $municipio = $archivoXML->cabecera[0]->municipio;
        $fechaEmision = $archivoXML->cabecera[0]->fechaEmision;
        $documentoid = $archivoXML->cabecera[0]->numeroDocumento;
        $razonSocial = $archivoXML->cabecera[0]->nombreRazonSocial;
        $codigoCliente = $archivoXML->cabecera[0]->codigoCliente;
        $montoTotal1 = $archivoXML->cabecera[0]->montoTotal;
        $montoGiftCard = $archivoXML->cabecera[0]->montoGiftCard;
        $descuentoAdicional = $archivoXML->cabecera[0]->descuentoAdicional;
        $leyenda = $archivoXML->cabecera[0]->leyenda;
        $complementoid = $archivoXML->cabecera[0]->complemento;

        
        $montoTotal = ($montoTotal1-$montoGiftCard);
        $totalpagar = number_format(floatval($montoTotal), 2);
        $totalpagar = str_replace(',', '', $totalpagar);
        $totalpagar = str_replace('.', ',', $totalpagar);
        $cifrasEnLetras = new CifrasEnLetrasController();
        $letra = ($cifrasEnLetras->convertirBolivianosEnLetras($totalpagar));


        $url = 'https://pilotosiat.impuestos.gob.bo/consulta/QR?nit=' . $nitEmisor . '&cuf=' . $cuf . '&numero=' . $numeroFactura . '&t=2';
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'imageBase64' => false,
            'scale' => 10,
        ]);
        $qrCode = new QRCode($options);
        $qrCode->render($url, public_path('qr/qrcode.png'));

        
        $pdf = new FPDF('P', 'mm', 'Letter');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(60, 4, utf8_decode('CONTAB SRL'), 0, 0, 'C');
        $pdf->Cell(40, 4, '', 0, 0, 'C');
        $pdf->Cell(27, 4, '', 0, 0, 'C');
        $pdf->Cell(38, 4, 'NIT', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(32, 4, $nitEmisor, 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(60, 4, utf8_decode('CASA MATRIZ'), 0, 0, 'C');
        $pdf->Cell(40, 4, '', 0, 0, 'C');
        $pdf->Cell(27, 4, '', 0, 0, 'C');
        $pdf->Cell(38, 4, utf8_decode('FACTURA N°'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(32, 4, $numeroFactura, 0, 1, 'L');
        
        $pdf->Cell(60, 4, utf8_decode('N° Punto de Venta '.$puntoVenta), 0, 0, 'C');
        $pdf->Cell(40, 4, '', 0, 0, 'C');
        $pdf->Cell(27, 4, '', 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(38, 4, utf8_decode('CÓD. AUTORIZACIÓN'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $y = $pdf->GetY();
        $pdf->MultiCell(32, 4, $cuf, 0, 'L');
        
        $pdf->SetY($y + 4);
        $pdf->MultiCell(60, 3, utf8_decode($direccion), 0, 'C');

        $pdf->Cell(60, 4, utf8_decode('Teléfono: ' . $telefono), 0, 1, 'C');
        $pdf->Cell(60, 4, utf8_decode($municipio), 0, 1, 'C');

        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 6, utf8_decode('FACTURA'), 0, 1, 'C');
        
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(0, 4, utf8_decode('(Con Derecho a Crédito Fiscal)'), 0, 1, 'C');

        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(40, 5, utf8_decode('Fecha:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(60, 5, $fechaEmision, 0, 0, 'L');
        
        $pdf->Cell(27, 5, '', 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(38, 5, 'NIT/CI/CEX:    ', 0, 0, 'R');
        $pdf->SetFont('Arial', '', 8);
        if (isset($complementoid) && $complementoid !== '') {
            $pdf->Cell(32, 5, $documentoid, 0, 1, 'L');
            //$pdf->Cell(32, 5, $documentoid . "-" . $complementoid, 0, 1, 'L');
        } else {
            $pdf->Cell(32, 5, $documentoid, 0, 1, 'L');
        }

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(40, 5, utf8_decode('Nombre/Razón Social:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(60, 5, utf8_decode($razonSocial), 0, 0, 'L');
        $pdf->Cell(27, 5, '', 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(38, 5, 'Cod. Cliente:    ', 0, 0, 'R');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(32, 5, $documentoid, 0, 1, 'L');

        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 8);
        $y = $pdf->GetY();
        $pdf->MultiCell(25, 3.5, utf8_decode('CÓDIGO PRODUCTO / SERVICIO'), 1, 'C');
        $pdf->SetY($y);
        $pdf->SetX(35);
        $pdf->MultiCell(25, 3.5, utf8_decode("\nCANTIDAD\n "), 1, 'C');
        $pdf->SetY($y);
        $pdf->SetX(60);
        $pdf->MultiCell(20, 3.5, utf8_decode("\nUNIDAD DE MEDIDA"), 1, 'C');
        $pdf->SetY($y);
        $pdf->SetX(80);
        $pdf->MultiCell(50, 3.5, utf8_decode("\nDESCRIPCIÓN\n "), 1, 'C');
        $pdf->SetY($y);
        $pdf->SetX(130);
        $pdf->MultiCell(25, 3.5, utf8_decode("\nPRECIO UNITARIO"), 1, 'C');
        $pdf->SetY($y);
        $pdf->SetX(155);
        $pdf->MultiCell(25, 3.5, utf8_decode("\nDESCUENTO\n "), 1, 'C');
        $pdf->SetY($y);
        $pdf->SetX(180);
        $pdf->MultiCell(27, 3.5, utf8_decode("\nSUBTOTAL\n "), 1, 'C');
        

        $pdf->SetFont('Arial', '', 8);
        $detalle = $archivoXML->detalle;
        $sumaSubTotales = 0.0;
        foreach ($detalle as $p) {
            $pdf->Cell(25, 5, $p->codigoProducto, 1, 0, 'L');
            $pdf->Cell(25, 5, $p->cantidad, 1, 0, 'R');
            $pdf->Cell(20, 5, "UNIDAD", 1, 0, 'L');
            $pdf->Cell(50, 5, $p->descripcion, 1, 0, 'L');
            $pdf->Cell(25, 5, number_format(floatval($p->precioUnitario), 2), 1, 0, 'R');
            $pdf->Cell(25, 5, number_format(floatval($p->montoDescuento), 2), 1, 0, 'R');
            $pdf->Cell(27, 5, number_format(floatval($p->subTotal), 2), 1, 1, 'R');

            //Sumar el subTotal actual
            $sumaSubTotales += floatval($p->subTotal);
        }

        $pdf->Cell(120, 5, '', 0, 0, 'L');
        $pdf->Cell(50, 5, 'SUBTOTAL Bs.', 1, 0, 'R');
        $pdf->Cell(27, 5, number_format(floatval($sumaSubTotales), 2), 1, 1, 'R');

        $pdf->Cell(120, 5, '', 0, 0, 'L');
        $pdf->Cell(50, 5, 'DESCUENTO Bs.', 1, 0, 'R');
        $pdf->Cell(27, 5, number_format(floatval($descuentoAdicional), 2), 1, 1, 'R');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(120, 5, 'Son: ' . ucfirst($letra), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(50, 5, 'TOTAL Bs.', 1, 0, 'R');
        $pdf->Cell(27, 5, number_format(floatval(($montoTotal)), 2), 1, 1, 'R');

        $pdf->Cell(120, 5, '', 0, 0, 'L');
        $pdf->Cell(50, 5, 'MONTO GIFT CARD Bs.', 1, 0, 'R');
        $pdf->Cell(27, 5, number_format(floatval(($montoGiftCard)), 2), 1, 1, 'R');

        $pdf->Cell(120, 5, '', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 5, 'MONTO A PAGAR Bs.', 1, 0, 'R');
        $pdf->Cell(27, 5, number_format(floatval(($montoTotal)), 2), 1, 1, 'R');

        $pdf->Cell(120, 5, '', 0, 0, 'L');
        $pdf->Cell(50, 5, utf8_decode('IMPORTE BASE CRÉDITO FISCAL'), 1, 0, 'R');
        $pdf->Cell(27, 5, number_format(floatval(($montoTotal)), 2), 1, 1, 'R');

        $pdf->Ln(10);
        $y = $pdf->GetY();
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(170, 5, utf8_decode('ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS, EL USO ILÍCITO SERÁ SANCIONADO PENALMENTE DE ACUERDO A LEY'), 0, 1, 'C');
        $pdf->Image(public_path('qr/qrcode.png'), 182, $y - 3, 25, 'PNG');
        
        $pdf->Ln(4);
        $pdf->Cell(170, 5, utf8_decode($leyenda), 0, 1, 'C');

        $pdf->Ln(2);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(170, 5, utf8_decode('"Este documento es la Representación Gráfica de un Documento Fiscal Digital emitido en una modalidad de facturación en línea"'), 0, 1, 'C');

        $pdf->Output(public_path('docs/facturaCarta.pdf'), 'F');

        $pdfPath = public_path('docs/facturaCarta.pdf');
        $xmlPath = public_path("docs/facturaxml.xml");

        \Mail::to($correo)->send(new \App\Mail\FacturaElectrónica($xmlPath, $pdfPath));

        return response()->download(public_path('docs/facturaCarta.pdf'));
    }

    public function imprimirFacturaRollo($id, $correo)
    {
        $user = Auth::user();
        $codigoPuntoVenta = '';
        if (!empty($user->idpuntoventa)) {
            $puntoVenta = PuntoVenta::find($user->idpuntoventa);
            if ($puntoVenta) {
                $codigoPuntoVenta = $puntoVenta->codigoPuntoVenta;
            }
        }

        //$puntoVenta = $user->idpuntoventa;
        $puntoVenta = $codigoPuntoVenta;

        $facturas = Factura::join('ventas', 'facturas.idventa', '=', 'ventas.id')
        ->select('facturas.*','ventas.cliente as razonSocial', 'ventas.documento as documentoid')
        ->where('facturas.id', '=', $id)
        ->orderBy('facturas.id', 'desc')->paginate(3);
        
        Log::info('Resultado', [
            //'facturas' => $facturas,
            'idFactura' => $id,
        ]);

        $xml = $facturas[0]->productos;
        $archivoXML = new SimpleXMLElement($xml);
        $nitEmisor = $archivoXML->cabecera[0]->nitEmisor;
        $numeroFactura = str_pad($archivoXML->cabecera[0]->numeroFactura, 5, "0", STR_PAD_LEFT);
        $cuf = $archivoXML->cabecera[0]->cuf;
        $direccion = $archivoXML->cabecera[0]->direccion;
        $telefono = $archivoXML->cabecera[0]->telefono;
        $municipio = $archivoXML->cabecera[0]->municipio;
        $fechaEmision = $archivoXML->cabecera[0]->fechaEmision;
        $fechaFormateada = date("d/m/Y h:i A", strtotime($fechaEmision));
        $documentoid = $archivoXML->cabecera[0]->numeroDocumento;
        $razonSocial = $archivoXML->cabecera[0]->nombreRazonSocial;
        $codigoCliente = $archivoXML->cabecera[0]->codigoCliente;
        $montoTotal1 = $archivoXML->cabecera[0]->montoTotal;
        $montoGiftCard = $archivoXML->cabecera[0]->montoGiftCard;
        $descuentoAdicional = $archivoXML->cabecera[0]->descuentoAdicional;
        $leyenda = $archivoXML->cabecera[0]->leyenda;
        $complementoid = $archivoXML->cabecera[0]->complemento;

        $montoTotal = ($montoTotal1-$montoGiftCard);
        $totalpagar = number_format(floatval($montoTotal), 2);
        $totalpagar = str_replace(',', '', $totalpagar);
        $totalpagar = str_replace('.', ',', $totalpagar);
        $cifrasEnLetras = new CifrasEnLetrasController();
        $letra = ($cifrasEnLetras->convertirBolivianosEnLetras($totalpagar));


        $url = 'https://pilotosiat.impuestos.gob.bo/consulta/QR?nit=' . $nitEmisor . '&cuf=' . $cuf . '&numero=' . $numeroFactura . '&t=2';
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'imageBase64' => false,
            'scale' => 10,
        ]);
        $qrCode = new QRCode($options);
        $qrCode->render($url, public_path('qr/qrcode.png'));

        //$pdf = new FPDF('P', 'mm', array(80, 0));
        $pdf = new FPDF('P', 'mm', array(80, 250));
        //$pdf = new FPDF();

        $pdf->SetAutoPageBreak(true, 10);
        $pdf->SetMargins(10, 10);
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(0, 3, 'FACTURA', 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(0, 3, utf8_decode('CON DERECHO A CRÉDITO FISCAL'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(0, 3, utf8_decode('365 SOFT'), 0, 1, 'C');
        $pdf->Cell(0, 3, utf8_decode('Casa Matriz'), 0, 1, 'C');
        $pdf->Cell(0, 3, utf8_decode('No. Punto de Venta '.$puntoVenta), 0, 1, 'C');

        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCell(0, 3, utf8_decode($direccion), 0, 'C');

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(0, 3, utf8_decode('Tel. ' . $telefono), 0, 1, 'C');
        $pdf->Cell(0, 3, utf8_decode($municipio), 0, 1, 'C');

        $y = $pdf->GetY();
        $pdf->SetY($y + 2);
        $pdf->SetLineWidth(0.2);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(0, 3, '', 'T', 1, 'C');

        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(0, 3, 'NIT', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 6);
        //$pdf->Cell(0, 3, utf8_decode($documentoid."-".$complementoid), 0, 1, 'C');
        $pdf->Cell(0, 3, utf8_decode($documentoid), 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(0, 3, utf8_decode('FACTURA N°'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(0, 3, utf8_decode($numeroFactura), 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(0, 3, utf8_decode('CÓD. AUTORIZACIÓN'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCell(0, 3, utf8_decode($cuf), 0, 'C');

        $y = $pdf->GetY();
        $pdf->SetY($y + 2);
        $pdf->SetLineWidth(0.2);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(0, 3, '', 'T', 1, 'C');

        $spacing = 2;

        $pdf->SetX(($pdf->GetPageWidth() - $pdf->GetStringWidth('NOMBRE/RAZON SOCIAL:') - $pdf->GetStringWidth($razonSocial)) / 2);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(10, 3, 'NOMBRE/RAZON SOCIAL:', 0, 0, 'C');
        $pdf->SetX($pdf->GetX() + $spacing);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(0, 3, utf8_decode($razonSocial), 0, 1, 'C');

        $spacingBetweenColumns = 10;
        $pdf->SetX(($pdf->GetPageWidth() - $pdf->GetStringWidth('NIT/CI/CEX:') - $pdf->GetStringWidth($documentoid)) / 2);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(2.5, 3, 'NIT/CI/CEX:', 0, 0, 'C');
        $pdf->SetX($pdf->GetX() + $spacingBetweenColumns);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(5.5, 3, utf8_decode($documentoid), 0, 1, 'C');

        $spacingBetweenColumns = 10;
        $pdf->SetX(($pdf->GetPageWidth() - $pdf->GetStringWidth('COD. CLIENTE:') - $pdf->GetStringWidth($codigoCliente)) / 2);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(2.5, 3, 'COD. CLIENTE:', 0, 0, 'C');
        $pdf->SetX($pdf->GetX() + $spacingBetweenColumns);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(9, 3, utf8_decode($codigoCliente), 0, 1, 'C');

        $spacingBetweenColumns = 10;
        $pdf->SetX(($pdf->GetPageWidth() - $pdf->GetStringWidth('FECHA DE EMISIÓN:') - $pdf->GetStringWidth($fechaEmision)) / 2);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(21.5, 3, utf8_decode('FECHA DE EMISIÓN:'), 0, 0, 'C');
        $pdf->SetX($pdf->GetX() + $spacingBetweenColumns);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(10, 3, utf8_decode($fechaFormateada), 0, 1, 'C');

        $y = $pdf->GetY();
        $pdf->SetY($y + 2);
        $pdf->SetLineWidth(0.2);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(0, 3, '', 'T', 1, 'C');

        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(0, 3, 'DETALLE', 0, 1, 'C');

        $detalle = $archivoXML->detalle;
        $sumaSubTotales = 0.0;
        foreach ($detalle as $p) {
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(0, 3, $p->codigoProducto . " - " . $p->descripcion, 0, 1, 'L');

            $medida = $p->unidadMedida;
            $nombreMedida = Medida::where('codigoClasificador', $medida)->value('descripcion_medida');

            $pdf->SetFont('Arial', '', 6);
            $pdf->Cell(0, 3, "Unidad de Medida: " . $nombreMedida, 0, 1, 'L');
            $pdf->Cell(0, 3, number_format(floatval($p->cantidad), 2) . " X " . number_format(floatval($p->precioUnitario), 2) . " - " . number_format(floatval($p->montoDescuento), 2), 0, 0, 'L');
            $pdf->Cell(0, 3, number_format(floatval($p->subTotal), 2), 0, 1, 'R');

            $sumaSubTotales += floatval($p->subTotal);
        }

        $y = $pdf->GetY();
        $pdf->SetY($y + 2);
        $pdf->SetLineWidth(0.2);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(0, 3, '', 'T', 1, 'C');

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(0, 3, 'SUBTOTAL Bs', 0, 0, 'C');
        $pdf->Cell(0, 3, number_format(floatval($sumaSubTotales), 2), 0, 1, 'R');
        $pdf->Cell(0, 3, 'DESCUENTO Bs', 0, 0, 'C');
        $pdf->Cell(0, 3, number_format(floatval($descuentoAdicional), 2), 0, 1, 'R');
        $pdf->Cell(0, 3, 'TOTAL Bs', 0, 0, 'C');
        $pdf->Cell(0, 3, number_format(floatval($montoTotal), 2), 0, 1, 'R');
        $pdf->Cell(0, 3, 'MONTO GIFT CARD Bs', 0, 0, 'C');
        $pdf->Cell(0, 3, number_format(floatval($montoGiftCard), 2), 0, 1, 'R');
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(0, 3, 'MONTO A PAGAR Bs', 0, 0, 'C');
        $pdf->Cell(0, 3, number_format(floatval($montoTotal), 2), 0, 1, 'R');
        $pdf->SetFont('Arial', 'B', 5);
        $pdf->Cell(0, 3, utf8_decode('IMPORTE BASE CRÉDITO FISCAL Bs'), 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(0, 3, number_format(floatval($montoTotal), 2), 0, 1, 'R');
        $pdf->Ln(6);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(0, 3, 'Son: ' . $letra, 0, 1, 'L');

        $y = $pdf->GetY();
        $pdf->SetY($y + 2);
        $pdf->SetLineWidth(0.2);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(0, 3, '', 'T', 1, 'C');

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(0, 3, utf8_decode('ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS,'), 0, 1, 'C');
        $pdf->Cell(0, 3, utf8_decode('EL USO ILÍCITO SERÁ SANCIONADO PENALMENTE DE'), 0, 1, 'C');
        $pdf->Cell(0, 3, utf8_decode('ACUERDO A LA LEY'), 0, 1, 'C');
        $pdf->Ln(3);
        $pdf->SetFont('Arial', '', 5);
        $pdf->MultiCell(0, 3, utf8_decode($leyenda), 0, 'C');
        $pdf->Ln(3);
        $pdf->Cell(0, 3, utf8_decode('Este documento es la Representación Gráfica de un'), 0, 1, 'C');
        $pdf->Cell(0, 3, utf8_decode('Documento Fiscal Digital emitido en una modalidad de'), 0, 1, 'C');
        $pdf->Cell(0, 3, utf8_decode('facturación en línea'), 0, 1, 'C');
        $pdf->Ln(3);

        $textY = $pdf->GetY();

        $imageWidth = 25;
        $pageWidth = $pdf->GetPageWidth();
        $imageX = ($pageWidth - $imageWidth) / 2;
        $pdf->Image(public_path('qr/qrcode.png'), $imageX, $textY + 3, $imageWidth, 0, 'PNG');



        $pdf->Output(public_path('docs/facturaRollo.pdf'), 'F');

        $pdfPath = public_path('docs/facturaRollo.pdf');
        $xmlPath = public_path("docs/facturaxml.xml");

        \Mail::to($correo)->send(new \App\Mail\FacturaElectrónica($xmlPath, $pdfPath));

        return response()->download(public_path('docs/facturaRollo.pdf'));
    }

    public function imprimirFacturaOffline($id, $correo) 
    {
        $user = Auth::user();
        $codigoPuntoVenta = '';
        if (!empty($user->idpuntoventa)) {
            $puntoVenta = PuntoVenta::find($user->idpuntoventa);
            if ($puntoVenta) {
                $codigoPuntoVenta = $puntoVenta->codigoPuntoVenta;
            }
        }

        //$puntoVenta = $user->idpuntoventa;
        $puntoVenta = $codigoPuntoVenta;

        /*$facturas = FacturaFueraLinea::join('personas', 'factura_fuera_lineas.idcliente', '=', 'personas.id')
        ->select('factura_fuera_lineas.*', 'personas.nombre as razonSocial', 'personas.email as email', 'personas.num_documento as documentoid', 'personas.complemento_id as complementoid')
        ->where('factura_fuera_lineas.id', '=', $id)
        ->orderBy('factura_fuera_lineas.id', 'desc')->paginate(3);*/

        $facturas = FacturaFueraLinea::join('ventas', 'factura_fuera_lineas.idventa', '=', 'ventas.id')
        ->select('factura_fuera_lineas.*','ventas.cliente as razonSocial', 'ventas.documento as documentoid')
        ->where('factura_fuera_lineas.id', '=', $id)
        ->orderBy('factura_fuera_lineas.id', 'desc')->paginate(3);
            
        Log::info('Resultado', [
            //'facturas' => $facturas,
            'idFactura' => $id,
        ]);    


        $xml = $facturas[0]->productos;
        $archivoXML = new SimpleXMLElement($xml);
        $nitEmisor = $archivoXML->cabecera[0]->nitEmisor;
        $numeroFactura = str_pad($archivoXML->cabecera[0]->numeroFactura, 5, "0", STR_PAD_LEFT);
        $cuf = $archivoXML->cabecera[0]->cuf;
        $direccion = $archivoXML->cabecera[0]->direccion;
        $telefono = $archivoXML->cabecera[0]->telefono;
        $municipio = $archivoXML->cabecera[0]->municipio;
        $fechaEmision = $archivoXML->cabecera[0]->fechaEmision;
        $fecha_formato = str_replace("T", "", $fechaEmision);
        $fecha_formato = str_replace("-", "", $fecha_formato);
        $fecha_formato = str_replace(":", "", $fecha_formato);
        $fecha_formato = str_replace(".", "", $fecha_formato);
        $documentoid = $archivoXML->cabecera[0]->numeroDocumento;
        $razonSocial = $archivoXML->cabecera[0]->nombreRazonSocial;
        $codigoCliente = $archivoXML->cabecera[0]->codigoCliente;
        $montoTotal1 = $archivoXML->cabecera[0]->montoTotal;
        $montoGiftCard = $archivoXML->cabecera[0]->montoGiftCard;
        $descuentoAdicional = $archivoXML->cabecera[0]->descuentoAdicional;
        $leyenda = $archivoXML->cabecera[0]->leyenda;
        $complementoid = $archivoXML->cabecera[0]->complemento;

        $montoTotal = ($montoTotal1-$montoGiftCard);
        $totalpagar = number_format(floatval($montoTotal), 2);
        $totalpagar = str_replace(',', '', $totalpagar);
        $totalpagar = str_replace('.', ',', $totalpagar);
        $cifrasEnLetras = new CifrasEnLetrasController();
        $letra = ($cifrasEnLetras->convertirBolivianosEnLetras($totalpagar));


        $url = 'https://pilotosiat.impuestos.gob.bo/consulta/QR?nit=' . $nitEmisor . '&cuf=' . $cuf . '&numero=' . $numeroFactura . '&t=2';
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'imageBase64' => false,
            'scale' => 10,
        ]);
        $qrCode = new QRCode($options);
        $qrCode->render($url, public_path('qr/qrcode.png'));


        $pdf = new FPDF('P', 'mm', 'Letter');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(60, 4, utf8_decode('CONTAB SRL'), 0, 0, 'C');
        $pdf->Cell(40, 4, '', 0, 0, 'C');
        $pdf->Cell(27, 4, '', 0, 0, 'C');
        $pdf->Cell(38, 4, 'NIT', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(32, 4, $nitEmisor, 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(60, 4, utf8_decode('CASA MATRIZ'), 0, 0, 'C');
        $pdf->Cell(40, 4, '', 0, 0, 'C');
        $pdf->Cell(27, 4, '', 0, 0, 'C');
        $pdf->Cell(38, 4, utf8_decode('FACTURA N°'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(32, 4, $numeroFactura, 0, 1, 'L');

        $pdf->Cell(60, 4, utf8_decode('N° Punto de Venta '.$puntoVenta), 0, 0, 'C');
        $pdf->Cell(40, 4, '', 0, 0, 'C');
        $pdf->Cell(27, 4, '', 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(38, 4, utf8_decode('CÓD. AUTORIZACIÓN'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $y = $pdf->GetY();
        $pdf->MultiCell(32, 4, $cuf, 0, 'L');

        $pdf->SetY($y + 4);
        $pdf->MultiCell(60, 3, utf8_decode($direccion), 0, 'C');

        $pdf->Cell(60, 4, utf8_decode('Teléfono: ' . $telefono), 0, 1, 'C');
        $pdf->Cell(60, 4, utf8_decode($municipio), 0, 1, 'C');

        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 6, utf8_decode('FACTURA'), 0, 1, 'C');

        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(0, 4, utf8_decode('(Con Derecho a Crédito Fiscal)'), 0, 1, 'C');

        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(40, 5, utf8_decode('Fecha:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(60, 5, $fechaEmision, 0, 0, 'L');

        $pdf->Cell(27, 5, '', 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(38, 5, 'NIT/CI/CEX:    ', 0, 0, 'R');
        $pdf->SetFont('Arial', '', 8);
        if (isset($complementoid) && $complementoid !== '') {
            $pdf->Cell(32, 5, $documentoid, 0, 1, 'L');
            //$pdf->Cell(32, 5, $documentoid . "-" . $complementoid, 0, 1, 'L');
        } else {
            $pdf->Cell(32, 5, $documentoid, 0, 1, 'L');
        }

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(40, 5, utf8_decode('Nombre/Razón Social:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(60, 5, utf8_decode($razonSocial), 0, 0, 'L');
        $pdf->Cell(27, 5, '', 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(38, 5, 'Cod. Cliente:    ', 0, 0, 'R');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(32, 5, $documentoid, 0, 1, 'L');

        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 8);
        $y = $pdf->GetY();
        $pdf->MultiCell(25, 3.5, utf8_decode('CÓDIGO PRODUCTO / SERVICIO'), 1, 'C');
        $pdf->SetY($y);
        $pdf->SetX(35);
        $pdf->MultiCell(25, 3.5, utf8_decode("\nCANTIDAD\n "), 1, 'C');
        $pdf->SetY($y);
        $pdf->SetX(60);
        $pdf->MultiCell(20, 3.5, utf8_decode("\nUNIDAD DE MEDIDA"), 1, 'C');
        $pdf->SetY($y);
        $pdf->SetX(80);
        $pdf->MultiCell(50, 3.5, utf8_decode("\nDESCRIPCIÓN\n "), 1, 'C');
        $pdf->SetY($y);
        $pdf->SetX(130);
        $pdf->MultiCell(25, 3.5, utf8_decode("\nPRECIO UNITARIO"), 1, 'C');
        $pdf->SetY($y);
        $pdf->SetX(155);
        $pdf->MultiCell(25, 3.5, utf8_decode("\nDESCUENTO\n "), 1, 'C');
        $pdf->SetY($y);
        $pdf->SetX(180);
        $pdf->MultiCell(27, 3.5, utf8_decode("\nSUBTOTAL\n "), 1, 'C');


        $pdf->SetFont('Arial', '', 8);
        $detalle = $archivoXML->detalle;
        $sumaSubTotales = 0.0;
        foreach ($detalle as $p) {
            $pdf->Cell(25, 5, $p->codigoProducto, 1, 0, 'L');
            $pdf->Cell(25, 5, $p->cantidad, 1, 0, 'R');
            $pdf->Cell(20, 5, $p->unidadMedida, 1, 0, 'L');
            $pdf->Cell(50, 5, $p->descripcion, 1, 0, 'L');
            $pdf->Cell(25, 5, number_format(floatval($p->precioUnitario), 2), 1, 0, 'R');
            $pdf->Cell(25, 5, number_format(floatval($p->montoDescuento), 2), 1, 0, 'R');
            $pdf->Cell(27, 5, number_format(floatval($p->subTotal), 2), 1, 1, 'R');

            //Sumar el subTotal actual
            $sumaSubTotales += floatval($p->subTotal);
        }

        $pdf->Cell(120, 5, '', 0, 0, 'L');
        $pdf->Cell(50, 5, 'SUBTOTAL Bs.', 1, 0, 'R');
        $pdf->Cell(27, 5, number_format(floatval($sumaSubTotales), 2), 1, 1, 'R');

        $pdf->Cell(120, 5, '', 0, 0, 'L');
        $pdf->Cell(50, 5, 'DESCUENTO Bs.', 1, 0, 'R');
        $pdf->Cell(27, 5, number_format(floatval($descuentoAdicional), 2), 1, 1, 'R');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(120, 5, 'Son: ' . ucfirst($letra), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(50, 5, 'TOTAL Bs.', 1, 0, 'R');
        $pdf->Cell(27, 5, number_format(floatval(($montoTotal)), 2), 1, 1, 'R');

        $pdf->Cell(120, 5, '', 0, 0, 'L');
        $pdf->Cell(50, 5, 'MONTO GIFT CARD Bs.', 1, 0, 'R');
        $pdf->Cell(27, 5, number_format(floatval(($montoGiftCard)), 2), 1, 1, 'R');

        $pdf->Cell(120, 5, '', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(50, 5, 'MONTO A PAGAR Bs.', 1, 0, 'R');
        $pdf->Cell(27, 5, number_format(floatval(($montoTotal)), 2), 1, 1, 'R');

        $pdf->Cell(120, 5, '', 0, 0, 'L');
        $pdf->Cell(50, 5, utf8_decode('IMPORTE BASE CRÉDITO FISCAL'), 1, 0, 'R');
        $pdf->Cell(27, 5, number_format(floatval(($montoTotal)), 2), 1, 1, 'R');
        -
        $pdf->Ln(10);
        $y = $pdf->GetY();
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(170, 5, utf8_decode('ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS, EL USO ILÍCITO SERÁ SANCIONADO PENALMENTE DE ACUERDO A LEY'), 0, 1, 'C');
        $pdf->Image(public_path('qr/qrcode.png'), 182, $y - 3, 25, 'PNG');

        $pdf->Ln(4);
        $pdf->Cell(170, 5, utf8_decode($leyenda), 0, 1, 'C');

        $pdf->Ln(2);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(170, 5, utf8_decode('"Este documento es la Representación Gráfica de un Documento Fiscal Digital emitido fuera de línea, verifique su envío con su proveedor o en la página web www.impuestos.gob.bo"'), 0, 1, 'C');

        $pdf->Output(public_path('docs/facturaCarta.pdf'), 'F');

        $pdfPath = public_path('docs/facturaCarta.pdf');
        $xmlPath = public_path("docs/temporal/facturaxml" . $fecha_formato . ".xml");
        

        \Mail::to($correo)->send(new \App\Mail\FacturaElectrónica($xmlPath, $pdfPath));

        return response()->download(public_path('docs/facturaCarta.pdf'));
    }

    public function imprimirFacturaRolloOffline($id, $correo)
    {
        $user = Auth::user();
        $codigoPuntoVenta = '';
        if (!empty($user->idpuntoventa)) {
            $puntoVenta = PuntoVenta::find($user->idpuntoventa);
            if ($puntoVenta) {
                $codigoPuntoVenta = $puntoVenta->codigoPuntoVenta;
            }
        }

        //$puntoVenta = $user->idpuntoventa;
        $puntoVenta = $codigoPuntoVenta;

        /*$facturas = FacturaFueraLinea::join('personas', 'factura_fuera_lineas.idcliente', '=', 'personas.id')
            ->select('factura_fuera_lineas.*', 'personas.nombre as razonSocial', 'personas.email as email', 'personas.num_documento as documentoid', 'personas.complemento_id as complementoid')
            ->where('factura_fuera_lineas.id', '=', $id)
            ->orderBy('factura_fuera_lineas.id', 'desc')->paginate(3);*/

        $facturas = FacturaFueraLinea::join('ventas', 'factura_fuera_lineas.idventa', '=', 'ventas.id')
        ->select('factura_fuera_lineas.*','ventas.cliente as razonSocial', 'ventas.documento as documentoid')
        ->where('factura_fuera_lineas.id', '=', $id)
        ->orderBy('factura_fuera_lineas.id', 'desc')->paginate(3);    

        Log::info('Resultado', [
            //'facturas' => $facturas,
            'idFactura' => $id,
        ]);

        $xml = $facturas[0]->productos;
        $archivoXML = new SimpleXMLElement($xml);
        $nitEmisor = $archivoXML->cabecera[0]->nitEmisor;
        $numeroFactura = str_pad($archivoXML->cabecera[0]->numeroFactura, 5, "0", STR_PAD_LEFT);
        $cuf = $archivoXML->cabecera[0]->cuf;
        $direccion = $archivoXML->cabecera[0]->direccion;
        $telefono = $archivoXML->cabecera[0]->telefono;
        $municipio = $archivoXML->cabecera[0]->municipio;
        $fechaEmision = $archivoXML->cabecera[0]->fechaEmision;
        $fechaFormateada = date("d/m/Y h:i A", strtotime($fechaEmision));
        $fecha_formato = str_replace("T", "", $fechaEmision);
        $fecha_formato = str_replace("-", "", $fecha_formato);
        $fecha_formato = str_replace(":", "", $fecha_formato);
        $fecha_formato = str_replace(".", "", $fecha_formato);
        $documentoid = $archivoXML->cabecera[0]->numeroDocumento;
        $razonSocial = $archivoXML->cabecera[0]->nombreRazonSocial;
        $codigoCliente = $archivoXML->cabecera[0]->codigoCliente;
        $montoTotal1 = $archivoXML->cabecera[0]->montoTotal;
        $montoGiftCard = $archivoXML->cabecera[0]->montoGiftCard;
        $descuentoAdicional = $archivoXML->cabecera[0]->descuentoAdicional;
        $leyenda = $archivoXML->cabecera[0]->leyenda;
        $complementoid = $archivoXML->cabecera[0]->complemento;

        $montoTotal = ($montoTotal1-$montoGiftCard);
        $totalpagar = number_format(floatval($montoTotal), 2);
        $totalpagar = str_replace(',', '', $totalpagar);
        $totalpagar = str_replace('.', ',', $totalpagar);
        $cifrasEnLetras = new CifrasEnLetrasController();
        $letra = ($cifrasEnLetras->convertirBolivianosEnLetras($totalpagar));


        $url = 'https://pilotosiat.impuestos.gob.bo/consulta/QR?nit=' . $nitEmisor . '&cuf=' . $cuf . '&numero=' . $numeroFactura . '&t=2';
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'imageBase64' => false,
            'scale' => 10,
        ]);
        $qrCode = new QRCode($options);
        $qrCode->render($url, public_path('qr/qrcode.png'));

        //$pdf = new FPDF('P', 'mm', array(80, 0));
        $pdf = new FPDF('P', 'mm', array(80, 250));
        //$pdf = new FPDF();

        $pdf->SetAutoPageBreak(true, 10);
        $pdf->SetMargins(10, 10);
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(0, 3, 'FACTURA', 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(0, 3, utf8_decode('CON DERECHO A CRÉDITO FISCAL'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(0, 3, utf8_decode('365 SOFT'), 0, 1, 'C');
        $pdf->Cell(0, 3, utf8_decode('Casa Matriz'), 0, 1, 'C');
        $pdf->Cell(0, 3, utf8_decode('No. Punto de Venta '.$puntoVenta), 0, 1, 'C');

        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCell(0, 3, utf8_decode($direccion), 0, 'C');

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(0, 3, utf8_decode('Tel. ' . $telefono), 0, 1, 'C');
        $pdf->Cell(0, 3, utf8_decode($municipio), 0, 1, 'C');

        $y = $pdf->GetY();
        $pdf->SetY($y + 2);
        $pdf->SetLineWidth(0.2);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(0, 3, '', 'T', 1, 'C');

        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(0, 3, 'NIT', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 6);
        //$pdf->Cell(0, 3, utf8_decode($documentoid."-".$complementoid), 0, 1, 'C');
        $pdf->Cell(0, 3, utf8_decode($documentoid), 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(0, 3, utf8_decode('FACTURA N°'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(0, 3, utf8_decode($numeroFactura), 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(0, 3, utf8_decode('CÓD. AUTORIZACIÓN'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCell(0, 3, utf8_decode($cuf), 0, 'C');

        $y = $pdf->GetY();
        $pdf->SetY($y + 2);
        $pdf->SetLineWidth(0.2);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(0, 3, '', 'T', 1, 'C');

        $spacing = 2;

        $pdf->SetX(($pdf->GetPageWidth() - $pdf->GetStringWidth('NOMBRE/RAZON SOCIAL:') - $pdf->GetStringWidth($razonSocial)) / 2);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(10, 3, 'NOMBRE/RAZON SOCIAL:', 0, 0, 'C');
        $pdf->SetX($pdf->GetX() + $spacing);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(0, 3, utf8_decode($razonSocial), 0, 1, 'C');

        $spacingBetweenColumns = 10;
        $pdf->SetX(($pdf->GetPageWidth() - $pdf->GetStringWidth('NIT/CI/CEX:') - $pdf->GetStringWidth($documentoid)) / 2);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(2.5, 3, 'NIT/CI/CEX:', 0, 0, 'C');
        $pdf->SetX($pdf->GetX() + $spacingBetweenColumns);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(5.5, 3, utf8_decode($documentoid), 0, 1, 'C');

        $spacingBetweenColumns = 10;
        $pdf->SetX(($pdf->GetPageWidth() - $pdf->GetStringWidth('COD. CLIENTE:') - $pdf->GetStringWidth($codigoCliente)) / 2);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(2.5, 3, 'COD. CLIENTE:', 0, 0, 'C');
        $pdf->SetX($pdf->GetX() + $spacingBetweenColumns);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(9, 3, utf8_decode($codigoCliente), 0, 1, 'C');

        $spacingBetweenColumns = 10;
        $pdf->SetX(($pdf->GetPageWidth() - $pdf->GetStringWidth('FECHA DE EMISIÓN:') - $pdf->GetStringWidth($fechaEmision)) / 2);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(21.5, 3, utf8_decode('FECHA DE EMISIÓN:'), 0, 0, 'C');
        $pdf->SetX($pdf->GetX() + $spacingBetweenColumns);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(10, 3, utf8_decode($fechaFormateada), 0, 1, 'C');

        $y = $pdf->GetY();
        $pdf->SetY($y + 2);
        $pdf->SetLineWidth(0.2);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(0, 3, '', 'T', 1, 'C');

        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(0, 3, 'DETALLE', 0, 1, 'C');

        $detalle = $archivoXML->detalle;
        $sumaSubTotales = 0.0;
        foreach ($detalle as $p) {
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(0, 3, $p->codigoProducto . " - " . $p->descripcion, 0, 1, 'L');

            $medida = $p->unidadMedida;
            $nombreMedida = Medida::where('codigoClasificador', $medida)->value('descripcion_medida');

            $pdf->SetFont('Arial', '', 6);
            $pdf->Cell(0, 3, "Unidad de Medida: " . $nombreMedida, 0, 1, 'L');
            $pdf->Cell(0, 3, number_format(floatval($p->cantidad), 2) . " X " . number_format(floatval($p->precioUnitario), 2) . " - " . number_format(floatval($p->montoDescuento), 2), 0, 0, 'L');
            $pdf->Cell(0, 3, number_format(floatval($p->subTotal), 2), 0, 1, 'R');

            $sumaSubTotales += floatval($p->subTotal);
        }

        $y = $pdf->GetY();
        $pdf->SetY($y + 2);
        $pdf->SetLineWidth(0.2);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(0, 3, '', 'T', 1, 'C');

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(0, 3, 'SUBTOTAL Bs', 0, 0, 'C');
        $pdf->Cell(0, 3, number_format(floatval($sumaSubTotales), 2), 0, 1, 'R');
        $pdf->Cell(0, 3, 'DESCUENTO Bs', 0, 0, 'C');
        $pdf->Cell(0, 3, number_format(floatval($descuentoAdicional), 2), 0, 1, 'R');
        $pdf->Cell(0, 3, 'TOTAL Bs', 0, 0, 'C');
        $pdf->Cell(0, 3, number_format(floatval($montoTotal), 2), 0, 1, 'R');
        $pdf->Cell(0, 3, 'MONTO GIFT CARD Bs', 0, 0, 'C');
        $pdf->Cell(0, 3, number_format(floatval($montoGiftCard), 2), 0, 1, 'R');
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(0, 3, 'MONTO A PAGAR Bs', 0, 0, 'C');
        $pdf->Cell(0, 3, number_format(floatval($montoTotal), 2), 0, 1, 'R');
        $pdf->SetFont('Arial', 'B', 5);
        $pdf->Cell(0, 3, utf8_decode('IMPORTE BASE CRÉDITO FISCAL Bs'), 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(0, 3, number_format(floatval($montoTotal), 2), 0, 1, 'R');
        $pdf->Ln(6);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(0, 3, 'Son: ' . $letra, 0, 1, 'L');

        $y = $pdf->GetY();
        $pdf->SetY($y + 2);
        $pdf->SetLineWidth(0.2);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(0, 3, '', 'T', 1, 'C');

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(0, 3, utf8_decode('ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS,'), 0, 1, 'C');
        $pdf->Cell(0, 3, utf8_decode('EL USO ILÍCITO SERÁ SANCIONADO PENALMENTE DE'), 0, 1, 'C');
        $pdf->Cell(0, 3, utf8_decode('ACUERDO A LA LEY'), 0, 1, 'C');
        $pdf->Ln(3);
        $pdf->SetFont('Arial', '', 5);
        $pdf->MultiCell(0, 3, utf8_decode($leyenda), 0, 'C');
        $pdf->Ln(3);
        $pdf->Cell(0, 3, utf8_decode('Este documento es la Representación Gráfica de un'), 0, 1, 'C');
        $pdf->Cell(0, 3, utf8_decode('Documento Fiscal Digital emitido fuera de línea,'), 0, 1, 'C');
        $pdf->Cell(0, 3, utf8_decode('verifique su envío con su proveedor o en la página web www.impuestos.gob.bo'), 0, 1, 'C');
        $pdf->Ln(3);

        $textY = $pdf->GetY();

        $imageWidth = 25;
        $pageWidth = $pdf->GetPageWidth();
        $imageX = ($pageWidth - $imageWidth) / 2;
        $pdf->Image(public_path('qr/qrcode.png'), $imageX, $textY + 3, $imageWidth, 0, 'PNG');

        $pdf->Output(public_path('docs/facturaRollo.pdf'), 'F');

        $pdfPath = public_path('docs/facturaRollo.pdf');
        $xmlPath = public_path("docs/temporal/facturaxml" . $fecha_formato . ".xml");

        \Mail::to($correo)->send(new \App\Mail\FacturaElectrónica($xmlPath, $pdfPath));

        return response()->download(public_path('docs/facturaRollo.pdf'));
    }

    public function selectRoles(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');
        $roles = Rol::where('condicion', '=', '1')
            ->select('id', 'nombre')->orderBy('nombre', 'asc')->get();
        return ['roles' => $roles];
    }

    public function reporteVentasDiarias(Request $request)
    {
        // Validar la presencia de la fecha en la solicitud
        $request->validate([
            'fecha' => 'required|date',
        ]);

        // Obtener las ventas para la fecha dada
        $query = DetalleVenta::join('ventas', 'detalle_ventas.idventa', '=', 'ventas.id')
            ->join('menu', 'detalle_ventas.codigoComida', '=', 'menu.codigo')
            ->select(
                'menu.nombre as articulo',
                'ventas.cliente as cliente',
                'detalle_ventas.cantidad',
                'detalle_ventas.precio',
                'detalle_ventas.descuento',
                'ventas.num_comprobante'
            )
            ->whereDate('ventas.created_at', $request->input('fecha'));

        if ($request->has('idCategoria') && $request->input('idCategoria') !== 'all') {
            $query->where('menu.idcategoria_menu', $request->input('idCategoria'));
        }

        $ventas = $query->get();

        if ($ventas->isEmpty()) {
            return response()->json(['mensaje' => 'Ninguna Venta Realizada en la Fecha Indicada']);
        }

        //$totalGanado = $ventas->sum('total');

        // Devolver las ventas como JSON
        return response()->json([
            'ventas' => $ventas
            //'totalGanado' => $totalGanado
        ]);
    }

}
