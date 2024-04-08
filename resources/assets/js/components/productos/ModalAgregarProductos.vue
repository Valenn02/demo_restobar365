<template>
            <div class="modal fade" tabindex="-1" :class="{ 'mostrar': true }" role="dialog" aria-labelledby="myModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" >Agregar productos</h4>
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
                                        <th>Código</th>
                                        <th>Nombre comercial</th>
                                        <th>Costo unit</th>
                                        <th>Costo paquete</th>
                                        <th>Proveedor habitual</th>
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
                                        <td v-text="articulo.precio_costo_unid"></td>
                                        <td v-text="articulo.precio_costo_unid*articulo.unidad_envase"></td>
                                        <td v-text="articulo.nombre_proveedor"></td>
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
</template>
<script>
// Importa las dependencias necesarias si es necesario

export default {
        props: {
      idproveedor: Number
    },
    created(){
        this.listarArticulo("","");

    },
    data() {
        return {
            tipoUnidadSeleccionada: "Unidades",
            arrayArticuloSeleccionado:{},
            fechavencimiento:null,

            AlmacenSeleccionado:'',
            arrayAlmacenes:[],
            ingreso_id: 0,
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
            // modal: 0,
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
  // Tu código existente

  methods: {
    cerrarModal() {
            console.log("Cerrando");
            this.$emit('cerrar');

        },
        listarArticulo(buscar, criterio) {
            console.log("Buscando")
            console.log(this.idproveedor);
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
        agregarDetalleModal(data) {
            console.log("agregando",data);
            this.$emit('agregarArticulo',data);

        },
    // Otros métodos de tu componente
  },
};
</script>