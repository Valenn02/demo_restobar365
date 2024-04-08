<template>
    <div class="card-body border">
        <div class="table-responsive">
            <div>
                <button type="button" @click="cargarExcel()" class="btn btn-info">
                    <i class="icon-doc"></i>&nbsp;Reporte
                </button>
            </div>
            <table class="table table-bordered table-striped table-sm">
                <thead>
                    <tr class="table-info"> <!-- Agrega la clase "table-info" para dar estilo al título -->
                        <th colspan="9">Productos con bajo stock (necesitan reposicion)</th>
                        <!-- Colspan="7" para que el título abarque todas las columnas -->
                    </tr>
                    <tr>
                        <th>Opciones</th>
                        <th>Codigo</th>
                        <th>Producto</th>
                        <th>Ubicacion</th>
                        <th>Unidad X Paq.</th>
                        <th>Saldo Stock</th>
                        <th>Stock minimo</th>
                        <th>Almacen</th>
                        <th>Proveedor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="inventario in arrayInventario" :key="inventario.id">
                        <td>
                            <button type="button" @click="abrirModal('almacenes', 'actualizar', inventario)"
                                class="btn btn-warning btn-sm">
                                <i class="icon-pencil"></i>
                            </button> &nbsp;
                        </td>
                        <td v-text="inventario.codigo"></td>
                        <td v-text="inventario.nombre_producto"></td>
                        <td v-text="inventario.ubicacion"></td>
                        <td v-text="inventario.unidad_envase"></td>
                        <td v-text="inventario.saldo_stock" style="background-color: rgb(219, 75, 75); color: white;"></td>
                        <td v-text="inventario.stock" style="background-color: #71AB57;"></td>
                        <td v-text="inventario.nombre_almacen"></td>
                        <td v-text="inventario.nombre_proveedor"></td>
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
                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page"></a>
                </li>
                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                    <a class="page-link" href="#"
                        @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                </li>
            </ul>
        </nav>
    </div>
</template>
<script>
export default {
    data() {
        return {
            arrayInventario: [],
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
            pagination: {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0,
            },
            offset: 3,
            criterio: 'nombre_almacen',
            buscar: ''
        }
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

        }
    },
    methods: {
        cambiarPagina(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            //Envia la petición para visualizar la data de esa página
            me.listarInventario(page, buscar, criterio);
        },
        listarInventario(page, buscar, criterio) {
            let me = this;
            var url = '/inventarios/productosbajostock?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                //console.log("lista almacen:",respuesta);
                me.arrayInventario = respuesta.inventarios.data;
                me.pagination = respuesta.pagination;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        cargarExcel() {
            window.open('/inventarios/listarReporteBajoStockExcel', '_blank');
        },
        // registrarAlmacen(){
        //     if (this.validarAlmacen()){
        //         return;
        //     }
        //     let me = this;

        //     axios.post('/almacen/registrar',{
        //         'nombre_almacen': this.nombre_almacen,
        //         'ubicacion': this.ubicacion,   
        //         'encargado': this.encargado,  
        //         'telefono': this.telefono,
        //         'lugar': this.lugar,   
        //         'observacion': this.observacion,  
        //     }).then(function (response) {
        //         me.cerrarModal();
        //         //console.log(response)
        //         me.listarAlmacenes(1,'','nombre_almacen');
        //     }).catch(function (error) {
        //         console.log(error);
        //     });
        // },
        // actualizarAlmacen(){
        //     if (this.validarAlmacen()){
        //         return;
        //     }
        //     let me = this;

        //     axios.put('/almacen/editar',{
        //         'id':this.almacen_id,
        //         'nombre_almacen': this.nombre_almacen,
        //         'ubicacion': this.ubicacion,   
        //         'encargado': this.encargado,  
        //         'telefono': this.telefono,
        //         'lugar': this.lugar,   
        //         'observacion': this.observacion,  
        //     }).then(function (response) {
        //         me.cerrarModal();
        //         //console.log(response)
        //         me.listarAlmacenes(1,'','nombre_almacen');
        //     }).catch(function (error) {
        //         console.log(error);
        //     });
        // },
        //validar almacen(){
        // validarAlmacen(){
        //     this.errorIndustria=0;
        //     this.errorMostrarMsjIndustria =[];

        //     if (!this.nombre_almacen) this.errorMostrarMsjIndustria.push("El nombre de Almacen no puede estar vacío.");
        //     if (this.errorMostrarMsjIndustria.length) this.errorIndustria = 1;

        //     return this.errorIndustria;
        // },
        // cerrarModal(){
        //     this.modal=0;
        //     this.tituloModal='';
        //     this.nombre_almacen='';
        //     this.ubicacion='';
        //     this.encargado='';
        //     this.telefono='';
        //     this.lugar='';
        //     this.observacion='';
        //     //this.descripcion='';
        // },
        abrirModal(modelo, accion, data = []) {
            switch (modelo) {
                case "inventario":
                    {
                        switch (accion) {
                            case 'registrar':
                                {
                                    // this.modal = 1;
                                    // this.tituloModal = 'Registrar Almacen';
                                    // this.nombre_almacen= '';
                                    // this.ubicacion = '';
                                    // this.encargado = '';
                                    // this.telefono = '';
                                    // this.lugar = '';
                                    // this.observacion = '';
                                    // this.tipoAccion = 1;
                                    break;
                                }
                            case 'actualizar':
                                {
                                    // console.log("Datos almacen:",data)
                                    // this.modal=1;
                                    // this.tituloModal='Actualizar Almacen';
                                    // this.tipoAccion=2;
                                    // this.almacen_id=data['id'];
                                    // this.nombre_almacen = data['nombre_almacen'];
                                    // this.ubicacion=data['ubicacion'];
                                    // this.encargado = data['encargado'];
                                    // this.telefono = data['telefono'];
                                    // this.lugar=data['lugar'];
                                    // this.observacion = data['observacion'];
                                    break;
                                }
                        }
                    }
            }
        }
    },
    mounted() {
        this.listarInventario(1, this.buscar, this.criterio);
    }
}
</script>
<style>    .modal-content {
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
</style>