<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb bg-light rounded px-3 shadow-sm">
            <li class="breadcrumb-item"><a class="text-primary text-decoration-none" href="/">Escritorio</a></li>
            <li class="breadcrumb-item active text-primary" aria-current="page">Compras</li>
            <li class="breadcrumb-item active text-primary" aria-current="page">Ingresos</li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card" v-if="listado!=0">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Ingresos
                    <button type="button" @click="mostrarDetalle()" v-if="listado!=0" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
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
                                    <input type="text" v-model="buscar" @keyup="listarIngreso(1, buscar, criterio)"
                                        class="form-control" placeholder="Texto a buscar">
                                    <!--button type="submit" @click="listarIngreso(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button-->
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Acciones</th>
                                        <th>Usuario</th>
                                        <th>Proveedor</th>
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
                                    <tr v-for="ingreso in arrayIngreso" :key="ingreso.id">
                                        <td>
                                            <button type="button" @click="verIngreso(ingreso.id)"
                                                class="btn btn-success btn-sm">
                                                <i class="icon-eye"></i>
                                            </button> &nbsp;
                                            <template v-if="ingreso.estado == 'Registrado'">
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    @click="desactivarIngreso(ingreso.id)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template>
                                        </td>
                                        <td v-text="ingreso.usuario"></td>
                                        <td v-text="ingreso.nombre"></td>
                                        <td v-text="ingreso.tipo_comprobante"></td>
                                        <td v-text="ingreso.serie_comprobante"></td>
                                        <td v-text="ingreso.num_comprobante"></td>
                                        <td v-text="ingreso.fecha_hora"></td>
                                        <td v-text="ingreso.total"></td>
                                        <td v-text="ingreso.impuesto"></td>
                                        <td v-text="ingreso.estado"></td>
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
                <template v-else-if="listado == 2">
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
                </template>
            </div>
        </div>
        <div v-if="showModalArticulos">
            <modalagregarproductos @cerrar="cerrarModal" @agregarArticulo="agregarArticuloSeleccionado" :idproveedor="idproveedor"></modalagregarproductos>
        </div>
        <div v-if="listado ==0" class="mx-3">

        <registrarcompra  @cerrar="ocultarDetalle" @listarArticuloProveedor="listarArticuloProveedor" @abrirModalArticulos="abrirModal" @listarIngreso="listarIngresosTabla" :arrayArticuloSeleccionado="arrayArticuloSeleccionado" ></registrarcompra>
</div>
    </main>
