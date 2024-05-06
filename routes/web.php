<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['guest']], function () {
    Route::get('/', 'Auth\LoginController@showLoginForm');
    Route::post('/', 'Auth\LoginController@login')->name('login');
});

Route::group(['middleware' => ['auth']], function () {

    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('/dashboard', 'DashboardController');
    //Notificaciones
    Route::post('/notification/get', 'NotificationController@get');

    Route::get('/main', function () {
        return view('contenido/contenido');
    })->name('main');

    Route::group(['middleware' => ['Almacenero']], function () {
        Route::get('/categoria', 'CategoriaController@index');
        Route::post('/categoria/registrar', 'CategoriaController@store');
        Route::put('/categoria/actualizar', 'CategoriaController@update');
        Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
        Route::put('/categoria/activar', 'CategoriaController@activar');
        Route::get('/categoria/selectCategoria', 'CategoriaController@selectCategoria');

        Route::get('/articulo', 'ArticuloController@index');
        Route::post('/articulo/registrar', 'ArticuloController@store');
        Route::put('/articulo/actualizar', 'ArticuloController@update');
        Route::put('/articulo/desactivar', 'ArticuloController@desactivar');
        Route::put('/articulo/activar', 'ArticuloController@activar');
        Route::get('/articulo/buscarArticulo', 'ArticuloController@buscarArticulo');
        Route::get('/articulo/listarArticulo', 'ArticuloController@listarArticulo');
        Route::get('/articulo/listarPdf', 'ArticuloController@listarPdf')->name('articulos_pdf');


        Route::get('/proveedor', 'ProveedorController@index');
        Route::post('/proveedor/registrar', 'ProveedorController@store');
        Route::put('/proveedor/actualizar', 'ProveedorController@update');
        Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');

        Route::get('/ingreso', 'IngresoController@index');
        Route::post('/ingreso/registrar', 'IngresoController@store');
        Route::put('/ingreso/desactivar', 'IngresoController@desactivar');
        Route::get('/ingreso/obtenerCabecera', 'IngresoController@obtenerCabecera');
        Route::get('/ingreso/obtenerDetalles', 'IngresoController@obtenerDetalles');
    });

    Route::group(['middleware' => ['Vendedor']], function () {
        Route::get('/cliente', 'ClienteController@index');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::put('/cliente/actualizar', 'ClienteController@update');
        Route::get('/cliente/selectCliente', 'ClienteController@selectCliente');

        Route::get('/articulo/buscarArticuloVenta', 'ArticuloController@buscarArticuloVenta');
        Route::get('/articulo/listarArticuloVenta', 'ArticuloController@listarArticuloVenta');

        Route::get('/venta', 'VentaController@index');
        Route::get('/ventaBuscar', 'VentaController@indexBuscar');
        Route::post('/venta/registrar', 'VentaController@store');
        Route::put('/venta/desactivar', 'VentaController@desactivar');
        Route::get('/venta/obtenerCabecera', 'VentaController@obtenerCabecera');
        Route::get('/venta/obtenerDetalles', 'VentaController@obtenerDetalles');
        Route::get('/venta/pdf/{id}', 'VentaController@pdf')->name('venta_pdf');
        Route::get('/venta/imprimir/{id}', 'VentaController@imprimirTicket');
    });

    Route::group(['middleware' => ['Administrador']], function () {

        Route::get('/empresa', 'EmpresaController@index');
        //Route::post('/empresa/registrar', 'EmpresaController@store');
        Route::put('/empresa/actualizar', 'EmpresaController@update');
        // Route::put('/empresa/desactivar', 'EmpresaController@desactivar');
        // Route::put('/empresa/activar', 'EmpresaController@activar');
        Route::get('/empresa/selectEmpresa', 'EmpresaController@selectEmpresa');

        //Rutas de configuracion de trabajo
        Route::get('/configuracion/saldos-negativos', 'ConfiguracionTrabajoController@obtenerSaldosNegativos');
        Route::get('/configuracion/iva', 'ConfiguracionTrabajoController@obtenerIva');
        Route::get('/configuracion/editar', 'ConfiguracionTrabajoController@edit');
        Route::put('/configuracion/actualizar', 'ConfiguracionTrabajoController@update');
        Route::get('/backup', 'BackupDbController@createBackup');
        Route::get('/categoria', 'CategoriaController@index');
        Route::post('/categoria/registrar', 'CategoriaController@store');
        Route::put('/categoria/actualizar', 'CategoriaController@update');
        Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
        Route::put('/categoria/activar', 'CategoriaController@activar');
        Route::get('/categoria/selectCategoria', 'CategoriaController@selectCategoria');


        Route::get('/marca', 'MarcaController@index');
        Route::post('/marca/registrar', 'MarcaController@store');
        Route::put('/marca/actualizar', 'MarcaController@update');
        Route::put('/marca/desactivar', 'MarcaController@desactivar');
        Route::put('/marca/activar', 'MarcaController@activar');


        Route::get('/articulo', 'ArticuloController@index');
        Route::post('/articulo/registrar', 'ArticuloController@store');
        Route::post('/articulo/actualizar', 'ArticuloController@update');
        Route::put('/articulo/desactivar', 'ArticuloController@desactivar');
        Route::put('/articulo/activar', 'ArticuloController@activar');
        Route::get('/articulo/buscarArticulo', 'ArticuloController@buscarArticulo');
        Route::get('/articulo/listarArticulo', 'ArticuloController@listarArticulo');
        Route::get('/articulo/listarArticuloVenta', 'ArticuloController@listarArticuloVenta');
        Route::get('/articulo/buscarArticuloVenta', 'ArticuloController@buscarArticuloVenta');
        Route::get('/articulo/listarPdf', 'ArticuloController@listarPdf')->name('articulos_pdf');
        Route::get('/articulo/listarArticuloPedido', 'ArticuloController@listPedProve'); //aumente esto 21 sept

        Route::post('/menu/registrar', 'MenuController@create');
        Route::get('/menu', 'MenuController@index');
        Route::get('/menu/getAllMenu', 'MenuController@getAllMenu');
        Route::post('/menu/actualizar', 'MenuController@update');
        Route::put('/menu/actualizar', 'MenuController@update');
        Route::put('/menu/desactivar', 'MenuController@desactivar');

        Route::get('/user/selectUser/filter', 'UserController@selectUsuarios');
        Route::get('/sucursal/selectedSucursal/filter', 'SucursalController@selectedSucursal');




        Route::get('/categoriamenu', 'CategoriaMenuController@index');
        Route::post('/categoriamenu/registrar', 'CategoriaMenuController@create');
        Route::post('/categoriamenuPrincipal/registrar', 'CategoriaMenuController@createCategoria');
        Route::put('/categoriamenu/actualizar', 'CategoriaMenuController@update');
        Route::put('/categoriamenu/desactivar', 'CategoriaMenuController@desactivar');
        Route::put('/categoriamenu/activar', 'CategoriaMenuController@activar');
        Route::get('/categorias_menu/getAll', 'CategoriaMenuController@getAllCatogoriasMenu');





        Route::get('/proveedor', 'ProveedorController@index');
        Route::post('/proveedor/registrar', 'ProveedorController@store');
        Route::put('/proveedor/actualizar', 'ProveedorController@update');
        Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');

        Route::get('/cliente', 'ClienteController@index');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::put('/cliente/actualizar', 'ClienteController@update');
        Route::get('/cliente/selectCliente', 'ClienteController@selectCliente');
        Route::get('/cliente/listarReporteClienteExcel', 'ClienteController@listarReporteClienteExcel');
        Route::put('/cliente/desactivarCli', 'ClienteController@desactivarCli');
        Route::put('/cliente/activarCli', 'ClienteController@activarCli');

        Route::get('/venta', 'VentaController@index');
        Route::post('/venta/registrar', 'VentaController@store');
        Route::put('/venta/desactivar', 'VentaController@desactivar');
        Route::get('/venta/obtenerCabecera', 'VentaController@obtenerCabecera');
        Route::get('/venta/obtenerDetalles', 'VentaController@obtenerDetalles');
        Route::get('/venta/pdf/{id}', 'VentaController@pdf')->name('venta_pdf');

        //new
        Route::post('/venta/verificarComunicacion', 'VentaController@verificarComunicacion');
        Route::post('/venta/cuis', 'VentaController@cuis');
        Route::post('/venta/cufd', 'VentaController@cufd');
        Route::post('/venta/emitirFactura', 'VentaController@emitirFactura');
        Route::post('/venta/insertarFactura', 'VentaController@insertarFactura');
        Route::post('/venta/paqueteFactura', 'VentaController@paqueteFactura');
        Route::post('/venta/enviarPaquete', 'VentaController@enviarPaquete');
        Route::post('/venta/validarPaquete', 'VentaController@validacionRecepcionPaqueteFactura');
        //credito_venta
        Route::get('/credito', 'CreditoVentaController@index');
        Route::post('/credito/registrar', 'CreditoVentaController@store');
        Route::put('/credito/actualizar', 'CreditoVentaController@update');
        Route::get('/credito/eliminar', 'CreditoVentaController@destroy');
        // cuota_credito
        Route::get('/cuota', 'CuotasCreditoController@index');
        Route::post('/cuota/registrar', 'CuotasCreditoController@store');
        Route::put('/cuota/actualizar', 'CuotasCreditoController@update');
        Route::get('/cuota/eliminar', 'CuotasCreditoController@destroy');


        // cotizacionventa
        Route::get('/cotizacionventa', 'CotizacionVentaController@index');
        Route::post('/cotizacionventa/registrar', 'CotizacionVentaController@store');
        Route::put('/cotizacionventa/desactivar', 'CotizacionVentaController@desactivar');
        Route::get('/cotizacionventa/obtenerCabecera', 'CotizacionVentaController@obtenerCabecera');
        Route::get('/cotizacionventa/obtenerDetalles', 'CotizacionVentaController@obtenerDetalles');
        Route::get('/cotizacionventa/pdf/{id}', 'CotizacionVentaController@pdf')->name('venta_pdf');
        Route::put('/cotizacionventa/activar', 'CotizacionVentaController@activar');
        Route::put('/cotizacionventa/eliminar', 'CotizacionVentaController@delete');

        Route::get('/rol', 'RolController@index');
        Route::get('/rol/selectRol', 'RolController@selectRol');

        Route::get('/user', 'UserController@index');
        Route::post('/user/registrar', 'UserController@store');
        Route::post('/user/actualizar', 'UserController@update');
        Route::put('/user/desactivar', 'UserController@desactivar');
        Route::put('/user/activar', 'UserController@activar');
        Route::get('/user/listarReporteUsuariosExcel', 'UserController@listarReporteUsuariosExcel');

        //Rura para que el usuario pueda editar su perfil
        Route::get('/user/editarpersona', 'UserController@editarPersona');
        //Route::put('/editarperfil', 'UserController@editarPerfil');

        Route::get('/ingreso', 'IngresoController@index');
        Route::post('/ingreso/registrar', 'IngresoController@store');
        Route::put('/ingreso/desactivar', 'IngresoController@desactivar');
        Route::get('/ingreso/obtenerCabecera', 'IngresoController@obtenerCabecera');
        Route::get('/ingreso/obtenerDetalles', 'IngresoController@obtenerDetalles');

        //------sucursales
        //Lisar sucursal
        Route::get('/sucursal', 'SucursalController@index');
        //---desactivar registro
        Route::post('/sucursal/registrar', 'SucursalController@store');

        Route::put('/sucursal/activar', 'SucursalController@activar');
        Route::put('/sucursal/desactivar', 'SucursalController@desactivar');
        // actualizar 
        Route::put('/sucursal/actualizar', 'SucursalController@update');
        Route::get('/sucursal/selectSucursal', 'SucursalController@selectSucursal');

        //Puntos de Venta
        Route::get('/puntoVenta', 'PuntoVentaController@index');
        Route::get('/puntoVenta/obtenerDatosTipoPuntoVenta', 'PuntoVentaController@obtenerDatosTipoPuntoVenta');
        Route::get('/puntoVenta/obtenerDatosSucursal', 'PuntoVentaController@obtenerDatosSucursal');
        Route::post('/puntoVenta/registrar', 'PuntoVentaController@store');
        Route::post('/puntoVenta/habilitar', 'VentaController@registroPuntoVenta');
        Route::post('/puntoVenta/cerrar', 'VentaController@cierrePuntoVenta');
        Route::put('/puntoVenta/cambioEstado', 'PuntoVentaController@cambioEstado');

        //Listar Moneda
        Route::get('/moneda', 'MonedaController@index');
        Route::post('/moneda/registrar', 'MonedaController@store');
        Route::put('/moneda/activar', 'MonedaController@activar');
        Route::put('/moneda/desactivar', 'MonedaController@desactivar');
        Route::put('/moneda/actualizar', 'MonedaController@update');

        //APERTURA/CIERRE CAJA
        //Listar
        Route::get('/caja', 'CajaController@index');
        Route::post('/caja/registrar', 'CajaController@store');
        Route::put('/caja/depositar', 'CajaController@depositar');
        Route::put('/caja/retirar', 'CajaController@retirar');
        Route::get('/caja/transacciones', 'CajaController@transacciones');
        Route::post('/caja/arqueoCaja', 'CajaController@arqueoCaja');
        Route::put('/caja/cerrar', 'CajaController@cerrar');

        //TRANSACCIONES CAJA
        Route::get('/transacciones/{id}', 'TransaccionesCajaController@index');

        //ARQUEO CAJA
        Route::post('/arqueoCaja/registrar', 'ArqueoCajaController@store');

        //FACTURAS
        Route::get('/factura', 'SiatController@index');
        Route::get('/factura/getFactura/{id}', 'SiatController@getFactura');
        Route::get('/factura/imprimir/{id}', 'VentaController@imprimirFactura');
        Route::get('/factura/anular/{cuf}/{motivoSeleccionado}', 'VentaController@anulacionFactura');
        Route::get('/factura/sincronizarActividades', 'VentaController@sincronizarActividades');
        Route::get('/factura/sincronizarParametricaTiposFactura', 'VentaController@sincronizarParametricaTiposFactura');
        Route::get('/factura/sincronizarListaLeyendasFactura', 'VentaController@sincronizarListaLeyendasFactura');
        Route::get('/factura/sincronizarListaProductosServicios', 'VentaController@sincronizarListaProductosServicios');
        Route::get('/factura/sincronizarParametricaMotivoAnulacion', 'VentaController@sincronizarParametricaMotivoAnulacion');
        Route::get('/factura/sincronizarParametricaEventosSignificativos', 'VentaController@sincronizarParametricaEventosSignificativos');
        Route::get('/factura/sincronizarParametricaUnidadMedida', 'VentaController@sincronizarParametricaUnidadMedida');
        Route::get('/factura/obtenerDatosMotivoAnulacion', 'FacturaController@obtenerDatosMotivoAnulacion');


        //--INDUSTRIA--
        //registrar
        Route::post('/industria/registrar', 'IndustriaController@store');
        Route::get('/industria', 'IndustriaController@index');
        Route::put('/industria/activar', 'IndustriaController@activar');
        Route::put('/industria/desactivar', 'IndustriaController@desactivar');
        Route::put('/industria/actualizar', 'IndustriaController@update');

        //MEDIDAS
        Route::get('/medida', 'MedidaController@index');
        Route::post('/medida/registrar', 'MedidaController@store');
        Route::put('/medida/actualizar', 'MedidaController@update');
        Route::put('/medida/desactivar', 'MedidaController@desactivar');
        Route::put('/medida/activar', 'MedidaController@activar');
        Route::get('/medida/selectCategoria', 'MedidaController@selectMedida');

        //Obtener último numero de comprobante
        Route::get('/ruta-a-tu-endpoint-laravel-para-obtener-ultimo-comprobante', 'VentaController@obtenerUltimoComprobante');

        //Obtener último numero de codigoSucursal
        Route::get('/ruta-api-para-obtener-ultimo-codigo-sucursal', 'SucursalController@obtenerUltimoCodigoSucursal');

        //Obtener la sesion guardada de Codigo Evento
        Route::get('/obtener-datos-sesion', 'EventosSignificativosController@obtenerDatosSesion');

        Route::get('/backup', 'BackupDbController@createBackup');


        //grupo
        Route::get('/grupos', 'GrupoController@index');
        Route::post('/grupos/registrar', 'GrupoController@store');
        Route::put('/grupos/actualizar', 'GrupoController@update');

        //precio
        Route::get('/precios', 'PrecioController@indexanctivo');
        Route::get('/preciosactivos', 'PrecioController@indexactivo'); //activos
        Route::post('/precios/registrar', 'PrecioController@store');
        Route::put('/precios/{id}/{accion}', 'PrecioController@cambiarEstado');

        //ALMACENES
        Route::get('/almacen', 'AlmacenController@index');
        Route::post('/almacen/registrar', 'AlmacenController@store');
        Route::put('/almacen/editar', 'AlmacenController@update');

        Route::get('/almacen/selectAlmacen', 'AlmacenController@selectAlmacen');
        Route::get('/almacen/selectAlmacenDest', 'AlmacenController@selectAlmacenDestino');

        //inventarios
        //Route::get('/inventarios', 'InventarioController@index');
        Route::post('/inventarios/registrar', 'InventarioController@store');

        Route::get('/inventarios/productosporvencer', 'InventarioController@productosPorVencer');
        Route::get('/inventarios/productosvencidos', 'InventarioController@productosVencidos');
        Route::get('/inventarios/productosbajostock', 'InventarioController@productosBajoStock');
        Route::get('/inventarios/listarReportePorVencerExcel', 'InventarioController@listarReportePorVencerExcel');
        Route::get('/inventarios/listarReporteVencidosExcel', 'InventarioController@listarReporteVencidosExcel');
        Route::get('/inventarios/listarReporteBajoStockExcel', 'InventarioController@listarReporteBajoStockExcel');
        //listado para seleccionar producto En TRASPASO
        Route::get('/inventariosTraspaso', 'InventarioController@indextraspaso'); //listar en traspaso para seleccionar el arTiculo de invenTario
        Route::get('/inventarios/itemLote/{tipo}', 'InventarioController@indexItemLote'); //listato por filtro
        //saldostosk
        Route::get('/inventarios/saldostock', 'InventarioController@indexsaldostock'); //listar el saldo_stock

        //traspaso
        Route::get('/list/traspasos', 'TraspasoController@index');
        Route::post('/traspaso/registrar', 'TraspasoController@store');
        Route::get('/traspaso/obtenerTraspaso', 'TraspasoController@indexPorID');

        //Eventos Significativos
        Route::get('/eventos', 'EventosSignificativosController@index');
        Route::get('/eventos/obtenerDatosMotivoEvento', 'EventosSignificativosController@obtenerDatosMotivoEvento');
        Route::post('/eventos/registrar', 'EventosSignificativosController@store');
        Route::put('/eventos/finalizarEvento', 'EventosSignificativosController@finalizarEvento');
        Route::put('/eventos/errorEvento', 'EventosSignificativosController@errorEvento');
        Route::put('/eventos/cambioEstadoEvento', 'EventosSignificativosController@cambioEstadoEvento');
        Route::post('/factura/eventosSignificativos', 'VentaController@registroEventoSignificativo');

        //PEDIDO A PROVEEDOR--
        Route::post('/registrar/pedidoprovee', 'PedidoProvController@store')->name('PEDIDO');
        Route::get('/pedidoProveedor', 'PedidoProvController@indexpedido');
        Route::get('/pedido/obtPediPrv', 'PedidoProvController@indexPedProv');
        Route::put('/editar/pedidoprovee', 'PedidoProvController@editar');
        Route::delete('/pedido/proveedor', 'PedidoProvController@eliminar');
        //-----seleccionar usuario , roles REGISTROS DE VENTAS
        Route::get('/roles/selectRoles', 'VentaController@selectRoles');

        //REPORTES
        Route::get('/ventas-diarias', 'VentaController@reporteVentasDiarias');
        Route::post('/qr/generarqr', 'QrController@generarQr');
        Route::post('/qr/verificarestado', 'QrController@verificarEstado');
        
    });

    //RUTA PARA RECUPERAR LA SESSION CON EL ID DE LA PERSONA LOGUEADA
    Route::get('/api/session', function () {
        return response()->json([
            'id' => session('id')
        ]);
    });
});

//Route::get('/home', 'HomeController@index')->name('home');
