<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Ventas
                    <button type="button" @click="ejecutarFunciones" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <button v-if="permitirDevolucion === 1" type="button" @click="listado = 3" class="btn btn-secondary">Devoluciones
                    </button>
                    <span class="badge bg-secondary" id="comunicacionSiat" style="color: white;">Desconectado</span>
                    <span class="badge bg-secondary" id="cuis">CUIS: Inexistente</span>
                    <span class="badge bg-secondary" id="cufd">No existe cufd vigente</span>
                    <span class="badge bg-secondary" id="direccion" v-show="mostrarDireccion">No hay dirección
                        registrada</span>
                    <span class="badge bg-primary" id="cufdValor" v-show="mostrarCUFD">No hay CUFD</span>
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
                                    <input type="text" v-model="buscar" @keyup="listarVenta(1, buscar, criterio)"
                                        class="form-control" placeholder="Texto a buscar">
                                    <!--button type="submit" @click="listarVenta(1, buscar, criterio)" class="btn btn-primary"><i
                                            class="fa fa-search"></i> Buscar</button-->
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Usuario</th>
                                        <th>Cliente</th>
                                        <th>Tipo Comprobante</th>
                                        <th>Serie Comprobante</th>
                                        <th>Número Comprobante</th>
                                        <th>Fecha Hora</th>
                                        <th>Total</th>
                                        <th>Impuesto</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="venta in arrayVenta" :key="venta.id">
                                        <td>
                                            <button type="button" @click="verVenta(venta.id)"
                                                class="btn btn-success btn-sm">
                                                <i class="icon-eye"></i>
                                            </button> &nbsp;
                                            <button type="button" @click="pdfVenta(venta.id)" class="btn btn-info btn-sm">
                                                <i class="icon-doc"></i>
                                            </button> &nbsp;
                                            <template v-if="venta.estado == 'Registrado'">
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    @click="desactivarVenta(venta.id)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template>
                                        </td>
                                        <td v-text="venta.usuario"></td>
                                        <td v-text="venta.nombre"></td>
                                        <td v-text="venta.tipo_comprobante"></td>
                                        <td v-text="venta.serie_comprobante"></td>
                                        <td v-text="venta.num_comprobante"></td>
                                        <td v-text="venta.fecha_hora"></td>
                                        <td v-text="venta.total"></td>
                                        <td v-text="venta.impuesto"></td>
                                        <td v-text="venta.estado"></td>
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
                <!-- Detalle-->
                <template v-else-if="listado == 0">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Cliente(*)</label>
                                    <v-select :on-search="selectCliente" label="nombre" :options="arrayCliente"
                                        placeholder="Buscar Clientes..." :onChange="getDatosCliente">
                                    </v-select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="">Razón Social</label>
                                <input type="text" id="nombreCliente" class="form-control" v-model="nombreCliente"
                                    ref="nombreRef" readonly>
                            </div>

                            <input type="hidden" id="idcliente" class="form-control" v-model="idcliente" ref="idRef"
                                readonly>
                            <input type="text" id="tipo_documento" class="form-control" v-model="tipo_documento"
                                ref="tipoDocumentoRef" readonly>
                            <input type="hidden" id="complemento_id" class="form-control" v-model="complemento_id"
                                ref="complementoIdRef" readonly>
                            <input type="hidden" id="usuarioAutenticado" class="form-control" v-model="usuarioAutenticado"
                                readonly>

                            <div class="col-md-3">
                                <label for="">Documento</label>
                                <input type="text" id="documento" class="form-control" v-model="documento"
                                    ref="documentoRef" readonly>
                            </div>

                            <div class="col-md-3">
                                <label for="">Email</label>
                                <input type="text" id="email" class="form-control" v-model="email" ref="emailRef" readonly>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="" for="proveedor">Almacen(*)</label>
                                    <v-select label="nombre_almacen" :options="arrayAlmacenes"
                                        placeholder="Seleccione un almacen" :onChange="getAlmacenProductos"></v-select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Comprobante(*)</label>
                                    <select class="form-control" v-model="tipo_comprobante" ref="tipoComprobanteRef">
                                        <option value="0">Seleccione</option>
                                        <option value="FACTURA">Factura</option>
                                        <option value="BOLETA">Boleta</option>
                                        <option value="TICKET">Ticket</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número Comprobante</label>
                                    <input type="text" id="num_comprobante" class="form-control" v-model="num_comprob"
                                        ref="numeroComprobanteRef" readonly>
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-inline">
                                        <button @click="abrirModal()" class="btn btn-primary">Buscar</button>
                                        <input type="text" class="form-control" v-model="codigo" ref="articuloRef" @keyup="buscarArticulo()" placeholder="Codigo del artículo">                   
                                        <input type="text" id="nombre_producto" readonly class="form-control"
                                            v-model="articulo">
                                        <label for="">Shift + R</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card-body" v-if="articulo !== ''">
                                    <h3>{{articulo }}</h3>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <img
                                    v-if="arrayArticulo.length > 0 && arrayArticulo[0].fotografia"
                                    :src="'img/articulo/' + arrayArticulo[0].fotografia + '?t=' + new Date().getTime()"
                                    width="50" height="50" ref="imagen" class="card-img"/>
                                <img v-else src="https://www.bicifan.uy/wp-content/uploads/2016/09/producto-sin-imagen.png"  alt="Imagen del Card" class="card-img">
                            </div>

                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>Total Uni/Stock</label>
                                    <input type="text" id="stock" style="color: red;" class="form-control" v-model="stock" readonly>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Precios(*)</label>
                                    <select class="form-control" v-model="precioseleccionado" @change="mostrarSeleccion" :disabled="precioBloqueado">
        
                                        <option :value="precio_uno" v-if="arrayPrecios[0]">{{ arrayPrecios[0].nombre_precio }}</option>
                                        <option :value="precio_dos" v-if="arrayPrecios[1]">{{arrayPrecios[1].nombre_precio}}</option>
                                        <option :value="precio_tres" v-if="arrayPrecios[2]">{{arrayPrecios[2].nombre_precio}}</option>
                                        <option :value="precio_cuatro" v-if="arrayPrecios[3]">{{arrayPrecios[3].nombre_precio}}</option>
                                    </select>
                                </div>
                            </div>

                            <input type="hidden" id="codigo" class="form-control" v-model="codigo" readonly>
                            <input type="hidden" id="codigoProductoSin" class="form-control" v-model="codigoProductoSin"
                                readonly>

                          <!-- <div class="col-md-2">
                                <div class="form-group">
                                    <label>Unidad Medida</label>
                                    <input type="text" id="medida" class="form-control" v-model="medida" readonly>
                                </div>
                            </div> 

                        -->
                            <div class="col-md-2">
                                 <div class="form-group"> 
                                    <label>Precio</label> 
                                    <input type="number" id="precio" value="0"  class="form-control" v-model="precioFinal" readonly>
                                    
                                    </div> 
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cantidad <span style="color: red;" v-show="cantidad == 0"></span></label>
                    
                                    <input type="number" id="cantidad" value="0" class="form-control" v-model="cantidad"
                                        ref="cantidadRef"> 
                                    <label for="">Shift + Y</label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Unidad Medida <span style="color: red;" v-show="cantidad == 0"></span></label>
                                   
                                    <select class="form-control" v-model="paquni" @change="preciounid">
                                        <option :value="unidad_envase" >paquete</option>
                                        <option :value="1" >unidad</option>
                                     </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Descuento % </label>
                                    <input type="number" id="descuento" value="0" class="form-control" v-model="descuento"
                                        ref="descuentoRef">
                                    <label for="">Shift + U</label>
                                </div>
                            </div>
                            <label id="sTotal" style="display: none;">{{ ((precioseleccionado * cantidad * paquni) - (precioseleccionado * cantidad * paquni * descuento / 100)).toFixed(2) }}
                            </label>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar"><i
                                            class="icon-plus"></i></button>
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
                                            <th>Unidad Medida</th>
                                            <th>Unidad Paq.</th>
                                            <th>Precio Unidad </th>
                                            <th>Unidades</th>
                                            <th>Cantidad Paquetes</th>
                                            <th>Total S/Descueto</th>
                                            <th>Descuento %</th>
                                            <th>Total C/Descueto</th>
                                
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
                                            <td v-text="detalle.medida">
                                            </td>
                                            <td>{{unidad_envase}}
                                            </td>
                                            <td v-text="detalle.precioseleccionado">
                                            </td>
                                            <td> {{ detalle.cantidad % unidad_envase}} </td>  
    
                                            <td> {{ Math.floor(detalle.cantidad / unidad_envase) }} </td>
                                             
                                            
                                             <td >
                                                {{(detalle.precioseleccionado * detalle.cantidad).toFixed(2)}} 

                                            </td>
                                            
                                        
                                            <td v-text="detalle.descuento">
                                            </td>
                                                <!--<span style="color:red;"
                                                    v-show="detalle.descuento > (detalle.precioseleccionado * detalle.cantidad)">Descuento
                                                    superior</span>
                                                <input v-model="detalle.descuento" type="number" class="form-control">-->


                                            <td>
                                                {{ ((detalle.precioseleccionado * detalle.cantidad) - (detalle.precioseleccionado * detalle.cantidad * detalle.descuento / 100)).toFixed(2) }}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="9" align="right"><strong>Sub Total: Bs.</strong></td>
                                            <td id="subTotal">{{ totalParcial=(calcularSubTotal).toFixed(2) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="9" align="right"><strong>Descuento Adicional: Bs.</strong></td>
                                            <input id="descuentoAdicional" v-model="descuentoAdicional" type="number"
                                                class="form-control">
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="9" align="right"><strong>Total Neto: Bs.</strong></td>
                                            <td id="montoTotal">{{ total=(calcularTotal).toFixed(2) }}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="10">
                                                
                                                No hay articulos agregados 
                                            </td>
                                            <td colspan="10"  v-show="true">
                                                
                                                hola {{ this.precioBloqueado=false }}
                                            </td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col" for="">Tipo de Pago</label>
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registrarAbrilModal()">Contado</button>
                                <button type="button" class="btn btn-primary" @click="registrarAbrilModal2()">Credito</button>
                            </div>
                        </div>
                    </div>
                </template>
                <!-- Fin Detalle-->
                <!--Ver ingreso-->
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Comprobante</label>
                                    <p v-text="tipo_comprobante"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie Comprobante</label>
                                    <p v-text="serie_comprobante"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número Comprobante</label>
                                    <p v-text="num_comprobante"></p>
                                </div>
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
                                            <td v-text="detalle.medida">
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
                            </div>
                        </div>
                    </div>
                </template>
                <!--Fin ver ingreso-->
                <!-- Vista Devoluciones -->
                <template v-else-if="listado == 3">
                    <devoluciones></devoluciones>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                        </div>
                    </div>
                </template>
            </div>
            <!-- HASTA AQUI DEVOLUCIONES -->
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
                                        <th>Estado</th>
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
                                        <td v-text="articulo.saldo_stock"></td>
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
        <!--#########- MODAL DE REISTRO DE TIPO VENTA--############3-->
        <div class="modal fade" tabindex="-1" :class="{ 'mostrar': modal2 }" role="dialog" aria-labelledby="myModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal2"></h4>
                        <button type="button" class="close" @click="cerrarModal2()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                   <label class="form-control-label" for="text-input">Recibido BS(*)</label>
                                   <div class="input-group">
                                       <input type="number" v-model="recibido" >
                                    </div>                                      
                                </div>
                                <div class="form-group">
                                   <label class="form-control-label" for="text-input">Calcular</label>
                                   <div class="input-group">
                                    <button type="button" class="btn btn-primary" @click="calcularCambio()">Calcular</button>
                                   </div>
                                </div>
                            </div>
                            <div class="col-md-6"> 
                               <div class="form-group">
                                   <label class="form-control-label" for="text-input">ToTal(*)</label>
                                   <div class="input-group">
                                    <input type="number"  v-model="total" class="font-weight-bold">
                                       <!-- <input type="number"  v-model="totalprue" class="negrita-input"> -->
                                    </div>   
                               </div>
                               <div class="form-group">
                                   <label class="form-control-label" for="text-input">Efec(*)</label>
                                   <div class="input-group">
                                       <input type="number"  v-model="efectivo">
                                   </div>
                               </div>
                               <div class="form-group">
                                   <label class="form-control-label" for="text-input">Cambio(*)</label>
                                   <div class="input-group">
                                       <input type="number"  v-model="cambio">
                                    </div>
                                       
                               </div>
                               <div class="form-group">
                                   <label class="form-control-label" for="text-input">Faltante(*)</label>
                                   <div class="input-group">
                                       <input type="number"  v-model="faltante">
                                   </div>
                               </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal2()">Volver</button>
                        <button type="button" v-if="tipoAccion2==1" class="btn btn-primary"  @click="registrar()">Cobrar</button>
                        <!-- <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarSucursal()">Actualizar</button> -->
                    </div>
                </div>  
            </div>  
        </div>
        <!--##################---HASTA AQUI---####################-->
        <!--##################---MODAL CREDITOS---####################-->
        <div class="modal fade" tabindex="-1" :class="{ 'mostrar': modal3 }" role="dialog" aria-labelledby="myModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header responsive">
                        <h4 class="modal-title" v-text="tituloModal3"></h4>
                        <button type="button" class="close" @click="cerrarModal3()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-control-label" for="text-input">Numero Cuotas</label>
                                    <div class="input-group">
                                        <input type="number" v-model="numero_cuotas">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-control-label" for="text-input">Tiempo Diaz</label>
                                    <div class="input-group">
                                        <input type="number" v-model="tiempo_diaz">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-control-label" for="text-input">Generar Cuota</label>
                                    <button @click="generarCuotas" >GENERAR_CUOTA</button>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-control-label" for="text-input">Primera Cuota</label>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <input type="checkbox" v-model="primera_cuota" id="primera_cuota">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--################################-listado de cuotas-#######################-->
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Fecha Pago</th>
                                            <th>Precio_Cuota</th>
                                            <th>Total Cancelado</th> 
                                            <th>Saldo</th>
                                            <th>Fecha Cancelado</th> 
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(cuota, index) in cuotas" :key="index">
                                            <td>{{ cuota.fechaPago }}</td>
                                            <td>{{ cuota.precioCuota }}</td>
                                            <td>{{ cuota.totalCancelado}}</td>
                                            <td>{{ cuota.saldo }}</td>
                                            <td>{{ cuota.fechaCancelado}}</td>
                                            <td>{{ cuota.estadocuocre }}</td>                                       
                                        </tr>
                                    </tbody>
                                    <!-- <tbody v-else>
                                        <tr>
                                            <td colspan="4">
                                                No hay Calculo
                                            </td>
                                        </tr>
                                    </tbody> -->
                                </table>
                            </div>
                        </div>
                        <!--######################################-hasta QUI-################################-->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal3()">Volver</button>
                        <button type="button" v-if="tipoAccion3==1" class="btn btn-primary"  @click="registrar()">Registrar</button>
                    </div>
                </div>  
            </div>  
        </div>
        <!--#################-------HASTA AQUI MODAL CREDITOS------#################-->
    </main>
</template>

<script>
import vSelect from 'vue-select';
export default {
    data() {
        return {
            permitirDevolucion: '',
            saldosNegativos: null,
            venta_id: 0,
            idcliente: 0,
            usuarioAutenticado: null,
            puntoVentaAutenticado: null,
            cliente: '',
            email: '',
            nombreCliente: '',
            documento: '',
            tipo_documento: '',
            complemento_id: '',
            descuentoAdicional: 0.00,
            tipo_comprobante: 'BOLETA',
            serie_comprobante: '',
            last_comprobante: 0,
            num_comprob: "",
            impuesto: 0.18,
            total: 0.0,
            totalImpuesto: 0.0,
            totalParcial: 0.0,
            arrayVenta: [],
            arrayCliente: [],
            arrayDetalle: [],
            arrayProductos: [],
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
            medida: '',
            codigoProductoSin: '',
            precio: 0,
            unidad_envase:0,
            cantidad: 1,
            paquni:'',
            precioBloqueado: false,

            descuento: 0,
            sTotal: 0,
            stock: 0,
            valorMaximoDescuento: '',
            mostrarDireccion: true,
            mostrarCUFD: true,

            //almacenes
            arrayAlmacenes: [],
            idAlmacen: 0,
            //-----PRECIOS- AUMENTE 3/OCTUBRE--------
            precioseleccionado :'',
            //precio : '',
            arrayPrecios :[],
            nombre_precio :'',
            precio_uno: '',
            precio_dos : '',
            precio_tres : '',
            precio_cuatro : '',
            //-----MODAL 2---
            modal2:0,
            tituloModal2: '',
            tipoAccion2: '',
            //-----MODAL 3---
            modal3:0,
            tituloModal3: '',
            tipoAccion3: '',
            //--nue☺8os datos--
            // fecha_credito : '',
            // proforma : '',
            // refe_pago : '',
            // orden_compra : '',
            // fecha_entrega : '',
            // checkboxHabilitado :'',
            // diasToAdd : null,
            //----CALCULADORA---
            //totalprue: 80,//PRUEBA PORQUE NO DA EL CALCULAR
            recibido: 0,
            efectivo: 0,
            cambio: 0,
            faltante : 0,
            //-------DEStE AQUI 13-OCTUBRE--
            idtipo_pago :'',
            tiempo_diaz : '',
            numero_cuotas : '',
            cuotas: [],//---para almacenar las fechas
            estadocrevent : 'activo',
            primera_cuota : '',
            habilitarPrimeraCuota : false,
        }
    },
    watch: {
        codigo(newValue) {
            if (newValue) {
                this.buscarArticulo();
            }
        }
   },
    components: {
        vSelect
    },
    computed: {

        precioFinal() { 
            // Buscamos la unidad y el precio seleccionados por su id 
            const unidad_ = this.paquni; 
            const final = this.precioseleccionado; 
            // Calculamos el precio final multiplicando el valor del precio por el factor de la unidad
        
            console.log ("precio final");
            console.log (unidad_);
            console.log (final);
            
            return (final * unidad_).toFixed(2);
            },

    

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
                resultado += ((this.arrayDetalle[i].precioseleccionado * this.arrayDetalle[i].cantidad)-(this.arrayDetalle[i].precioseleccionado * this.arrayDetalle[i].cantidad * this.arrayDetalle[i].descuento/100))
          
            }
            resultado -= this.descuentoAdicional;
            return resultado;
        },

        calcularSubTotal: function () {
            var resultado = 0.0;
            for (var i = 0; i < this.arrayDetalle.length; i++) {
                resultado = resultado + ((this.arrayDetalle[i].precioseleccionado * this.arrayDetalle[i].cantidad)-(this.arrayDetalle[i].precioseleccionado * this.arrayDetalle[i].cantidad * this.arrayDetalle[i].descuento/100))
            }
            return resultado;
        },

    },
    methods: {
        ejecutarFunciones() {
            this.obtenerSaldosNegativos();
            this.mostrarDetalle();
        },
        atajoButton: function (event) {
            //console.log(event.keyCode);
            //console.log(event.ctrlKey);
            if (event.shiftKey && event.keyCode === 81) {
                event.preventDefault();
                this.$refs.impuestoRef.focus();
            }
            if (event.shiftKey && event.keyCode === 87) {
                event.preventDefault();
                this.$refs.serieComprobanteRef.focus();
            }
            if (event.shiftKey && event.keyCode === 69) {
                event.preventDefault();
                this.$refs.numeroComprobanteRef.focus();
            }
            if (event.shiftKey && event.keyCode === 82) {
                event.preventDefault();
                this.$refs.articuloRef.focus();
            }
            if (event.shiftKey && event.keyCode === 84) {
                event.preventDefault();
                this.$refs.precioRef.focus();
            }
            if (event.shiftKey && event.keyCode === 89) {
                event.preventDefault();
                this.$refs.cantidadRef.focus();
            }
            if (event.shiftKey && event.keyCode === 65) {
                event.preventDefault();
                this.$refs.cantidad_paquetesRef.focus();
            }
            if (event.shiftKey && event.keyCode === 85) {
                event.preventDefault();
                this.$refs.descuentoRef.focus();
            }
        },

        verificarComunicacion() {
            axios.post('/venta/verificarComunicacion')
                .then(function (response) {
                    if (response.data.RespuestaComunicacion.transaccion === true) {
                        document.getElementById("comunicacionSiat").innerHTML = response.data.RespuestaComunicacion.mensajesList.descripcion;
                        document.getElementById("comunicacionSiat").className = "badge bg-success";
                        // Actualiza el valor de scuis
                        //this.scuis = response.data.scuis;
                    } else {
                        document.getElementById("comunicacionSiat").innerHTML = "Desconectado";
                        document.getElementById("comunicacionSiat").className = "badge bg-secondary";
                        // Actualiza el valor de scuis
                        //this.scuis = "Inexistente";
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        cuis() {
            axios.post('/venta/cuis')
                .then(function (response) {
                    if (response.data.RespuestaCuis.transaccion === false) {
                        document.getElementById("cuis").innerHTML = "CUIS: " + response.data.RespuestaCuis.codigo;
                        document.getElementById("cuis").className = "badge bg-primary";
                    } else {
                        document.getElementById("cuis").innerHTML = "CUIS: Inexistente";
                        document.getElementById("cuis").className = "badge bg-secondary";
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        cufd() {
            axios.post('/venta/cufd')
                .then(function (response) {
                    if (response.data.transaccion != false) {
                        document.getElementById("cufd").innerHTML = "CUFD vigente: " + response.data.fechaVigencia.substring(0, 16);
                        document.getElementById("direccion").innerHTML = response.data.direccion;
                        document.getElementById("cufdValor").innerHTML = response.data.codigo;
                        document.getElementById("cufd").className = "badge bg-info";
                    } else {
                        document.getElementById("cufd").innerHTML = "No existe CUFD vigente";
                        document.getElementById("cufd").className = "badge bg-secondary";
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        nextNumber() {
            if (!this.num_comprob || this.num_comprob === "") {
                this.last_comprobante++;
                // Completa con ceros a la izquierda hasta alcanzar 5 dígitos
                this.num_comprob = this.last_comprobante.toString().padStart(5, "0");
            }
        },


        listarVenta(page, buscar, criterio) {
            let me = this;
            var url = '/venta?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayVenta = respuesta.ventas.data;
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
                //console.log(response.clientes);
                let respuesta = response.data;
                q: search
                me.arrayCliente = respuesta.clientes;
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
            //console.log(val1);
            this.email = val1.email;
            this.nombreCliente = val1.nombre;
            this.documento = val1.num_documento;
            this.tipo_documento = val1.tipo_documento;
            this.complemento_id = val1.complemento_id;

        },

        buscarArticulo() {
            clearTimeout(this.timer);
            this.timer = setTimeout(() => {
                let me = this;
                var url = '/articulo/buscarArticuloVenta?filtro=' + me.codigo;

                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    console.log('busqueda articulo', respuesta);
                    me.arrayArticulo = respuesta.articulos;
                    if (me.arrayArticulo.length > 0) {
                        me.fecha_vencimiento = me.arrayArticulo[0]['fecha_vencimiento'];
                    }
                    const fechaActual = new Date();
                    const año = fechaActual.getFullYear();
                    let mes = fechaActual.getMonth() + 1;
                    mes = mes < 10 ? '0' + mes : mes; 
                    let dia = fechaActual.getDate();
                    dia = dia < 10 ? '0' + dia : dia;
                    const fechaVencimiento = me.fecha_vencimiento;
                    if (fechaVencimiento <= `${año}-${mes}-${dia}`) {
                        console.log('VENCIDO', fechaVencimiento);
                        me.arrayArticulo.length = 0;
                        me.precioseleccionado = null;
                        me.advertenciaFechaVencimiento();
                        me.codigo = null;
                    } else {
                        if (me.arrayArticulo.length > 0) {
                        me.articulo = me.arrayArticulo[0]['nombre'];
                        me.idarticulo = me.arrayArticulo[0]['id'];
                        me.codigo = me.arrayArticulo[0]['codigo'];
                        me.precio = me.arrayArticulo[0]['precio_venta'];
                        me.stock = me.arrayArticulo[0]['saldo_stock'];
                        me.medida = me.arrayArticulo[0]['medida'];
                        me.codigoProductoSin = me.arrayArticulo[0]['codigoProductoSin'];

                        //----precios---
                        me.unidad_envase= me.arrayArticulo[0]['unidad_envase'];
                        me.precio_uno = me.arrayArticulo[0]['precio_uno'];
                        me.precio_dos = me.arrayArticulo[0]['precio_dos'];
                        me.precio_tres = me.arrayArticulo[0]['precio_tres'];
                        me.precio_cuatro = me.arrayArticulo[0]['precio_cuatro'];
                        me.fotografia = me.arrayArticulo[0]['fotografia'];
                        }
                        else {
                            me.articulo = 'No existe este articulo';
                            me.idarticulo = 0;
                        }
                        me.codigo = me.codigo;
                        console.log('SIRVE', fechaVencimiento);
                    }
                    // console.log('fechaDeVencimiento:', me.fecha_vencimiento);
                    console.log('Fecha Actual:', fechaActual);
                })
                    .catch(function (error) {
                        console.log(error);
                    });
            }, 1000);
        },
        pdfVenta(id) {
            window.open('/venta/pdf/' + id, '_blank');
        },
        cambiarPagina(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            //Envia la petición para visualizar la data de esa página
            me.listarVenta(page, buscar, criterio);
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
            this.precioBloqueado = true;
            let me = this;

            let actividadEconomica = 474110;
            let codigoProductoSin = document.getElementById("codigoProductoSin").value;
            let codigoProducto = document.getElementById("codigo").value;
            let descripcion = document.getElementById("nombre_producto").value;
            let cantidad = document.getElementById("cantidad").value;
            let unidadMedida = 57;
            let precioUnitario = document.getElementById("precio").value;
            let montoDescuento = document.getElementById("descuento").value;
            let subTotalValor = document.getElementById("sTotal");
            let subTotal = subTotalValor.textContent;
            let numeroSerie = null;
            let numeroImei = null;


            if (me.idarticulo == 0 || me.cantidad == 0 || me.precio == 0) {

            } else {
                if (me.encuentra(me.idarticulo)) {
                    swal({
                        type: 'error',
                        title: 'Error...',
                        text: 'Este Artículo ya se encuentra agregado!',
                    })
                } else {
                    console.log('saldo negativooo', me.saldosNegativos);
                    if (me.saldosNegativos === 0) {
                        console.log('cantidad', me.cantidad);
                        console.log('saldo stock', me.stock);
                        if(this.paquni >1){
                                me.cantidad = me.cantidad*this.paquni;     
                                  console.log('cantidad agregado', me.cantidad);
                        
                            }

                        if (me.stock < me.cantidad) {
                            swal({
                                type: 'error',
                                title: 'Error...',
                                text: 'No hay stock disponible!'
                            });
                        } else {
                           
                         
                            me.arrayDetalle.push({
                                idarticulo: me.idarticulo,
                                articulo: me.articulo,
                                medida: me.medida,
                                cantidad: me.cantidad,
                                cantidad_paquetes: me.cantidad_paquetes,
                                precio: me.precio,
                                descuento: me.descuento,
                                stock: me.saldo_stock,
                                precioseleccionado: me.precioseleccionado//--aumenTe esTo

                            });
                            console.log("ARRAY_AGREGADO",me.arrayDetalle);

                            me.arrayProductos.push({
                                actividadEconomica: actividadEconomica,
                                codigoProductoSin: codigoProductoSin,
                                codigoProducto: codigoProducto,
                                descripcion: descripcion,
                                cantidad: cantidad,
                                unidadMedida: unidadMedida,
                                precioUnitario: precioUnitario,
                                montoDescuento: montoDescuento,
                                subTotal: subTotal,
                                numeroSerie: numeroSerie,
                                numeroImei: numeroImei
                            });

                            me.codigo = '';
                            me.idarticulo = 0;
                            me.articulo = '';
                            me.medida = '';
                            me.cantidad = 0;
                            me.precio = 0;
                            me.descuento = 0;
                            me.stock = 0;
                            me.sTotal = 0;
                        }
                    } else if (me.saldosNegativos === 1) {
                        console.log('saldo negativo', me.saldosNegativos);
                        me.arrayDetalle.push({
                            idarticulo: me.idarticulo,
                            articulo: me.articulo,
                            medida: me.medida,
                            cantidad: me.cantidad,
                            cantidad_paquetes:me.cantidad_paquetes,
                            precio: me.precio,
                            descuento: me.descuento,
                            stock: me.saldo_stock,
                            precioseleccionado: me.precioseleccionado
                        });
                        console.log("ARRAY_AGREGADO",me.arrayDetalle);

                        me.arrayProductos.push({
                            actividadEconomica: actividadEconomica,
                            codigoProductoSin: codigoProductoSin,
                            codigoProducto: codigoProducto,
                            descripcion: descripcion,
                            cantidad: cantidad,
                            unidadMedida: unidadMedida,
                            precioUnitario: precioUnitario,
                            montoDescuento: montoDescuento,
                            subTotal: subTotal,
                            numeroSerie: numeroSerie,
                            numeroImei: numeroImei
                        });

                        me.codigo = '';
                        me.idarticulo = 0;
                        me.articulo = '';
                        me.medida = '';
                        me.cantidad = 0;
                        me.precio = 0;
                        me.descuento = 0;
                        me.stock = 0;
                        me.sTotal = 0;
                    }
                }

            }

        },
        agregarDetalleModal(data) {
            this.codigo = data.codigo;
            console.log('SLECCIONE ESTO:', this.codigo);
        },
        listarArticulo(buscar, criterio) {
            let me = this;
            var url = '/articulo/listarArticuloVenta?buscar=' + buscar + '&criterio=' + criterio + '&idAlmacen=' + this.idAlmacen;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayArticulo = respuesta.articulos;
                console.log('listar articulo',respuesta);
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        obtenerSaldosNegativos() {
            let me = this;
            axios.get('/configuracion/saldos-negativos')
                .then(response => {
                    me.saldosNegativos = response.data.saldosNegativos;
                    console.log('Saldos Negativos:', me.saldosNegativos);
                })
                .catch(error => {
                    console.error('Error al obtener saldos negativos:', error);
                });
        },
        datosConfiguracion() {
            let me = this;
            var url = '/configuracion/editar';

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.permitirDevolucion = respuesta.configuracionTrabajo.permitirDevolucion;
                console.log("PermitirDevolucion: " + me.permitirDevolucion);

            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        obtenerDatosUsuario() {
            axios.get('/venta')
                .then(response => {
                    this.usuarioAutenticado = response.data.usuario.usuario;
                    this.puntoVentaAutenticado = response.data.usuario.idpuntoventa;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        
        //--------HASTA AQUI--------------------
        selectAlmacen() {
            let me = this;
            var url = '/almacen/selectAlmacen';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayAlmacenes = respuesta.almacenes;
                console.log(me.arrayAlmacenes);

            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getAlmacenProductos(almacen) {
            let me = this;
            me.idAlmacen = almacen.id;
            console.log(me.idAlmacen);
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
            if (me.tipo_comprobante == 0) me.errorMostrarMsjVenta.push("Seleccione el Comprobante");
            if (!me.impuesto) me.errorMostrarMsjVenta.push("Ingrese el impuesto de compra");
            if (me.arrayDetalle.length <= 0) me.errorMostrarMsjVenta.push("Ingrese detalles");

            if (me.errorMostrarMsjVenta.length) me.errorVenta = 1;

            return me.errorVenta;
        },

        aplicarDescuento() {
            const descuentoGiftCard = this.descuentoGiftCard;
            const numeroTarjeta = this.numeroTarjeta;
            let idtipo_pago;

            if (numeroTarjeta && descuentoGiftCard) {
                idtipo_pago = 86;
            } else if (numeroTarjeta && !descuentoGiftCard) {
                idtipo_pago = 10;
            } else {
                idtipo_pago = descuentoGiftCard ? 35 : 1;
            }

            this.registrarVenta(idtipo_pago);
        },

        aplicarCombinacion() {
            const descuentoGiftCard = this.descuentoGiftCard
            const idtipo_pago = descuentoGiftCard ? 40 : 2; 

            this.registrarVenta(idtipo_pago);
        },

        otroMetodo(metodoPago){
            const idtipo_pago = metodoPago;
            this.registrarVenta(idtipo_pago);
        },
        //-------------REGISTRARAR VENTA ------
        registrarVenta(idtipo_pago) {
            if (this.validarVenta()) {
                return;
            }

            let me = this;
            this.mostrarSpinner = true;
            this.idtipo_pago = idtipo_pago;

            for (let i = 0; i < me.cuotas.length; i++) {                
                console.log('LLEGA ARRAYDATA!', me.cuotas[i]);
            }

            console.log("hola");
            console.log(this.primer_precio_cuota);
            axios.post('/venta/registrar', {
                'idcliente': this.idcliente,
                'tipo_comprobante': this.tipo_comprobante,
                'serie_comprobante': this.serie_comprobante,
                'num_comprobante': this.num_comprob,
                'impuesto': this.impuesto,
                'total': this.calcularTotal,
                'idAlmacen': this.idAlmacen,
                'idtipo_pago': idtipo_pago,
                'idtipo_venta': this.idtipo_venta,
                'primer_precio_cuota': this.primer_precio_cuota,
                // Datos para el crédito de venta
                'cliente': this.cliente,
                'documento': this.documento,
                'tipoEntrega': this.mesa,
                'numero_cuotasCredito': this.numero_cuotas, // Cambio de nombre
                'tiempo_dias_cuotaCredito': this.tiempo_diaz, // Cambio de nombre
                'totalCredito': this.primera_cuota ? this.calcularTotal - this.cuotas[0].totalCancelado : this.calcularTotal, // Asegúrate de tener esta variable
                'estadoCredito': "Pendiente", // Asegúrate de tener esta variable

                // Cuotas del crédito
                'cuotaspago': this.cuotas,
                'data': this.arrayDetalle

            }).then((response) => {
                let idVentaRecienRegistrada = response.data.id;
                this.emitirFactura(idVentaRecienRegistrada);

                if (response.data.id > 0) {
                    // Restablecer valores después de una venta exitosa
                    me.listado = 1;
                    me.listarVenta(1, '', 'num_comprob');
                    me.cerrarModal2();
                    me.cerrarModal3();
                    me.idproveedor = 0;
                    me.tipo_comprobante = 'FACTURA';
                    me.nombreCliente = '';
                    me.idcliente = 0;
                    me.tipo_documento = 0;
                    me.complemento_id = '';
                    me.documento = '';
                    me.imagen = '';
                    me.serie_comprobante = '';
                    me.num_comprob = '';
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
                    me.primer_precio_cuota = 0;

                    //window.open('/factura/imprimir/' + response.data.id);
                } else {
                    console.log(response)
                    if (response.data.valorMaximo) {
                        //alert('El valor de descuento no puede exceder el '+ response.data.valorMaximo);
                        swal(
                            'Aviso',
                            'El valor de descuento no puede exceder el ' + response.data.valorMaximo,
                            'warning'
                        )
                        return;
                    } else {

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

            }).catch((error) => {
                console.log(error);
            });
        },

        async emitirFactura(idVentaRecienRegistrada) {

        let me = this;

        let idventa = idVentaRecienRegistrada;
        let numeroFactura = document.getElementById("num_comprobante").value;
        let cuf = "464646464";
        let cufdValor = document.getElementById("cufdValor");
        let cufd = cufdValor.textContent;
        let direccionValor = document.getElementById("direccion");
        let direccion = direccionValor.textContent;
        var tzoffset = (new Date()).getTimezoneOffset() * 60000;
        let fechaEmision = (new Date(Date.now() - tzoffset)).toISOString().slice(0, -1);
        let id_cliente = document.getElementById("idcliente").value;
        let nombreRazonSocial = document.getElementById("nombreCliente").value;
        let numeroDocumento = document.getElementById("documento").value;
        let complemento = document.getElementById("complemento_id").value;
        let tipoDocumentoIdentidad = document.getElementById("tipo_documento").value;
        let montoTotal = (this.calcularTotal * parseFloat(this.monedaVenta[0])).toFixed(2);
        let descuentoAdicional = document.getElementById("descuentoAdicional").value;
        let usuario = document.getElementById("usuarioAutenticado").value;
        let codigoPuntoVenta = document.getElementById("puntoVentaAutenticado").value;
        //let codigoPuntoVenta = this.puntoVentaAutenticado;
        let montoGiftCard = document.getElementById("descuentoGiftCard").value;
        let codigoMetodoPago = this.idtipo_pago;
        let montoTotalSujetoIva = montoTotal - this.descuentoGiftCard;
        let correo = document.getElementById("email").value;

        console.log("El monto de Descuento de Gift Card es: " + this.descuentoGiftCard);
        console.log("El tipo de documento es: " + tipoDocumentoIdentidad);
        console.log("El complemento de documento es: " + complemento);

        try {
            const response = await axios.get('/factura/obtenerLeyendaAleatoria');
            this.leyendaAl = response.data.descripcionLeyenda;
            console.log("El dato de leyenda llegado es: " + this.leyendaAl);
        } catch (error) {
            console.error(error);
            return '"Ley N° 453: Los servicios deben suministrarse en condiciones de inocuidad, calidad y seguridad."';
        }

        try {
                if (tipoDocumentoIdentidad === '5') {
                    const response = await axios.post('/factura/verificarNit/' + numeroDocumento);
                    if (response.data === 'NIT ACTIVO') {
                        me.codigoExcepcion = 0;
                        alert("NIT VÁLIDO.");
                    } else {
                        me.codigoExcepcion = 1;
                        alert("NIT INVÁLIDO.");
                    }
                }else{
                    me.codigoExcepcion = 0;
                }
            } catch (error) {
                console.error(error);
                return 'No se pudo verificar el NIT';
            }

        var factura = [];
        factura.push({
            cabecera: {
                nitEmisor: "5153610012",
                razonSocialEmisor: "365 SOFT",
                municipio: "Cochabamba",
                telefono: "77490451",
                numeroFactura: numeroFactura,
                cuf: cuf,
                cufd: cufd,
                codigoSucursal: 0,
                direccion: direccion,
                codigoPuntoVenta: 6,
                fechaEmision: fechaEmision,
                nombreRazonSocial: nombreRazonSocial,
                codigoTipoDocumentoIdentidad: tipoDocumentoIdentidad,
                numeroDocumento: numeroDocumento,
                complemento: complemento,
                codigoCliente: numeroDocumento,
                codigoMetodoPago: codigoMetodoPago,
                numeroTarjeta: this.numeroTarjeta,
                montoTotal: montoTotal,
                montoTotalSujetoIva: montoTotalSujetoIva,
                codigoMoneda: 1,
                tipoCambio: 1,
                montoTotalMoneda: montoTotal,
                montoGiftCard: this.descuentoGiftCard,
                descuentoAdicional: descuentoAdicional,
                codigoExcepcion: this.codigoExcepcion,
                cafc: null,
                leyenda: this.leyendaAl,
                usuario: usuario,
                codigoDocumentoSector: 1
            }
        })
        me.arrayProductos.forEach(function (prod) {
            factura.push({ detalle: prod })
        })

        var datos = { factura };

        axios.post('/venta/emitirFactura', {
            factura: datos,
            id_cliente: id_cliente,
            idventa: idventa,
            correo: correo,
            cufd: cufd
        })
            .then(function (response) {
                var data = response.data;
                console.log(response);

                if (data === "VALIDADA") {
                    swal(
                        'FACTURA VALIDADA',
                        'Correctamente',
                        'success'
                    )
                    me.arrayProductos = [];
                    me.codigoExcepcion = 0;
                    me.idtipo_pago = '';
                    me.email = '';
                    me.descuentoGiftCard = '';
                    me.numeroTarjeta =  null;
                    me.recibido = '';
                    me.metodoPago = '';
                    me.cerrarModal2();
                    me.cerrarModal3();
                    me.listarVenta(1, '', 'id');
                    me.mostrarSpinner = false;
                } else{
                    me.arrayProductos = [];
                    me.codigoExcepcion = 0;
                    me.idtipo_pago = '';
                    me.descuentoGiftCard = '';
                    me.numeroTarjeta =  null;
                    me.recibido = '';
                    me.metodoPago = '';
                    me.last_comprobante = '';
                    me.cerrarModal2();
                    me.cerrarModal3();
                    me.listarVenta(1, '', 'id');
                    me.mostrarSpinner = false;
                    swal(
                        'FACTURA RECHAZADA',
                        data,
                        'warning'
                    );
                    me.eliminarVenta(idVentaRecienRegistrada);
                }
            })
            .catch(function (error) {
                console.error(error);
                me.arrayProductos = [];
                me.codigoExcepcion = 0;
                swal(
                    'INTENTE DE NUEVO',
                    'Comunicacion con SIAT fallida',
                    'error');
                me.mostrarSpinner = false;
                me.idtipo_pago = '';
                me.numeroTarjeta =  null;
                me.descuentoGiftCard = '';
                me.recibido = '';
                me.metodoPago = '';
            });
        },

        mostrarDetalle() {
            let me = this;
            me.selectAlmacen();
            me.listado = 0;

            me.idproveedor = 0;
            me.tipo_comprobante = 'BOLETA';
            me.serie_comprobante = '';
            me.nextNumber();
            //me.num_comprobante = '';
            me.impuesto = 0.18;
            me.total = 0.0;
            me.idarticulo = 0;
            me.articulo = '';
            me.cantidad = 0;
            me.precio = 0;
            me.arrayDetalle = [];
        },
        ocultarDetalle() {
            this.listado = 1;
            this.codigo = null;
            this.arrayArticulo.length = 0;
            this.precioseleccionado = null;
            this.medida = null;
            this.nombreCliente = null;
            this.documento = null;
            this.email = null;

        },
        verVenta(id) {
            let me = this;
            me.listado = 2;

            //Obtener datos del ingreso
            var arrayVentaT = [];
            var url = '/venta/obtenerCabecera?id=' + id;

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                arrayVentaT = respuesta.venta;

                me.cliente = arrayVentaT[0]['nombre'];
                me.tipo_comprobante = arrayVentaT[0]['tipo_comprobante'];
                me.serie_comprobante = arrayVentaT[0]['serie_comprobante'];
                me.num_comprobante = arrayVentaT[0]['num_comprobante'];
                me.impuesto = arrayVentaT[0]['impuesto'];
                me.total = arrayVentaT[0]['total'];
            })
                .catch(function (error) {
                    console.log(error);
                });

            //obtener datos de los detalles
            var url = '/venta/obtenerDetalles?id=' + id;

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
            if (this.idAlmacen == 0) {
                return;
            }
            this.listarArticulo('', 'nombre');
            this.arrayArticulo = [];
            this.modal = 1;
            this.tituloModal = 'Seleccione los articulos que desee';
        },
        advertenciaFechaVencimiento() {
            swal({
                title: 'No Disponible',
                text: 'No puede seleccionar este producto porque esta vencido.',
                type: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar',
                confirmButtonClass: 'btn btn-success',
                buttonsStyling: false,
            }).then(() => {
                this.cerrarModal();
            });
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
                        me.listarVenta(1, '', 'num_comprobante');
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
         //-------------OBTENER PRECIOS Y MABRIR_MODAL----------
         listarPrecio() {
            let me = this;
            var url = '/precios';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayPrecios = respuesta.precio.data;
                console.log('PRECIOS',me.arrayPrecios);
                //me.precioCount = me.arrayBuscador.length;
            }).catch(function (error) {
                console.log(error);
            });
        },
        mostrarSeleccion() {
            console.log('Precio seleccionado:', this.precioseleccionado);
        },
        //--------------ABRIR MODAL------------------
        registrarAbrilModal() {
            this.modal2 = 1;
            this.cliente = this.nombreCliente;
            console.log('USUARIO LLEGA:', this.cliente);
            this.tituloModal2 = 'PAGO AL CONTADO ' + this.cliente; // Usamos '+' para concatenar el nombre del cliente
            this.tipoAccion2 = 1;
            this.idtipo_pago = 1
            console.log('idtipo_pago LLEGA:', this.idtipo_pago);
        },
        registrarAbrilModal2() {
            this.modal3 = 1;
            this.cliente = this.nombreCliente;
            console.log('USUARIO LLEGA:', this.cliente);
            this.tituloModal3 = 'CREDITOS ' + this.cliente; // Usamos '+' para concatenar el nombre del cliente
            this.tipoAccion3 = 1;
            this.idtipo_pago = 2
            console.log('idtipo_pago LLEGA:', this.idtipo_pago);
        },
        cerrarModal2() {
            this.modal2 = 0;
            this.tituloModal2 = '';
        },
        cerrarModal3() {
            this.modal3 = 0;
            this.tituloModal3 = '';
            this.numero_cuotas = '';
            this.tiempo_diaz = '';
            this.primera_cuota = false;
            this.cuotas = [];
        },
        // inicializarFechas() {
        //         const fechaActual = new Date().toISOString().substr(0, 10);
        //         this.fecha_credito = fechaActual;
        //         this.fecha_entrega = fechaActual;
        //         this.diasToAdd = 0;
        //         console.log('fecha_credito inicial:', this.fecha_credito);
        //         console.log('diasToAdd inicial:', this.diasToAdd);
        // },
        // //--------SUMAR LOS DIAS---
        // sumarDias() {
        //     console.log('Evento @input llamado');
        //     if (this.diasToAdd && this.diasToAdd > 0) {
        //         const fechaActual = new Date(this.fecha_credito);
        //         fechaActual.setDate(fechaActual.getDate() + parseInt(this.diasToAdd));
        //         this.fecha_credito = fechaActual.toISOString().substr(0, 10);
        //         // this.fecha_credito = fechaActual.toISOString().split('T')[0];
        //     }
        // },
        //------CALCULAR CALCULADORA--
        // calcularCambio() {
        //     if (isNaN(this.totalprue) || isNaN(this.recibido)) {
        //         alert('Ingresa valores válidos.');
        //         return;
        //     }
        //     if (this.recibido === 0) {
        //         this.efectivo = this.recibido;
        //         console.log('EFECTIVO',this.efectivo);
        //         this.cambio = 0;
        //         this.faltante = 0;
                
        //     } else if (this.recibido < this.totalprue) {
        //         this.efectivo = this.recibido;
        //         this.faltante = (this.totalprue - this.efectivo).toFixed(2);
        //     } else if (this.recibido === this.totalprue) {
        //         this.efectivo = this.recibido;
        //         this.cambio = '0 BS (Sin cambio)';
        //         this.cambio = 0;
        //         this.faltante = 0;
        //     } else {
        //         this.efectivo = this.recibido;
        //         this.cambio = (this.efectivo - this.totalprue).toFixed(2);
        //         this.faltante = 0;
        //     }
        // },
        calcularCambio() {
            // Convierte this.recibido a un número
            const recibidoNumero = parseFloat(this.recibido);
            if (recibidoNumero === 0) {
            this.efectivo = recibidoNumero;
            console.log('EFECTIVO', this.efectivo);
            this.cambio = 0;
            this.faltante = 0;
            } else if (recibidoNumero < this.total) {
            this.efectivo = recibidoNumero;
            this.faltante = (this.total - this.efectivo).toFixed(2);
            } else if (recibidoNumero === this.total) {
            this.efectivo = recibidoNumero;
            this.cambio = 0;
            this.faltante = 0;
            } else {
            this.efectivo = recibidoNumero;
            this.cambio = (this.efectivo - this.total).toFixed(2);
            this.faltante = 0;
            }
        },
        generarCuotas() {
            this.cuotas = []; // Limpiamos la lista de cuotas
            const fechaHoy = new Date(); // Obtenemos la fecha de hoy

            // Calcular el monto entero y el monto con decimal
            const montoEntero = Math.floor(this.total / this.numero_cuotas);
            const montoDecimal = (this.total - montoEntero * (this.numero_cuotas - 1)).toFixed(2);

            let primerPrecioCuota;
            let fechaInicioPago;
            let saldos_total;
            let estadoCuota;
            if (this.primera_cuota) {
                primerPrecioCuota = montoEntero;
                console.log('primerPrecioCuota',primerPrecioCuota);
                fechaInicioPago = fechaHoy;
                saldos_total = (this.total - primerPrecioCuota).toFixed(2);
                console.log('saldos_total',saldos_total);
                estadoCuota = 'Pagado';
            } else {
                primerPrecioCuota = 0;
                fechaInicioPago = new Date(fechaHoy.getTime() + this.tiempo_diaz * 24 * 60 * 60 * 1000); // Sumar 7 días
                saldos_total = this.total;
                estadoCuota = 'Pendiente';
            }

            for (let i = 0; i < this.numero_cuotas; i++) {
                const fechaPago = new Date(fechaInicioPago.getTime() + i * this.tiempo_diaz * 24 * 60 * 60 * 1000);
                const dia = fechaPago.getDate();
                const mes = fechaPago.getMonth() + 1;
                const año = fechaPago.getFullYear();

                const cuota = {
                    fechaPago: `${año}-${mes}-${dia} ${fechaPago.toLocaleTimeString()}`,
                    precioCuota: i === this.numero_cuotas - 1 ? parseFloat(montoDecimal).toFixed(2)  : montoEntero,
                    totalCancelado: i === 0 ? primerPrecioCuota : 0,
                    saldo : saldos_total,//saldo
                    fechaCancelado: i === 0 && this.primera_cuota ? `${año}-${mes}-${dia} ${fechaPago.toLocaleTimeString()}` : null,
                    estadocuocre: i === 0 ? (this.primera_cuota ? 'Pagado' : estadoCuota) : 'Pendiente',
                };

                this.cuotas.push(cuota);
                console.log('LLEGA_ARRAY_CUOTA',this.cuotas);
            }
        },
        preciounid(){
        
        console.log("precio unid",this.paquni);}
        

       // convertirCantidad() {
             // Buscamos la unidad seleccionada por su id 
    //         const unidad = this.paquni; 
             // Si la unidad es paquete, dividimos la cantidad por 6 y redondeamos hacia arriba 
             // Si la unidad es unidad, multiplicamos la cantidad por 6
        //      if (unidad > 1 ) {
          //       } 
           //      else if (unidad === 1 ) {
           //          this.cantidad = this.cantidad * this.unidad_envase; } },
        
        
        
       

       
        
    
        //--------------HASTA AQUI-----------------------------

    },

    created() {
        this.listarPrecio();
        axios.get('/ruta-a-tu-endpoint-laravel-para-obtener-ultimo-comprobante')
            .then(response => {
                const lastComprobante = response.data.last_comprobante;

                this.last_comprobante = lastComprobante;

                this.nextNumber();
            })
            .catch(error => {
                console.error('Error al obtener el último comprobante:', error);
            });
    },



    mounted() {
        this.listarVenta(1, this.buscar, this.criterio);
        window.addEventListener('keydown', this.atajoButton);
        this.datosConfiguracion();
        this.verificarComunicacion();
        this.cuis();
        this.cufd();
        this.obtenerDatosUsuario();
    }
}
</script>
<style>    
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

    @media (min-width: 600px) {
        .btnagregar {
            margin-top: 2rem;
        }
    }
    .card-img {
        width: 120px;
        height: auto;
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px;
    }
    /* .negrita-input {
        font-weight: bold;
    } */
</style>