</template>
<script>
import vSelect from 'vue-select';
export default {
    data() {
        return {
            showModalArticulos:false,
            tipoUnidadSeleccionada: "Unidades",
            arrayArticuloSeleccionado:{},
            fechavencimiento:null,

            AlmacenSeleccionado:'',
            arrayAlmacenes:[],
            ingreso_id: 0,
            idproveedor: 0,
            proveedor: '',
            nombre: '',
            tipo_comprobante: 'BOLETA',
            serie_comprobante: '',
            num_comprobante: '',
            impuesto: 0.18,
            total: 0.0,
            totalImpuesto: 0.0,
            totalParcial: 0.0,
            arrayIngreso: [],
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
            articulo: '',
            precio: 0,
            cantidad: 1,
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
                resultado = resultado + (this.arrayDetalle[i].precio * this.arrayDetalle[i].cantidad)
            }
            return resultado;
        }
    },
    methods: {

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

        atajoButton: function (event) {
            console.log(event.keyCode);
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
        },
        listarIngresosTabla(dato){
            const page=dato.page;
            const buscar=dato.buscar;
            const criterio=dato.criterio;
            this.listarIngreso(page,buscar,criterio);
        },
        listarIngreso(page, buscar, criterio) {

            let me = this;
            var url = '/ingreso?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayIngreso = respuesta.ingresos.data;
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
                //console.log(response);
                let respuesta = response.data;
                q: search
                me.arrayProveedor = respuesta.proveedores;
                loading(false)
            })
                .catch(function (error) {
                    console.log(error);
                });
        },


        buscarArticulo() {
            console.log("BNuscando");
            let me = this;
            var url = '/articulo/buscarArticulo?filtro=' + me.codigo;

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayArticulo = respuesta.articulos;
                if (me.arrayArticulo.length > 0) {
                    me.arrayArticuloSeleccionado=me.arrayArticulo[0];
                    // me.articulo = me.arrayArticulo[0]['nombre'];
                    // me.idarticulo = me.arrayArticulo[0]['id'];
                    console.log("==========");

                    console.log(me.arrayArticulo);
                    console.log("==========");

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
            //Actualiza la página actual
            me.pagination.current_page = page;
            //Envia la petición para visualizar la data de esa página
            me.listarIngreso(page, buscar, criterio);
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
            console.log("Almacen seleccionadop");
            console.log(me.AlmacenSeleccionado);
            console.log("Almacen seleccionadop");

            if (me.arrayArticuloSeleccionado.length==0 || me.cantidad == 0 ||me.fechavencimiento==null ||me.AlmacenSeleccionado==0) {
                console.log("Seleccione un producto o la fecha o verifique la cantidads o almacen");
            } else {
                if (me.encuentra(me.arrayArticuloSeleccionado.id)) {
                    swal({
                        type: 'error',
                        title: 'Error...',
                        text: 'Este Artículo ya se encuentra agregado!',
                    })
                 } 
                else {
                    if (me.tipoUnidadSeleccionada=="Paquetes"){

                        me.arrayDetalle.push({

                        idarticulo: me.arrayArticuloSeleccionado.id,
                        idalmacen:me.AlmacenSeleccionado,
                        codigo:me.arrayArticuloSeleccionado.codigo,
                        articulo: me.arrayArticuloSeleccionado.nombre,
                        precio: me.arrayArticuloSeleccionado.precio_costo_unid,
                        unidad_x_paquete:me.arrayArticuloSeleccionado.unidad_envase,
                        

                        fecha_vencimiento:me.fechavencimiento,
                        cantidad: me.cantidad*me.arrayArticuloSeleccionado.unidad_envase,
                    });
                    }else{
                        
                    
                    me.arrayDetalle.push({
                        idarticulo: me.arrayArticuloSeleccionado.id,
                        idalmacen:me.AlmacenSeleccionado,

                        codigo:me.arrayArticuloSeleccionado.codigo,
                        articulo: me.arrayArticuloSeleccionado.nombre,
                        precio: me.arrayArticuloSeleccionado.precio_costo_unid,
                        unidad_x_paquete:me.arrayArticuloSeleccionado.unidad_envase,

                        fecha_vencimiento:me.fechavencimiento,
                        cantidad: me.cantidad,
                    });
                }
                    me.arrayArticuloSeleccionado={};
                    me.codigo = '';
                    me.idarticulo = 0;
                    me.articulo = '';
                    me.cantidad = 1;
                    me.precio = 0;
                    me.fechavencimiento=null;
                }

            }

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
                console.log("==========");
                console.log(data);
                console.log("==========");
                me.arrayArticuloSeleccionado={
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
        listarArticulo(buscar, criterio) {
            console.log("Listando");
            let me = this;
            var url = '/articulo/listarArticulo?buscar=' + buscar + '&criterio=' + criterio + '&idProveedor=' + this.idproveedor;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayArticulo = respuesta.articulos.data;
                console.log(me.arrayArticulo);
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        guardarInventarios() {
                axios.post('/inventarios/registrar', { inventarios: this.arrayDetalle })
                .then(response => {
                    console.log(response.data);
                })
                .catch(error => {
                    console.error(error);
                });
        },
        registrarIngreso() {
            if (this.validarIngreso()) {
                return;
            }

            let me = this;
            for (let i = 0; i < me.arrayDetalle.length; i++) {
                console.log(me.arrayDetalle[i].idalmacen);
                console.log(me.arrayDetalle[i].idarticulo);
                console.log(me.arrayDetalle[i].fecha_vencimiento);
                console.log(me.arrayDetalle[i].cantidad);
                console.log(me.arrayDetalle[i]);

            }
 
            axios.post('/ingreso/registrar', {
                'idproveedor': this.idproveedor,
                'tipo_comprobante': this.tipo_comprobante,
                'serie_comprobante': this.serie_comprobante,
                'num_comprobante': this.num_comprobante,
                'impuesto': this.impuesto,
                'total': this.total,
                'data': this.arrayDetalle

            }).then(function (response) {
                if(response.data.id > 0)
                {
                    me.guardarInventarios();
                    me.listado = 1;
                    me.listarIngreso(1, '', 'num_comprobante');
                    me.idproveedor = 0;
                    me.tipo_comprobante = 'BOLETA';
                    me.serie_comprobante = '';
                    me.num_comprobante = '';
                    me.impuesto = 0.18;
                    me.total = 0.0;
                    me.idarticulo = 0;
                    me.articulo = '';
                    me.cantidad = 1;
                    me.precio = 0;
                    me.arrayDetalle = [];
                }else{
                    swal(
                        'Aviso',
                        response.data.caja_validado,
                        'warning'
                    )
                    return; 
                }

            }).catch(function (error) {
                console.log(error);
            });
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
        mostrarDetalle() {
            let me = this;
            me.selectAlmacen();

            me.listado = 0;

            me.idproveedor = 0;
            me.tipo_comprobante = 'BOLETA';
            me.serie_comprobante = '';
            me.num_comprobante = '';
            me.impuesto = 0.18;
            me.total = 0.0;
            me.idarticulo = 0;
            me.articulo = '';
            me.cantidad = 1;
            me.precio = 0;
            me.arrayDetalle = [];
        },
        ocultarDetalle() {
            this.listado = 1;
        },
        verIngreso(id) {
            let me = this;
            me.listado = 2;

            //Obtener datos del ingreso
            var arrayIngresoT = [];
            var url = '/ingreso/obtenerCabecera?id=' + id;

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                arrayIngresoT = respuesta.ingreso;

                me.proveedor = arrayIngresoT[0]['nombre'];
                me.tipo_comprobante = arrayIngresoT[0]['tipo_comprobante'];
                me.serie_comprobante = arrayIngresoT[0]['serie_comprobante'];
                me.num_comprobante = arrayIngresoT[0]['num_comprobante'];
                me.impuesto = arrayIngresoT[0]['impuesto'];
                me.total = arrayIngresoT[0]['total'];
            })
                .catch(function (error) {
                    console.log(error);
                });

            //obtener datos de los detalles
            var url = '/ingreso/obtenerDetalles?id=' + id;

            axios.get(url).then(function (response) {
                console.log(response);
                var respuesta = response.data;
                me.arrayDetalle = respuesta.detalles;

            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        cerrarModal() {
            this.modal = 0;
            this.showModalArticulos=false;
            this.tituloModal = '';
        },
        abrirModal() {
            this.listarArticulo("","");
            this.arrayArticulo = [];
            this.modal = 1;
            this.showModalArticulos=true;
            this.tituloModal = 'Seleccione los articulos que desee';

        },
        listarArticuloProveedor(dato){
            this.idproveedor=dato.idproveedor;
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

    },
    mounted() {
        this.listarIngreso(1, this.buscar, this.criterio);
        window.addEventListener('keydown', this.atajoButton);
    }
}
</script>
<style>    


.card-img {
  width: 120px;
  height: auto;
  border-top-right-radius: 8px;
  border-bottom-right-radius: 8px;
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

    @media (min-width: 600px) {
        .btnagregar {
            margin-top: 2rem;
        }
    }</style>
