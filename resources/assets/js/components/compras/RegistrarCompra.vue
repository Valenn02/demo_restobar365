<template class="">
    <div class="card card-body p-2" style="padding:5px">
        <div class="card-header"> Registrar compra</div>

        <div class="form-group row ">
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
<span class="text-danger small" v-show="idproveedor == 0">(!) Seleccione Proveedor</span>
</div>
</div>

<div class="col-md-1">
<label for="" class="font-weight-bold">Impuesto <span class="text-danger">*</span></label>
<div>
  <input type="text" class="form-control" v-model="impuesto" ref="impuestoRef" v-if="actualizarIva === 1">
  <input type="text" class="form-control" v-model="impuesto" ref="impuestoRef" readonly v-else>
</div>
<!-- <label class="small" for="impuesto">Shift + Q</label> -->
</div>

<div class="col-md-2">
<div class="form-group">
<label for="" class="font-weight-bold">Tipo comprobante <span class="text-danger">*</span></label>

<select class="form-control" v-model="tipo_comprobante">
<option value="0">Seleccione</option>
<option value="BOLETA">Boleta</option>
<option value="FACTURA">Factura</option>
<option value="TICKET">Ticket</option>
</select>
</div>
</div>

<div class="col-md-2">
<div class="form-group">
<label for="" class="font-weight-bold">Serie comprobante <span class="text-danger">*</span></label>

<input type="text" class="form-control" v-model="serie_comprobante" placeholder="000x" ref="serieComprobanteRef">
<label class="small" for="serieComprobante">Shift + W</label>
</div>
</div>

<div class="col-md-2">
<div class="form-group">
<label for="" class="font-weight-bold">N° Comprobante <span class="text-danger">*</span></label>

<input type="text" class="form-control" v-model="num_comprobante" placeholder="000xx" ref="numeroComprobanteRef">
<label class="small" for="numComprobante">Shift + E</label>
</div>
</div>

<div class="col-md-2">
<div class="form-group">
<label for="" class="font-weight-bold">Almacen <span class="text-danger">*</span></label>

<select class="form-control" v-model="AlmacenSeleccionado">
<option value="0" disabled selected>Seleccione</option>
<option v-for="opcion in arrayAlmacenes" :key="opcion.id" :value="opcion.id">{{ opcion.nombre_almacen }}</option>
</select>
</div>
</div>




<div class="col-md-12">
<div v-show="errorIngreso" class="form-group row div-error">
<div class="text-center text-error">
<div v-for="error in errorMostrarMsjIngreso" :key="error" v-text="error"></div>
</div>
</div>
</div>
</div>
        <div class="card">

<div class="row no-gutters">
<div class="col-md-3">
                <div class="form-group">
                    <label for="" class="font-weight-bold">Seleccione producto<span class="text-danger">*</span></label>

                    <div class="form-inline">
                        <input v-if="idproveedor==0" disabled type="text" class="form-control" v-model="codigo" @keyup="buscarArticulo()"
                            placeholder="Escriba el codigo" ref="articuloRef">
                        <input  v-if="idproveedor!=0" type="text" class="form-control" v-model="codigo" @keyup="buscarArticulo()"
                            placeholder="Escriba el codigo" ref="articuloRef">
                        <button @click="abrirArticulos()" class="btn btn-primary" v-if="idproveedor==0" disabled>...</button>
                        <button @click="abrirArticulos()" class="btn btn-primary" v-else>...</button>
                        <label class="small light-gray-text" for="">Shift + R</label>

                        <!-- <input type="text" readonly class="form-control" v-model="articulo"> -->
                    </div>
                </div>
            </div>
<!-- Parte Izquierda con Texto -->
    <div class="col-md-7" v-if="arrayArticuloSeleccionado.id">
        <div class="card-body">
        <h5 class="card-title" >{{this.arrayArticuloSeleccionado.nombre}}</h5>
        <p class="card-text">
        {{this.arrayArticuloSeleccionado.descripcion}}
        <br>
        <b>Costo unitario</b> {{this.arrayArticuloSeleccionado.precio_costo_unid}}
        <br>
        <b>Costo paquete</b> {{this.arrayArticuloSeleccionado.unidad_envase*this.arrayArticuloSeleccionado.precio_costo_unid}}
        <br>
        <b>Unidades por envase</b> {{this.arrayArticuloSeleccionado.unidad_envase}}

        </p>
        </div>
    </div>

<!-- Parte Derecha con la Imagen -->
<div class="col-md-2" v-if="arrayArticuloSeleccionado.id">
<img :src="'img/articulo/' + this.arrayArticuloSeleccionado.fotografia + '?t=' + new Date().getTime()"
v-if="this.arrayArticuloSeleccionado.fotografia"      width="50" height="50"  ref="imagen" class="card-img">
<img v-else src="https://www.bicifan.uy/wp-content/uploads/2016/09/producto-sin-imagen.png"  alt="Imagen del Card" class="card-img">

