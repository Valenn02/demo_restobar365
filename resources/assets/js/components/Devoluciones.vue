<template>
    <!-- Breadcrumb -->
    
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <label for="fechaInicio">Fecha Inicio:</label>
                <input type="date" id="fechaInicio" v-model="fechaInicio" @change="mandarFechas">
                <label for="fechaFin">Fecha Fin:</label>
                <input type="date" id="fechaFin" v-model="fechaFin" @change="mandarFechas">
                <!-- <button @click="fetchTraspasos">Filtrar</button> -->
                <div class="form-row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>VENDEDOR(*)</label>
                            <select class="form-control" v-model="RolSeleccionado" @change="getDatosRol">
                                <!-- <option value="0" disabled>Todos</option> -->
                                <option v-for="opcion in arrayRoles" :key="opcion.id" :value="opcion.id">{{ opcion.nombre }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>TIPO VENTA(*)</label>
                            <select class="form-control" v-model="tipoVenta">
                                <option v-for="tipov in arrayTipoVenta" :key="tipov" :value="tipov" v-text="tipov"></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>ALMACEN(*)</label>
                            <select class="form-control" v-model="AlmacenSeleccionado" @change="getDatosAlmacen">
                                <option v-for="opcion in arrayAlmacenes" :key="opcion.id" :value="opcion.id">{{ opcion.nombre_almacen }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!--##################---HASTA AQUI SELECT-#############-->
            </div>
            <!--###########-LISTADO-########-->
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control col-md-3" v-model="criterio">
                                <option value="nombre">Cliente</option>
                                <option value="almacen">Almacen</option>
                            </select>
                            <input type="text" v-model="buscar" @keyup.enter="listasventas" class="form-control" placeholder="Texto a buscar">
                            <button type="button" @click="listasventas" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <div style="text-align: center; font-size: 18px;">VENTAS REGISTRADAS</div>
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
                                            class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
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
            
            <div class="table-responsive">
                <div style="text-align: center; font-size: 18px;">DETALLE DE VENTA</div>
                <table class="table table-bordered table-striped table-sm">  
                    <thead>                    
                        <tr>
                              <th>Accion</th>
                            <th>Artículo</th>
                            <th>Unidad Paq.</th>
                            <th>Precio Unidad </th>
                            <th>Unidades</th>
                            <th>Cantidad Paquetes</th>
                            <th>Total S/Descueto</th>
                            <th>Descuento %</th>
                            <th>Total C/Descueto</th>
                
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                              <td>
                                  <button @click="" type="button" class="btn btn-success btn-sm">
                                      <i class="icon-check"></i>
                                  </button>
                              </td>
                            <td>{{ detalle.articulo }}</td>
                            <td>{{ detalle.unidad_envase }}</td>
                            <td>{{ detalle.precio }}</td>
                            <!-- <td>{{ detalle.cantidad % detalle.unidad_envase }}</td> -->
                          <td>
                              <span style="color:red;" v-show="detalle.cantidad > detalle.stock">Stock:
                                  {{ detalle.cantidad }}</span>
                              <input v-model="detalle.cantidad" type="number" class="form-control">
                          </td>
                            <td>{{ Math.floor(detalle.cantidad / detalle.unidad_envase)}}</td>
                            <td>{{ (detalle.precio * detalle.cantidad).toFixed(2)}}</td>
                            <td>{{ detalle.descuento }}</td>
                            <td>{{ ((detalle.precio * detalle.cantidad) - (detalle.precio * detalle.cantidad * detalle.descuento / 100)).toFixed(2) }}</td>
                            
                            <!-- <td>{{ detalle.subtotal }}</td> -->
                            
                        </tr>
                    </tbody>
                </table>
            </div>   
        </div> 
    </div>
  </template>
  <script>
  import vSelect from 'vue-select';
  export default {
    data() {
        return {
            fechaInicio:'',
            fechaFin:'',
            pagination: {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0,
            },
            offset: 3,
            arrayVenta: [],
            arrayDetalle: [],
            filtrar : '',
            vendedor : '',
            tipo_venta : '',
            idalmacen : 0,
            AlmacenSeleccionado : 0,
            arrayAlmacenes : [],
            arrayRoles : [],
            idroles :0,
            RolSeleccionado : 0,
            tipoVenta : 'Todos',
            arrayTipoVenta : ['Todos', 'Credito','Contado','Factura','Sin_Factura'],
            //--busacador
            criterio: '',
            buscar : '',
            activeTab: 0,
        }
    },
    computed:{
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
    },
    methods: {
        listarVenta(page, buscar, criterio) {
        let me = this;
        console.log('ROL EN LISTAR VENTA ',me.idroles)
        console.log('ALMACEN ',me.idalmacen)
        console.log('FECHA INICIO ',me.fechaInicio)
        console.log('FECHA FIN ',me.fechaFin)
        var url = '/ventaBuscar?page=' + page + '&idRoles=' + me.idroles + '&idAlmacen=' + me.idalmacen + '&fechaInicio=' + me.fechaInicio + '&fechaFin=' + me.fechaFin + '&buscar=' + buscar + '&criterio=' + criterio;
        axios.get(url).then(function (response) {
            var respuesta = response.data;
            me.arrayVenta = respuesta.ventas.data;
            console.log('array ventas', me.arrayVenta)
            me.pagination = respuesta.pagination;
        })
            .catch(function (error) {
                console.log(error);
            });
    },
    verVenta(id) {
        let me = this;
        me.listado = 2;
        //obtener datos de los detalles
        var url = '/venta/obtenerDetalles?id=' + id;
  
        axios.get(url).then(function (response) {
            //console.log(response);
            var respuesta = response.data;
            me.arrayDetalle = respuesta.detalles;
            console.log('arrayDetalle', me.arrayDetalle);
  
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
          me.listarVenta(page, buscar, criterio);
      },
        //----------------importar REPORTE DE VENTA A excel----
        inicializarFechas() {
            const fechaActual = new Date();
            this.fechaInicio = fechaActual.toISOString().substr(0, 10);
            
            fechaActual.setDate(fechaActual.getDate() + 1); // Agregar 1 día a la fecha actual
            
            this.fechaFin = fechaActual.toISOString().substr(0, 10);
            
            console.log('INICIO', this.fechaInicio, 'FINAL', this.fechaFin);
            
            this.listarVenta(1, this.buscar, this.criterio);
        },
  
        mandarFechas() {
            
            this.listarVenta(1, this.buscar, this.criterio);
        },
        //---------------------------
        //--garrar el id para que liste el inventario con ese almacen
        getDatosAlmacen() {
            let me = this;
            if (me.AlmacenSeleccionado !== '') { // Comprobar si hay un almacén seleccionado
                me.loading = true;
                me.idalmacen = Number(me.AlmacenSeleccionado); // Convertir a número antes de asignarlo
                console.log('IDalmacen: ' + me.idalmacen);
                this.listarVenta(1, this.buscar, this.criterio);
            }
        },
        selectAlmacen() {
            let me = this;
            var url = '/almacen/selectAlmacen';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                // Agregar "Todos" automáticamente al principio de la lista si no está presente
                if (!respuesta.almacenes.some(almacen => almacen.id === "0")) {
                    respuesta.almacenes.unshift({ id: "0", nombre_almacen: "Todos" });
                }
                me.arrayAlmacenes = respuesta.almacenes;
                console.log('ALAMCEN',me.arrayAlmacenes);
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        selectRoles() {
            let me = this;
            var url = '/roles/selectRoles';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                // Agregar "Todos" automáticamente al principio de la lista si no está presente
                if (!respuesta.roles.some(rol => rol.id === "0")) {
                    respuesta.roles.unshift({ id: "0", nombre: "Todos" });
                }
                me.arrayRoles = respuesta.roles;
                console.log('ROLES ARRAY',me.arrayRoles);
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        getDatosRol() {
            let me = this;
            if (me.RolSeleccionado !== '') { // Comprobar si hay un almacén seleccionado
                me.loading = true;
                me.idroles = Number(me.RolSeleccionado); // Convertir a número antes de asignarlo
                console.log('IDrol: ' + me.idroles);
                this.listarVenta(1, this.buscar, this.criterio);
            }
        },
        listasventas(){
            
        }
    },
    mounted() {
        this.inicializarFechas();
        this.listarVenta(1, this.buscar, this.criterio);
        this.selectAlmacen();
        this.selectRoles();
        //this.
    },
  
  }
  </script>