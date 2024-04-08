<template>
            <div class="modal fade" tabindex="-1" :class="{ 'mostrar': true }" role="dialog" aria-labelledby="myModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        PEDIDO

                    </div>

                    <div class="modal-body">
                        <div class="row mx-2 flex justify-content-between">
                            <p class="h4">Detalles del pedido:</p>
                            <button type="button" class="btn btn-outline-success" @click="abrirCompra()">
                                    <i class="fa fa-shopping-cart"></i>
                                    Realizar compra</button>
                        </div>

                          <div>
                            <dl class="row">
                                <dt class="col-sm-3">Proveedor:</dt>
                                <dd class="col-sm-9">
                                    {{ arrayPedidoSeleccionado.nombre_proveedor }}
                                </dd>


                                <dt class="col-sm-3">Estado:</dt>
                                <dd class="col-sm-9">
                                    {{ new Date(arrayPedidoSeleccionado.fecha_entrega) < new Date()?"Caducada": arrayPedidoSeleccionado.estado }}
                                    
                                </dd>

                                <dt class="col-sm-3">Fecha pedido:</dt>
                                <dd class="col-sm-9">
                                    {{ new Intl.DateTimeFormat('es-ES').format(new Date(arrayPedidoSeleccionado.fecha_pedido)) }}
                                    {{ new Date(arrayPedidoSeleccionado.fecha_pedido).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) }}

                                </dd>

                                <dt class="col-sm-3 text-truncate">Fecha de entrega:</dt>
                                <dd class="col-sm-9">
                                    {{ new Intl.DateTimeFormat('es-ES').format(new Date(arrayPedidoSeleccionado.fecha_entrega)) }}
                                </dd>

                                <dt class="col-sm-3">Almacen:</dt>
                                <dd class="col-sm-3">
                                    {{ arrayPedidoSeleccionado.nombre_almacen }}
                                </dd>
                                <dd class="col-sm-6">
                                    <dl class="row">
                                    <dt class="col-sm-4">Forma de pago:</dt>
                                    <dd class="col-sm-8">
                                        {{ arrayPedidoSeleccionado.forma_pago }}
                                    </dd>
                                    </dl>
                                </dd>
                            </dl>
                            <table class="table table-responsive table-bordered table-striped table-sm">  
                                <thead> 
                                    <tr>
                                        <td colspan="7">
                                            <h6 class="text-center font-weight-bold ">Detalle del pedido</h6>
                                        </td>
                                    </tr>                   
                                    <tr>
                                        <th>CODIGO</th>
                                        <th>PRODUCTO</th>
                                        <th>TIPO DE UNIDAD</th>
                                        <th>CANTIDAD</th>
                                        <th>UNIDAD</th>
                                        <th>IMPORTE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="pedprovdet in arrayPedidoProvDet" :key="pedprovdet.id">
                                        <td v-text="pedprovdet.codigo"></td>
                                        <td v-text="pedprovdet.articulo"></td>
                                        <td >Unidades</td>
                                        <td v-text="pedprovdet.cantidad"></td>
                                        <td v-text="pedprovdet.precio"></td>
                                        <td >{{ pedprovdet.cantidad*pedprovdet.precio }}</td>
                                    </tr>
                                    <tr>
                                            <td colspan="5">
                                                <h6 class="text-right font-weight-bold ">Total</h6>
                                            </td>
                                            <td >
                                                <h6 class="font-weight-bold ">{{ arrayPedidoSeleccionado.total }}</h6>
                                            </td>
                                        </tr>
                                </tbody>
                            </table>
                        </div> 
                        <!-- <button type="button" class="btn btn-info" @click="cerrarModal()">Exportar</button> -->

                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>

                    </div>

                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
</template>

<script>
export default {
  props: {
    arrayPedidoProvDet: {
      type: Array, // Indica que esperas un array
      required: true // Opcional: indica si la prop es obligatoria o no
    },
    arrayPedidoSeleccionado: {
      type: Object, // Indica que esperas un array
      required: true // Opcional: indica si la prop es obligatoria o no
    }
  },
  data() {
    return {
      // Inicializa los datos locales si es necesario
    };
  },
  created() {
    console.log("============================")
    console.log(this.arrayPedidoProvDet);
    console.log("============================")

    // Lógica que se ejecuta cuando el componente es creado
  },
  methods: {
    cerrarModal(){
        this.$emit('cerrar');
    },
    abrirCompra(){
        const datos={
            pedido:this.arrayPedidoSeleccionado,
            detalles:this.arrayPedidoProvDet
        }
        this.$emit('abrirCompra',datos);

    }
  }
};
</script>