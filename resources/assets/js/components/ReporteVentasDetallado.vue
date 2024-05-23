<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a class="text-decoration-none" href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">

            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Reporte de Ventas Detallado
                    <button type="button" @click="abrirModal('articulo', 'registrar'); listarPrecio()" class="btn btn-primary">
                        <i class="fa fa-search"></i>&nbsp;Filtros</button>
                </div>
                <template v-if="listado == 1">
                <div class="card-body"  style="max-height: 600px; overflow-y: auto;" >
                    <div class = "table-resposive" > 
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>NUM FACTURA</th>
                                    <th>FECHA</th>
                                    <th>HORA</th>
                                    <th>SUCURSAL</th>
                                    <th>TIPO DE PAGO</th>
                                    <th>TIPO DE ENTREGA</th>
                                    <th>USUARIO</th>
                                    <th>CLIENTE</th>
                                    <th>IMPORTE BS</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="articulo in arrayReporte" :key="articulo.id">
                                    <td class="d-flex align-items-center">
                                            <button type="button" @click="verVenta(articulo.id)"
                                                class="btn btn-success btn-sm mr-1">
                                                <i class="icon-eye"></i>
                                            </button></td>
                                    <td v-text="articulo.Factura"></td>
                                    <td v-text="articulo.fecha_hora"></td>  
                                    <td v-text="articulo.fecha_hora"></td>  
                                    <td v-text="articulo.Nombre_sucursal"></td>
                                    <td v-text="articulo.Tipo_venta"></td>
                                    <td v-text="articulo.Tipo_entrega"></td>
                                    <td v-text="articulo.usuario"></td>
                                    <td v-text="articulo.nombre"></td>
                                    <td v-text="articulo.importe_BS"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--<div class="text-right">
                        <strong>Total Saldo: </strong> {{ total_saldofisico }} Unidades
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
                    </nav>-->
                </div>
                <div class="d-flex justify-content-between">
                        <div>
                            <p>Exportar Detallado</p>
                            <div class="d-inline-block">
                                <button type="button" @click="exportarExcelDetallado" class="btn btn-success"> <i class="icon-doc"></i>&nbsp;Excel</button>
                                <button type="button" @click="exportarPdfDetallado" class="btn btn-danger"> <i class="icon-doc"></i>&nbsp;PDF</button>
                            </div>
                        </div>
                    </div>
            </template>

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
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                            <td v-text="detalle.nombre"></td>

                                            <td>
                                                {{ ((detalle.precio / detalle.cantidad)).toFixed(2) }}
                                            </td>

                                            <td v-text="detalle.cantidad"></td>
                                            <td>
                                                {{ (((detalle.precio/detalle.cantidad) * detalle.cantidad - detalle.descuento)).toFixed(2) }}
                                            </td>
                                        </tr>
                                        <!--<tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Parcial:</strong>
                                            </td>
                                            <td>
                                                {{ ((totalParcial = (total - totalImpuesto))).toFixed(2) }} 
                                            </td>

                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Impuesto:</strong></td>
                                            <td>
                                                {{ ((totalImpuesto = (total * impuesto))).toFixed(2) }}

                                            </td>
                                        </tr>-->
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="3" align="right"><strong>Total Neto:</strong></td>
                                                <td>
                                                    {{ (total) }}

                                                </td>
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
            </div>
        </div>
        <!-- MODAL LISTADO DE MARCAS -->

        <!-- contenido del modal -->
        
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal" tabindex="-1" :class="{ 'mostrar': modal }" role="dialog" aria-labelledby="myModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Filtros de reportes</h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form @submit.prevent="enviarFormulario">
                        <div class="modal-body">
                            <div class="form-group row">

                                <div class="col-md-6">
                                    <label for="" class="font-weight-bold">Sucursal <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" placeholder="Seleccione una sucursal" disabled
                                            v-model="sucursalseleccionada.nombre" :class="{ 'is-invalid': errores.idcategoria }" @input="validarCampo('codigo')">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button"
                                                @click="abrirModal2('Sucursal')">...</button>
                                        </div>
                                    </div>
                                    <p class="text-danger" v-if="errores.idcategoria">{{ errores.idcategoria }}</p>

                                    <label for="" class="font-weight-bold">Vendedores <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" placeholder="Seleccione una linea" disabled
                                            v-model="ejecutivoseleccionado.nombre"
                                            :class="{ 'is-invalid': errores.idcategoria }" @input="validarCampo('codigo')">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button"
                                                @click="abrirModal2('Ejecutivo')">...</button>
                                        </div>
                                    </div>
                                    <p class="text-danger" v-if="errores.idcategoria">{{ errores.idcategoria }}</p>


                                </div>
                                <div class="col-md-6">
                                    <label for="" class="font-weight-bold">Estado Venta <span class="text-danger">*</span></label>
                                    <div class="input-group">  
                                        <select class="form-control col-md-12" v-model="criterioEstado">
                                        <option value="Todos">Todos</option>
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="Registrado">Registrado</option>
                                    </select>
                                    </div>
                                    <p class="text-danger" v-if="errores.idcategoria">{{ errores.idcategoria }}</p>

                                    
                                </div> 
                                <div class="col-md-6">
                                    <label for="" class="font-weight-bold">Fecha Inicio: <span class="text-danger">*</span> </label>
                                    <input class="form-control" type="date" v-model="fechaInicio" >
                                    
                                    
                                </div>
                                <div class="col-md-6">
                                    
                                    <label for="" class="font-weight-bold">Fecha Fin: <span class="text-danger">*</span></label>
                                    <input class="form-control" type="date" v-model="fechaFin" >
                                    
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" @click="cerrarModal()">Cerrar</button>
                            <button type="submit" @click="listaReporte();listaReporteDetallado(); cerrarModal()" class="btn btn-primary">Visualizar Reporte</button>
                        </div>
                    </form>

                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!-- MODAL PARA LA LISTA DE MEDIDA -->
        <div class="modal " tabindex="-1" :class="{ 'mostrar': modal6 }" role="dialog" aria-labelledby="myModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal6"></h4>
                        <button type="button" class="close" @click="modal6 = false" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <button v-show="tituloModal6 == 'Medidas'" type="button"
                                        @click="abrirModal7('medida', 'registrarMed')" class="btn btn-secondary">
                                        <i class="icon-plus"></i>&nbsp;Nuevo
                                    </button>
                                
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th style="display: none;">ID</th>
                                        <th>Opciones</th>
                                        <th>Medida</th>
                                        <th>Descripción Corta</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="arrayelemento in arrayBuscador" :key="arrayelemento.id">
                                        <td>
                                            <button type="button" @click="seleccionar2(arrayelemento)"
                                                class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                            </button>
                                        </td>
                                        <td v-if="mostrarElemento" v-text="arrayelemento.id"></td>
                                        <td v-text="arrayelemento.descripcion_medida"></td>
                                        <td v-text="arrayelemento.descripcion_corta"></td>
                                        <td v-if="tituloModal6 == 'Medidas'">
                                            <div v-if="arrayelemento.estado">
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
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="paginationMedida.current_page > 1">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaMedida(paginationMedida.current_page - 2, buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumberMedida" :key="page"
                                    :class="[page == isActivedM ? 'active' : '']">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaMedida(page, buscar, criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="paginationMedida.current_page < paginationMedida.last_page">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaMedida(paginationMedida.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="modal6 = false">Cerrar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- HASTA AQUI EL MODAL DE LISTA MEDIDA -->

        <div class="modal " tabindex="-1" :class="{ 'mostrar': modal2 }" role="dialog" aria-labelledby="myModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal2"></h4>
                        <button type="button" class="close" @click="modal2 = false" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterioA">
                                        <option v-if="tituloModal2 !== 'Grupos'" value="nombre">Nombre</option>
                                        <option v-if="tituloModal2 == 'Articulo'" value="descripcion">Descripcion</option>
                                        <option v-else-if="tituloModal2 == 'Grupos'" value="nombre_grupo">Grupo</option>
                                        <!-- <option v-if="tituloModal2=='Grupos'" value="nombre_grupo">Nombre_grupo</option> -->
                                    </select>
                                    <input v-if="tituloModal2 == 'Ejecutivo'" type="text" v-model="buscarA"
                                        @keyup="listarEjecutivo(1, buscarA, criterioA)" class="form-control"
                                        placeholder="Texto a buscar">
                                    <input v-if="tituloModal2 == 'Cliente'" type="text" v-model="buscarA"
                                        @keyup="listarPersona(1, buscarA, criterioA)" class="form-control"
                                        placeholder="Texto a buscar">
                                    <input v-if="tituloModal2 == 'Sucursal'" type="text" v-model="buscarA"
                                        @keyup="listarSucursal(1, buscarA, criterioA)" class="form-control"
                                        placeholder="Texto a buscar">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm" v-if="tituloModal2 !== 'Grupos'">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Id</th>
                                        <th>Nombre</th>
                                        
                                            <th v-if="tituloModal2 == 'Cliente'">
                                                Tipo de Documento
                                            </th>
                                            <th v-if="tituloModal2 == 'Cliente'">
                                                Num Documento
                                            </th>
                                            <th v-if="tituloModal2 == 'Cliente'">
                                                Telefono
                                            </th>
                                            <div v-else>
                                                Estado
                                            </div>
                                            
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="arrayelemento in arrayBuscador" :key="arrayelemento.id">
                                        <td>
                                            <button type="button" @click="seleccionar(arrayelemento)"
                                                class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                            </button>
                                        </td>
                                        <td v-text="arrayelemento.id"></td>
                                        <td v-text="arrayelemento.nombre"></td>

    
                                            <td v-if="tituloModal2 == 'Cliente'" v-text="arrayelemento.tipo_documento"></td>
                                            <td v-if="tituloModal2 == 'Cliente'" v-text="arrayelemento.num_documento"></td>
                                            <td v-if="tituloModal2 == 'Cliente'" v-text="arrayelemento.telefono"></td>
                                    

                                        <td v-if="tituloModal2 == 'Ejecutivo' || tituloModal2 == 'Sucursal'">
                                            <div v-if="arrayelemento.condicion">
                                                <span class="badge badge-success">Activo</span>
                                            </div>
                                            <div v-else>
                                                <span class="badge badge-danger">Desactivado</span>
                                            </div>
                                        </td>
                                        
                                        <div v-if="tituloModal2 == 'Proveedors'">
                                            <td v-text="arrayelemento.num_documento"></td>
                                        </div>
                                    </tr>
                                </tbody>
                            </table>
                            <!--###########################################################-->
                            <table class="table table-bordered table-striped table-sm" v-else-if="tituloModal2 == 'Grupos'">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Id</th>
                                        <th>Nombre</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="arrayelemento in arrayBuscador" :key="arrayelemento.id">
                                        <td>
                                            <button type="button" @click="seleccionar(arrayelemento)"
                                                class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                            </button>
                                           
                                        </td>
                                        <td v-text="arrayelemento.id"></td>
                                        <td v-text="arrayelemento.nombre_grupo"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <!--##################################################################3-->
                        </div>
                        <nav v-if="tituloModal2 == 'Marcas'">
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaMarca(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page"
                                    :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaMarca(page, buscar, criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaMarca(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                        <nav v-else-if="tituloModal2 == 'Lineas'">
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaLinea(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page"
                                    :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaLinea(page, buscar, criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaLinea(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                        <nav v-else-if="tituloModal2 == 'Cliente'">
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaIndustria(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page"
                                    :class="[page == isActivedMar ? 'active' : '']">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaIndustria(page, buscar, criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaIndustria(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                        <nav v-else-if="tituloModal2 == 'Proveedors'">
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaProveedor(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page"
                                    :class="[page == isActivedMar ? 'active' : '']">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaProveedor(page, buscar, criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaProveedor(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                        <nav v-else-if="tituloModal2 == 'Grupos'">
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaGrupo(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page"
                                    :class="[page == isActivedMar ? 'active' : '']">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaGrupo(page, buscar, criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaGrupo(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="modal2 = false">Cerrar</button>
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
import { esquemaArticulos } from '../constants/validations';
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import VueBarcode from 'vue-barcode';
import * as XLSX from 'xlsx-js-style';

export default {
    data() {
        return {
            datosFormulario: {
                nombre: '',
                descripcion: '',
                nombre_generico: '',
                unidad_envase: 0,
                precio_costo_unid: 0,
                precio_costo_paq: 0,
                precio_venta: 0,
                precio_uno: 0,
                precio_dos: 0,
                precio_tres: 0,
                precio_cuatro: 0,
                stock: 0,
                costo_compra: 0,
                codigo: '',
                codigo_alfanumerico: '',
                descripcion_fabrica: '',
                idcategoria: null,
                idmarca: null,
                idindustria: null,
                idgrupo: null,
                idproveedor: null,
                idmedida: null
            },
            listado: 1,
            cliente: '',
            totalImpuesto: 0.0,
            
            errores: {},

            monedaPrincipal: [],

            successImport: false,
            registrosSuccess: [],
            errorsImport: [],
            erroresNoExiste: [],

            parsedPreviewCsv: [],
            selectedDelimiter: ';',
            includeHeader: true,

            pageImportar: 0,
            modalImportar: 0,
            selectedFile: null,
            csvHeaders: null,
            selectedHeadersFromFile: [],
            selectedHeadersToAssign: [],
            headersAssigned: false,
            previewCsv: "",
            headersArray: [
                "Linea",
                "Grupo",
                "Proveedor",
                "Medida",
                "Codigo",
                "Nombre",
                "Nombre generico",
                "Unidad envase",
                "Precio List unidad",
                "Precio costo unidad",
                "Precio costo paquete",
                "Precio venta",
                "Precio uno",
                "Precio dos",
                "Precio tres",
                "Precio cuatro",
                "Stock minimo",
                "Descripciòn",
                "Estado",
                "Costo compra",
                "Marca",
                "Industria"
            ],


            criterioA: 'nombre',
            criterioEstado: 'Todos',
            buscarA: '',
            tituloModal2: '',
            clienteseleccionada: [],
            ejecutivoseleccionado: [],
            industriaseleccionada: [],
            lineaseleccionada: [],
            proveedorseleccionada: [],
            gruposeleccionada: [],
            nombre_grupo: '',

            modal2: false,
            modal6: false,
            articulo_id: 0,
            idcategoria: 0,
            idmarca: 0,
            idindustria: 0,
            idproveedor: 0,
            idgrupo: 0,
            codigoProductoSin: 0,
            idmedida: 0,
            nombreLinea: '',
            nombre_categoria: '',
            nombre_proveedor: '',
            //id:'',//aumente 7 julio
            codigo: '',
            nombre: '',
            nombre_producto: '',
            nombre_generico: '',
            //validaion para inputs
            nombreProductoVacio: false,
            codigoVacio: false,
            unidad_envaseVacio: false,
            nombre_genericoVacio: false,
            precio_costo_unidVacio: false,
            precio_costo_paqVacio: false,
            precio_ventaVacio: false,
            costo_compraVacio: false,
            stockVacio: false,
            descripcionVacio: false,
            fotografiaVacio: false,
            lineaseleccionadaVacio: false,
            clienteseleccionadaVacio: false,
            ejecutivoseleccionadoVacio: false,
            industriaseleccionadaVacio: false,
            proveedorseleccionadaVacio: false,
            gruposeleccionadaVacio: false,
            medidaseleccionadaVacio: false,
            sucursalseleccionadaVacio: false,
            articuloseleccionadaVacio: false,

            //aumente esto
            unidad_envase: 0,
            precio_costo_unid: 0,
            precio_costo_paq: 0,
            //hasta aqui
            precios: [],
            //precioss: [],
            precio: '',//aumente 5julio
            //mostrarPrecios: false,
            //precioCount: 0,
            //aumento 13_junio
            precio_uno: 0,
            precio_dos: 0,
            precio_tres: 0,
            precio_cuatro: 0,
            //hasta aqui
            //--------proveedor para almacer el registrado y editar------
            proveedor_id: 0,
            tipo_documento: 'DNI',
            num_documento: '',
            direccion: '',
            telefono: '',
            email: '',
            contacto: '',
            telefono_contacto: '',
            //--------hasta aqui-----------------
            //--grupo--
            nombre_grupo: '',
            grupo_id: 0,
            //---hasta aqui
            precio_venta: 0,
            costo_compra: 0,

            stock: 5,
            nombre_persona: '',
            descripcion: '',
            fotografia: '',
            fotoMuestra: null,
            arrayArticulo: [],
            arrayBuscador: [],
            modal: 0,

            tituloModal: '',
            tipoAccion: 0,
            tipoAccion2: 0,
            tipoAccion6: 0,
            errorArticulo: 0,
            errorMostrarMsjArticulo: [],
            //------registro industia, marcas--
            modal3: 0,
            tituloModal3: '',
            industria_id: 0,
            marca_id: 0,
            linea_id: 0,
            estado: 1,
            condicion: 1,
            nombre_industria: '',
            mostrarElemento: '',
            errorIndustria: 0,
            errorMostrarMsjIndustria: [],
            //--------hasta aqui---
            pagination: this.createPaginationObject(),
            paginationMedida: this.createPaginationObject(),
            offset: {
                pagination: 3,
                paginationMedida: 3,
            },
            criterio: 'nombre',
            buscar: '',
            arrayCategoria: [],

            //CONFIGURACIONES
            mostrarSaldosStock: '',
            mostrarProveedores: '',
            mostrarCostos: '',
            rolUsuario: '',

            //medida
            arrayMedida: [],
            errorMedida: 0,
            errorMostrarMsjMedida: [],
            modal7: 0,
            tituloModal6: '',
            tituloModal7: '',
            medida_id: 0,
            descripcion_medida: '',
            descripcion_corta: '',
            medidaseleccionada: [],

            //Sucursal
            arraySucursal:[],
            sucursalseleccionada:[],

            //articulo 
            articuloseleccionada : [],  
            arrayReporte:[],
            total_saldofisico:0,

            //fechas
            fechaInicio:'',
            fechaFin:'',

            arrayDetalle: [],
            arrayReporteDetallado:[]

        }
    },
    components: {
        'barcode': VueBarcode
    },
    computed: {
        sortedResultados: function() {
        return Object.values(this.arrayReporte).sort((a, b) => {
            return new Date(a.fecha_hora) - new Date(b.fecha_hora);
        });
    },
        isActived: function () {
            return this.pagination.current_page;
        },
        isActivedM: function () {
            return this.pagination.current_page;
        },
        isActivedMar: function () {
            return this.pagination.current_page;
        },

        pagesNumber: function () {
            return this.calculatePages(this.pagination, this.offset.pagination);
        },
        pagesNumberMedida: function () {
            return this.calculatePages(this.paginationMedida, this.offset.paginationMedida);
        },
        imagen() {
            console.log(this.fotoMuestra);
            return this.fotoMuestra;
        },
        calcularTotal: function () {
            var resultado = 0.0;
            for (var i = 0; i < this.arrayDetalle.length; i++) {
                resultado += ((this.arrayDetalle[i].precioseleccionado * this.arrayDetalle[i].cantidad) - (this.arrayDetalle[i].precioseleccionado * this.arrayDetalle[i].cantidad * this.arrayDetalle[i].descuento / 100))

            }
            resultado -= this.descuentoAdicional;
            resultado -= this.descuentoGiftCard;
            return resultado;
        },

        calcularSubTotal: function () {
            var resultado = 0.0;
            for (var i = 0; i < this.arrayDetalle.length; i++) {
                resultado = resultado + ((this.arrayDetalle[i].precioseleccionado * this.arrayDetalle[i].cantidad) - (this.arrayDetalle[i].precioseleccionado * this.arrayDetalle[i].cantidad * this.arrayDetalle[i].descuento / 100))
            }
            return resultado;
        },
    },
    watch: {
        selectedDelimiter: 'updateData',
        previewCsv: 'parseCsv', // Llama a parseCsv cuando previewCsv cambie
    },
    methods: {
        toastSuccess(mensaje){
            this.$toasted.show(`
    <div style="height: 60px;font-size:16px;">
        <br>
        `+mensaje+`.<br>
    </div>`, {
                    type: "success",
                    position: "bottom-right",
                    duration: 4000
                });
        },
        toastError(mensaje){
            this.$toasted.show(`
    <div style="height: 60px;font-size:16px;">
        <br>
        `+mensaje+`<br>
    </div>`, {
                    type: "error",
                    position: "bottom-right",
                    duration: 4000
                });
        },
        asignarCampos() {
            this.datosFormulario.idcategoria = this.lineaseleccionada.id
            this.datosFormulario.idCliente = this.clienteseleccionada.id
            this.datosFormulario.idproveedor = this.proveedorseleccionada.id
            this.datosFormulario.idindustria = this.industriaseleccionada.id
            this.datosFormulario.idmedida = this.medidaseleccionada.id
            this.datosFormulario.idgrupo = this.gruposeleccionada.id
            this.datosFormulario.idSucursal = this.sucursalseleccionada.id
            this.datosFormulario.idArticulo = this.articuloseleccionada.id

            this.datosFormulario.precio_costo_unid = this.convertDolar(this.datosFormulario.precio_costo_unid);
            this.datosFormulario.precio_costo_paq = this.convertDolar(this.datosFormulario.precio_costo_paq);
            this.datosFormulario.precio_venta = this.convertDolar(this.datosFormulario.precio_venta);

            this.datosFormulario.precio_uno = this.convertDolar(this.precio_uno);
            this.datosFormulario.precio_dos = this.convertDolar(this.precio_dos);
            this.datosFormulario.precio_tres = this.convertDolar(this.precio_tres);
            this.datosFormulario.precio_cuatro = this.convertDolar(this.precio_cuatro);
            this.datosFormulario.costo_compra = this.convertDolar(this.datosFormulario.costo_compra);
        },
        async validarCampo(campo) {
            this.asignarCampos();
            try {
                await esquemaArticulos.validateAt(campo, this.datosFormulario);
                this.errores[campo] = null;
            } catch (error) {
                this.errores[campo] = error.message;
            }
        },
        async enviarFormulario() {
            this.asignarCampos();

            await esquemaArticulos.validate(this.datosFormulario, { abortEarly: false })
                .then(() => {
                    this.datosFormulario.fotografia = this.fotografia

                    console.log(this.datosFormulario)
                    if (this.tipoAccion == 2) {
                        this.actualizarArticulo(this.datosFormulario)
                    } else {
                        this.registrarArticulo(this.datosFormulario)

                    }
                })
                .catch((error) => {
                    const erroresValidacion = {};
                    error.inner.forEach((e) => {
                        erroresValidacion[e.path] = e.message;
                    });

                    this.errores = erroresValidacion;
                });
        },
        obtenerConfiguracionTrabajo() {
            // Utiliza Axios para realizar la solicitud al backend
            axios.get('/configuracion')
                .then(response => {
                    console.log("Esta es la configuracion: ", response.data.configuracionTrabajo)
                })
                .catch(error => {
                    console.error('Error al obtener configuración de trabajo:', error);
                });
        },


        confirmarRegistro() {
            this.erroresNoExiste.forEach((elemento) => {
                const palabras = elemento.split(' ');
                const primeraPalabra = palabras.shift();
                const restoDelString = palabras.join(' ');
                console.log(palabras)
                if ("Linea" === primeraPalabra) {
                    console.log("Se encontro " + restoDelString)
                    this.agregarLinea(restoDelString)
                } else if ("Marca" === primeraPalabra) {
                    this.agregarMarca(restoDelString)
                    console.log("Se encontro " + restoDelString)


                } else if ("Grupo" === primeraPalabra) {
                    this.agregarGrupo(restoDelString)
                    console.log("Se encontro " + restoDelString)

                } else if ("Industria" === primeraPalabra) {
                    this.agregarIndustria(restoDelString)
                    console.log("Se encontro " + restoDelString)



                }

            });

            this.submitForm();

            // Puedes realizar alguna acción adicional aquí
        },
        cancelarRegistro() {
            alert("Registro cancelado");
            // Puedes realizar alguna acción adicional aquí
        },
        updateData() {
            this.extractCSVHeaders(this.selectedFile)
                .then(headers => {
                    this.csvHeaders = headers;
                })
                .catch(error => {
                    console.error('Error al extraer encabezados:', error);
                });
        },
        parseCsv() {
            // Divide la cadena de vista previa en filas y luego cada fila en columnas
            this.parsedPreviewCsv = this.previewCsv.split('\n').map(row => row.split(this.selectedDelimiter));
        },

        selectAllHeaders() {
            // Seleccionar todos los encabezados automáticamente
            this.selectedHeadersFromFile = [...this.csvHeaders];
        },


        handleFileChange(event) {
            this.selectedFile = event.target.files[0];
            this.extractCSVHeaders(this.selectedFile)
                .then(headers => {
                    this.csvHeaders = headers;
                })
                .catch(error => {
                    console.error('Error al extraer encabezados:', error);
                });
            this.selectedHeadersFromFile = [];
            this.selectedHeadersToAssign = [];
            this.headersAssigned = false;
        },
        removeFile() {
            this.selectedFile = null;
            this.csvHeaders = null;
            this.selectedHeadersFromFile = [];
            this.selectedHeadersToAssign = [];
            this.headersAssigned = false;
            this.$refs.fileInput.value = '';
            this.previewCsv = "";
        },
        confirmCsv() {
            this.pageImportar += 1;

            if (!this.includeHeader) {
                const mapping = {};
                this.csvHeaders.forEach((item, index) => {
                    mapping[item] = (index + 1).toString();
                });
                const newArray = this.csvHeaders.map(item => mapping[item]);
                this.csvHeaders = newArray;
                console.log(this.csvHeaders);
            }
            console.log(this.selectedDelimiter);
        },
        assignHeaders() {
            if (!this.selectedFile) {
                console.error("No se ha seleccionado un archivo.");
                return;
            }
            this.pageImportar += 1;

            const reader = new FileReader();

            reader.onload = (event) => {
                let csvContent = event.target.result;
                if (!this.includeHeader) {
                    const primeraFila = this.csvHeaders.join(',');
                    csvContent = `${primeraFila}\n${csvContent}`;

                }

                const newCsvContent = this.getCsvSubset(csvContent, this.selectedHeadersFromFile);

                this.previewCsv = newCsvContent;
            };

            reader.readAsText(this.selectedFile);
        },

        getCsvSubset(csvContent, selectedHeaders) {
            const rows = csvContent.split("\n");
            const headerIndices = selectedHeaders.map((header) => rows[0].split(this.selectedDelimiter).indexOf(header));

            let newRows;

            newRows = rows.slice(1).map((row) => {
                const columns = row.split(this.selectedDelimiter);
                return headerIndices.map((index) => columns[index]).join(",");
            });


            const newCsvContent = newRows.join("\n");
            console.log(newCsvContent);
            console.log(this.includeHeader)
            return newCsvContent;
        },


        downloadCsv() {
            const blob = new Blob([this.previewCsv], { type: "text/csv" });
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "nuevo_csv.csv";
            link.click();
        },
        createNewCsvData() {
            const selectedHeadersFromFile = this.selectedHeadersFromFile;
            const selectedHeadersToAssign = this.selectedHeadersToAssign;
            console.log("xd")

            const newData = this.csvData.map((row) => {
                const newRow = {};

                selectedHeadersFromFile.forEach((header) => {
                    newRow[header] = row[header];
                });

                selectedHeadersToAssign.forEach((header) => {
                    newRow[header] = '';
                });

                return newRow;
            });

            const newCsvContent = [selectedHeadersToAssign.concat(selectedHeadersFromFile).join(',')];
            newData.forEach((row) => {
                const values = selectedHeadersToAssign.concat(selectedHeadersFromFile).map((header) => row[header]);
                newCsvContent.push(values.join(','));
            });

            return newCsvContent.join('\n');
        },
        extractCSVHeaders(file) {
            console.log("XDDD esta por acaaaa")
            return new Promise((resolve, reject) => {
                const reader = new FileReader();

                reader.onload = (e) => {
                    const content = e.target.result;
                    const rows = content.split('\n');
                    const headers = rows[0].split(this.selectedDelimiter);
                    resolve(headers);
                };

                reader.onerror = (error) => {
                    reject(error);
                };

                reader.readAsText(file);
            });
        },
        submitForm() {
            if (!this.previewCsv) {
                alert('No hay un nuevo CSV para importar.');
                return;
            }
            this.pageImportar = 3;


            const blob = new Blob([this.previewCsv], { type: 'text/csv' });
            const newCsvFile = new File([blob], 'nuevo_csv.csv', { type: 'text/csv' });
            const formData = new FormData();
            formData.append('archivo', newCsvFile);
            axios.post('/articulos/importar', formData)
                .then(response => {
                    console.log(response);
                    this.erroresNoExiste = [];
                    this.errorsImport = [];
                    this.successImport = true;

                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        const datos = error.response.data.errors;
                        console.log(datos);
                        this.erroresNoExiste = datos.flatMap(item => {
                            const match = item.match(/No existe '([^']+)'/);
                            return match ? [match[1]] : [];
                        });
                        console.log("Estos no existen: ", this.erroresNoExiste)
                        this.errorsImport = datos.filter(item => !item.includes("No existe"));
                        this.erroresNoExiste = this.erroresNoExiste.filter((valor, indice, array) => array.indexOf(valor) === indice);
                        // Mostrar el nuevo array con los elementos filtrados
                        console.log(this.erroresNoExiste);
                        console.log(this.errorsImport)

                    } else {
                        console.error(error);
                    }
                });

        },
        calculatePages: function (paginationObject, offset) {
            if (!paginationObject.to) {
                return [];
            }

            var from = paginationObject.current_page - offset;
            if (from < 1) {
                from = 1;
            }

            var to = from + (offset * 2);
            if (to >= paginationObject.last_page) {
                to = paginationObject.last_page;
            }

            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        },
        createPaginationObject() {
            return {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0,
            }
        },
        calcularPrecioCostoUnid() {
            if (this.datosFormulario.unidad_envase && this.datosFormulario.precio_costo_paq) {
                this.datosFormulario.precio_costo_unid = this.datosFormulario.precio_costo_paq / this.datosFormulario.unidad_envase;
                this.datosFormulario.precio_costo_unidVacio = false;
            }
        },
        calcularPrecioCostoPaq() {
            if (this.datosFormulario.unidad_envase && this.datosFormulario.precio_costo_unid) {
                this.datosFormulario.precio_costo_paq = this.datosFormulario.precio_costo_unid * this.datosFormulario.unidad_envase;
                this.datosFormulario.precio_costo_paqVacio = false;
            }
        },
        calcularPrecioP(precio_costo_unid, porcentage) {
            const margenG = precio_costo_unid * (porcentage / 100);
            const precioP = parseFloat(precio_costo_unid) + parseFloat(margenG);
            return precioP.toFixed(2);
        },
        //-------------hasta qui calcular -----------
        seleccionar(selected) {
            if (this.tituloModal2 == "Marcas") {
                this.clienteseleccionada = false;
                if (selected.condicion == 1) {
                    this.clienteseleccionada = selected;
                    this.validarCampo("idmarca");

                } else if (selected.condicion == 0) {
                    this.advertenciaInactiva('Marcas');
                }
            } else if (this.tituloModal2 == "Cliente") {
                this.clienteseleccionadaVacio = false;
                this.clienteseleccionada = selected;
                
            } else if (this.tituloModal2 == "Lineas") {
                if (selected.condicion == 1) {
                    this.lineaseleccionada = selected;
                    this.validarCampo("idcategoria");

                } else if (selected.condicion == 0) {
                    this.advertenciaInactiva('Lineas');
                }
            } else if (this.tituloModal2 == "Proveedors") {
                // this.proveedorseleccionada.id = selected.id;
                // this.proveedorseleccionada.nombre = selected.nombre;
                this.proveedorseleccionada = selected;
                this.validarCampo("idproveedor");

            } else if (this.tituloModal2 == "Grupos") {
                this.gruposeleccionada = selected;
                this.validarCampo("idgrupo");

            }else if (this.tituloModal2 == "Sucursal") {
                if (selected.condicion == 1) {
                    this.sucursalseleccionada = selected;
                    this.validarCampo("idSucursal");

                } else if (selected.condicion == 0) {
                    this.advertenciaInactiva('Sucursal');
                }
            }else if (this.tituloModal2 == "Articulo") {
                if (selected.condicion == 1) {
                    this.articuloseleccionada = selected;
                    this.validarCampo("idArticulo");

                } else if (selected.condicion == 0) {
                    this.advertenciaInactiva('Articulo');
                }
            }else if (this.tituloModal2 == "Ejecutivo") {
                if (selected.condicion == 1) {
                    this.ejecutivoseleccionado = selected;
                }
            }
            
            // if (this.marcaseleccionada.condicion == 1 ){
            //     console.log("selcciona", selected);
            // }else if (this.marcaseleccionada.condicion == 0 ){
            //     console.log("Estado invalido", this.marcaseleccionada.condicion);
            // }
            console.log("Seleccionado", selected);
            this.arrayBuscador = [];
            this.modal2 = false;
        },

        seleccionar2(selected) {
            if (this.tituloModal6 == "Medidas") {
                this.medidaseleccionadaVacio = false;
                if (selected.estado == 1) {
                    this.medidaseleccionada = selected;
                    this.validarCampo("idmedida");

                } else if (selected.estado == 0) {
                    this.advertenciaInactiva('Medidas');
                }
            }
            this.arrayBuscador = [];
            this.modal6 = false;
        },


        listarPersona(page,buscar,criterio){
                let me=this;
                var url= '/cliente?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayBuscador = respuesta.usuarios.data;
                    me.pagination= respuesta.pagination;
                    console.log("hola",me.arrayBuscador);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

        listarLinea(page, buscar, criterio) {
            let me = this;
            var url = '/categoria?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayBuscador = respuesta.categorias.data;
                me.pagination = respuesta.pagination;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },

        listarMedida(page, buscar, criterio) {
            let me = this;
            var url = '/medida?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayBuscador = respuesta.medidas.data;
                me.paginationMedida = respuesta.paginationMedida;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },

        abrirModal2(titulo) {
            if (titulo == "Estado Venta") {
                this.listarMarca(1, '', 'nombre');
                this.modal2 = true;
                this.tituloModal2 = titulo;
                this.clienteseleccionada = false;

            } else if (titulo == "Cliente") {
                this.listarPersona(1, '', 'nombre');
                this.modal2 = true;
                this.tituloModal2 = titulo;
                this.clienteseleccionada = false;


            } else if (titulo == "Ejecutivo") {
                this.listarEjecutivo(1, '', 'nombre');
                this.modal2 = true;
                this.tituloModal2 = titulo;
                this.ejecutivoseleccionado = false;

            }else if (titulo == "Sucursal") {
                this.listarSucursal(1, '', 'nombre');
                this.modal2 = true;
                this.tituloModal2 = titulo;
                this.gruposeleccionadaVacio = false;
            }

        },

        abrirModal6(titulo) {
            if (titulo == "Medidas") {
                this.listarMedida(1, '', 'descripcion_medida');
                this.modal6 = true;
                this.tituloModal6 = titulo;
                this.medidaseleccionadaVacio = false;
            }
        },
        listarSucursal(page, buscar, criterio) {
            let me = this;
            var url = '/sucursal?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
            var respuesta = response.data;
            me.arrayBuscador = respuesta.sucursales.data;
            me.pagination = respuesta.pagination;
      })
        .catch(function (error) {
          console.log(error);
        });
        },

        listarEjecutivo(page, buscar, criterio) {
            let me = this;
        var url = '/user/selectUser/rol?filtro=' + 2;
        axios.get(url).then(function (response) {
            var respuesta = response.data;
            me.arrayBuscador = respuesta.usuarios;
            console.log('Ejecutivos',me.arrayBuscador);
        })
        .catch(function (error) {
            console.log(error);
        });
        },

        listarMarca(page, buscar, criterio) {
            let me = this;
            console.log("Listano");
            var url = '/marca?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;

            axios.get(url).then(function (response) {

                var respuesta = response.data;
                console.log(respuesta);

                me.arrayBuscador = respuesta.marcas.data;
                me.pagination = respuesta.pagination;
                console.log("Listad0");

            })
                .catch(function (error) {
                    console.log(error);
                });
        },


       
        //--grupo listado ,registro y actualizar
        listargrupo(page, buscar, criterio) {
            let me = this;
            console.log("ListanoGrupos");
            var url = '/grupos?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                console.log(respuesta);
                me.arrayBuscador = respuesta.grupos.data;
                me.pagination = respuesta.pagination;
                console.log("Listad::");
                
            })
                .catch(function (error) {
                    console.log('ERRORES', error);
                });
        },


        listaReporte() {
            let me = this;
            var url = '/resumen-ventas-documento?';

            // Agregar los parámetros obligatorios
            url += 'sucursal=' + this.sucursalseleccionada.id + '&ejecutivoCuentas=' + this.ejecutivoseleccionado.id + '&estadoVenta=' + this.criterioEstado + '&idcliente=' + this.clienteseleccionada.id;

            // Agregar las fechas de inicio y fin
            url += '&fechaInicio=' + me.fechaInicio + '&fechaFin=' + me.fechaFin;

            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.total_saldofisico = respuesta.total_BS;
                    me.arrayReporte = respuesta.ventas;
                    console.log("array reporte",me.arrayReporte);
                })
                .catch(function (error) {
                    console.log('ERRORES', error);
                });
        },
        listaReporteDetallado() {
            let me = this;
            var url = '/resumen-ventas-documento-detallado?';

            // Agregar los parámetros obligatorios
            url += 'sucursal=' + this.sucursalseleccionada.id + '&ejecutivoCuentas=' + this.ejecutivoseleccionado.id + '&estadoVenta=' + this.criterioEstado + '&idcliente=' + this.clienteseleccionada.id;

            // Agregar las fechas de inicio y fin
            url += '&fechaInicio=' + me.fechaInicio + '&fechaFin=' + me.fechaFin;

            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayReporteDetallado = respuesta.ventas;
                    console.log("array reporte detallado",me.arrayReporteDetallado);
                })
                .catch(function (error) {
                    console.log('ERRORES', error);
                });
        },
        exportarPDF() {
            const pdf = new jsPDF('landscape');
            
            const titulo = 'RESUMEN DE VENTA POR DOCUMENTO';
            const fechaInicio = `Fecha Inicio: ${this.fechaInicio}`;
            const fechaFin = `Fecha Fin: ${this.fechaFin}`;
            const sucursal = `Sucursal: ${this.sucursalseleccionada.nombre}`;
            const venta = `Venta: ${this.criterioEstado}`;
            const cliente = `Cliente: ${this.clienteseleccionada.nombre}`;

            pdf.setFont('helvetica');
            pdf.setFontSize(16); // Tamaño de letra más grande para el título
            pdf.text(titulo, 100, 10);

            pdf.setFontSize(10); // Tamaño de letra más pequeño para los elementos restantes
            pdf.text(fechaInicio, 15, 20);
            pdf.text(fechaFin, 125, 20);
            pdf.text(sucursal, 240, 20);
            pdf.text(venta, 15, 30);
            pdf.text(cliente, 125, 30);

            const tableYPosition = 40;

            const columns = ['Factura', 'Sucursal', 'Fecha', 'Tipo de cambio','Tipo de venta','Ejecutivo de Venta','Nombre Ejecutivo de Venta','Cliente','Importe Bs','Importe US'];
            const rows = this.arrayReporte.map(item => [
                    item.Factura,
                    item.Nombre_sucursal,
                    item.fecha_hora,
                    item.Tipo_Cambio,
                    item.Tipo_venta,
                    item.nombre_rol,
                    item.usuario,
                    item.nombre,
                    item.importe_BS,
                    item.importe_usd,
                ]);

            pdf.autoTable({ head: [columns], body: rows, startY: tableYPosition });

            pdf.save('reporte_resumen_ventas_por_documento.pdf');
        },

        exportarPdfDetallado() {
    const pdf = new jsPDF({
        orientation: 'l',
        unit: 'mm',
        format: 'letter',
    });

    let startRow = 40;
    const lineHeight = 2; // Altura de línea reducida
    const fontSize = 7; // Tamaño de fuente
    const spaceBetweenGroups = 10; // Espacio adicional entre grupos de ventas
    const maxRowsPerPage = 50; // Máximo número de filas por página

    const groupedData = this.groupById(); // Asegúrate de que esta función agrupe los datos correctamente
    pdf.setFontSize(16);
    pdf.setTextColor(0, 0, 0);
    pdf.text('DETALLE DE VENTAS POR DOCUMENTOS', 148, 15, { align: 'center' });

    // Fechas e información general (aparecerán en todas las páginas)
    pdf.setFontSize(12);
    pdf.text(`Fecha inicio: ${this.fechaInicio}`, 10, 25);
    pdf.text(`Fecha fin: ${this.fechaFin}`, 70, 25);
    pdf.text(`Sucursal: ${this.sucursalseleccionada.nombre}`, 140, 25);
    pdf.text(`Ventas: ${this.criterioEstado}`, 10, 30);

    groupedData.forEach((venta, index) => {
        const spaceNeeded = (venta.length * lineHeight) + 30; // Espacio necesario para los datos del grupo
        const spaceLeftOnPage = pdf.internal.pageSize.height - startRow; // Espacio restante en la página actual

        if (spaceNeeded > spaceLeftOnPage) {
            pdf.addPage(); // Agregar una nueva página si no hay suficiente espacio
            startRow = 10; // Reiniciar la posición de inicio en la nueva página

            // Título del reporte y datos de filtro (aparecerán en todas las páginas)
            pdf.setFontSize(16);
            pdf.setTextColor(0, 0, 0);
            pdf.text('DETALLE DE VENTAS POR DOCUMENTOS', 148, 15, { align: 'center' });

            // Fechas e información general (aparecerán en todas las páginas)
            pdf.setFontSize(12);
            pdf.text(`Fecha inicio: ${this.fechaInicio}`, 10, startRow + 15);
            pdf.text(`Fecha fin: ${this.fechaFin}`, 70, startRow + 15);
            pdf.text(`Sucursal: ${this.sucursalseleccionada.nombre}`, 140, startRow + 15);
            pdf.text(`Ventas: ${this.criterioEstado}`, 10, startRow + 20);

            startRow += 30; // Espacio adicional después del título y datos de filtro
        }

        const headersGenerales = [
            'Factura', 'Fecha', 'Sucursal', 'Tipo_entrega', 'Tipo_venta',
            'Vendedor', 'Cliente', 'Importe Bs'
        ];

        pdf.setTextColor(0, 0, 0);
        pdf.setFont('helvetica', 'bold');
        pdf.setFontSize(fontSize);

        headersGenerales.forEach((header, colIndex) => {
            pdf.rect(10 + colIndex * 30, startRow, 30, lineHeight + 2, 'S');
            pdf.text(header, 12 + colIndex * 30, startRow + lineHeight + 2);
        });

        startRow += lineHeight + 2; // Espacio reducido entre encabezados y datos generales

        pdf.setFont('helvetica', 'normal'); // Estilo de texto normal

        const datosGenerales = [
            venta[0].Factura,
            venta[0].Fecha,
            venta[0].Sucursal,
            venta[0].Tipo_entrega,
            venta[0].Tipo_venta,
            venta[0].Vendedor,
            venta[0].Cliente,
            venta[0].Importe_Bs
        ];

        pdf.setTextColor(0, 0, 0);

        datosGenerales.forEach((data, colIndex) => {
            pdf.text(String(data), 12 + colIndex * 30, startRow + lineHeight + 2);
        });

        startRow += lineHeight + 5; // Espacio adicional antes de los detalles

        const headersDetalle = ['Codigo item', 'Descripcion', 'Cantidad', 'P/U', 'Importe Bs'];

        pdf.setTextColor(0, 0, 0);
        pdf.setFont('helvetica', 'normal');
        pdf.setFontSize(fontSize);

        headersDetalle.forEach((header, colIndex) => {
            pdf.rect(10 + colIndex * 30, startRow, 30, lineHeight + 2, 'S');
            pdf.text(header, 12 + colIndex * 30, startRow + lineHeight + 2);
        });

        startRow += lineHeight + 2; // Espacio reducido entre encabezados y datos de detalle

        venta.forEach((item, rowIndex) => {
            if (startRow + lineHeight + 2 > pdf.internal.pageSize.height) {
                pdf.addPage();
                startRow = 10;

                pdf.setFontSize(16);
                pdf.setTextColor(0, 0, 0);
                pdf.text('DETALLE DE VENTAS POR DOCUMENTOS', 148, startRow, { align: 'center' });

                pdf.setFontSize(12);
                pdf.text(`Fecha inicio: ${this.fechaInicio}`, 10, startRow + 10);
                pdf.text(`Fecha fin: ${this.fechaFin}`, 70, startRow + 10);
                pdf.text(`Sucursal: ${this.sucursalseleccionada.nombre}`, 140, startRow + 10);
                pdf.text(`Ventas: ${this.criterioEstado}`, 10, startRow + 15);

                startRow += 30;
            }

            const rowDataDetalle = [
                item.codigoComida,
                item.nombre ? item.articulo_nombre : item.menu_nombre, // Mostrar el nombre correspondiente
                item.cantidad,
                item.precio_unitario,
                item.precio
            ];

            pdf.setTextColor(0, 0, 0);

            rowDataDetalle.forEach((data, colIndex) => {
                pdf.text(String(data), 12 + colIndex * 30, startRow + lineHeight + 2);
            });

            startRow += lineHeight + 2;
        });

        startRow += 15; // Espacio adicional después de los detalles
    });

    pdf.save('reporte_resumen_ventas_por_documento_detallado.pdf');
},




        exportarExcel() {
            const workbook = XLSX.utils.book_new();
            const worksheet = XLSX.utils.aoa_to_sheet([]);
            const startRow = 5;
            
            // Merge de celdas para el título
            worksheet['!merges'] = [{ s: { r: 0, c: 0 }, e: { r: 0, c: 9 } }];
            // Título del reporte
            worksheet['A1'] = { t: 's', v: 'RESUMEN DE VENTAS POR DOCUMENTOS', s: { 
                font: { sz: 16, bold: true, color: { rgb: 'FFFFFF' } },
                alignment: { horizontal: 'center', vertical: 'center' },
                fill: { fgColor: { rgb: '3669a8' } } } };

            // Estilo para la fecha
            const fechaStyle = { font: { bold: true, color: { rgb: '000000' } } };
            // Fechas de inicio y fin
            worksheet['A2'] = { t: 's', v: `Fecha inicio: ${this.fechaInicio}`, s: fechaStyle };
            worksheet['C2'] = { t: 's', v: `Fecha fin: ${this.fechaFin}`, s: fechaStyle };
            worksheet['F2'] = { t: 's', v: `Sucursal: ${this.sucursalseleccionada.nombre}`, s: fechaStyle };
            worksheet['A3'] = { t: 's', v: `Ventas: ${this.criterioEstado}`, s: fechaStyle };
            worksheet['C3'] = { t: 's', v: `Cliente: ${this.clienteseleccionada.nombre}`, s: fechaStyle };


            // Estilo para los encabezados
            const headerStyle = { font: { bold: true, color: { rgb: 'FFFFFF' } }, fill: { fgColor: { rgb: '3669a8' } } };
            // Cabeceras de las columnas
            const headers = ['Factura', 'Sucursal', 'Fecha', 'Tipo de cambio','Tipo de venta','Ejecutivo de Venta','Nombre Ejecutivo de Venta','Cliente','Importe Bs','Importe US'];

            // Añadir las cabeceras a la hoja de cálculo
            headers.forEach((header, index) => {
                worksheet[XLSX.utils.encode_cell({ r: 3, c: index })] = { t: 's', v: header, s: headerStyle };
            });

            // Añadir los datos al kardex
            Object.values(this.arrayReporte).forEach((item, rowIndex) => {
                const rowData = [
                    item.Factura,
                    item.Nombre_sucursal,
                    item.fecha_hora,
                    item.Tipo_Cambio,
                    item.Tipo_venta,
                    item.nombre_rol,
                    item.usuario,
                    item.nombre,
                    item.importe_BS,
                    item.importe_usd,
                ];

                // Añadir la fila al kardex
                XLSX.utils.sheet_add_aoa(worksheet, [rowData], { origin: `A${startRow + rowIndex}` });
            });

            // Añadir el total ganado al final del reporte
            
           // const totalRow = [`Total Ganado: Bs. ${this.total_saldo}`];
            //worksheet['!merges'].push({ s: { r: startRow + Object.values(this.sortedResultados).length, c: 0 }, e: { r: startRow + Object.values(this.sortedResultados).length, c: 3 } });

            // Establecer el ancho de las columnas
            const columnWidths = [
                { wch: 21.56 },
                { wch: 16.0 },   
                { wch: 22.22 },
                { wch: 15.14 },
                { wch: 14.78 },
                { wch: 17.11 },
                { wch: 25.0 },
                { wch: 26.67 },
                { wch: 13.11 },
                { wch: 12.78 },

            ];
            worksheet['!cols'] = columnWidths;

            // Añadir la hoja de cálculo al libro
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Resumen Ventas por Documento');

            // Descargar el archivo
            XLSX.writeFile(workbook, 'reporte_resumen_ventas_por_documento.xlsx');
        },
        
        exportarExcelDetallado() {
            const workbook = XLSX.utils.book_new();
            const worksheet = XLSX.utils.aoa_to_sheet([]);
            let startRow = 3;

            worksheet['!merges'] = [{ s: { r: 0, c: 0 }, e: { r: 0, c: 9 } }];
            // Título del reporte
            worksheet['A1'] = {
                    t: 's',
                    v: 'DETALLE DE VENTAS POR DOCUMENTOS',
                    s: {
                        font: { sz: 16, bold: true, color: { rgb: 'FFFFFF' } },
                        alignment: { horizontal: 'center', vertical: 'center' },
                        fill: { fgColor: { rgb: '3669a8' } }
                    }
                };

                // Estilo para la fecha
                const fechaStyle = { font: { bold: true, color: { rgb: '000000' } } };

                // Fechas de inicio y fin
                worksheet['A2'] = { t: 's', v: `Fecha inicio: ${this.fechaInicio}`, s: fechaStyle };
                worksheet['C2'] = { t: 's', v: `Fecha fin: ${this.fechaFin}`, s: fechaStyle };
                worksheet['F2'] = { t: 's', v: `Sucursal: ${this.sucursalseleccionada.nombre}`, s: fechaStyle };
                worksheet['A3'] = { t: 's', v: `Ventas: ${this.criterioEstado}`, s: fechaStyle };

            // Agrupar datos por 'id'
            const groupedData = this.groupById();

            groupedData.forEach((venta, index) => {
                
                // Encabezados generales
                const headersGenerales = [
                    'Factura', 'Sucursal', 'Fecha', 'Tipo_Cambio', 'Tipo de venta',
                    'Ejecutivo de Venta', 'Nombre Ejecutivo de Venta', 'Cliente', 'Importe Bs', 'Importe Bs'
                ];

                // Estilo para los encabezados generales
                const headerGeneralStyle = { font: { bold: true, color: { rgb: 'FFFFFF' } }, fill: { fgColor: { rgb: '3669a8' } } };

                // Obtener datos generales del primer elemento del grupo
                const datosGenerales = headersGenerales.map((header) => (venta[0][header] !== undefined ? venta[0][header] : ''));

                // Añadir los encabezados generales a la hoja de cálculo
                headersGenerales.forEach((header, colIndex) => {
                    worksheet[XLSX.utils.encode_cell({ r: startRow, c: colIndex })] = {
                        t: 's',
                        v: header,
                        s: headerGeneralStyle
                    };
                    worksheet[XLSX.utils.encode_cell({ r: startRow + 1, c: colIndex })] = {
                        t: 's',
                        v: datosGenerales[colIndex],
                        s: {}
                    };
                    
                });

                // Separador entre los encabezados generales y del detalle
                worksheet[XLSX.utils.encode_cell({ r: startRow + 2, c: 0 })] = { t: 's', v: '', s: {} };

                // Encabezados del detalle
                const headersDetalle = ['Codigo item', 'Marca', 'Linea', 'Industria', 'Descripcion', 'Unidad', 'Cantidad', 'P/U', 'Importe Bs', 'Importe US'];

                // Estilo para los encabezados del detalle
                const headerDetalleStyle = { font: { bold: true, color: { rgb: 'FFFFFF' } }, fill: { fgColor: { rgb: '2F75B5' } } };

                // Añadir las cabeceras del detalle a la hoja de cálculo
                headersDetalle.forEach((header, colIndex) => {
                    worksheet[XLSX.utils.encode_cell({ r: startRow + 3, c: colIndex })] = {
                        t: 's',
                        v: header,
                        s: headerDetalleStyle
                    };
                });

                // Datos del detalle de la venta
                venta.forEach((item, rowIndex) => {
                    const rowDataDetalle = [
                        item.codigo_item,
                        item.nombre_marca,
                        item.nombre_categoria,
                        item.nombre_industria,
                        item.nombre_articulo,
                        item.medida,
                        item.cantidad,
                        item.precio_unitario,
                        item.precio,
                        item.importe_usd
                    ];
                    XLSX.utils.sheet_add_aoa(worksheet, [rowDataDetalle], { origin: `A${startRow +5 + rowIndex}` });
                });

                startRow += 8 + venta.length;

            });

            // Establecer el ancho de las columnas
            const columnWidths = [
                { wch: 21.56 },
                { wch: 16.0 },   
                { wch: 22.22 },
                { wch: 15.14 },
                { wch: 28.44 },
                { wch: 17.11 },
                { wch: 25.0 },
                { wch: 26.67 },
                { wch: 13.11 },
                { wch: 12.78 },
            ];
            worksheet['!cols'] = columnWidths;

            XLSX.utils.book_append_sheet(workbook, worksheet, 'Resumen Documento Detallado');

            // Descargar el archivo
            XLSX.writeFile(workbook, 'reporte_resumen_ventas_por_documento_detallado.xlsx');
        },

        groupById() {
            // Función para agrupar datos por 'id'
            const groupedData = {};
            this.arrayReporteDetallado.forEach((item) => {
                const key = item.id;
                if (!groupedData[key]) {
                    groupedData[key] = [];
                }
                groupedData[key].push(item);
            });
            return Object.values(groupedData);
        },


        formateaKardex(){
            let saldo = 0;
            let me = this;
            me.arrayReporte = this.arrayReporte.map(item => {
            if (item.tipo === 'Ingreso') {
            saldo += item.cantidad;
            return { fecha_hora: item.fecha_hora, entrada: item.cantidad, salida: 0, saldo };
            } else if (item.tipo === 'Salida') {
            saldo -= item.cantidad;
            return { fecha_hora: item.fecha_hora, entrada: 0, salida: item.cantidad, saldo };
            }
    // Si hay más tipos, puedes agregar condiciones aquí
            });

            console.log("KARDEX",me.arrayReporte);
        },  
        
        //----listar precio 4_julio-------
        listarPrecio() {
            let me = this;
            var url = '/precios';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.precios = respuesta.precio.data;
                console.log('PRECIOS', me.precios);
                //me.precioCount = me.arrayBuscador.length;
            }).catch(function (error) {
                console.log(error);
            });
        },
        //---------hasta aqui----------------


        selectMedida() {
            let me = this;
            var url = '/medida/selectMedida';
            axios.get(url).then(function (response) {
                //console.log(response);
                var respuesta = response.data;
                me.arrayMedida = respuesta.medidas;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },

        cambiarPagina(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            //Envia la petición para visualizar la data de esa página
            me.listarEjecutivo(page, buscar, criterio);
        },
        cambiarPaginaMedida(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.paginationMedida.current_page = page;
            me.listarMedida(page, buscar, criterio);
            //Envia la petición para visualizar la data de esa página
        },
        cambiarPaginaMarca(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            me.listarMarca(page, buscar, criterio);
            //Envia la petición para visualizar la data de esa página
        },
        cambiarPaginaLinea(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            me.listarLinea(page, buscar, criterio);
            //Envia la petición para visualizar la data de esa página
        },
        cambiarPaginaIndustria(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            me.listarIndustria(page, buscar, criterio);
            //Envia la petición para visualizar la data de esa página
        },
        cambiarPaginaProveedor(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            me.listarproveedor(page, buscar, criterio);
            //Envia la petición para visualizar la data de esa página
        },
        cambiarPaginaGrupo(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            me.listargrupo(page, buscar, criterio);
            //Envia la petición para visualizar la data de esa página
        },
        //mostrar foto de articulo
        obtenerFotografia(event) {

            let file = event.target.files[0];

            let fileType = file.type;
            // Validar si el archivo es una imagen en formato PNG o JPG
            if (fileType !== 'image/png' && fileType !== 'image/jpeg') {
                alert('Por favor, seleccione una imagen en formato PNG o JPG.');
                return;
            }

            this.fotografia = file;
            this.mostrarFoto(file);
        },
        mostrarFoto(file) {

            let reader = new FileReader();

            reader.onload = (file) => {
                this.fotoMuestra = file.target.result;
            }
            reader.readAsDataURL(file);
        },
        calcularPrecioValorMoneda(precio) {
            console.log(precio)
            return ((precio * parseFloat(this.monedaPrincipal)).toFixed(2))
        },
        convertDolar(precio) {
            return (precio / parseFloat(this.monedaPrincipal))
        },


        advertenciaInactiva(nombre) {
            swal({
                title: 'Opción Inactiva',
                text: 'No puede seleccionar esta opción porque está inactiva.',
                type: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar',
                confirmButtonClass: 'btn btn-success',
                buttonsStyling: false,
            }).then(() => {
                if (nombre == 'Medidas') {
                    this.abrirModal6(nombre);
                }
                else {
                    this.abrirModal2(nombre);
                }

            });
        },

        //#################hasta aqui####################
        validarArticulo() {
            this.errorArticulo = 0;
            this.errorMostrarMsjArticulo = [];

            // if (this.lineaseleccionada.length == 0) this.errorMostrarMsjArticulo.push("");
            // if (this.industriaseleccionada.length == 0) this.errorMostrarMsjArticulo.push("");
            // if (this.marcaseleccionada.length == 0) this.errorMostrarMsjArticulo.push("");
            // if (this.proveedorseleccionada.length == 0) this.errorMostrarMsjArticulo.push("");
            // if (this.medidaseleccionada.length == 0) this.errorMostrarMsjArticulo.push("");
            // if (this.gruposeleccionada.length == 0) this.errorMostrarMsjArticulo.push("");

            if (!this.unidad_envase) this.errorMostrarMsjArticulo.push("");
            if (!this.codigo) this.errorMostrarMsjArticulo.push("");
            if (!this.nombre_producto) this.errorMostrarMsjArticulo.push("");
            if (!this.nombre_generico) this.errorMostrarMsjArticulo.push("");
            if (!this.precio_costo_unid) this.errorMostrarMsjArticulo.push("");
            if (!this.precio_costo_paq) this.errorMostrarMsjArticulo.push("");
            if (!this.descripcion) this.errorMostrarMsjArticulo.push("");
            if (!this.stock) this.errorMostrarMsjArticulo.push("");
            if (!this.precio_venta) this.errorMostrarMsjArticulo.push("");
            if (!this.costo_compra) this.errorMostrarMsjArticulo.push("");
            if (!this.fotografia) this.errorMostrarMsjArticulo.push("");

            if (this.errorMostrarMsjArticulo.length) this.errorArticulo = 1;

            return this.errorArticulo;
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
            this.idAlmacen = null;
            this.arrayProductos = [];
            this.arrayDetalle = [];
            this.precioBloqueado = false;

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
                console.log("VIENDO ", respuesta)

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
                console.log("viendo este detalle: ", me.arrayDetalle)

            })
                .catch(function (error) {
                    console.log(error);
                });
        },

        cerrarModal() {
            this.modal = 0;
            this.tituloModal = '';
            //this.idcategoria = 0;
            //this.nombre_categoria = '';
            //validacion para quitar borde rojo en los inputs
            this.codigoVacio = false;
            this.nombreProductoVacio = false;
            this.unidad_envaseVacio = false;
            this.nombre_genericoVacio = false;
            this.precio_costo_unidVacio = false;
            this.precio_costo_paqVacio = false;
            this.precio_ventaVacio = false;
            this.costo_compraVacio = false;
            this.stockVacio = false;
            this.descripcionVacio = false;
            this.fotografiaVacio = false;
            this.lineaseleccionadaVacio = false;
            this.industriaseleccionadaVacio = false;
            this.proveedorseleccionadaVacio = false;
            this.gruposeleccionadaVacio = false;
            this.medidaseleccionadaVacio = false;
            this.articuloseleccionadaVacio = false;
            //
            this.codigo = '';
            this.nombre_producto = '';
            this.nombre_generico = '';
            this.precio_venta = 0;
            this.precio_costo_unid = 0;
            this.precio_costo_paq = 0;
            this.stock = 5;
            this.descripcion = '';
            this.fotografia = ''; //Pasando el valor limpio de la referencia
            this.fotoMuestra = null;
            this.lineaseleccionada.nombre = '';
            this.industriaseleccionada.nombre = '';
            this.proveedorseleccionada.nombre = '';
            this.gruposeleccionada.nombre_grupo = '';
            this.medidaseleccionada.descripcion_medida = '';
            this.lineaseleccionada.nombre = '';
            this.articuloseleccionada.nombre = '';
            //this.sucursalseleccionada.nombre = '';

            this.errorArticulo = 0;

            this.idmedida = 0;
            this.costo_compra = 0;

            this.precio_uno = 0;
            this.precio_dos = 0;
            this.precio_tres = 0;
            this.precio_cuatro = 0;
            
            // unidad_envaseVacio: false,
            // nombre_genericoVacio: false,
            // precio_costo_unidVacio: false,
            // precio_costo_paqVacio: false,
            // precio_ventaVacio: false,
            // costo_compraVacio: false,
            // stockVacio: false,
            // descripcionVacio: false,
            // fecha_vencimientoVacio: false,
            // fotografiaVacio: false,
            // lineaseleccionadaVacio: false,
            // marcaseleccionadaVacio: false,
            // industriaseleccionadaVacio: false,
            // proveedorseleccionadaVacio: false,
            // gruposeleccionadaVacio: false,
            // medidaseleccionadaVacio: false,
        },
        abrirModal(modelo, accion, data = []) {
            this.ejecutivoseleccionado = false;
            this.clienteseleccionada = false;
            this.sucursalseleccionada = false;
            this.fechaInicio = '';
            this.fechaFin ='';
            switch (modelo) {
                case "articulo":
                    {
                        switch (accion) {

                            case 'registrar':
                                {
                                    this.modal = 1;
                                    this.tituloModal = 'Registrar Artículo';

                                    this.tipoAccion = 1;
                                    this.fotografia = '';

                                    this.datosFormulario = {
                                        nombre: '',
                                        descripcion: '',
                                        nombre_generico: '',
                                        unidad_envase: 0,
                                        precio_costo_unid: 0,
                                        precio_costo_paq: 0,
                                        precio_venta: 0,
                                        precio_uno: 0,
                                        precio_dos: 0,
                                        precio_tres: 0,
                                        precio_cuatro: 0,
                                        stock: 0,
                                        costo_compra: 0,
                                        codigo: '',
                                        codigo_alfanumerico: '',
                                        descripcion_fabrica: '',
                                        idcategoria: null,
                                        idmarca: null,
                                        idindustria: null,
                                        idgrupo: null,
                                        idproveedor: null,
                                        idmedida: null
                                    };
                                    this.errores = {};
                                    break;
                                }
                            case 'actualizar':
                                {
                                    console.log("datass", data);
                                    this.modal = 1;
                                    this.tituloModal = 'Actualizar Artículo';
                                    this.tipoAccion = 2;
                                    this.datosFormulario = {
                                        nombre: data['nombre'],
                                        descripcion: data['descripcion'],
                                        nombre_generico: data['nombre_generico'],
                                        unidad_envase: data['unidad_envase'],
                                        precio_costo_unid: this.calcularPrecioValorMoneda(data['precio_costo_unid']),
                                        precio_costo_paq: this.calcularPrecioValorMoneda(data['precio_costo_paq']),
                                        precio_venta: this.calcularPrecioValorMoneda(data['precio_venta']),
                                        precio_uno: 0,
                                        precio_dos: 0,
                                        precio_tres: 0,
                                        precio_cuatro: 0,
                                        stock: data['stock'],
                                        costo_compra: this.calcularPrecioValorMoneda(data['costo_compra']),
                                        codigo: data['codigo'],
                                        codigo_alfanumerico: data['codigo_alfanumerico'],
                                        descripcion_fabrica: data['descripcion_fabrica'],
                                        idcategoria: null,
                                        idmarca: null,
                                        idindustria: null,
                                        idgrupo: null,
                                        idproveedor: null,
                                        idmedida: data['idmedida'],
                                        id: data['id']
                                    };
                                    this.errores = {};
                                    this.idmedida = data['idmedida'];

                                    this.fotografia = data['fotografia'];
                                    this.fotoMuestra = data['fotografia'] ? 'img/articulo/' + data['fotografia'] : null;
                                    //this.industriaseleccionada = { nombre: data['industriaseleccionada.nombre'] };

                                    //this.industriaseleccionada = {nombre: data['nombre_industria']};
                                    this.industriaseleccionada = { nombre: data['nombre_industria'], id: data['idindustria'] };
                                    //this.lineaseleccionada = {nombre: data['nombre_categoria']};
                                    this.lineaseleccionada = { nombre: data['nombre_categoria'], id: data['idcategoria'] };
                                    //this.marcaseleccionada = {nombre: data['nombre_marca']};
                                    this.clienteseleccionada = { nombre: data['nombre'], id: data['idmarca'] };
                                    this.proveedorseleccionada = { nombre: data['nombre_proveedor'], id: data['idproveedor'] };
                                    //this.gruposeleccionada = {nombre_grupo: data['nombre_grupo']};
                                    this.gruposeleccionada = { nombre_grupo: data['nombre_grupo'], id: data['idgrupo'] };
                                    this.medidaseleccionada = { descripcion_medida: data['descripcion_medida'], id: data['idmedida'] };

                                    this.precio_uno = this.calcularPrecioValorMoneda(data['precio_uno']);
                                    this.precio_dos = this.calcularPrecioValorMoneda(data['precio_dos']);
                                    this.precio_tres = this.calcularPrecioValorMoneda(data['precio_tres']);
                                    this.precio_cuatro = this.calcularPrecioValorMoneda(data['precio_cuatro']);
                                    // this.precios.forEach((precio) => {
                                    //     this.calcularPrecio(precio);
                                    // });
                                    break;

                                }
                            case 'registrarInd':
                                {
                                    this.modal = 1;
                                    this.tituloModal = 'Registrar Industria';
                                    this.nombre = '';
                                    //this.descripcion = '';
                                    this.tipoAccion = 3;
                                    break;
                                }
                        }
                    }
            }
        },

        calcularPrecio(precio, index) {
            if (isNaN(this.datosFormulario.precio_costo_unid) || isNaN(parseFloat(precio.porcentage))) {
                console.log('El valor de precio_costo_unid o porcentaje no es válido');
                return;
            }
            const margen_ganancia = parseFloat(this.datosFormulario.precio_costo_unid) * (parseFloat(precio.porcentage) / 100);
            const precio_publico = parseFloat(this.datosFormulario.precio_costo_unid) + margen_ganancia;

            if (index === 0) {
                this.precio_uno = precio_publico.toFixed(2);
            } else if (index === 1) {
                this.precio_dos = precio_publico.toFixed(2);
            } else if (index === 2) {
                this.precio_tres = precio_publico.toFixed(2);
            } else if (index === 3) {
                this.precio_cuatro = precio_publico.toFixed(2);
            }
            console.log('Precio público:', precio_publico);
            console.log('precio_dos:', this.precio_dos);
        },

        cerrarModal3() {
            this.modal3 = 0;
            this.tituloModal3 = '';
            this.nombre = '';
            this.limpiarErrores();
        },
        cerrarModal6() {
            this.modal6 = 0;
            this.tituloModal6 = '';
            this.descripcion_medida = '';
            this.descripcion_corta = '';
        },
        cerrarModal7() {
            this.modal7 = 0;
            this.tituloModal7 = '';
            this.descripcion_medida = '';
            this.descripcion_corta = '';
            this.limpiarErrores2();
        },
        limpiarErrores() {
            if (this.tituloModal2 === 'Industrias') {
                this.errorMostrarMsjIndustria = [];
                this.errorIndustria = 0;
            } else if (this.tituloModal2 === 'Marcas') {
                this.errorMostrarMsjIndustria = [];
                this.errorIndustria = 0;
            } else {
                this.errorMostrarMsjIndustria = [];
                this.errorIndustria = 0;
            }
        },
        limpiarErrores2() {
            this.tituloModal6 === 'Medidas'
            this.errorMostrarMsjMedida = [];
            this.errorMedida = 0;
        },


        //################placeholder mensaje si es indus►ria, marca o linea########
        placeholderInput(inputType) {
            if (this.tituloModal2 === 'Marcas') {
                return 'Nombre de Marca'
            } else if (this.tituloModal2 === 'Industrias') {
                return 'Nombre de Industria'
                // } else if (this.tituloModal2 === 'Proveedor') {
                //     return 'Nombre de Proveedor'
            } else if (this.tituloModal2 === 'Grupos') {
                return 'Nombre de Grupo'
            } else if (this.tituloModal2 === 'Lineas') {
                if (inputType === 'nombre') {
                    return 'Nombre de Linea';
                } else if (inputType === 'descripcion') {
                    return 'Descripcion de Linea';
                }
                else if (inputType === 'codigoProductoSin') {
                    return 'Codigo de Linea';
                }
            }
        },
        //############hasta aqui-#########
        //################-Abrl moda de industrial,marca,Linea########
        abrirModal3(modelo3, accion3, data = []) {
            switch (modelo3) {
                case "industria":
                    {
                        switch (accion3) {
                            case 'registrarInd':
                                {
                                    this.modal3 = 1;
                                    this.tituloModal3 = 'Registrar Industria';
                                    this.nombre = '';
                                    this.estado = '';
                                    this.tipoAccion2 = 3;
                                    //this.modal3=true;
                                    break;
                                }
                            case 'actualizarInd':
                                {
                                    this.modal3 = 1;
                                    this.tituloModal3 = 'Actualizar Industria';
                                    this.tipoAccion2 = 4;
                                    this.industria_id = data['id'];
                                    this.nombre = data['nombre'];
                                    this.estado = data['estado'];
                                    break;
                                }

                        }
                    }
                case "Marca":
                    {
                        switch (accion3) {
                            case 'registrarMar':
                                {
                                    this.modal3 = 1;
                                    this.tituloModal3 = 'Registrar Marca';
                                    this.nombre = '';
                                    this.condicion = '';
                                    this.tipoAccion2 = 5;
                                    break;
                                }
                            case 'actualizar':
                                {
                                    this.modal3 = 1;
                                    this.tituloModal3 = 'Actualizar marca';
                                    this.tipoAccion2 = 6;
                                    this.marca_id = data['id'];
                                    this.nombre = data['nombre'];
                                    this.condicion = data['condicion'];
                                    break;
                                }
                        }
                    }
                case "Linea":
                    {
                        switch (accion3) {
                            case 'registrarLin':
                                {
                                    this.modal3 = 1;
                                    this.tituloModal3 = 'Registrar Linea';
                                    this.nombreLinea = '';
                                    this.descripcion = '';
                                    this.codigoProductoSin = 0;
                                    this.condicion = '';
                                    this.tipoAccion2 = 7;
                                    break;

                                }
                            case 'actualizarLin':
                                {
                                    this.modal3 = 1;
                                    this.tituloModal3 = 'Actualizar Linea';
                                    this.tipoAccion2 = 8;
                                    this.linea_id = data['id'];
                                    this.nombreLinea = data['nombre'];
                                    this.descripcion = data['descripcion'];
                                    this.codigoProductoSin = data['codigoProductoSin'];
                                    this.condicion = data['condicion'];
                                    break;
                                }
                        }

                    }
                case "Grupo":
                    {
                        switch (accion3) {
                            case 'registrarGrupo':
                                {
                                    this.modal3 = 1;
                                    this.tituloModal3 = 'Registrar Grupo';
                                    this.tipoAccion2 = 11;
                                    this.nombre_grupo = '';

                                    break;
                                }
                            case 'actualizarGrupo':
                                {
                                    this.modal3 = 1;
                                    this.tituloModal3 = 'Actualizar Grupo';
                                    this.tipoAccion2 = 12;
                                    this.grupo_id = data['id'];
                                    this.nombre_grupo = data['nombre_grupo'];
                                    break;
                                }
                        }
                    }
                case "Proveedor":
                    {
                        switch (accion3) {
                            case 'registrarProv':
                                {
                                    this.modal3 = 1;
                                    this.tituloModal3 = 'Registrar Proveedor';
                                    this.nombre = '';
                                    this.tipo_documento = 'RUC';
                                    this.num_documento = '';
                                    this.direccion = '';
                                    this.telefono = '';
                                    this.email = '';
                                    this.contacto = '';
                                    this.telefono_contacto = '';
                                    this.tipoAccion2 = 9;
                                    break;
                                }
                            case 'actualizarProv':
                                {
                                    //console.log('Proveedor',data)
                                    this.modal3 = 1;
                                    this.tituloModal3 = 'Actualizar Proveedor';
                                    this.tipoAccion2 = 10;
                                    this.proveedor_id = data['id'];
                                    this.nombre = data['nombre'];
                                    this.tipo_documento = data['tipo_documento'];
                                    this.num_documento = data['num_documento'];
                                    this.direccion = data['direccion'];
                                    this.telefono = data['telefono'];
                                    this.email = data['email'];
                                    this.contacto = data['contacto'];
                                    this.telefono_contacto = data['telefono_contacto'];
                                    break;
                                }
                        }
                    }
                case "Sucursal":
                    {
                        switch (accion3) {
                            case 'registrarLin':
                                {
                                    this.modal3 = 1;
                                    this.tituloModal3 = 'Registrar Linea';
                                    this.nombreLinea = '';
                                    this.descripcion = '';
                                    this.codigoProductoSin = 0;
                                    this.condicion = '';
                                    this.tipoAccion2 = 7;
                                    break;

                                }
                            case 'actualizarLin':
                                {
                                    this.modal3 = 1;
                                    this.tituloModal3 = 'Actualizar Linea';
                                    this.tipoAccion2 = 8;
                                    this.linea_id = data['id'];
                                    this.nombreLinea = data['nombre'];
                                    this.descripcion = data['descripcion'];
                                    this.codigoProductoSin = data['codigoProductoSin'];
                                    this.condicion = data['condicion'];
                                    break;
                                }
                        }

                    }
            }
        },
        //############3hasta aqui######################

        //############3hasta aqui######################
        abrirModal7(modelo6, accion6, data = []) {
            switch (modelo6) {
                case "medida":
                    {
                        switch (accion6) {
                            case 'registrarMed':
                                {
                                    this.modal7 = 1;
                                    this.tituloModal7 = 'Registrar Medida';
                                    this.descripcion_medida = '';
                                    this.descripcion_corta = '';
                                    this.estado = '1';
                                    this.tipoAccion6 = 6;
                                    break;
                                }
                            case 'actualizarMed':
                                {
                                    this.modal7 = 1;
                                    this.tituloModal7 = 'Actualizar Medida';
                                    this.tipoAccion6 = 7;
                                    this.medida_id = data['id'];
                                    this.descripcion_medida = data['descripcion_medida'];
                                    this.descripcion_corta = data['descripcion_corta'];
                                    this.estado = data['estado'];
                                    break;
                                }

                        }
                    }
            }
        },


        datosConfiguracion() {
            let me = this;
            var url = '/configuracion';

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.mostrarSaldosStock = respuesta.configuracionTrabajo.mostrarSaldosStock;
                me.mostrarCostos = respuesta.configuracionTrabajo.mostrarCostos;
                me.mostrarProveedores = respuesta.configuracionTrabajo.mostrarProveedores;

                me.monedaPrincipal = [respuesta.configuracionTrabajo.valor_moneda_principal, respuesta.configuracionTrabajo.simbolo_moneda_principal]
                console.log("MostrarCostos: " + me.mostrarCostos);
                console.log("ProveedorEstado: " + me.mostrarProveedores);
                console.log("MostrarSaldosStock: " + me.mostrarSaldosStock);
                console.log("Moneda principal; ",me.monedaPrincipal);
                console.log("Moneda; ",me.monedaPrincipal[0]);

            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        recuperarIdRol() {
            this.rolUsuario = window.userData.rol;
            console.log('ID_ROL: ' + this.rolUsuario);
        },

    },
    mounted() {
        this.recuperarIdRol();
        this.datosConfiguracion();
        this.obtenerConfiguracionTrabajo();
        this.listarPrecio();//aumenTe 6julio
    }
}
</script>
<style>    
    .card-error {
        margin-bottom: 10px;
        width: 100%;
        padding: 15px;
        border-radius: 15px;
        border: 2px solid #ab7078;
        background-color: #fec0ca;

    }

    .csv-headers-container {
        margin-top: 10px;
    }

    .csv-headers-list {
        list-style-type: none;
        padding: 0;
        display: flex;
        flex-wrap: wrap;
    }

    .csv-header {
        background-color: #3498db;
        color: white;
        padding: 5px 10px;
        margin: 5px;
        border-radius: 5px;
        cursor: pointer;
    }

    .csv-header label {
        display: flex;
        align-items: center;
    }

    .csv-header input {
        margin-right: 5px;
    }

    .selected-headers-container {
        margin-top: 10px;
    }

    .selected-headers-list {
        list-style-type: none;
        padding: 10px;
        display: flex;
        flex-wrap: wrap;
    }

    /* .selected-header {
        box-shadow: 10px 5px 5px black;
        color: black;

        -webkit-box-shadow: 3px -7px 38px -1px rgba(27, 209, 11, 0.69);
        -moz-box-shadow: 3px -7px 38px -1px rgba(27, 209, 11, 0.69);
        box-shadow: 3px -7px 38px -1px rgba(27, 209, 11, 0.69);
    } */

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

    .table-responsive {
        overflow-x: auto;
    }


    .sticky-column {
        position: sticky;
        left: 0;
        z-index: 1;
        background-color: white;
    }

    .border-red {
        border-color: red !important;
    }

    .loader {
        display: block;
        position: relative;
        height: 12px;
        width: 100%;
        border: 1px solid #fff;
        border-radius: 10px;
        overflow: hidden;
    }

    .loader::after {
        content: '';
        width: 40%;
        height: 100%;
        background: #FF3D00;
        position: absolute;
        top: 0;
        left: 0;
        box-sizing: border-box;
        animation: animloader 2s linear infinite;
    }

    @keyframes animloader {
        0% {
            left: 0;
            transform: translateX(-100%);
        }

        100% {
            left: 100%;
            transform: translateX(0%);
        }
    }

    /**
 * Extracted from: SweetAlert
 * Modified by: Istiak Tridip
 */
    .success-checkmark {
        width: 80px;
        height: 115px;
        margin: 0 auto;

        .check-icon {
            width: 80px;
            height: 80px;
            position: relative;
            border-radius: 50%;
            box-sizing: content-box;
            border: 4px solid #4CAF50;

            &::before {
                top: 3px;
                left: -2px;
                width: 30px;
                transform-origin: 100% 50%;
                border-radius: 100px 0 0 100px;
            }

            &::after {
                top: 0;
                left: 30px;
                width: 60px;
                transform-origin: 0 50%;
                border-radius: 0 100px 100px 0;
                animation: rotate-circle 4.25s ease-in;
            }

            &::before,
            &::after {
                content: '';
                height: 100px;
                position: absolute;
                background: #FFFFFF;
                transform: rotate(-45deg);
            }

            .icon-line {
                height: 5px;
                background-color: #4CAF50;
                display: block;
                border-radius: 2px;
                position: absolute;
                z-index: 10;

                &.line-tip {
                    top: 46px;
                    left: 14px;
                    width: 25px;
                    transform: rotate(45deg);
                    animation: icon-line-tip 0.75s;
                }

                &.line-long {
                    top: 38px;
                    right: 8px;
                    width: 47px;
                    transform: rotate(-45deg);
                    animation: icon-line-long 0.75s;
                }
            }

            .icon-circle {
                top: -4px;
                left: -4px;
                z-index: 10;
                width: 80px;
                height: 80px;
                border-radius: 50%;
                position: absolute;
                box-sizing: content-box;
                border: 4px solid rgba(76, 175, 80, .5);
            }

            .icon-fix {
                top: 8px;
                width: 5px;
                left: 26px;
                z-index: 1;
                height: 85px;
                position: absolute;
                transform: rotate(-45deg);
                background-color: #FFFFFF;
            }
        }
    }

    @keyframes rotate-circle {
        0% {
            transform: rotate(-45deg);
        }

        5% {
            transform: rotate(-45deg);
        }

        12% {
            transform: rotate(-405deg);
        }

        100% {
            transform: rotate(-405deg);
        }
    }

    @keyframes icon-line-tip {
        0% {
            width: 0;
            left: 1px;
            top: 19px;
        }

        54% {
            width: 0;
            left: 1px;
            top: 19px;
        }

        70% {
            width: 50px;
            left: -8px;
            top: 37px;
        }

        84% {
            width: 17px;
            left: 21px;
            top: 48px;
        }

        100% {
            width: 25px;
            left: 14px;
            top: 45px;
        }
    }

    @keyframes icon-line-long {
        0% {
            width: 0;
            right: 46px;
            top: 54px;
        }

        65% {
            width: 0;
            right: 46px;
            top: 54px;
        }

        84% {
            width: 55px;
            right: 0px;
            top: 35px;
        }

        100% {
            width: 47px;
            right: 8px;
            top: 38px;
        }
    }
</style>
