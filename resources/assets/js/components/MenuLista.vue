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
                    <i class="fa fa-align-justify"></i> Cartilla de Menu
                    <button type="button" @click="abrirModal('articulo', 'registrar'); listarPrecio()"
                        class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <button type="button" @click="cargarPdf()" class="btn btn-info">
                        <i class="icon-doc"></i>&nbsp;Reporte
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="nombre">Nombre</option>
                                    <option value="descripcion">Descripción</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarArticulo(1, buscar, criterio)"
                                    class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarArticulo(1, buscar, criterio)"
                                    class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <div style="overflow-x: auto;">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Codigo</th>
                                    <th>Nombre</th>
                                    <th>Categorìa</th>
                                    <th>Precio</th>
                                    <th>Descripción</th>
                                    <th>Foto</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="articulo in listaArticulosConImagenSrc" :key="articulo.id">
                                    <button type="button" @click="abrirModal('articulo', 'actualizar', articulo)"
                                            class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="articulo.condicion">
                                            <button type="button" class="btn btn-danger btn-sm"
                                                @click="desactivarArticulo(articulo.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm"
                                                @click="activarArticulo(articulo.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    <td v-text="articulo.codigo"></td>
                                    <td v-text="articulo.nombre"></td>
                                    <td v-text="articulo.nombre_categoria"></td>
                                    <td v-text="articulo.precio_venta"></td>
                                    <td v-text="articulo.descripcion"></td>
                                    <td class="text-center">
                                        <img :src="articulo.imagenSrc"
                                            width="50" height="50" ref="imagen">
                                    </td>

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
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!-- MODAL LISTADO DE MARCAS -->

        <!-- contenido del modal -->

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
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div style="display: none;" class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input"
                                    style="color: blue;">Código</label>
                                <div class="col-md-4">
                                    <input type="text"
                                        v-model="codigo"
                                        class="form-control"
                                        :class="{ 'border-red': codigoVacio }"
                                        @input="codigoVacio = false"
                                        placeholder="Código de barras">
                                </div>
                                <div class="col-md-12 order-md-2">
                                    <div class="barcode-container">
                                        <barcode :value="codigo" :options="{ format: 'EAN-13' }"></barcode>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="text-input"><strong>Nombre(*)</strong></label>
                                        <input type="text"
                                        v-model="nombre_producto"
                                        class="form-control"
                                        :class="{ 'border-red': nombreProductoVacio }"
                                        @input="nombreProductoVacio = false"
                                        placeholder="Nombre de artículo">
                                    </div>              
                                </div>
                                <!---DERECHA-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="text-input"><strong>Precio(*)</strong></label>
                                        <input type="number" 
                                            v-model="precio_venta" 
                                            class="form-control"
                                            :class="{ 'border-red': precio_ventaVacio }" 
                                            @input="precio_ventaVacio = false"
                                            placeholder="">
                                    </div>
                                </div>
                            </div>
                           
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-control-label" for="text-input"><strong>Descripción</strong></label>
                                        <input type="email" 
                                        v-model="descripcion" 
                                        class="form-control"
                                        :class="{ 'border-red': descripcionVacio }" 
                                        @input="descripcionVacio = false"
                                        placeholder="Ingrese descripción">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-control-label" for="text-input"><strong>Fotografia(*)</strong></label>
                                        <div class="input-group">
                                            <input type="file" @change="obtenerFotografia" class="form-control"
                                            :class="{ 'border-red': fotografiaVacio }" 
                                            @input="fotografiaVacio = false"
                                            placeholder="fotografia usuario" ref="fotografiaInput">                                           
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <figure>
                                            <img :src="imagen" width="60" height="60" alt="Foto articulo">
                                        </figure>
                                    </div>
                                </div>
                            </div>
                            
                            <div v-show="errorArticulo" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjArticulo" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        </form>
                        <div  class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="text-input"><strong>Categoria(*)</strong></label>
                                    <div class="input-group">
                                        <input type="text" disabled v-model="lineaseleccionada.nombre"
                                        :class="{ 'border-red': lineaseleccionadaVacio }">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button" @click="abrirModal2('Lineas')">...</button>
                                        </div>
                                    </div>
                                </div>     
                            </div> 

                             <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="text-input"><strong>Codigo(*)</strong></label>
                                        <input type="text"
                                        v-model="codigo_producto"
                                        class="form-control"
                                        :class="{ 'border-red': codigoVacio }"
                                        @input="codigoVacio = false"
                                        placeholder="Codigo del plato">
                                    </div>              
                                </div>             
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion == 1" class="btn btn-primary"
                            @click="registrarArticulo()">Guardar</button>
                        <button type="button" v-if="tipoAccion == 2" class="btn btn-primary"
                            @click="actualizarArticulo()">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!-- MODAL PARA LA LISTA DE MEDIDA -->
        <div class="modal fade" tabindex="-1" :class="{ 'mostrar': modal6 }" role="dialog" aria-labelledby="myModalLabel"
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
                                    <!--button type="submit" @click="listarArticulo(buscarA, criterioA)"
                                class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button-->
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
                                            <button v-if="tituloModal6 == 'Medidas'" type="button"
                                                @click="abrirModal7('medida', 'actualizarMed', arrayelemento)"
                                                class="btn btn-warning btn-sm">
                                                <i class="icon-pencil"></i>
                                            </button> &nbsp;
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
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginaMedida(page, buscar, criterio)"
                                        v-text="page"></a>
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

        <div class="modal fade" tabindex="-1" :class="{ 'mostrar': modal2 }" role="dialog" aria-labelledby="myModalLabel"
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
                                    <select class="form-control col-md-3">
                                        <option v-if="tituloModal2 !== 'Grupos'" value="nombre">Nombre</option>
                                        <option v-else-if="tituloModal2 == 'Grupos'" value="nombre_grupo">Grupo</option>
                                        <!-- <option v-if="tituloModal2=='Grupos'" value="nombre_grupo">Nombre_grupo</option> -->
                                    </select>
                                    <input v-if="tituloModal2 == 'Marcas'" type="text" v-model="buscarA"
                                        @keyup="listarMarca(1, buscarA, criterioA)" class="form-control"
                                        placeholder="Texto a buscar">
                                    <input v-if="tituloModal2 == 'Industrias'" type="text" v-model="buscarA"
                                        @keyup="listarIndustria(1, buscarA, criterioA)" class="form-control"
                                        placeholder="Texto a buscar">
                                    <input v-if="tituloModal2 == 'Lineas'" type="text" v-model="buscarA"
                                        @keyup="listarLinea(1, buscarA, criterioA)" class="form-control"
                                        placeholder="Texto a buscar">
                                    <input v-if="tituloModal2 == 'Proveedors'" type="text" v-model="buscarA"
                                        @keyup="listarproveedor(1, buscarA, criterioA)" class="form-control"
                                        placeholder="Texto a buscar">
                                    <input v-if="tituloModal2 == 'Grupos'" type="text" v-model="buscarA"
                                        @keyup="listargrupo(1, buscarA, criterioA)" class="form-control"
                                        placeholder="Texto a buscar">

                                    <button v-show="tituloModal2 == 'Industrias'" type="button"
                                        @click="abrirModal3('industria', 'registrarInd')" class="btn btn-secondary">
                                        <i class="icon-plus"></i>&nbsp;Nuevo
                                    </button>
                                    <button v-show="tituloModal2 == 'Marcas'" type="button"
                                        @click="abrirModal3('Marca', 'registrarMar')" class="btn btn-secondary">
                                        <i class="icon-plus"></i>&nbsp;Nuevo
                                    </button>
                                    <button v-show="tituloModal2 == 'Lineas'" type="button"
                                        @click="abrirModal3('Linea', 'registrarLin')" class="btn btn-secondary">
                                        <i class="icon-plus"></i>&nbsp;Nuevo
                                    </button>
                                    <button v-show="tituloModal2 == 'Proveedors'" type="button"
                                        @click="abrirModal3('Proveedor', 'registrarProv')" class="btn btn-secondary">
                                        <i class="icon-plus"></i>&nbsp;Nuevo
                                    </button>
                                    <button v-show="tituloModal2 == 'Grupos'" type="button"
                                        @click="abrirModal3('Grupo', 'registrarGrupo')" class="btn btn-secondary">
                                        <i class="icon-plus"></i>&nbsp;Nuevo
                                    </button>
                                    <!--button type="submit" @click="listarArticulo(buscarA, criterioA)"
                                        class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button-->
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
                                        <!-- <th>Estado</th> -->
                                        <th>
                                            <div v-if="tituloModal2 == 'Proveedors'">
                                                Nit
                                            </div>
                                            <div v-else>
                                                Estado
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="arrayelemento in arrayBuscador" :key="arrayelemento.id">
                                        <td>
                                            <button type="button" @click="seleccionar(arrayelemento)"
                                                class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                            </button>
                                            <button v-if="tituloModal2 == 'Industrias'" type="button"
                                                @click="abrirModal3('industria', 'actualizarInd', arrayelemento)"
                                                class="btn btn-warning btn-sm">
                                                <i class="icon-pencil"></i>
                                            </button> &nbsp;
                                            <button v-if="tituloModal2 == 'Marcas'" type="button"
                                                @click="abrirModal3('Marca', 'actualizar', arrayelemento)"
                                                class="btn btn-warning btn-sm">
                                                <i class="icon-pencil"></i>
                                            </button> &nbsp;
                                            <button v-if="tituloModal2 == 'Lineas'" type="button"
                                                @click="abrirModal3('Linea', 'actualizarLin', arrayelemento)"
                                                class="btn btn-warning btn-sm">
                                                <i class="icon-pencil"></i>
                                            </button> &nbsp;
                                            <button v-if="tituloModal2 == 'Proveedors'" type="button"
                                                @click="abrirModal3('Proveedor', 'actualizarProv', arrayelemento)"
                                                class="btn btn-warning btn-sm">
                                                <i class="icon-pencil"></i>
                                            </button> &nbsp;

                                        </td>
                                        <td v-text="arrayelemento.id"></td>
                                        <!-- <div v-if="tituloModal2=='Grupos'">
                                            <td  v-text="arrayelemento.nombre_grupo"></td>
                                        </div> -->
                                        <td v-text="arrayelemento.nombre"></td>
                                        <td v-if="tituloModal2 == 'Industrias'">
                                            <div v-if="arrayelemento.estado">
                                                <span class="badge badge-success">Activo</span>
                                            </div>
                                            <div v-else>
                                                <span class="badge badge-danger">Desactivado</span>
                                            </div>
                                        </td>
                                        <td v-if="tituloModal2 == 'Marcas' || tituloModal2 == 'Lineas'">
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
                                            <button v-if="tituloModal2 == 'Grupos'" type="button"
                                                @click="abrirModal3('Grupo', 'actualizarGrupo', arrayelemento)"
                                                class="btn btn-warning btn-sm">
                                                <i class="icon-pencil"></i>
                                            </button> &nbsp;
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
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginaMarca(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginaMarca(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginaMarca(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                        <nav v-else-if="tituloModal2 == 'Lineas'">
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginaLinea(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginaLinea(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginaLinea(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                        <nav v-else-if="tituloModal2 == 'Industrias'">
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginaIndustria(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActivedMar ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginaIndustria(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginaIndustria(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                        <nav v-else-if="tituloModal2 == 'Proveedors'">
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginaProveedor(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActivedMar ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginaProveedor(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginaProveedor(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                        <nav v-else-if="tituloModal2 == 'Grupos'">
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginaGrupo(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActivedMar ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginaGrupo(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginaGrupo(pagination.current_page + 1,buscar,criterio)">Sig</a>
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
        <!--######################################-aqui registro de industria,Marca,Linea#####################-->
        <div class="modal fade" tabindex="-1" :class="{ 'mostrar': modal3 }" role="dialog" aria-labelledby="myModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal3"></h4>
                        <button type="button" class="close" @click="cerrarModal3()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div v-if="tituloModal2 !== 'Proveedors'" class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div v-if="tituloModal2 !== 'Grupos' && tituloModal2 !== 'Lineas'" class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombre" class="form-control1"
                                        :placeholder="placeholderInput()">
                                </div>
                            </div>
                            <div v-else-if="tituloModal2 == 'Grupos'" class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre Grupo</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombre_grupo" class="form-control"
                                        :placeholder="placeholderInput()">
                                </div>
                            </div>
                            <div v-if="tituloModal2 == 'Lineas'" class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre Linea</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombreLinea" class="form-control1"
                                        :placeholder="placeholderInput('nombre')">
                                </div>
                                <label class="col-md-3 form-control-label" for="text-input">Descripcion</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="descripcion" class="form-control1"
                                        :placeholder="placeholderInput('descripcion')">
                                </div>
                            </div>
                            <!-- prueba de habilitar  -->
                            <div v-if="tituloModal2 == 'Industrias'" class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Estado</label>
                                <div class="col-md-9">
                                    <input type="checkbox" v-model="estado" v-bind:value="1" />
                                    <span>{{ estado ? 'Habilitar' : 'Inhabilitado' }}</span>
                                </div>
                            </div>
                            <div v-if="tituloModal2 == 'Marcas'" class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Estado</label>
                                <div class="col-md-9">
                                    <input type="checkbox" v-model="condicion" v-bind:value="1" />
                                    <span>{{ condicion ? 'Habilitar' : 'Inhabilitado' }}</span>
                                </div>
                            </div>
                            <div v-if="tituloModal2 == 'Lineas'" class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Estado</label>
                                <div class="col-md-9">
                                    <input type="checkbox" v-model="condicion" v-bind:value="1" />
                                    <span>{{ condicion ? 'Habilitar' : 'Inhabilitado' }}</span>
                                </div>
                            </div>

                            <div v-show="errorIndustria" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjIndustria" :key="error" v-text="error"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--############################## registro de proveedor####################################3---->
                    <div v-else-if="tituloModal2 == 'Proveedors'" class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre (*)</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombre" class="form-control"
                                        placeholder="Nombre de la persona">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Tipo Documento</label>
                                <div class="col-md-9">
                                    <select v-model="tipo_documento" class="form-control">
                                        <option value="DNI">DNI</option>
                                        <option value="RUC">RUC</option>
                                        <option value="PASS">PASS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Número</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="num_documento" class="form-control"
                                        placeholder="Número de documento">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Dirección</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="direccion" class="form-control" placeholder="Dirección">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Teléfono</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="telefono" class="form-control" placeholder="Teléfono">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Email</label>
                                <div class="col-md-9">
                                    <input type="email" v-model="email" class="form-control" placeholder="Email">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Contacto</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="contacto" class="form-control"
                                        placeholder="Nombre del contacto">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Teléfono de contacto</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="telefono_contacto" class="form-control"
                                        placeholder="Teléfono del contacto">
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--######################################-hasta aqui###############################-->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal3()">Cerrar</button>
                        <!-- <button type="button" class="btn btn-secondary" @click="modal3=0">Cerrar</button> -->
                        <button type="button" v-if="tipoAccion2 == 3" class="btn btn-primary"
                            @click="registrarIndustria()">Guardar</button>
                        <button type="button" v-if="tipoAccion2 == 4" class="btn btn-primary"
                            @click="actualizarIndustria()">Actualizar</button>
                        <button type="button" v-if="tipoAccion2 == 5" class="btn btn-primary"
                            @click="registrarMarca()">Guardar</button>
                        <button type="button" v-if="tipoAccion2 == 6" class="btn btn-primary"
                            @click="actualizarMarca()">Actualizar</button>
                        <button type="button" v-if="tipoAccion2 == 7" class="btn btn-primary"
                            @click="registrarLinea()">Guardar</button>
                        <button type="button" v-if="tipoAccion2 == 8" class="btn btn-primary"
                            @click="actualizarLinea()">Actializar</button>
                        <button type="button" v-if="tipoAccion2 == 9" class="btn btn-primary"
                            @click="registrarProveedor()">Guardar</button>
                        <button type="button" v-if="tipoAccion2 == 10" class="btn btn-primary"
                            @click="actualizarProveedor()">Actializar</button>
                        <button type="button" v-if="tipoAccion2 == 11" class="btn btn-primary"
                            @click="registrarGrupo()">Guardar</button>
                        <button type="button" v-if="tipoAccion2 == 12" class="btn btn-primary"
                            @click="actualizarGrupo()">Actializar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
        <!--######################################hasta aqui registro de industria#####################-->

        <!--######################################-aqui registro de medida#####################-->
        <div class="modal fade" tabindex="-1" :class="{ 'mostrar': modal7 }" role="dialog" aria-labelledby="myModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal7"></h4>
                        <button type="button" class="close" @click="cerrarModal6()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Medida</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="descripcion_medida" class="form-control"
                                        :placeholder="placeholderInput()">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Descripción Corta</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="descripcion_corta" class="form-control"
                                        :placeholder="placeholderInput()">
                                </div>
                            </div>
                            <!-- prueba de habilitar  -->
                            <div v-if="tituloModal2 == 'Medidas'" class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Estado</label>
                                <div class="col-md-9">
                                    <input type="checkbox" v-model="estado" v-bind:value="1" />
                                    <span>{{ estado ? 'Habilitar' : 'Inhabilitado' }}</span>
                                </div>
                            </div>

                            <div v-show="errorMedida" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjMedida" :key="error" v-text="error"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal7()">Cerrar</button>
                        <!-- <button type="button" class="btn btn-secondary" @click="modal3=0">Cerrar</button> -->
                        <button type="button" v-if="tipoAccion6 == 6" class="btn btn-primary"
                            @click="registrarMedida()">Guardar</button>
                        <button type="button" v-if="tipoAccion6 == 7" class="btn btn-primary"
                            @click="actualizarMedida()">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--######################################hasta aqui registro de medida#####################-->
    </main>
</template>

<script>
import VueBarcode from 'vue-barcode';
export default {
    data() {
        return {

            criterioA: 'nombre',
            buscarA: '',
            tituloModal2: '',
            marcaseleccionada: [],
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
            codigo_producto: 0,
            idmedida: 0,
            nombreLinea:'',
            nombre_categoria: '',
            nombre_proveedor: '',
            //id:'',//aumente 7 julio
            codigo: '',
            nombre: '',
            nombre_producto : '',
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
            marcaseleccionadaVacio: false,
            industriaseleccionadaVacio: false,
            proveedorseleccionadaVacio: false,
            gruposeleccionadaVacio: false,
            medidaseleccionadaVacio: false,
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
            precio_uno:0,
            precio_dos:0,
            precio_tres:0,
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
            fotoMuestra: 'img/articulo/defecto.jpg',
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
        }
    },
    components: {
        'barcode': VueBarcode
    },
    computed: {
        isActived: function () {
            return this.pagination.current_page;
        },
        isActivedM: function () {
            return this.pagination.current_page;
        },
        isActivedMar:function () {
            return this.pagination.current_page;
        },
        //Calcula los elementos de la paginación
        // pagesNumber: function () {
        //     if (!this.pagination.to) {
        //         return [];
        //     }

        //     var from = this.pagination.current_page - this.offset;
        //     if (from < 1) {
        //         from = 1;
        //     }

        //     var to = from + (this.offset * 2);
        //     if (to >= this.pagination.last_page) {
        //         to = this.pagination.last_page;
        //     }

        //     var pagesArray = [];
        //     while (from <= to) {
        //         pagesArray.push(from);
        //         from++;
        //     }
        //     return pagesArray;

        // },
        pagesNumber: function () {
            return this.calculatePages(this.pagination, this.offset.pagination);
        },
        pagesNumberMedida: function () {
            return this.calculatePages(this.paginationMedida, this.offset.paginationMedida);
        },
        imagen() {
            //console.log(this.fotoMuestra);
            return this.fotoMuestra;
        },
        listaArticulosConImagenSrc() {
            return this.arrayArticulo.map(articulo => ({
                ...articulo,
                imagenSrc: articulo.fotografia
                    ? `img/menu/${articulo.fotografia}?id=${articulo.id}_${this.uniqueId()}`
                    : 'img/articulo/defecto.jpg'
            }));
        }
    },
    methods: {
        uniqueId() {
            return new Date().getTime();
        },
        calculatePages: function(paginationObject, offset) {
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
        createPaginationObject(){
            return{
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0,
            }
        },
        //-------------calcualr precio--------------
        calcularPrecioCostoUnid() {
            if (this.unidad_envase && this.precio_costo_paq) {
                this.precio_costo_unid = this.precio_costo_paq / this.unidad_envase;
                this.precio_costo_unidVacio = false;
            }
        },
        calcularPrecioCostoPaq() {
            if (this.unidad_envase && this.precio_costo_unid) {
                this.precio_costo_paq = this.precio_costo_unid * this.unidad_envase;
                this.precio_costo_paqVacio = false;
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
                this.marcaseleccionadaVacio = false;
                if (selected.condicion == 1) {
                this.marcaseleccionada = selected;
                } else if (selected.condicion == 0) {
                this.advertenciaInactiva('Marcas');
                }
            } else if (this.tituloModal2 == "Industrias") {
                this.industriaseleccionadaVacio = false;
                if (selected.estado == 1) {
                this.industriaseleccionada = selected;
                } else if (selected.estado == 0) {
                this.advertenciaInactiva('Industrias');
                }
            } else if (this.tituloModal2 == "Lineas") {
                this.lineaseleccionadaVacio = false;
                if (selected.condicion == 1) {
                this.lineaseleccionada = selected;
                } else if (selected.condicion == 0) {
                this.advertenciaInactiva('Lineas');
                }
            } else if (this.tituloModal2 == "Proveedors") {
                this.proveedorseleccionadaVacio = false;
                // this.proveedorseleccionada.id = selected.id;
                // this.proveedorseleccionada.nombre = selected.nombre;
                this.proveedorseleccionada = selected;
            } else if (this.tituloModal2 == "Grupos") {
                this.gruposeleccionada = selected;
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
                } else if (selected.estado == 0) {
                this.advertenciaInactiva('Medidas');
                }
            }
            this.arrayBuscador = [];
            this.modal6 = false;
        },


        listarIndustria(page, buscar, criterio) {
            let me = this;
            var url = '/industria?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayBuscador = respuesta.industrias.data;
                me.pagination = respuesta.pagination;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        listarLinea(page, buscar, criterio) {
            let me = this;
            var url = '/categoriamenu?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
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
            if (titulo == "Marcas") {

                this.listarMarca(1, '', 'nombre');
                this.modal2 = true;
                this.tituloModal2 = titulo;
                this.marcaseleccionadaVacio = false;

            } else if (titulo == "Industrias") {
                this.listarIndustria(1, '', 'nombre');
                this.modal2 = true;
                this.tituloModal2 = titulo;
                this.industriaseleccionadaVacio = false;

            }
            else if (titulo == "Lineas") {
                this.listarLinea(1, '', 'nombreLinea');
                this.modal2 = true;
                this.tituloModal2 = titulo;
                this.lineaseleccionadaVacio = false;

            } else if (titulo == "Proveedors") {
                this.listarproveedor(1, '', 'nombre');
                this.modal2 = true;
                this.tituloModal2 = titulo;
                this.proveedorseleccionadaVacio = false;

            } else if (titulo == "Grupos") {
                this.listargrupo(1, '', 'nombre_grupo');
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

        listarArticulo(page, buscar, criterio) {
            let me = this;
            var url = '/menu?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayArticulo = respuesta.articulos.data;
                me.pagination = respuesta.pagination;
                console.log('lista: ', me.arrayArticulo);
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
        //------listado proveedor, Registro Proveedor y editar-----------
        listarproveedor(page, buscar, criterio) {
            let me = this;
            console.log("ListanoProveedor");
            var url = '/proveedor?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                console.log(respuesta);
                me.arrayBuscador = respuesta.personas.data;
                me.pagination = respuesta.pagination;
                console.log("Listad0");
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        registrarProveedor() {
            // if (this.validarPersona()){
            //     return;
            // }

            let me = this;

            axios.post('/proveedor/registrar', {
                'nombre': this.nombre,
                'tipo_documento': this.tipo_documento,
                'num_documento': this.num_documento,
                'direccion': this.direccion,
                'telefono': this.telefono,
                'email': this.email,
                'contacto': this.contacto,
                'telefono_contacto': this.telefono_contacto,

            }).then(function (response) {
                me.cerrarModal3();
                me.listarproveedor(1, '', 'nombre');
            }).catch(function (error) {
                console.log(error);
            });
        },
        actualizarProveedor() {
            // if (this.validarPersona()){
            //         return;
            // }

            let me = this;

            axios.put('/proveedor/actualizar', {
                'id': this.proveedor_id,
                'nombre': this.nombre,
                'tipo_documento': this.tipo_documento,
                'num_documento': this.num_documento,
                'direccion': this.direccion,
                'telefono': this.telefono,
                'email': this.email,
                'contacto': this.contacto,
                'telefono_contacto': this.telefono_contacto,
            }).then(function (response) {
                me.cerrarModal3();
                me.listarproveedor(1, '', 'nombre');
            }).catch(function (error) {
                console.log(error);
            });
        },
        //--------------------hasta aqui proveedor--------
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
        registrarGrupo() {
            // if (this.validarPersona()){
            //     return;
            // }                
            let me = this;

            axios.post('/grupos/registrar', {
                'nombre_grupo': this.nombre_grupo,
            }).then(function (response) {
                me.cerrarModal3();
                me.listargrupo(1, '', 'nombre_grupo');
            }).catch(function (error) {
                console.log(error);
            });
        },
        actualizarGrupo() {
            // if (this.validarPersona()){
            //         return;
            // }               
            let me = this;

            axios.put('/grupos/actualizar', {
                'id': this.grupo_id,
                'nombre_grupo': this.nombre_grupo,
            }).then(function (response) {
                me.cerrarModal3();
                me.listargrupo(1, '', 'nombre_grupo');
            }).catch(function (error) {
                console.log(error);
            });
        },
        //----listar precio 4_julio-------
        listarPrecio() {
            let me = this;
            var url = '/precios';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.precios = respuesta.precio.data;
                console.log('PRECIOS',me.precios);
                //me.precioCount = me.arrayBuscador.length;
            }).catch(function (error) {
                console.log(error);
            });
        },
        //---------hasta aqui----------------
        cargarPdf() {
            window.open('/articulo/listarPdf', '_blank');
        },

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
            me.listarArticulo(page, buscar, criterio);
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
        registrarArticulo() {

            this.nombreProductoVacio = !this.nombre_producto;
            this.codigoVacio = !this.codigo_producto;
            this.unidad_envaseVacio = !this.unidad_envase;
            this.nombre_genericoVacio = !this.nombre_generico;
            this.precio_costo_unidVacio = !this.precio_costo_unid;
            this.precio_costo_paqVacio = !this.precio_costo_paq;
            this.precio_ventaVacio = !this.precio_venta;
            this.costo_compraVacio = !this.costo_compra;
            this.stockVacio = !this.stock;
            this.descripcionVacio = !this.descripcion;
            this.fotografiaVacio = !this.fotografia;
            this.lineaseleccionadaVacio = !this.lineaseleccionada.nombre;
            this.marcaseleccionadaVacio = !this.marcaseleccionada.nombre;
            this.industriaseleccionadaVacio = !this.industriaseleccionada.nombre;
            this.proveedorseleccionadaVacio = !this.proveedorseleccionada.nombre;
            this.gruposeleccionadaVacio = !this.gruposeleccionada.nombre_grupo;
            this.medidaseleccionadaVacio = !this.medidaseleccionada.descripcion_medida;

            if (this.validarArticulo()) {
                return;
            }

            let me = this;
            let formData = new FormData();

            formData.append('idcategoria_menu', this.lineaseleccionada.id);
            formData.append('nombre', this.nombre_producto);
            formData.append('codigo', this.codigo_producto);      
            formData.append('precio_venta', this.precio_venta);
            formData.append('descripcion', this.descripcion);
            formData.append('fotografia', this.fotografia);
            
            console.log("FORMDATA",formData);
            axios.post('/menu/registrar', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }

            }).then(function (response) {
                me.cerrarModal();
                me.listarArticulo(1, '', 'nombre');
            }).catch(function (error) {
                console.log(error);
            });

        },
        //---actuslizar articulo
        actualizarArticulo() {
            this.nombreProductoVacio = !this.nombre_producto;
            this.codigoVacio = !this.codigo_producto;         
            this.precio_ventaVacio = !this.precio_venta;
            this.descripcionVacio = !this.descripcion;
            this.fotografiaVacio = !this.fotografia;
            if (this.validarArticulo()) {
                return;
            }
            let me = this;
            let formData = new FormData();
            formData.append('id', this.articulo_id);
            formData.append('idcategoria_menu', this.lineaseleccionada.id);
            formData.append('nombre', this.nombre_producto);   
            formData.append('codigo', this.codigo_producto);      
            formData.append('precio_venta', this.precio_venta);
            formData.append('descripcion', this.descripcion);
            formData.append('fotografia', this.fotografia);


            axios.post('/menu/actualizar', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(function (response) {
                //alert("Datos actualizados con éxito");
                //console.log("datos actuales",formData);
                me.cerrarModal();
                me.listarArticulo(1, '', 'nombre');
                console.log(formData);
            }).catch(function (error) {
                console.log(error);
            });
        },
        desactivarArticulo(id) {
            swal({
                title: 'Esta seguro de desactivar este artículo?',
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

                    axios.put('/menu/desactivar', {
                        'id': id
                    }).then(function (response) {
                        me.listarArticulo(1, '', 'nombre');
                        swal(
                            'Desactivado!',
                            'El registro ha sido desactivado con éxito.',
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
        activarArticulo(id) {
            swal({
                title: 'Esta seguro de activar este artículo?',
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

                    axios.put('/menu/activar', {
                        'id': id
                    }).then(function (response) {
                        me.listarArticulo(1, '', 'nombre');
                        swal(
                            'Activado!',
                            'El registro ha sido activado con éxito.',
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
                if (nombre=='Medidas'){
                    this.abrirModal6(nombre);
                }
                else{
                    this.abrirModal2(nombre);
                }

            });
        },
        //#################registro industria############
        registrarIndustria() {
            if (this.validarIndustria()) {
                return;
            }
            let me = this;

            axios.post('/industria/registrar', {
                'nombre': this.nombre,
                'estado': this.estado
            }).then(function (response) {
                me.cerrarModal3();
                //me.modal3=0;
                console.log(response)
                me.listarIndustria(1, '', 'nombre');
            }).catch(function (error) {
                console.log(error);
            });
        },
        //#################hasta aqui####################
        //#################Actualizar Industria####################
        actualizarIndustria() {
            if (this.validarIndustria()) {
                return;
            }

            let me = this;

            axios.put('/industria/actualizar', {
                'nombre': this.nombre,
                'estado': this.estado,
                'id': this.industria_id
            }).then(function (response) {
                me.cerrarModal3();
                me.listarIndustria(1, '', 'nombre');
            }).catch(function (error) {
                console.log(error);
            });
        },
        //#################hasta aqui####################
        //#################registro industria############
        registrarMarca() {
            if (this.validarIndustria()) {
                return;
            }
            let me = this;

            axios.post('/marca/registrar', {
                'nombre': this.nombre,
                'condicion': this.condicion
            }).then(function (response) {
                me.cerrarModal3();
                //me.modal3=0;
                console.log(response)
                me.listarMarca(1, '', 'nombre');
            }).catch(function (error) {
                console.log(error);
            });
        },
        //#################hasta aqui####################
        //#################-Actualizar Industria-####################
        actualizarMarca() {
            if (this.validarIndustria()) {
                return;
            }
            let me = this;

            axios.put('/marca/actualizar', {
                'nombre': this.nombre,
                'condicion': this.condicion,
                'id': this.marca_id
            }).then(function (response) {
                me.cerrarModal3();
                me.listarMarca(1, '', 'nombre');
            }).catch(function (error) {
                console.log(error);
            });
        },
        //#################hasta aqui####################
        //##############registrar linea##########

        registrarLinea() {
            //if (this.validarIndustria()) {
              //  return;
            //}
            let me = this;
            axios.post('/categoriamenu/registrar', {
                'nombre': this.nombreLinea,
                'condicion': this.condicion,
                'descripcion':this.descripcion,
            }).then(function (response) {
                me.cerrarModal3();
                //me.modal3=0;
                console.log(response)
                me.listarLinea(1, '', 'nombre');
            }).catch(function (error) {
                console.log(error);
            });
        },
        //#################hasta aqui####################
        actualizarLinea() {
            if (this.validarIndustria()) {
                return;
            }
            let me = this;

            axios.put('/categoria/actualizar', {
                'nombre': this.nombreLinea,
                'condicion': this.condicion,
                'descripcion': this.descripcion,
                'codigoProductoSin': this.codigoProductoSin,
                'id': this.linea_id
            }).then(function (response) {
                me.cerrarModal3();
                me.listarLinea(1, '', 'nombre');
            }).catch(function (error) {
                console.log(error);
            });
        },

        //#################registro medida############
        registrarMedida() {
            if (this.validarMedida()) {
                return;
            }
            let me = this;

            axios.post('/medida/registrar', {
                'descripcion_medida': this.descripcion_medida,
                'descripcion_corta': this.descripcion_corta,
                'estado': this.estado
            }).then(function (response) {
                me.cerrarModal7();
                console.log(response)
                me.listarMedida(1, '', 'descripcion_medida');
            }).catch(function (error) {
                console.log(error);
            });
        },
        //#################hasta aqui####################
        actualizarMedida() {
            if (this.validarMedida()) {
                return;
            }

            let me = this;

            axios.put('/medida/actualizar', {
                'descripcion_medida': this.descripcion_medida,
                'descripcion_corta': this.descripcion_corta,
                'estado': this.estado,
                'id': this.medida_id
            }).then(function (response) {
                me.cerrarModal7();
                me.listarMedida(1, '', 'descripcion_medida');
            }).catch(function (error) {
                console.log(error);
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

            //if (!this.unidad_envase) this.errorMostrarMsjArticulo.push("sin unidad envase");
            //if (!this.codigo) this.errorMostrarMsjArticulo.push("sin codigo");
            if (!this.nombre_producto) this.errorMostrarMsjArticulo.push("sin nombre producto");
            //if (!this.nombre_generico) this.errorMostrarMsjArticulo.push("sin nombre generico");
            if (!this.descripcion) this.errorMostrarMsjArticulo.push("sin descipcion");
            if (!this.precio_venta) this.errorMostrarMsjArticulo.push("sin precio venta");
            //if (!this.costo_compra) this.errorMostrarMsjArticulo.push("sin costo compra");
            if (!this.fotografia) this.errorMostrarMsjArticulo.push("sin fotografia");

            if (this.errorMostrarMsjArticulo.length) this.errorArticulo = 1;

            return this.errorArticulo;
        },
        cerrarModal() {
            this.modal = 0;
            this.tituloModal = '';

            this.nombreProductoVacio= false;
            this.descripcionVacio= false;
            this.fotografiaVacio= false;
            this.lineaseleccionadaVacio= false;

            //
            this.codigo = '';
            this.nombre_producto = '';
            this.precio_venta = 0;
            this.descripcion = '';
            this.codigo_producto = '';
            this.fotografia = ''; //Pasando el valor limpio de la referencia
            this.fotoMuestra = '';
            this.lineaseleccionada.nombre = '';
            this.errorArticulo = 0;

        },
        abrirModal(modelo, accion, data = []) {
            switch (modelo) {
                case "articulo":
                    {
                        switch (accion) {

                            case 'registrar':
                                {
                                    this.modal = 1;
                                    this.tituloModal = 'Registrar Artículo';
                                    this.idcategoria = 0;
                                    this.nombre_categoria = '';
                                    this.nombre_proveedor = '';
                                    this.codigo = '';
                                    this.nombre_producto = '';
                                    this.nombre_generico = '';
                                    this.unidad_envase = 0;
                                    this.precio_costo_unid = 0;
                                    this.precio_costo_paq = 0;
                                    this.precio_venta = 0;
                                    this.stock = 5;
                                    this.descripcion = '';
                                    this.tipoAccion = 1;
                                    this.fotografia = '';

                                    this.idmedida = 0; // new
                                    break;
                                }
                            case 'actualizar':
                                {
                                    console.log("datass", data);
                                    this.modal = 1;
                                    this.tituloModal = 'Actualizar Artículo';
                                    this.tipoAccion = 2;
                                    this.articulo_id = data['id'];
                                    this.nombre_producto = data['nombre'];
                                    this.precio_venta = data['precio_venta'];
                                    this.descripcion = data['descripcion'];
                                    this.fotografia = data['fotografia'];
                                    this.fotoMuestra = data['fotografia'] ? 'img/menu/' + data['fotografia'] : 'img/articulo/defecto.jpg';
                                    //this.lineaseleccionada = {nombre: data['nombre_categoria']};
                                    this.lineaseleccionada = { nombre: data['nombre_categoria'], id: data['idcategoria_menu'] };

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
            console.log("PRECIO_COSTO",this.precio_costo_unid);
            console.log("PRECIO_PORCENTAGE",precio.porcentage);
            if (isNaN(this.precio_costo_unid) || isNaN(parseFloat(precio.porcentage))) {
                console.log('El valor de precio_costo_unid o porcentaje no es válido');
                return;
            }
            const margen_ganancia = parseFloat(this.precio_costo_unid) * (parseFloat(precio.porcentage) / 100);
            const precio_publico = parseFloat(this.precio_costo_unid) + margen_ganancia;

            if (index === 0) {
                this.precio_uno = precio_publico.toFixed(2);
            } else if (index === 1) {
                this.precio_dos = precio_publico.toFixed(2);
            } else if (index === 2) {
                this.precio_tres = precio_publico.toFixed(2);
            }else if (index === 3) {
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
        //################Validar registros de industrial########
        validarIndustria() {
            this.errorIndustria = 0;
            this.errorMostrarMsjIndustria = [];

            if (this.tituloModal2 === 'Industrias') {
                if (!this.nombre) this.errorMostrarMsjIndustria.push("El nombre de Industria no puede estar vacío.");
            } else if (this.tituloModal2 === 'Marcas') {
                if (!this.nombre) this.errorMostrarMsjIndustria.push("El nombre de Marca no puede estar vacío.");
            } else if (this.tituloModal2 === 'Lineas') {
                if (!this.nombreLinea) this.errorMostrarMsjIndustria.push("El nombre de Linea no puede estar vacío.");
                if (!this.descripcion) this.errorMostrarMsjIndustria.push("La descripcion de Linea no puede estar vacío.");
                if (!this.codigoProductoSin) this.errorMostrarMsjIndustria.push("El codigo de Linea no puede estar vacío.");
            }

            //if (!this.nombre) this.errorMostrarMsjIndustria.push("El nombre de Industria no puede estar vacío.");
            if (this.errorMostrarMsjIndustria.length) this.errorIndustria = 1;

            return this.errorIndustria;
        },
        //################Validar registros de medida########
        validarMedida() {
            this.errorMedida = 0;
            this.errorMostrarMsjMedida = [];

            if (!this.descripcion_medida) this.errorMostrarMsjMedida.push("El nombre de la Medida no puede estar vacío.");
            if (this.errorMostrarMsjMedida.length) this.errorMedida = 1;

            return this.errorMedida;
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
            var url = '/configuracion/editar';

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.mostrarSaldosStock = respuesta.configuracionTrabajo.mostrarSaldosStock;
                me.mostrarCostos = respuesta.configuracionTrabajo.mostrarCostos;
                me.mostrarProveedores = respuesta.configuracionTrabajo.mostrarProveedores;
                console.log("MostrarCostos: " + me.mostrarCostos);
                console.log("ProveedorEstado: " + me.mostrarProveedores);
                console.log("MostrarSaldosStock: " + me.mostrarSaldosStock);

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
        this.listarArticulo(1, this.buscar, this.criterio);
        this.listarPrecio();//aumenTe 6julio
    }
}
</script>
<style>    .modal-content {
        width: 100% !important;
        position: absolute !important;
    }

    .mostrar {
        overflow-y: scroll;

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

    .sticky-column {
        position: sticky;
        left: 0;
        z-index: 1;
        background-color: white;
    }
    .border-red {
        border-color: red !important;
    }
</style>