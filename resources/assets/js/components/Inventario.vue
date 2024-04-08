<template>
    <main class="main">
        <ol class="breadcrumb">
             <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label> ALMACEN DE TRABAJO </label>
                                <select class="form-control" v-model="AlmacenSeleccionado" @change="getDatosAlmacen">
                                    <option value="0" disabled>Seleccione</option>
                                    <option v-for="opcion in arrayAlmacenes" :key="opcion.id" :value="opcion.id">{{ opcion.nombre_almacen }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex flex-column">
                                <label class="mb-1"> MODO VISTA </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" v-model="tipoSeleccionado" value="item" @change="cambiarTipo">
                                    <label class="form-check-label ms-2">Por Item</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" v-model="tipoSeleccionado" value="lote" @change="cambiarTipo">
                                    <label class="form-check-label ms-2">Por Lote</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                <option value="nombre">Nombre Producto</option>
                                <!-- <option value="descripcion">Descripción</option> -->
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarInventario" class="form-control" placeholder="Texto a buscar">
                                <button type="button" @click="listarInventario" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <div v-if="tipoSeleccionado == 'item'" class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <!-- <th>Opciones</th> -->
                                    <th>Product</th>
                                    <th>Unidad X Paq.</th>                                   
                                    <th>Saldo_stock_total</th>
                                    <th>Almacen</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="inventario in arrayInventario" :key="inventario.id">
                                    <!-- <td>
                                        <button type="button" @click="abrirModal('almacenes','actualizar',inventario)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                    </td> -->
                                    <td v-text="inventario.nombre_producto"></td>
                                    <td v-text="inventario.unidad_envase"></td>
                                    <td v-text="inventario.saldo_stock_total"></td>
                                    <td v-text="inventario.nombre_almacen"></td>
                                </tr>                                
                            </tbody>
                        </table>
                    </div>
                    <!--#####################################-LIStADO LOTE-###############-->
                    <div v-if="tipoSeleccionado == 'lote'" class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Unid.X.Paq</th>
                                    <th>Costo Unidad</th>
                                    <th>Saldo Stock</th>
                                    <th>Fecha Ingreso</th>
                                    <th>Fecha Vencimiento</th>
                                    <th>Almacen</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="inventario in arrayInventario" :key="inventario.id">
                                    <td v-text="inventario.nombre_producto"></td>
                                    <td v-text="inventario.unidad_envase"></td>
                                    <td v-text="inventario.precio_costo_unid"></td>
                                    <td v-text="inventario.saldo_stock"></td>
                                    <td v-text="inventario.fecha_ingreso"></td>
                                    <td v-text="inventario.fecha_vencimiento"></td>
                                    <td v-text="inventario.nombre_almacen"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--######################################-hasta AQUI#################-->
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                    
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        
        <!--Fin del modal-->
    </main>
</template>
<script>
    export default {
        data (){
            return {
                arrayInventario: [],
                //---------------------
                arrayAlmacenes: [],
                AlmacenSeleccionado: 1,
                idalmacen: 0,
                tipoSeleccionado: 'item',
                //---------------------
                // almacen_id:0,
                // nombre_almacen: '',
                // ubicacion: '',
                // encargado: '',
                // telefono: '',
                // lugar: '',
                // observacion: '',

                // modal : 0,
                // tituloModal : '',
                // tipoAccion : 0,
                // errorMostrarMsjIndustria: [],
                // errorIndustria : 0,
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre',
                buscar : ''
            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
        },
        methods : {
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarInventario(page,buscar,criterio);
            },
            //---------------------------------------
            listarInventario (){
                let me=this;
                let url = '/inventarios/itemLote/' + me.tipoSeleccionado + '?&idAlmacen=' + me.almacenSeleccionado + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    console.log("ARRAy:",respuesta);
                    me.arrayInventario = respuesta.inventarios;
                    console.log('LLEGA:',me.arrayInventario);
                    //me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectAlmacen() {
                let me = this;
                var url = '/almacen/selectAlmacen';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAlmacenes = respuesta.almacenes;
                    console.log('ALMACEN:',me.arrayAlmacenes);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosAlmacen() {
                let me = this;
                if (me.AlmacenSeleccionado !== '') {
                    me.loading = true;
                    me.almacenSeleccionado = me.AlmacenSeleccionado; // Almacenar el valor seleccionado
                    me.idalmacen = Number(me.AlmacenSeleccionado);
                    console.log('IDalmacen: ' + me.idalmacen);
                    me.listarInventario();
                }
            },
            cambiarTipo() {
                this.getDatosAlmacen(); // Actualizar datos de almacén
                //this.listarInventario(); // Listar inventario basado en almacén seleccionado
            },
            //--------------------------------------
        },
        mounted() {
            //this.listarInventario(1,this.buscar,this.criterio);
            this.getDatosAlmacen();
            //this.listarInventario(1,this.buscar,this.criterio);
            this.listarInventario();
            this.selectAlmacen();
        },
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>