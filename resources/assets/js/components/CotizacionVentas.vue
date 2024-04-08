<template>
    <main class="main">
        <!-- Breadcrumb -->
        <!-- <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol> -->
  <div class="m-2 p-2">
    
  </div>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header" v-if="listado==1" >
                    <i class="fa fa-align-justify"></i> Cotización ventas
                    <button type="button" @click="mostrarDetalle('REGISTRAR COTIZACIÒN',0)" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
 
                <div class="card-header" v-if="listado!=1 ">
                    <i class="icon-basket"></i><label v-text="titulocard"></label>
                </div>
                <!-- Listado-->
                <template v-if="listado == 1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                        <option value="tipo_comprobante">Tipo Comprobante</option>
                                        <option value="num_comprobante">Número Comprobante</option>
                                        <option value="fecha_hora">Fecha-Hora</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup="listarCotizacion(1, buscar, criterio)"
                                        class="form-control" placeholder="Texto a buscar">
                                    <!--button type="submit" @click="listarCotizacion(1, buscar, criterio)" class="btn btn-primary"><i
                                            class="fa fa-search"></i> Buscar</button-->
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>ID</th>

                                        <th>FECHA</th>
                                        <th>HORA</th>

                                        <th>CLIENTE</th>
                                        <th>NIT/CI</th>

                                        <th>IMPUESTO</th>
                                        <th>TOTAL</th>
                                        <th>USUARIO</th>
                                        <th>EXPIRA EN</th>
                                        <th>NOTA/REF</th>
                                        <th class="fit-content">ACCIONES</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="venta in arrayVenta" :key="venta.id">
                                        <td v-text="venta.id"></td>
                                        
                                        <td>{{ new Intl.DateTimeFormat('es-ES').format(new Date(venta.fecha_hora)) }}</td>
                                        <td>{{ new Date(venta.fecha_hora).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) }}</td>
                                        <td v-text="venta.nombre"></td>
                                        <td v-text="venta.num_documento"></td>
                                        <td v-text="venta.impuesto"></td>

                                        <td v-text="venta.total"></td>
                                        <td v-text="venta.usuario"></td>

                                        <td>{{ Math.floor((new Date(venta.validez) - new Date()) / (1000 * 60 * 60 * 24))+1 }} dias</td>

                                        <!-- <td>{{ new Date(venta.validez).toLocaleString() }} {{ new Date(venta.fecha_hora).toLocaleString() }} </td> -->


                                        <td v-text="venta.nota"></td>
                                        <td>
                                            <div class="d-flex justify-content-start align-items-center">
                                                <button type="button" @click="verVenta(venta.id)" class="btn btn-sm rounded">
                                                    <i class="icon-eye fa-lg" style="color: blue;"></i>

                                                </button>
                                                <button type="button" @click="pdfVenta(venta.id)" class="btn  btn-sm rounded">
                                                    <i class="icon-doc fa-lg" style="color: skyblue;"></i>

                                                </button>
                                                 
                                                <button type="button" @click="abrirVenta(venta.id)" class="btn btn-sm rounded">
                                                    <i class="icon-basket fa-lg" style="color: green;"></i>

                                                </button>
                                                <button type="button" @click="mostrarDetalle('EDITAR COTIZACIÒN',venta.id)" class="btn btn-sm rounded">
                                                    <i class="icon-pencil fa-lg" style="color: orange;"></i>

                                                </button>
                                                <template v-if="venta.condicion">
                                                    <button type="button" class="btn btn-sm rounded" @click="desactivarCotizacion(venta.id)">
                                                    <i class="icon-trash fa-lg" style="color: red;"></i>

                                                    </button>
                                                </template>
                                                <template v-else>
                                                    <button type="button" class="btn btn-sm rounded" @click="activarCotizacion(venta.id)">
                                                        <i class="icon-check"></i>
                                                    </button>
                                                </template>
                                            </div>
                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page"
                                    :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)"
                                        v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </template>
                <!--Fin Listado-->
                <!-- Registrar cotizacion-->
                <template v-else-if="listado == 0">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for=""><b>CLIENTE</b>(*)</label>
                                    <v-select :on-search="selectCliente" label="nombre" :options="arrayCliente" placeholder="Buscar Clientes..." :onChange="getDatosCliente">
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="">NIT/CI</label>
                                <input type="text" class="form-control" v-model="nitcliente" >
                            </div>
                            <div class="col-md-2">
                                <label for="">Impuesto(*)</label>
                                <input type="text" class="form-control" v-model="impuesto" ref="impuestoRef">
                                <label for=""  class="small-text">Shift + Q</label>
                            </div>
                           

                        </div>
                        <div class="form-group row border">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><b>ARTICULO</b> <span style="color: red;" v-show="idarticulo == 0">*</span></label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="codigo" ref="articuloRef" @keyup="buscarArticulo()" placeholder="Codigo del artículo">
                                        <button @click="abrirModal()" class="btn btn-primary">...</button>
                                        <input type="text" readonly class="form-control" v-model="articulo">
                                        <label for="" class="small-text">Shift + R</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Precio <span style="color: red;" v-show="precio==0">*</span></label>
                                    <input disabled type="number" value="0" step="any" class="form-control" v-model="precio" ref="precioRef">
                                    <label for="" class="small-text">Shift + T</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cantidad <span style="color: red;" v-show="cantidad==0">*</span></label>
                                    <input type="number" value="0" class="form-control" v-model="cantidad" ref="cantidadRef">
                                    <label for="" class="small-text">Shift + Y</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Descuento</label>
                                    <input type="number" value="0" class="form-control" v-model="descuento" ref="descuentoRef">
                                    <label for="" class="small-text">Shift + U</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row border">
                            
                    
                            <div class="table-responsive col-md-12">
                                <h6 class="text-center" style="font-weight: normal;"> DETALLE DE LA COTIZACIÒN</h6>
                                <table class="table table-bordered table-striped table-sm">
                                    
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Artículo</th>
                                            <th>Precio/U</th>
                                            <th>Cantidad</th>
                                            <th>Descuento</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="(detalle, index) in arrayDetalle" :key="detalle.id">
                                            <td>
                                                <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td v-text="detalle.articulo"></td>
                                            <td>
                                                <input disabled v-model="detalle.precio" type="number" class="form-control">
                                            </td>
                                            <td>
                                                <span style="color:red;" v-show="detalle.cantidad > detalle.stock">Stock: {{ detalle.stock }}</span>
                                                <input v-model="detalle.cantidad" type="number" class="form-control">
                                            </td>
                                            <td>
                                                <span style="color:red;" v-show="detalle.descuento > (detalle.precio * detalle.cantidad)">Descuento superior</span>
                                                <input v-model="detalle.descuento" type="number" class="form-control">
                                            </td>
                                            <td>{{ detalle.precio * detalle.cantidad - detalle.descuento }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            
                                            <td colspan="5" align="right"><strong>Total Parcial:</strong></td>
                                            <td>$ {{ totalParcial=(total - totalImpuesto).toFixed(2) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Total Impuesto:</strong></td>
                                            <td>$ {{ totalImpuesto=((total * impuesto) / (1 + impuesto)).toFixed(2) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Total Neto:</strong></td>
                                            <td>$ {{ total=calcularTotal }}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="6">No hay articulos agregados</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2">
                                <label for="">Dias de validez</label>
                                <input type="number" class="form-control" v-model="dias_validez" >
                            </div>
                            <div class="col-md-2">
                                <label for="">Tiempo de entrega</label>
                                <input type="text" class="form-control" v-model="tiempo_entrega" >
                            </div>
                            <div class="col-md-2">
                                <label for="">Lugar de entrega</label>
                                <input type="text" class="form-control" v-model="lugar_entrega" >
                            </div>
                            <div class="col-md-2">
                                <label for="">Forma de pago</label>
                                <input type="text" class="form-control" v-model="forma_pago" >
                            </div>
                            <div class="col-md-2">
                                <label for="">Nota</label>
                                <input type="text" class="form-control" v-model="nota" >
                            </div>
                            <div class="col-md-1.5">
                                <label for="my-selector">Imprimir:</label>
                                <select class="form-control" id="my-selector" v-model="imprimir">
                                <option value="">Ninguno</option>
                                <option v-for="option in options_print" :value="option.value" :key="option.value">{{ option.label }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12 text-right">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                                <button v-if="titulocard=='REGISTRAR COTIZACIÒN'" type="button" class="btn btn-primary" @click="registrarCotizacion()">Registrar Cotización</button>

                                <button v-else type="button" class="btn btn-primary" @click="registrarCotizacion()">Editar Cotización</button>
                            </div>
                        </div>

                    </div>

                </template>
                <!-- Fin registro-->
                <!--Ver DETALLEW-->
                <template v-else-if="listado == 2">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Cliente</label>
                                    <p v-text="cliente"></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">Impuesto</label>
                                <p v-text="impuesto"></p>
                            </div>
                            
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Artículo</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Descuento</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                            <td v-text="detalle.articulo">
                                            </td>
                                            <td v-text="detalle.precio">
                                            </td>
                                            <td v-text="detalle.cantidad">
                                            </td>
                                            <td v-text="detalle.descuento">
                                            </td>
                                            <td>
                                                {{ detalle.precio * detalle.cantidad - detalle.descuento }}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Parcial:</strong></td>
                                            <td>$ {{ totalParcial=(total - totalImpuesto).toFixed(2) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Impuesto:</strong></td>
                                            <td>$ {{ totalImpuesto=(total * impuesto).toFixed(2) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Neto:</strong></td>
                                            <td>$ {{ total }}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="5">
                                            <p>No hay artículos agregados</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </template>
                <!--Fin ver DETALLO-->
                <!-- INICIO REGISTRAR VENTA -->
                <template v-else-if="listado == 3">
                    <div class="card-body">
                        
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Cliente(*)</label>

                                    <v-select  :on-search="selectCliente" label="nombre" :options="arrayCliente" 
                                        placeholder="Buscar Clientes..." :onChange="getDatosCliente">
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">Impuesto(*)</label>
                                <input type="text" class="form-control" v-model="impuesto" ref="impuestoRef" :disabled="rolUsuario !== 1 && actualizarIva === 0">
                                <label for="">Shift + Q</label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Comprobante(*)</label>
                                    <select class="form-control" v-model="tipo_comprobante" ref="tipoComprobanteRef">
                                        <option value="0">Seleccione</option>
                                        <option value="BOLETA">Boleta</option>
                                        <option value="FACTURA">Factura</option>
                                        <option value="TICKET">Ticket</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie Comprobante</label>
                                    <input type="text" class="form-control" v-model="serie_comprobante" placeholder="000x" ref="serieComprobanteRef">
                                    <label for="">Shift + W</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número Comprobante(*)</label>
                                    <input type="text" class="form-control" v-model="num_comprobante" placeholder="000xx" ref="numeroComprobanteRef">
                                    <label for="">Shift + E</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div v-show="errorVenta" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjVenta" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Artículo <span style="color: red;" v-show="idarticulo == 0">(*Seleccione
                                            Articulo)</span></label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="codigo" ref="articuloRef"
                                            @keyup="buscarArticulo()" placeholder="Codigo del artículo">
                                        <button @click="abrirModal()" class="btn btn-primary">...</button>
                                        <input type="text" readonly class="form-control" v-model="articulo">
                                        <label for="">Shift + R</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Precio <span style="color: red;" v-show="precio==0">(*Ingrese Precio)</span></label>
                                    <input type="number" value="0" step="any" class="form-control" v-model="precio" ref="precioRef">
                                    <label for="">Shift + T</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cantidad <span style="color: red;" v-show="cantidad==0">(*Ingrese Cantidad)</span></label>
                                    <input type="number" value="0" class="form-control" v-model="cantidad" ref="cantidadRef">
                                    <label for="">Shift + Y</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Descuento</label>
                                    <input type="number" value="0" class="form-control" v-model="descuento" ref="descuentoRef">
                                    <label for="">Shift + U</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Artículo</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Descuento</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="(detalle, index) in arrayDetalle" :key="detalle.id">
                                            <td>
                                                <button @click="eliminarDetalle(index)" type="button"
                                                    class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td v-text="detalle.articulo">
                                            </td>
                                            <td>
                                                <input v-model="detalle.precio" type="number" class="form-control">
                                            </td>
                                            <td>
                                                <span style="color:red;" v-show="detalle.cantidad > detalle.stock">Stock:
                                                    {{ detalle.stock }}</span>
                                                <input v-model="detalle.cantidad" type="number" class="form-control">
                                            </td>
                                            <td>
                                                <span style="color:red;"
                                                    v-show="detalle.descuento > (detalle.precio * detalle.cantidad)">Descuento
                                                    superior</span>
                                                <input v-model="detalle.descuento" type="number" class="form-control">
                                            </td>
                                            <td>
                                                {{ detalle.precio * detalle.cantidad - detalle.descuento }}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Total Parcial:</strong></td>
                                            <td>$ {{ totalParcial=(total - totalImpuesto).toFixed(2) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Total Impuesto:</strong></td>
                                            <td>$ {{ totalImpuesto=((total * impuesto) / (1 + impuesto)).toFixed(2) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Total Neto:</strong></td>
                                            <td>$ {{ total=calcularTotal }}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="6">
                                                No hay articulos agregados
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registrarVenta()">Registrar
                                    Venta</button>
                            </div>
                        </div>
                    </div>
                </template>
                <!-- FIN REGISTRAR VENTA -->


            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{ 'mostrar': modal }" role="dialog" aria-labelledby="myModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterioA">
                                        <option value="nombre">Nombre</option>
                                        <option value="descripcion">Descripción</option>
                                        <option value="codigo">Código</option>
                                    </select>
                                    <input type="text" v-model="buscarA" @keyup="listarArticulo(buscarA, criterioA)"
                                        class="form-control" placeholder="Texto a buscar">
                                    <!--button type="submit" @click="listarArticulo(buscarA, criterioA)"
                                        class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button-->
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Categoría</th>
                                        <th>Precio Venta</th>
                                        <th>Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                                        <td>
                                            <button type="button" @click="agregarDetalleModal(articulo)"
                                                class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                            </button>
                                        </td>
                                        <td v-text="articulo.codigo"></td>
                                        <td v-text="articulo.nombre"></td>
                                        <td v-text="articulo.nombre_categoria"></td>
                                        <td v-text="articulo.precio_venta"></td>
                                        <td v-text="articulo.stock"></td>
                                        <td>
                                            <div v-if="articulo.condicion">
                                                <span class="badge badge-success">Activo</span>
                                            </div>
                                            <div v-else>
                                                <span class="badge badge-danger">Desactivado</span>
                                            </div>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion == 1" class="btn btn-primary"
                            @click="registrarPersona()">Guardar</button>
                        <button type="button" v-if="tipoAccion == 2" class="btn btn-primary"
                            @click="actualizarPersona()">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
    </main>
</template>

<script>
import vSelect from 'vue-select';
export default {
    data() {
        return {
            idcotizacion:0,
            actualizarIva: '',

            rolUsuario: '',

            titulocard:'',
            dias_validez:30,

            tiempo_entrega:"Inmediata",
            lugar_entrega:"Deposito",
            forma_pago:"Al contado",
            nota:"",
            options_print: [

        { value: "opcion1", label: "Cotizaciòn A" },
        { value: "opcion2", label: "Cotizaciòn B" },
        { value: "opcion3", label: "Cotizaciòn C" }
      ],
      imprimir: "",
            venta_id: 0,
            idcliente: 0,
            nitcliente:"",
            cliente: '',
            tipo_comprobante: 'BOLETA',
            serie_comprobante: '',
            num_comprobante: '',
            impuesto: 0.18,
            total: 0.0,
            totalImpuesto: 0.0,
            totalParcial: 0.0,
            arrayVenta: [],
            arrayCliente: [],
            arrayDetalle: [],
            listado: 1,
            modal: 0,
            tituloModal: '',
            tipoAccion: 0,
            errorVenta: 0,
            errorMostrarMsjVenta: [],
            pagination: {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0,
            },
            offset: 3,
            criterio: 'num_comprobante',
            buscar: '',
            criterioA: 'nombre',
            buscarA: '',
            arrayArticulo: [],
            idarticulo: 0,
            codigo: '',
            articulo: '',
            precio: 0,
            cantidad: 0,
            descuento: 0,
            stock: 0,
            valorMaximoDescuento: '',
        }
    },
    components: {
        vSelect
    },
    computed: {
        isActived: function () {
            return this.pagination.current_page;
        },

        //Calcula los elementos de la paginación
        pagesNumber: function () {
            if (!this.pagination.to) {
                return [];
            }

            var from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }

            var to = from + (this.offset * 2);
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }

            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;

        },

        calcularTotal: function () {
            var resultado = 0.0;
            for (var i = 0; i < this.arrayDetalle.length; i++) {
                resultado = resultado + (this.arrayDetalle[i].precio * this.arrayDetalle[i].cantidad - this.arrayDetalle[i].descuento)
            }
            return resultado;
        }
    },
    methods: {
        datosConfiguracion() {
            let me = this;
            var url = '/configuracion/editar';

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.actualizarIva = respuesta.configuracionTrabajo.actualizarIva;
                console.log(me.actualizarIva);

            })
            .catch(function (error) {
                console.log(error);
            });
        },
        recuperarIdRol() {
            this.rolUsuario = window.userData.rol;
            console.log('ID_ROL: ' + this.rolUsuario);
        },
        registrarVenta(id) {
            if (this.validarVenta()) {
                console.log("Completa todos los campos");
                return;
            }
            console.log("Validado xd");
            let me = this;
            console.log(me.idcliente);
            console.log(me.tipo_comprobante);
            console.log(me.num_comprobante);
            console.log(me.impuesto);
            console.log("=========================");
            console.log(me.idcotizacion);
            console.log("=========================");


            axios.post('/venta/registrar', {
                'idcliente': this.idcliente,
                'tipo_comprobante': this.tipo_comprobante,
                'serie_comprobante': this.serie_comprobante,
                'num_comprobante': this.num_comprobante,
                'impuesto': this.impuesto,
                'total': this.total,
                'data': this.arrayDetalle

            }).then(function (response) {
                console.log(me.idcotizacion);
                me.eliminarCotizacion(me.idcotizacion);
                // axios.delete('/cotizacionventa/eliminar/' + { id:  me.idcotizacion})
                //     .then(response => {
                //         // Manejar la respuesta exitosa aquí, si es necesario
                //         console.log(response.data);
                //         console.log("Eliminada");
                //     })
                //     .catch(error => {
                //         // Manejar el error aquí, si es necesario
                //         console.error(error);
                //     });
                //console.log(response.data.id);
                if (response.data.id > 0) {
                    me.listado = 1;
                    me.listarCotizacion(1, '', '');
                    me.idproveedor = 0;
                    me.tipo_comprobante = 'BOLETA';
                    me.serie_comprobante = '';
                    me.num_comprobante = '';
                    me.impuesto = 0.13;
                    me.total = 0.0;
                    me.idarticulo = 0;
                    me.articulo = '';
                    me.cantidad = 0;
                    me.precio = 0;
                    me.stock = 0;
                    me.codigo = '';
                    me.descuento = 0;
                    me.arrayDetalle = [];
           
                    window.open('/venta/pdf/'+ response.data.id);
                }else{
                    if(response.data.valorMaximo){
                        //alert('El valor de descuento no puede exceder el '+ response.data.valorMaximo);
                        swal(
                            'Aviso',
                            'El valor de descuento no puede exceder el '+ response.data.valorMaximo+ ' %',
                            'warning'
                        )
                        return;
                    }else{
                        //alert(response.data.caja_validado); 
                        swal(
                            'Aviso',
                            response.data.caja_validado,
                            'warning'
                        )
                        return;
                    }
                    //console.log(response.data.valorMaximo)
                }

            }).catch(function (error) {
                console.log(error);
            });
            console.log("SE vendio");
        },

        eliminarCotizacion(id) {
    axios.put('/cotizacionventa/eliminar', { id: id })
      .then(response => {
        // Manejar la respuesta exitosa aquí, si es necesario
        console.log(response.data);
      })
      .catch(error => {
        // Manejar el error aquí, si es necesario
        console.error(error);
      });
  },
        abrirVenta(id){


            let me = this;
            me.idcotizacion=id;
            console.log("==============");
            console.log(me.idcotizacion);
            console.log("==============");

            me.listado=3
            me.titulocard="REGISTRAR VENTA";;

            //Obtener datos del ingreso
            var arrayVentaT = [];
            var url = '/cotizacionventa/obtenerCabecera?id=' + id;

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                arrayVentaT = respuesta.cotizacion;
                console.log("Cliente");
            
                console.info(arrayVentaT[0]['nombre']);
                console.log("Cliente");
                
                me.cliente = arrayVentaT[0]['nombre'];
                me.idcliente=arrayVentaT[0]['idcliente'];
                me.arrayCliente=[
                        {
                            'id':me.idcliente,
                            'nombre':me.cliente,
                        }           
                ];
                
                console.log(me.arrayCliente);
                console.log("XD");
                me.impuesto = arrayVentaT[0]['impuesto'];
                me.total = arrayVentaT[0]['total'];
            })
                .catch(function (error) {
                    console.log(error);
                });

            //obtener datos de los detalles
            var url = '/cotizacionventa/obtenerDetalles?id=' + id;

            axios.get(url).then(function (response) {
                //console.log(response);
                var respuesta = response.data;
                me.arrayDetalle = respuesta.detalles;
                console.log("=======");
                console.log(me.arrayDetalle);

            })
                .catch(function (error) {
                    console.log(error);
                });
        },

        desactivarCotizacion(id) {
            swal({
                title: '¿Esta seguro de deshabilitar esta cotizaciòn?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/cotizacionventa/desactivar', {
                        'id': id
                    }).then(function (response) {
                        me.listarCotizacion(1, '', 'nombre');

                        swal(
                            'Desactivado!',
                            'La cotizaciòn ha sido desactivado con éxito.',
                            'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });


                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {

                }
            })
        },
        activarCotizacion(id) {
            swal({
                title: '¿Esta seguro de habilitar esta cotizaciòn?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/cotizacionventa/activar', {
                        'id': id
                    }).then(function (response) {
                        me.listarCotizacion(1, '', 'nombre');
                        swal(
                            'Activado!',
                            'La cotizaciòn ha sido activado con éxito.',
                            'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });


                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {

                }
            })
        },
        atajoButton: function(event) {
            //console.log(event.keyCode);
            //console.log(event.ctrlKey);
            if(event.shiftKey && event.keyCode === 81) {
                event.preventDefault();
                this.$refs.impuestoRef.focus();
            }
            if(event.shiftKey && event.keyCode === 87) {
                event.preventDefault();
                this.$refs.serieComprobanteRef.focus();
            }
            if(event.shiftKey && event.keyCode === 69) {
                event.preventDefault();
                this.$refs.numeroComprobanteRef.focus();
            }
            if(event.shiftKey && event.keyCode === 82) {
                event.preventDefault();
                this.$refs.articuloRef.focus();
            }
            if(event.shiftKey && event.keyCode === 84) {
                event.preventDefault();
                this.$refs.precioRef.focus();
            }  
            if(event.shiftKey && event.keyCode === 89) {
                event.preventDefault();
                this.$refs.cantidadRef.focus();
            }
            if(event.shiftKey && event.keyCode === 85) {
                event.preventDefault();
                this.$refs.descuentoRef.focus();
            }       
        },
        listarCotizacion(page, buscar, criterio) {
            let me = this;
            var url = '/cotizacionventa?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;

                me.arrayVenta = respuesta.cotizacion_venta.data;
                me.pagination = respuesta.pagination;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        selectCliente(search, loading) {
            let me = this;
            loading(true)
            var url = '/cliente/selectCliente?filtro=' + search;
            axios.get(url).then(function (response) {
                //console.log(response);
                let respuesta = response.data;
                q: search
                me.arrayCliente = respuesta.clientes;
                console.log(me.arrayCliente);
                loading(false)
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getDatosCliente(val1) {
            let me = this;
            me.loading = true;
            me.idcliente = val1.id;
            me.nitcliente=val1.num_documento;
            console.log(val1.num_documento);
        },

        buscarArticulo() {
            let me = this;
            var url = '/articulo/buscarArticuloVenta?filtro=' + me.codigo;

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayArticulo = respuesta.articulos;

                if (me.arrayArticulo.length > 0) {
                    me.articulo = me.arrayArticulo[0]['nombre'];
                    me.idarticulo = me.arrayArticulo[0]['id'];
                    me.precio = me.arrayArticulo[0]['precio_venta'];
                    me.stock = me.arrayArticulo[0]['stock'];
                }
                else {
                    me.articulo = 'No existe este articulo';
                    me.idarticulo = 0;
                }
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        pdfVenta(id) {
            window.open('/cotizacionventa/pdf/' + id, '_blank');
        },
        cambiarPagina(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            //Envia la petición para visualizar la data de esa página
            me.listarCotizacion(page, buscar, criterio);
        },
        encuentra(id) {
            var sw = 0;
            for (var i = 0; i < this.arrayDetalle.length; i++) {
                if (this.arrayDetalle[i].idarticulo == id) {
                    sw = true;
                }
            }
            return sw;
        },
        eliminarDetalle(index) {
            let me = this;
            me.arrayDetalle.splice(index, 1);
        },
        agregarDetalle() {
            let me = this;
            if (me.idarticulo == 0 || me.cantidad == 0 || me.precio == 0) {

            } else {
                if (me.encuentra(me.idarticulo)) {
                    swal({
                        type: 'error',
                        title: 'Error...',
                        text: 'Este Artículo ya se encuentra agregado!',
                    })
                } else {
                    if (me.cantidad > me.stock) {
                        swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'No hay stock disponible!',
                        })
                    } else {
                        me.arrayDetalle.push({
                            idarticulo: me.idarticulo,
                            articulo: me.articulo,
                            cantidad: me.cantidad,
                            precio: me.precio,
                            descuento: me.descuento,
                            stock: me.stock
                        });
                        me.codigo = '';
                        me.idarticulo = 0;
                        me.articulo = '';
                        me.cantidad = 0;
                        me.precio = 0;
                        me.descuento = 0;
                        me.stock = 0
                    }
                }

            }

        },
        agregarDetalleModal(data = []) {
            let me = this;
            if (me.encuentra(data['id'])) {
                swal({
                    type: 'error',
                    title: 'Error...',
                    text: 'Este Artículo ya se encuentra agregado!',
                })
            } else {
                me.arrayDetalle.push({
                    idarticulo: data['id'],
                    articulo: data['nombre'],
                    cantidad: 1,
                    precio: data['precio_venta'],
                    descuento: 0,
                    stock: data['stock']
                });
            }
        },
        listarArticulo(buscar, criterio) {
            let me = this;
            var url = '/articulo/listarArticuloVenta?buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayArticulo = respuesta.articulos.data;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        registrarCotizacion() {
            if (this.validarCotizacion()) {
                console.log("Rellene todos los campos");
                return;
            }

            let me = this;
//             var fechaHoy = new Date();
//             console.log(me.dias_validez);
// fechaHoy.setDate(fechaHoy.getDate() + parseInt( me.dias_validez,10));
// console.log(fechaHoy);
// console.log(fechaHoy);



            axios.post('/cotizacionventa/registrar', {
                'idcliente': this.idcliente,
                'impuesto': this.impuesto,
                'total': this.total,
                'n_validez':this.dias_validez,
                'tiempo_entrega':this.tiempo_entrega,
                'lugar_entrega':this.lugar_entrega,
                'forma_pago':this.forma_pago,
                'nota':this.nota,
                
                'data': this.arrayDetalle

            }).then(function (response) {
                console.log("cotizacionREgistraado");

                //console.log(response.data.id);
                if (response.data.id > 0) {
                    console.log("cotizacionREgistraado"+me.listado);
                    
                    me.listado = 1;
                    me.listarCotizacion(1,'','');
                    me.idproveedor = 0;
                    me.impuesto = 0.18;
                    me.total = 0.0;
                    me.idarticulo = 0;
                    me.articulo = '';
                    me.cantidad = 0;
                    me.precio = 0;
                    me.stock = 0;
                    me.codigo = '';
                    me.descuento = 0;
                    me.arrayDetalle = [];
                    // window.open('/cotizacionventa/pdf/'+ response.data.id);
                }else{
                    if(response.data.valorMaximo){
                        //alert('El valor de descuento no puede exceder el '+ response.data.valorMaximo);
                        swal(
                            'Aviso',
                            'El valor de descuento no puede exceder el '+ response.data.valorMaximo,
                            'warning'
                        )
                        return;
                    }else{
                        //alert(response.data.caja_validado); 
                        swal(
                            'Aviso',
                            response.data.caja_validado,
                            'warning'
                        )
                        return;
                    }
                    //console.log(response.data.valorMaximo)
                }

            }).catch(function (error) {
                console.log(error);
            });
        },
        validarCotizacion() {
            let me = this;
            me.errorVenta = 0;
            me.errorMostrarMsjVenta = [];
            var art;

            me.arrayDetalle.map(function (x) {
                if (x.cantidad > x.stock) {
                    art = x.articulo + " Stock insuficiente";
                    me.errorMostrarMsjVenta.push(art);
                }
            });

            if (me.idcliente == 0) me.errorMostrarMsjVenta.push("Seleccione un Cliente");
            if (!me.impuesto) me.errorMostrarMsjVenta.push("Ingrese el impuesto de compra");
            if (me.arrayDetalle.length <= 0) me.errorMostrarMsjVenta.push("Ingrese detalles");

            if (me.errorMostrarMsjVenta.length) me.errorVenta = 1;
            console.log(me.errorVenta);
            return me.errorVenta;
        },
        validarVenta() {
            let me = this;
            me.errorVenta = 0;
            me.errorMostrarMsjVenta = [];
            var art;

            me.arrayDetalle.map(function (x) {
                if (x.cantidad > x.stock) {
                    art = x.articulo + " Stock insuficiente";
                    me.errorMostrarMsjVenta.push(art);
                }
            });

            if (me.idcliente == 0) me.errorMostrarMsjVenta.push("Seleccione un Cliente");
            if (me.tipo_comprobante == 0) me.errorMostrarMsjVenta.push("Sleccione el Comprobante");
            if (!me.num_comprobante) me.errorMostrarMsjVenta.push("Ingrese el numero de comprobante");
            if (!me.impuesto) me.errorMostrarMsjVenta.push("Ingrese el impuesto de compra");
            if (me.arrayDetalle.length <= 0) me.errorMostrarMsjVenta.push("Ingrese detalles");

            if (me.errorMostrarMsjVenta.length) me.errorVenta = 1;

            return me.errorVenta;
        },
        mostrarDetalle(titulo,ventaid) {
            let me = this;
            me.listado = 0;
            me.titulocard=titulo;

            if (me.titulocard=="REGISTRAR COTIZACIÒN"){
                me.idproveedor = 0;
                me.impuesto = 0.18;
                me.total = 0.0;
                me.idarticulo = 0;
                me.articulo = '';
                me.cantidad = 0;
                me.precio = 0;
                me.arrayDetalle = [];
            }else{

                me.actualizarCotizacion(ventaid);


            }


        },
        ocultarDetalle() {
            this.listado = 1;
            this.num_comprobante='';
            this.cliente='';
            this.arrayArticulo=[];
            this.serie_comprobante='';
        },
        actualizarCotizacion(id) {
            let me = this;

            //Obtener datos del ingreso
            var arrayVentaT = [];
            var url = '/cotizacionventa/obtenerCabecera?id=' + id;

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                arrayVentaT = respuesta.cotizacion;
                console.log(me.arrayCliente);
                me.cliente = arrayVentaT[0]['nombre'];
                me.idcliente=arrayVentaT[0]['idcliente'];
                me.arrayCliente=[
                        {
                            'id':me.idcliente,
                            'nombre':me.cliente,
                        }           

                ];


                me.impuesto = arrayVentaT[0]['impuesto'];
                me.total = arrayVentaT[0]['total'];
                console.log(me.arrayCliente);

            })
                .catch(function (error) {
                    console.log(error);
                });

            //obtener datos de los detalles
            var url = '/cotizacionventa/obtenerDetalles?id=' + id;

            axios.get(url).then(function (response) {
                //console.log(response);
                var respuesta = response.data;
                me.arrayDetalle = respuesta.detalles;

            })
                .catch(function (error) {
                    console.log(error);
                });
        },


        verVenta(id) {
            let me = this;
            me.titulocard="COTIZACION";
            me.listado = 2;

            //Obtener datos del ingreso
            var arrayVentaT = [];
            var url = '/cotizacionventa/obtenerCabecera?id=' + id;

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                arrayVentaT = respuesta.cotizacion;

                me.cliente = arrayVentaT[0]['nombre'];
                me.impuesto = arrayVentaT[0]['impuesto'];
                me.total = arrayVentaT[0]['total'];
            })
                .catch(function (error) {
                    console.log(error);
                });

            //obtener datos de los detalles
            var url = '/cotizacionventa/obtenerDetalles?id=' + id;

            axios.get(url).then(function (response) {
                //console.log(response);
                var respuesta = response.data;
                me.arrayDetalle = respuesta.detalles;

            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        cerrarModal() {
            this.modal = 0;
            this.tituloModal = '';
  
        },
        abrirModal() {
            this.listarArticulo("","");
            this.arrayArticulo = [];
            this.modal = 1;
            this.tituloModal = 'Seleccione los articulos que desee';

        },

        desactivarVenta(id) {
            swal({
                title: 'Esta seguro de anular esta venta?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/venta/desactivar', {
                        'id': id
                    }).then(function (response) {
                        me.listarCotizacion(1, '', 'num_comprobante');
                        swal(
                            'Anulado!',
                            'La venta ha sido anulado con éxito.',
                            'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });


                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {

                }
            })
        },

    },
    mounted() {
        this.listarCotizacion(1, this.buscar, this.criterio);
        window.addEventListener('keydown', this.atajoButton);

    }
}
</script>
<style>    
.fit-content {
  width: 1%;
  white-space: nowrap;
}

.modal-content {
        width: 100% !important;
        position: absolute !important;
    }

    .mostrar {
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }

    .div-error {
        display: flex;
        justify-content: center;
    }

    .text-error {
        color: red !important;
        font-weight: bold;
    }
    .small-text {
        font-size: 12px;
        white-space: nowrap;
        color: darkgrey;
        }

    @media (min-width: 600px) {
        .btnagregar {
            margin-top: 2rem;
        }
    }</style>