</div>
</div>

<div class="row">
<div class="m-1 p-1"></div>



<div class="col-md-2">
<div class="form-group" v-if="arrayArticuloSeleccionado.id">
<label for="campo1">{{tipoUnidadSeleccionada}}:
<i class="fa fa-exchange small"  @click="cambiarTipoUnidad()"></i>


</label>



<input type="number" class="form-control" v-model="cantidad">
</div>
</div>
<div class="col-md-2" v-if="arrayArticuloSeleccionado.id">
<div class="form-group">
<label for="campo2">Fecha vencimiento</label>
<input type="date" class="form-control" v-model="fechavencimiento">
</div>
</div>
<div class="col-md-2" v-if="arrayArticuloSeleccionado.id">
<div class="form-group">
<label for="campo2">Precio total</label>
<input disabled type="number" class="form-control" v-model="resultadoMultiplicacion">
</div>
</div>

<div class="col-md-2" v-if="arrayArticuloSeleccionado.id">
                <div class="form-group">
                    <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar"><i
                            class="icon-plus"></i> Agregar</button>
                </div>
</div>
</div>
</div>
        <!-- <div class="form-group row ">
       
            <div class="container mt-4">

</div>

            <div class="col-md-2">
                <div class="form-group">
                    <label>Precio </label>
                    <input type="number" value="0" step="any" class="form-control" v-model="precio" ref="precioRef">
                    <label for="">Shift + T</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Cantidad </label>
                    <input type="number" value="0" class="form-control" v-model="cantidad" ref="cantidadRef">
                    <label for="">Shift + Y</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar"><i
                            class="icon-plus"></i></button>
                </div>
            </div>
        </div> -->
        <div class="form-group row ">
            <div class="table-responsive col-md-12">
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Acciones</th>
                            <th>Codigo</th>

                            <th>Producto</th>
                            <th>Costo unitario</th>

                            <th>Unidad por Paquete</th>
                            <th>Paquetes</th>

                            <th>Unidades</th>

                            <th>Fecha vencimiento</th>

                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody v-if="arrayDetallePedido || arrayDetalle">
                        <tr v-for="(detalle, index) in arrayDetalle" :key="detalle.id">
                            <td>
                                <button @click="eliminarDetalle(index)" type="button"
                                    class="btn btn-danger btn-sm">
                                    <i class="icon-close"></i>
                                </button>
                            </td>
                            <td v-text="detalle.codigo"></td>
                            <td  v-text="detalle.articulo"></td>

                            <td>
                                {{ detalle.precio }}
                            </td>
                            <td v-text="detalle.unidad_x_paquete"></td>
                            <td v-text="(detalle.cantidad / detalle.unidad_x_paquete).toFixed(2)"></td>

                            <td>
                                <input v-model="detalle.cantidad" type="number" value="2"
                                    class="form-control">
                            </td>
                            <td>
                                <input  type="date" 
                                    class="form-control" v-model="detalle.fecha_vencimiento">
                            </td>
                            <td>
                                {{ detalle.precio * detalle.cantidad }}
                            </td>
                        </tr>
                        <tr style="background-color: #CEECF5;">
                            <td colspan="8" align="right"><strong>Total Parcial:</strong></td>
                            <td>$ {{ totalParcial=(total - totalImpuesto).toFixed(2) }}</td>
                        </tr>
                        <tr style="background-color: #CEECF5;">
                            <td colspan="8" align="right"><strong>Total Impuesto:</strong></td>
                            <td>$ {{ totalImpuesto=((total * impuesto) / (1 + impuesto)).toFixed(2) }}</td>
                        </tr>
                        <tr style="background-color: #CEECF5;">
                            <td colspan="8" align="right"><strong>Total Neto:</strong></td>
                            <td>$ {{ total=calcularTotal }}</td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="9">
                                No hay articulos agregados
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-12">
                <button type="button" @click="cerrarFormulario()" class="btn btn-secondary">Cerrar</button>
                <button type="button" class="btn btn-primary" @click="registrarIngreso()">Registrar
                    Compra</button>
            </div>
        </div>

    </div>

</template>
  
  <script>
import vSelect from 'vue-select';

