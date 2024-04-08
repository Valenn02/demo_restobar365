<template>
    
    <main class="main">

        <!-- Breadcrumb -->
        <ol class="breadcrumb bg-light rounded px-3 shadow-sm">
            <li class="breadcrumb-item"><a class="text-primary text-decoration-none" href="/">Escritorio</a></li>
            <li class="breadcrumb-item active text-primary" aria-current="page">Compras</li>
            <li class="breadcrumb-item active text-primary" aria-current="page">{{showRegistrarCompra?"Ingresos":"Pedidos a proveedores"}}</li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <!-- <div :class="{ 'card': listado === 1 }"> -->
            <div class="card" v-if="!showRegistrarCompra">
                <div class="card-header" >
                    <i class="fa fa-align-justify"></i>{{ titulo }}
                    <button v-if="listado==1" type="button" @click="mostrarDetalle('pedido', 'registrar')" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>

                    <!-- <button type="button" @click="mostrarDetalle()" class="btn btn-secondary"> -->
                </div>

                <!-- Listado EN LA VISTA PRINCIPAL-->
                <div v-if="listado == 1">
                    <div class="card-body">
                        <div class="form-group row justify-content-between">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="custom-select bg-light" v-model="criterio">
                                        <option value="tipo_comprobante">Proveedor</option>
                                        <option value="num_comprobante">Nro Doc</option>
                                        <option value="fecha_hora">Almacen</option>
                                        <option value="fecha_hora">Nota/Ref</option>

                                    </select>
                                    <div class="input-group mb-3 border border-gray shadow-lg">
                                        
                                        <div class="input-group-prepend flex items-center mt-1 ml-2">
                                            <span class="input-group-text bg-transparent border-0"><i class="fa fa-search"></i></span>
                                        </div>
                                        <input type="text" class="form-control border-0" placeholder="Buscar" aria-label="Buscar">
                                    </div>
                                    
                                </div>

                            </div>
                            <div class="d-flex align-items-center">
                                <small class="text-muted">Mostrar:&nbsp;</small>

                                <select class="custom-select bg-light"  v-model="itemsPerPage" @change="cambiarNumeroElementos">
                                    <option value="5">5</option>

                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                </select>
                            </div>



                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Proveedor</th>
                                        <th>Almacen</th>
                                        <th>Total</th>
                                        <th>Fecha entrega</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="pedprov in arrayPedidoProv" :key="pedprov.id">
                                        <td v-text="pedprov.id"></td>
                                        <td>{{ new Intl.DateTimeFormat('es-ES').format(new Date(pedprov.fecha_pedido)) }}</td>
                                        <td>{{ new Date(pedprov.fecha_pedido).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) }}</td>

                                        <td v-text="pedprov.nombre_proveedor"></td>
                                        <td v-text="pedprov.nombre_almacen"></td>
                                        <td v-text="pedprov.total"></td>
                                        <!-- <td>{{ Math.floor((new Date(pedprov.fecha_entrega) - new Date()) / (1000 * 60 * 60 * 24))+1 }} dias</td> -->
                                        <td>{{ new Intl.DateTimeFormat('es-ES').format(new Date(pedprov.fecha_entrega)) }}</td>


                                        <td >
                                            <i class="fa fa-circle" :style="{ color: getColorForEstado(pedprov.estado,pedprov.fecha_entrega) }"></i>&nbsp;
                                            {{new Date(pedprov.fecha_entrega) < new Date() ? 'Caducada' : pedprov.estado }}
                                        </td>
                                        
                                        <td>
                                            <button type="button" @click="abrirModalDetalles(pedprov)"
                                                    class="btn btn-outline-success btn-sm rounded">
                                                <i class="fa fa-eye fa-sm"></i>
                                            </button>
                                            <button type="button" @click="mostrarDetalle('pedido', 'editar', pedprov)" 
                                                class="btn btn-outline-info btn-sm rounded"
                                                >
                                                <i class="fa fa-print fa-sm"></i>
                                            </button>
                                            <button type="button" @click="mostrarDetalle('pedido', 'editar', pedprov)" 
                                                class="btn btn-outline-warning btn-sm rounded"
                                                >
                                                <i class="fa fa-pencil fa-sm"></i>
                                            </button>
                                            <button type="button" @click="eliminarPedido(pedprov.id)" 
                                                class="btn btn-outline-danger btn-sm rounded ">
                                                <i class="fa fa-trash fa-sm"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- <div class="table-responsive">
                            <div style="text-align: center; font-size: 18px;">DETALLE DE PEDIDO</div>
                            <table class="table table-bordered table-striped table-sm">  
                                <thead>                    
                                    <tr>
                                        <th>CODIGO</th>
                                        <th>PRODUCTO</th>
                                        <th>UNIDAD</th>
                                        <th>CANTIDAD</th>
                                        <th>UNIDAD</th>
                                        <th>IMPORTE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="pedprovdet in arrayPedidoProvDet" :key="pedprovdet.id">
                                        <td v-text="pedprovdet.codigo"></td>
                                        <td v-text="pedprovdet.articulo"></td>
                                        <td v-text="pedprovdet.unid_paq"></td>
                                        <td v-text="pedprovdet.cantidad"></td>
                                        <td v-text="pedprovdet.precio"></td>
                                        <td v-text="pedprovdet.total"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> -->
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
                </div>
                <!--Fin Listado HASTA QUI-->
                <!-- Detalle-->
                <div v-else-if="listado == 0" >
                        <div class="card-body form-group row pb-0">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="" class="font-weight-bold">Proveedor <span class="text-danger">*</span></label>

                                    <v-select 
                                        :on-search="selectProveedor" 
                                        label="nombre" 
                                        :options="arrayProveedor"
                                        placeholder="Buscar Proveedores..." 
                                        :onChange="getDatosProveedor"
                                        v-model="proveedorSeleccionado"
                                        >
                                    </v-select>
                                    <p v-if="!proveedorSeleccionado" class="card-text"><small class="text-danger">Seleccione un proveedor para agregar productos al pedido</small></p>

                                    
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="" class="font-weight-bold">Almacen <span class="text-danger">*</span></label>
                                    <select class="form-control" v-model="AlmacenSeleccionado" @change="getDatosAlmacen">
                                        <option value="0" disabled>Seleccione</option>
                                        <option v-for="opcion in arrayAlmacenes" :key="opcion.id" :value="opcion.id">{{ opcion.nombre_almacen }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="" class="font-weight-bold">Unidad <span class="text-danger">*</span></label>
                                    <select class="form-control" v-model="tipopedido">
                                        <option value="0" disabled>Seleccione</option>
                                        <option v-for="tipopedido in arrayTipPedi" :key="tipopedido" :value="tipopedido" v-text="tipopedido"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="" class="font-weight-bold">Fecha pedido <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" v-model="fechaPedido">
                            </div>
                            <div class="col-md-2">
                                <label for="" class="font-weight-bold">Fecha entrega <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" v-model="fechaEntrega">
                            </div>

                            <!-- <div class="col-md-12">
                                <div v-show="errorIngreso" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjIngreso" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                        <hr/>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-3">
                                <div class="form-group" v-if="proveedorSeleccionado">
                                    <label  class="font-weight-bold">Seleccione producto
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="codigo" @keyup="buscarArticulo()"
                                            placeholder="Codigo producto" ref="articuloRef" >
                                        <button @click="abrirModal()" class="btn btn-primary" >...</button>
                                    </div>
                                    
                                </div>
                                <div class="form-group" v-else>
                                    <label  class="font-weight-bold">Seleccione producto
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="codigo" @keyup="buscarArticulo()"
                                            placeholder="Codigo producto" ref="articuloRef" disabled>
                                        <button @click="abrirModal()" class="btn btn-primary" disabled>...</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card-body" v-if="arrayArticuloSeleccionado.length>0">
                                        <h3>
                                            {{ arrayArticuloSeleccionado[0].nombre }}
                                            <small class="text-muted">803</small>
                                        </h3>
                                        <p>
                                            {{ arrayArticuloSeleccionado[0].descripcion }}
                                        </p>
                                </div>
                            </div>
                            <div class="col-md-2" v-if="arrayArticuloSeleccionado.length > 0" >
                                <img
                                    v-if="arrayArticuloSeleccionado.length > 0 && arrayArticuloSeleccionado[0].fotografia"
                                    :src="'img/articulo/' + arrayArticuloSeleccionado[0].fotografia + '?t=' + new Date().getTime()"
                                    width="50" height="50" ref="imagen" class="card-img"/>
                                    <div v-else style="height:100px" class="bg-light p-3 d-flex justify-content-center align-items-center">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fa fa-camera" aria-hidden="true"></i>
                                            <span class="mr-2 text-center">Producto sin imagen</span>
                                        </div>
                                    </div>


                            </div>
                            <div class="col-md-2" v-if="arrayArticuloSeleccionado.length > 0">
                                <div class="form-group">
                                    <label>Costo Paquete:</label>
                                    <p class="h5">{{ precio_costo_paq }}</p>
                                    <!-- <label for="">Shift + T</label> -->
                                </div>
                            </div>
                            <div class="col-md-2" v-if="arrayArticuloSeleccionado.length > 0">
                                <div class="form-group">
                                    <label>Costo unitario:</label>
                                    <p class="h5">{{ precio }}</p>
                                    <!-- <label for="">Shift + T</label> -->
                                </div>
                            </div>
                            <div class="col-md-2" v-if="arrayArticuloSeleccionado.length > 0">
                                <div class="form-group">
                                    <label>Unidades por paquete:</label>
                                    <p class="h5">{{ unidad_x_paquete }}</p>

                                    <!-- <label for="">Shift + T</label> -->
                                </div>
                            </div>   
                        </div>
                        <hr/>
                        <div class="form-group row" v-if="arrayArticuloSeleccionado.length > 0">
                            <div class="col-md-2">
                           
                                <div class="input-group mb-2 align-items-center">

                                    <div class="input-group-append">
                                        <small class="font-weight-bold">{{ tipopedido }}:&nbsp</small>
                                    </div>
                                    <input type="number" id="cantidad" class="form-control font-weight-bold  form-control-lg" v-model="cantidad" aria-describedby="basic-addon2">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group mb-3 align-items-center">
                                    <div class="input-group-append">
                                        <small class="font-weight-bold">Precio total:&nbsp</small>
                                    </div>
                                    <input type="number"  value="0" step="any" class="form-control font-weight-bold  form-control-lg" v-model="Sumatotal" readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button @click="agregarDetalle()" class="btn btn-outline-success btn-block ">
                                        <i class="fa fa-plus"></i> Añadir item
                                    </button>
                                    
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button @click="eliminarArticuloSeleccionado()" class="btn btn-outline-danger btn-block ">
                                        <i class="fa fa-plus"></i> Eliminar item
                                    </button>
                                </div>
                            </div>


                        </div>
                    

                        <!--listado para mostrar lo Añadido de item-->
                        <div class="form-group row ">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <td colspan="7">
                                                <h6 class="text-center font-weight-bold ">Detalle del pedido</h6>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Codigo</th>
                                            <th>Producto</th>
                                            <th>Costo Unit.</th>
                                            <th>Unid/Paq</th>
                                            <th>Total {{ tipopedido }}</th>
                                            <th>Costo Total</th>
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
                                            <!-- <td v-text="detalle.articulo"></td> -->
                                            <td>
                                                {{ detalle.codigo }}
                                            </td>
                                            <td>    
                                                {{ detalle.articulo }}
                                            </td>
                                            <td>
                                                {{ detalle.precio }}
                                            </td>
                                            <td>
                                                {{ detalle.unidad_x_paquete }}
                                            </td>
                                            <td>
                                                <input v-if="tipopedido=='Unidades'" v-model="detalle.cantidad" type="number" value="2"
                                                    class="form-control">
                                                <input v-else type="number" :value="(detalle.cantidad / detalle.unidad_x_paquete).toFixed(2)" class="form-control" disabled>

                                            </td>
                                            <td>
                                                {{ detalle.cantidad * detalle.precio }}
                                            </td>

                                        </tr>
                                        <tr>
                                            <td colspan="6">
                                                <h6 class="text-right font-weight-bold ">Total</h6>
                                            </td>
                                            <td >
                                                <h6 class="font-weight-bold ">{{ Totales }}</h6>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="7">
                                                No hay articulos agregados
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><span style="font-weight: bold;">FORMA DE PAGO</span></label>
                                    <input type="text" value="0" class="form-control" v-model="forma_pago" placeholder="Ej. Al contado">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><span style="font-weight: bold;">OBSERVACION</span></label>
                                    <input type="text" value="0" class="form-control" v-model="observacion" >
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label style="font-weight: bold;">IMPRIMIR</label>
                                    <select class="form-control" v-model="imprimir">
                                        <option value="0" disabled>Seleccione</option>
                                        <option v-for="imprimir in arrayImprimir" :key="imprimir" :value="imprimir" v-text="imprimir"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                                <button v-if="idpedido!=null" type="button" class="btn btn-primary" @click="editarPedidoProv()">Editar
                                    Pedido</button>
                                <button v-else type="button" class="btn btn-primary" @click="registrarPedido()">Registrar
                                    Pedido</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin Detalle-->
                <!--Ver ingreso-->
                <div v-else-if="listado == 2">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Proveedor</label>
                                    <p v-text="proveedor"></p>
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
                                            <td>
                                                {{ detalle.precio * detalle.cantidad }}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="3" align="right"><strong>Total Parcial:</strong></td>
                                            <td>$ {{ totalParcial=(total - totalImpuesto).toFixed(2) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="3" align="right"><strong>Total Impuesto:</strong></td>
                                            <td>$ {{ totalImpuesto=(total * impuesto).toFixed(2) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="3" align="right"><strong>Total Neto:</strong></td>
                                            <td>$ {{ total }}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="4">
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
                </div>
                <!--Fin ver ingreso-->
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
                                    <!--button type="submit" @click="listarArticulo(buscarA,criterioA)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button-->
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Nombre</th>
                                        <th>Código</th>
                                        <th>Proveedor</th>
                                        <th>Costo Unid.</th>
                                        <th>Costo Paq.</th>
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
                                        <td v-text="articulo.articulo"></td>
                                        <td v-text="articulo.codigo"></td>
                                        <td v-text="articulo.nombre_proveedor"></td>
                                        <td v-text="articulo.precio"></td>
                                        <td v-text="articulo.precio_costo_paq"></td>
                                        <!-- <td>
                                            <div v-if="articulo.condicion">
                                                <span class="badge badge-success">Activo</span>
                                            </div>
                                            <div v-else>
                                                <span class="badge badge-danger">Desactivado</span>
                                            </div>

                                        </td> -->
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
        <detallepedidosproveedor v-if="showModalDetalle" @cerrar="cerrarModalDetalles" @abrirCompra="abrirFormularioCompra" :arrayPedidoSeleccionado="arrayPedidoSeleccionado" :arrayPedidoProvDet="arrayPedidoProvDet"></detallepedidosproveedor>
        <div v-if="showRegistrarCompra" class="mx-3">
            <registrarcompra @editarEstadoPedido="editarPedidoComprado"   @cerrar="cerrarFormularioCompra" :arrayDetallePedido="arrayDetallesAComprar" :arrayPedidoSeleccionado="arrayPedidoSeleccionado" @listarArticuloProveedor="listarArticuloProveedor" @abrirModalArticulos="abrirModalArticulos" :arrayArticuloSeleccionado="arrayArticuloSeleccionadoModal"></registrarcompra>

        </div>
        <div v-if="showModalArticulos">
            <modalagregarproductos @cerrar="cerrarModalArticulos" @agregarArticulo="agregarArticuloSeleccionado" :idproveedor="idproveedor"></modalagregarproductos>
        </div>

    </main>
</template>

<script>
import vSelect from 'vue-select';
export default {
    data() {
        return {
            titulo:'Pedidos a proveedor',
            estadoPedido:'En espera',
            showModalArticulos:false,
            arrayDetallesAComprar:[],
            showModalDetalle:false,
            showRegistrarCompra:false,
            itemsPerPage: 10,
            ingreso_id: 0,
            idproveedor: 0,
            idpedido:null,
            proveedor: '',
            nombre: '',
            tipo_comprobante: 'BOLETA',
            serie_comprobante: '',
            num_comprobante: '',
            impuesto: 0.18,
            total: 0.0,
            totalImpuesto: 0.0,
            totalParcial: 0.0,
            arrayPedidoProv:[],
            arrayProveedor: [],
            arrayDetalle: [],
            listado: 1,
            modal: 0,
            tituloModal: '',
            tipoAccion: 0,
            errorIngreso: 0,
            errorMostrarMsjIngreso: [],
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
            precio: 0,
            cantidad: 0,
            fechaEntrega: '',
            fechaPedido: '',
            AlmacenSeleccionado:1,
            idalmacen: 1,
            arrayAlmacenes: [],
            tipopedido : 'Unidades',
            arrayTipPedi : ['Unidades', 'Paquetes'],
            articulo: '',
            arrayArticuloSeleccionado: [],
            precio_costo_paq: 0,
            fotografia : '',
            Sumatotal: 0,
            unidad_x_paquete: 0,
            forma_pago: '',
            observacion: '',
            imprimir: 'Orden de pedido',
            arrayImprimir: ['Orden de pedido','Ninguno'],
            Totales : 0,
            arrayPedPrvDet:[],
            idtraspaso:0,
            arrayPedidoProvDet:[],
            arrayPedidoSeleccionado:{},
            proveedorSeleccionado:'',
            arrayArticuloSeleccionadoModal:{}
            
        }
    },
    components: {
        vSelect
    },
    computed: {
        isActived: function () {
            return this.pagination.current_page;
        },
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
                resultado = resultado + (this.arrayDetalle[i].precio * this.arrayDetalle[i].cantidad)
            }
            return resultado;
        }
    },
    watch: {
        cantidad: function() {
            this.calculoTotal();
        },
        tipopedido: function() {
            this.calculoTotal();
        },
        arrayDetalle: {
        deep: true,
        handler: function(newVal) {
            this.Totales = newVal.reduce((acc, detalle) => {
                return acc + (detalle.cantidad * detalle.precio);
            }, 0);
        }
        }
    },
    methods: {
        abrirFormularioCompra(dato){
            this.showRegistrarCompra=true;
            this.listado=10;
            let idalmacen = dato.pedido.idalmacen;
            let arrayConIdsAlmacen= dato.detalles.map(objeto => {
            return { ...objeto, idalmacen:idalmacen };
            });
            this.arrayDetallesAComprar=arrayConIdsAlmacen;
            this.arrayPedidoSeleccionado=dato.pedido;

            this.cerrarModalDetalles();
        },
        cerrarFormularioCompra(){
            this.showRegistrarCompra=false;
            this.listado=1;
        },
        abrirModalDetalles(pedprov){
            this.showModalDetalle=true;
            this.verPedidoDet(pedprov);
        },
        cerrarModalDetalles(){
            this.showModalDetalle=false;
        },
        cambiarNumeroElementos() {
            this.listarPedidoProv(1, this.buscar, this.criterio);
        },
    getColorForEstado(estado, fechaEntrega) {
    const fechaEntregaCaducada = new Date(fechaEntrega) < new Date();

    if (fechaEntregaCaducada) {
        return '#ff0000'; // Rojo para indicar que la fecha de entrega ha caducado
    }
    switch(estado) {
      case 'Pago pendiente':
        return '#b3b9fe'; 
      case 'Procesando':
        return '#c6e0c7'; 
      case 'Completado':
        return '#5ebf5f'; 
      case 'Cancelado':
        return '#d76868'; 
      case 'Reintegrado':
        return '#7ac1cb';       
      case 'Caducado':
        return '#ce4444'; 
      default:
        return '#f9dda6'; 
    }
  },
        atajoButton: function (event) {
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
        },
        listarPedidoProv(page, buscar, criterio) {
            let me = this;
            var url = '/pedidoProveedor?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio+ '&per_page=' + this.itemsPerPage;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayPedidoProv = respuesta.pedidoprov.data;
                me.pagination = respuesta.pagination;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        selectProveedor(search, loading) {
            let me = this;
            loading(true)
            var url = '/proveedor/selectProveedor?filtro=' + search;
            axios.get(url).then(function (response) {
                let respuesta = response.data;
                q: search
                me.arrayProveedor = respuesta.proveedores;
                loading(false)
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        //--SELECCIONAR OSEA AGARRA EL ID DEL PROVEEDOR
        getDatosProveedor(val1) {
            let me = this;
            me.loading = true;

            if (val1 && val1.id) {
                me.idproveedor = val1.id;
                me.proveedorSeleccionado = val1.nombre;
            }
        },
        buscarArticulo() {
            let me = this;
            var url = '/articulo/buscarArticulo?filtro=' + me.codigo;

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayArticulo = respuesta.articulos;
                me.arrayArticuloSeleccionado=respuesta.articulos;

                if (me.arrayArticulo.length > 0) {
                    me.articulo = me.arrayArticulo[0]['nombre'];
                    me.idarticulo = me.arrayArticulo[0]['id'];
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

        cambiarPagina(page, buscar, criterio) {
            let me = this;
            me.pagination.current_page = page;
            me.listarPedidoProv(page, buscar, criterio);
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
            if (me.arrayArticuloSeleccionado.length == 0 || me.cantidad == 0) {
                console.log("Seleccione un producto o verifique la cantidad");
                
            }else {
                if(me.encuentra(me.idarticulo)) {
                    swal({
                        type: 'error',
                        title: 'Error...',
                        text: 'Este Artículo ya se encuentra agregado!',
                    })
                }else{
                    if (me.tipopedido=="Paquetes"){
                        me.cantidad=me.arrayArticuloSeleccionado[0].unidad_x_paquete*me.cantidad;
                    }
                    me.arrayDetalle.push({
                                idarticulo: me.arrayArticuloSeleccionado[0].idarticulo,
                                codigo: me.arrayArticuloSeleccionado[0].codigo,
                                articulo: me.arrayArticuloSeleccionado[0].nombre,
                                unidad_x_paquete: me.arrayArticuloSeleccionado[0].unidad_x_paquete,
                                precio : me.arrayArticuloSeleccionado[0].precio,
                                cantidad: me.cantidad,
                                total: me.Sumatotal,
                    });
                }
                me.eliminarArticuloSeleccionado();
            }
        },
        eliminarArticuloSeleccionado(){
            let me = this;

            me.arrayArticuloSeleccionado = [];
                me.idarticulo ='' ;
                me.codigo = '';
                me.articulo = '';
                me.precio = 0;
                me.precio_costo_paq= 0;
                me.unidad_x_paquete = 0;
                me.cantidad = 0;

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
                    me.arrayArticuloSeleccionado = [{
                        idarticulo: data['id'],
                        nombre: data['articulo'],
                        descripcion:data['descripcion'],
                        fotografia: data['fotografia'],
                        precio: data['precio'],
                        precio_costo_paq: data['precio_costo_paq'],
                        codigo: data['codigo'],
                        unidad_x_paquete: data['unidad_x_paquete'],
                    }]
                    me.codigo = me.arrayArticuloSeleccionado[0].codigo;
                    me.articulo = data['nombre'];
                    me.precio = me.arrayArticuloSeleccionado[0].precio;
                    me.precio_costo_paq = me.arrayArticuloSeleccionado[0].precio_costo_paq;
                    me.unidad_x_paquete = me.arrayArticuloSeleccionado[0].unidad_x_paquete;
                    me.modal=0;
            }
        },
        listarArticulo (page){
            let me=this;
            var url= '/articulo/listarArticuloPedido?page=' + page + '&buscar='+ me.buscar + '&criterio='+ me.criterio + '&idProveedor=' +  me.idproveedor;
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayArticulo = respuesta.articulos.data;
                me.pagination= respuesta.pagination;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        editarPedidoProv() {
            let me = this;
            if (me.arrayDetalle.length === 0) {
                return;
            }
            let horaActual = new Date().toLocaleTimeString('es-ES', {hour12: false});
            me.fechaEntrega=me.fechaEntrega+" "+horaActual
            me.fechaPedido=me.fechaPedido+" "+horaActual
            axios.put(`/editar/pedidoprovee`, {
                'idpedido': me.idpedido, // Agrega el idpedido al cuerpo del request
                'idproveedor': me.idproveedor,
                'idalmacen': me.idalmacen,
                'fecha_pedido': me.fechaPedido,
                'fecha_entrega': me.fechaEntrega,
                'forma_pago': me.forma_pago,
                'observacion': me.observacion,
                'total': me.Totales,
                'data': me.arrayDetalle,
                'estado':me.estadoPedido
            }).then(function (response) {
                me.listado = 1;
                me.listarPedidoProv(1, "", "");
            }).catch(function (error) {
                console.log('ERROR AL EDITAR', error);
            });
        },
        editarPedidoComprado(data) {
            let me = this;
            const detalles=data.detalles;
            const pedido=data.pedido;
            let horaActual = new Date().toLocaleTimeString('es-ES', {hour12: false});
            let fechaEntrega=pedido.fechaEntrega+" "+horaActual
            let fechaPedido=pedido.fechaPedido+" "+horaActual
            axios.put(`/editar/pedidoprovee`, {
                'idpedido': pedido.id, // Agrega el idpedido al cuerpo del request
                'idproveedor': pedido.idproveedor,
                'idalmacen': pedido.idalmacen,
                'fecha_pedido': pedido.fecha_pedido,
                'fecha_entrega':pedido.fecha_entrega,
                'forma_pago': pedido.forma_pago,
                'observacion': "",
                'total': pedido.total,
                'data': detalles,
                'estado':"Completado"
            }).then(function (response) {
                me.listado = 1;
                me.listarPedidoProv(1, "", "");
            }).catch(function (error) {
                console.log('ERROR AL EDITAR', error);
            });
        },
        
        registrarPedido() {
            let me = this;
            if (me.arrayDetalle.length === 0) {
                    return;
                }


            let horaActual = new Date().toLocaleTimeString('es-ES', {hour12: false});
            me.fechaEntrega=me.fechaEntrega+" "+horaActual
            me.fechaPedido=me.fechaPedido+" "+horaActual

            axios.post('/registrar/pedidoprovee', {
                'idproveedor': me.idproveedor,
                'idalmacen': me.idalmacen,
                'fecha_pedido': me.fechaPedido,
                'fecha_entrega': me.fechaEntrega,
                'forma_pago': me.forma_pago,
                'observacion': me.observacion,
                'total': me.Totales,
                'data': me.arrayDetalle

            }).then(function (response) {
                me.listado=1;
                me.listarPedidoProv(1, "", "");
            }).catch(function (error) {
                console.log(error);
            });
        },
        eliminarPedido(idPedido) {
            axios.delete('/pedido/proveedor', { data: { id: idPedido } })
                .then(response => {
                console.log(response.data.message);
                })
                .catch(error => {
                console.error('Error al eliminar el pedido:', error);
                });
            this.listarPedidoProv(1, "", "");

            },
        validarIngreso() {
            this.errorIngreso = 0;
            this.errorMostrarMsjIngreso = [];

            if (this.idproveedor == 0) this.errorMostrarMsjIngreso.push("Seleccione un Proveedor");
            if (this.tipo_comprobante == 0) this.errorMostrarMsjIngreso.push("Sleccione el Comprobante");
            if (!this.num_comprobante) this.errorMostrarMsjIngreso.push("Ingrese el numero de comprobante");
            if (!this.impuesto) this.errorMostrarMsjIngreso.push("Ingrese el impuesto de compra");
            if (this.arrayDetalle.length <= 0) this.errorMostrarMsjIngreso.push("Ingrese detalles");

            if (this.errorMostrarMsjIngreso.length) this.errorIngreso = 1;

            return this.errorIngreso;
        },
        mostrarDetalle(modelo, accion, data = []) 
        {
            let me = this;
            switch (modelo) {
                case "pedido":
                {
                    switch (accion) 
                    {
                        case 'registrar':
                            {
                                me.listado = 0;
                                me.titulo="Registar nuevo pedido";

                                me.idproveedor = 0;
                                me.idproveedor = '';
                                me.fechaPedido = '';
                                me.fechaEntrega = '';
                                me.idalmacen = 1;
                                me.observacion ='';
                                me.forma_pago ='';
                                me.arrayDetalle = [];
                                me.proveedorSeleccionado='';
                                this.inicializarFechas();
                                break;
                            }                      
                        case 'editar':
                            {
                                me.titulo="Editar pedido a proveedor";
                                me.idpedido=data['id'];
                                me.listado = 0;
                                me.fechaPedido = new Date(data['fecha_pedido']).toISOString().split('T')[0];
                                me.fechaEntrega = new Date(data['fecha_entrega']).toISOString().split('T')[0];
                                me.Totales=data['total'];
                                me.AlmacenSeleccionado = data['idalmacen'];
                                me.proveedorSeleccionado = data['nombre_proveedor'];
                                me.idproveedor =data['idproveedor'];
                                me.forma_pago=data['forma_pago'];
                                me.estadoPedido=data['estado']
                                //me.label = data['nombre_proveedor'];
                                //selectProveedor(search, loading);
                                me.verPedidoDet(data)
                                break;
                            }
                    }
                }
            }
        },
        ocultarDetalle() {
            this.listado = 1;
            this.idpedido=null;
            this.proveedorSeleccionado="";
            this.titulo="Pedidos a proveedor"
            this.idproveedor = 0;
            this.fechaPedido = '';
            this.fechaEntrega = '';
            this.idalmacen = 1;
            this.observacion ='';
            this.forma_pago ='';
            this.arrayDetalle = [];
        },       

        verPedidoDet(data) {
                let idpedido = data.id; 
                let me = this;

                me.arrayPedidoSeleccionado=data;

                var url = '/pedido/obtPediPrv?idpedido=' + idpedido;
                axios.get(url)
                    .then(function (response) {
                    var respuesta = response.data;
                    me.arrayPedidoProvDet = respuesta.pedidoprov; // Corrección aquí
                    me.arrayDetalle=respuesta.pedidoprov;
                    
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
            this.arrayArticulo = [];
            this.modal = 1;
            this.tituloModal = 'Seleccione los articulos que desee';
            this.listarArticulo(1);
        },

        desactivarIngreso(id) {
            swal({
                title: 'Esta seguro de desactivar este ingreso?',
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

                    axios.put('/ingreso/desactivar', {
                        'id': id
                    }).then(function (response) {
                        me.listarIngreso(1, '', 'num_comprobante');
                        swal(
                            'Anulado!',
                            'El ingreso ha sido anulado con éxito.',
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
        selectAlmacen() {
            let me = this;
            var url = '/almacen/selectAlmacen';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayAlmacenes = respuesta.almacenes;

            }).catch(function (error) {
                console.log(error);
            });
        },
        getDatosAlmacen() {
            let me = this;
            if (me.AlmacenSeleccionado !== '') { 
                me.loading = true;
                me.idalmacen = Number(me.AlmacenSeleccionado); 
                console.log('IDalmacen: ' + me.idalmacen);
            }
        },
        inicializarFechas() {
            const fechaActual = new Date();
            const fechaPedido = fechaActual.toISOString().substr(0, 10);

            const fechaEntrega = new Date(fechaActual);
            fechaEntrega.setDate(fechaActual.getDate() + 1);
            const fechaEntregaFormateada = fechaEntrega.toISOString().substr(0, 10);

            this.fechaPedido = fechaPedido;
            this.fechaEntrega = fechaEntregaFormateada;

        },
        cambiarTipoUnidad(){
            if (this.tipopedido=="Unidades"){
                //this.Sumatotal = 0;
            }else{
                this.tipopedido=="Paquetes";
                //this.Sumatotal = 0;
            }
        },
        calculoTotal(){
            if (this.tipopedido=="Unidades"){
                this.Sumatotal= this.cantidad * this.precio;

            }else{
                this.tipopedido=="Paquetes";
                this.Sumatotal= this.cantidad * this.precio_costo_paq;
            }  
        },
        // necesario para el componente registrar compra
        cerrarModalArticulos() {
            this.showModalArticulos=false;
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
        agregarArticuloSeleccionado(data = []) {
            let me = this;
            if (me.encuentra(data['id'])) {
                swal({
                    type: 'error',
                    title: 'Error...',
                    text: 'Este Artículo ya se encuentra agregado!',
                })
            } else {
                me.arrayArticuloSeleccionadoModal={
                    codigo:data['codigo'],
                    descripcion:data['descripcion'],
                    fotografia:data['fotografia'],
                    id:data['id'],
                    nombre:data['nombre'],
                    precio_costo_unid:data['precio_costo_unid'],
                    unidad_envase:data['unidad_envase']

                }
                me.codigo=me.arrayArticuloSeleccionado.codigo;
                this.showModalArticulos=false;
            }
        },
        abrirModalArticulos() {
            this.listarArticulo("","");
            this.arrayArticulo = [];
            this.showModalArticulos=true;
            this.tituloModal = 'Seleccione los articulos que desee';

        },
        listarArticuloModal(buscar, criterio) {
            let me = this;
            var url = '/articulo/listarArticulo?buscar=' + buscar + '&criterio=' + criterio + '&idProveedor=' + this.idproveedor;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayArticulo = respuesta.articulos.data;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        listarArticuloProveedor(dato){
            this.idproveedor=dato.idproveedor;
        },
    },
    mounted() {
        this.listarPedidoProv(1, this.buscar, this.criterio);
        //this.verPedidoDet();
        this.selectAlmacen();
        window.addEventListener('keydown', this.atajoButton);
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
</style>