export default {
    props: {
        arrayArticuloSeleccionado: {
            type: Object, // Asegúrate de que el tipo sea Object si esperas un objeto
            required: false // Si es obligatorio, agrega esta línea
            },
        arrayPedidoSeleccionado: {
            type: Object, // Asegúrate de que el tipo sea Object si esperas un objeto
            required: false // Si es obligatorio, agrega esta línea
            },
        arrayDetallePedido:{
            type:Array,
            required:false
        }
    },
    created() {
        this.selectAlmacen();
        this.articuloSeleccionadoLocal = { ...this.arrayArticuloSeleccionado };
        if(this.arrayDetallePedido){
            
            this.arrayDetalle = [...this.arrayDetallePedido];

            this.AlmacenSeleccionado=this.arrayPedidoSeleccionado.idalmacen
            this.proveedorSeleccionado=this.arrayPedidoSeleccionado.nombre_proveedor
            this.idproveedor=this.arrayPedidoSeleccionado.idproveedor


        }
  },
    data() {
        return {
            actualizarIva: null,
            proveedorSeleccionado:"",
            tipoUnidadSeleccionada: "Unidades",
            fechavencimiento:null,
            arrayArticuloSeleccionadoLocal:{},
            AlmacenSeleccionado:'1',
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
    watch: {
  arrayDetalle: {
    deep: true,
    handler: function(newVal) {
      if (Array.isArray(newVal)) {
        this.total = newVal.reduce((acc, detalle) => {
          return acc + (detalle.cantidad * detalle.precio);
        }, 0);
      } else {
        console.error('arrayDetalle no es un array:', newVal);
      }
    }
  }
}
,
    computed:{
        resultadoMultiplicacion() {
            if (this.arrayArticuloSeleccionado){
                if ( this.tipoUnidadSeleccionada=="Paquetes"){
                return this.cantidad * (this.arrayArticuloSeleccionado.precio_costo_unid*this.arrayArticuloSeleccionado.unidad_envase);

            }else{
                return this.cantidad * this.arrayArticuloSeleccionado.precio_costo_unid;
            }
            }

    },
    calcularTotal: function () {
            var resultado = 0.0;
            for (var i = 0; i < this.arrayDetalle.length; i++) {
                resultado = resultado + (this.arrayDetalle[i].precio * this.arrayDetalle[i].cantidad)
            }
            return resultado;
        }

    },
    mounted() {
        this.obtenerIva();
    },
    methods:{
        cambiarTipoUnidad(){
            if (this.tipoUnidadSeleccionada=="Paquetes"){
                this.tipoUnidadSeleccionada="Unidades";
            }else{
                this.tipoUnidadSeleccionada="Paquetes";

            }

        },
        cerrarFormulario() {
      this.$emit('cerrar');
    },
    editarEstado(){
        const datos={
            pedido:this.arrayPedidoSeleccionado,
            detalles:this.arrayDetallePedido
        }
        this.$emit('editarEstadoPedido',datos);

    },
    listarIngreso(page, buscar, criterio){
        const datos={
            page:page, buscar:buscar, criterio:criterio
        }
        this.$emit('listarIngreso', datos);

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
        selectAlmacen() {
            let me = this;
            var url = '/almacen/selectAlmacen';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayAlmacenes = respuesta.almacenes;

            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getDatosProveedor(val1) {
            let me = this;
            me.loading = true;
            if(!val1.id && this.arrayPedidoSeleccionado ){
                this.idproveedor=this.arrayPedidoSeleccionado.idproveedor
            }else{
                me.idproveedor = val1.id;

            }
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
        obtenerIva() {
            let me = this;
            axios.get('/configuracion/iva')
                .then(response => {
                    me.actualizarIva = response.data.actualizarIva;
                    // me.impuesto = response.data.impuesto;
                    console.log('Actualizar Iva:', me.actualizarIva);
                })
                .catch(error => {
                    console.error('Error al obtener iva:', error);
                });
                console.log('pruebaaa',me.actualizarIva);
        },

    abrirArticulos(){
        const datos={idproveedor:this.idproveedor}
      this.$emit('listarArticuloProveedor',datos);

      this.$emit('abrirModalArticulos');
        
    },
    registrarIngreso() {
            if (this.validarIngreso()) {
                return;
            }

            let me = this;

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
                    me.cerrarFormulario();
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
        guardarInventarios() {
            this.editarEstado();

                axios.post('/inventarios/registrar', { inventarios: this.arrayDetalle })
                .then(response => {
                    console.log(response.data);
                })
                .catch(error => {
                    console.error(error);
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
            agregarDetalle() {
            let me = this;

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

                    me.arrayArticuloSeleccionadoLocal={};
                    me.codigo = '';
                    me.idarticulo = 0;
                    me.articulo = '';
                    me.cantidad = 1;
                    me.precio = 0;
                    me.fechavencimiento=null;
                }

            }

        },
    }
  };
  </script>
  