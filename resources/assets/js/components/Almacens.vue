<template>
    <main class="main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Almacenes
                    <button type="button" @click="abrirModal('almacenes', 'registrar')" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-4" v-model="criterio">
                                    <option value="nombre_almacen">Nombre Almacen</option>
                                    <option value="nombre_encargado">Nombre Encargado</option>  
                                    <option value="nombre_sucursal">Nombre Sucursal</option>                                
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarAlmacenes(1, buscar, criterio)"
                                    class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarAlmacenes(1, buscar, criterio)"
                                    class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Acciones</th>
                                    <th>Nombre del Almacén</th>
                                    <th>Dirección (Ubicación)</th>
                                    <th>Encargado</th>
                                    <th>Teléfono</th>
                                    <th>Sucursal</th>
                                    <th>Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="almacen in arrayAlmacen" :key="almacen.id">
                                    <td>
                                        <button type="button" @click="abrirModal('almacenes', 'actualizar', almacen)"
                                            class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                        </button> &nbsp;

                                    </td>
                                    <td v-text="almacen.nombre_almacen"></td>
                                    <td v-text="almacen.ubicacion"></td>
                                    <td v-text="almacen.nombre_encargado"></td>
                                    <td v-text="almacen.telefono"></td>
                                    <td v-text="almacen.nombre_sucursal"></td>
                                    <td v-text="almacen.observacion"></td>

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
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal " tabindex="-1" :class="{ 'mostrar': modal }" role="dialog" aria-labelledby="myModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form @submit.prevent="enviarFormulario">

                        <div class="modal-body">


                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="nombre_almacen" class="font-weight-bold">Nombre del almacén <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="nombre_almacen" v-model="datosFormulario.nombre_almacen"
                                        class="form-control" placeholder="Ej. Almacén Principal"
                                        :class="{ 'is-invalid': errores.nombre_almacen }"
                                        @input="validarCampo('nombre_almacen')" />
                                    <p class="text-danger" v-if="errores.nombre_almacen">{{ errores.nombre_almacen }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label for="ubicacion" class="font-weight-bold">Ubicacion <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="ubicacion" v-model="datosFormulario.ubicacion"
                                        class="form-control" placeholder="Ej. Calle 123, Ciudad"
                                        :class="{ 'is-invalid': errores.ubicacion }" @input="validarCampo('ubicacion')" />
                                    <p class="text-danger" v-if="errores.ubicacion">{{ errores.ubicacion }}</p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="encargado" class="font-weight-bold">Encargado <span
                                            class="text-danger">*</span></label>
                                    <v-select :on-search="selectUsuario" label="nombre" :options="arrayUsuario"
                                        :class="{ 'is-invalid': errores.encargado }" placeholder="Buscar encargado..."
                                        :onChange="getDatosUsuarios" v-model="usuarioSeleccionado">
                                    </v-select>
                                    <p class="text-danger" v-if="errores.encargado">{{ errores.encargado }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label for="telefono" class="font-weight-bold">Teléfono <span
                                            class="text-danger">*</span></label>
                                    <input type="number" id="telefono" v-model="datosFormulario.telefono"
                                        class="form-control" placeholder="Ej. 123456789"
                                        :class="{ 'is-invalid': errores.telefono }" @input="validarCampo('telefono')" />
                                    <p class="text-danger" v-if="errores.telefono">{{ errores.telefono }}</p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="sucursal" class="font-weight-bold">Sucursal <span
                                            class="text-danger">*</span></label>
                                    <v-select :on-search="selectSucursal" label="nombre" :options="arraySucursal"
                                        :class="{ 'is-invalid': errores.sucursal }" placeholder="Buscar Sucursales..."
                                        :onChange="getDatosSucursales" v-model="sucursalSeleccionado">
                                    </v-select>
                                    <p class="text-danger" v-if="errores.sucursal">{{ errores.sucursal }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label for="observacion" class="font-weight-bold">Observaciones</label>
                                    <textarea id="observacion" v-model="datosFormulario.observacion"
                                        class="form-control"
                                        placeholder="Ej. Horario de funcionamiento, Capacitad de almacenamiento, etc."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="submit" v-if="tipoAccion == 1" class="btn btn-primary">Guardar</button>
                            <button type="submit" v-if="tipoAccion == 2" class="btn btn-primary">Actualizar</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
    </main>
</template>
<script>
import vSelect from 'vue-select';
import { esquemaAlmacen } from '../constants/validations';

export default {
    data() {
        return {
            arraySucursal: [],
            sucursalSeleccionado: '',

            arrayUsuario: [],
            usuarioSeleccionado: '',

            datosFormulario: {
                nombre_almacen: '',
                ubicacion: '',
                encargado: -1,
                telefono: '',
                sucursal: -1,
                observacion: ''
            },
            errores: {},

            arrayAlmacen: [],
            modal: 0,
            tituloModal: '',
            tipoAccion: 0,
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
    components: {
        vSelect
    },
    methods: {
        getDatosSucursales(val1) {
            console.log("Ejecucion de sucursales")
            if (this.tipoAccion == 2) {
                this.datosFormulario.sucursal = val1 && val1.id ? val1.id : this.datosFormulario.sucursal2;
                delete this.datosFormulario['sucursal2']

            } else {
                this.datosFormulario.sucursal = val1 && val1.id ? val1.id : null;


            }


        },
        getDatosUsuarios(val1) {
            if (this.tipoAccion == 2) {

                this.datosFormulario.encargado = val1 && val1.id ? val1.id : this.datosFormulario.encargado2;
                delete this.datosFormulario['encargado2']

            } else {
                this.datosFormulario.encargado = val1 && val1.id ? val1.id : null;

            }

        },

        selectSucursal(search, loading) {
            let me = this;
            loading(true)
            let url = '/sucursal/selectedSucursal/filter?filtro=' + search;
            axios.get(url).then(function (response) {
                let respuesta = response.data;
                me.arraySucursal = respuesta.sucursales;
                console.log(respuesta)
                loading(false)
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        selectUsuario(search, loading) {
            let me = this;
            loading(true)
            let url = '/user/selectUser/filter?filtro=' + search;
            axios.get(url).then(function (response) {
                let respuesta = response.data;
                me.arrayUsuario = respuesta.usuarios;
                console.log(respuesta)
                loading(false)
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        async validarCampo(campo) {
            try {
                await esquemaAlmacen.validateAt(campo, this.datosFormulario);
                this.errores[campo] = null;
            } catch (error) {
                this.errores[campo] = error.message;
            }
        },
        async enviarFormulario() {
            console.log("Llego aca", this.datosFormulario);
            await esquemaAlmacen.validate(this.datosFormulario, { abortEarly: false })
                .then(() => {
                    console.log(this.datosFormulario)
                    if (this.tipoAccion == 2) {
                        this.actualizarAlmacen(this.datosFormulario);
                    } else {
                        this.registrarAlmacen(this.datosFormulario);
                    }
                })
                .catch((error) => {
                    console.log(error)
                    const erroresValidacion = {};
                    error.inner.forEach((e) => {
                        erroresValidacion[e.path] = e.message;
                    });

                    this.errores = erroresValidacion;
                });
        },
        cambiarPagina(page, buscar, criterio) {
            let me = this;
            me.pagination.current_page = page;
            me.listarAlmacenes(page, buscar, criterio);
        },
        listarAlmacenes(page, buscar, criterio) {
            let me = this;
            var url = '/almacen?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                let respuesta = response.data;
                me.arrayAlmacen = respuesta.almacenes.data;
                me.pagination = respuesta.pagination;
                console.log("Respuesta: ",respuesta)
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        registrarAlmacen(data) {
            let me = this;
            axios.post('/almacen/registrar', data).then(function (response) {
                me.cerrarModal();
                me.listarAlmacenes(1, '', 'nombre_almacen');
            }).catch(function (error) {
                console.log(error);
            });
        },
        actualizarAlmacen(data) {

            let me = this;
            axios.put('/almacen/editar', data).then(function (response) {
                me.cerrarModal();
                //console.log(response)
                me.listarAlmacenes(1, '', 'nombre_almacen');
            }).catch(function (error) {
                console.log(error);
            });
        },

        cerrarModal() {
            this.modal = 0;
            this.tituloModal = '';
            this.sucursalSeleccionado = '';
            this.usuarioSeleccionado = '';

        },
        abrirModal(modelo, accion, data = []) {
            switch (modelo) {
                case "almacenes":
                    {
                        switch (accion) {
                            case 'registrar':
                                {
                                    this.modal = 1;
                                    this.tituloModal = 'Registrar Almacen';
                                    this.tipoAccion = 1;
                                    this.datosFormulario = {
                                        nombre_almacen: '',
                                        ubicacion: '',
                                        encargado: '',
                                        telefono: '',
                                        sucursal: '',
                                        observacion: ''
                                    };
                                    this.errores = {};
                                    break;
                                }
                            case 'actualizar':
                                {
                                    console.log("Datos almacen:", data)
                                    this.modal = 1;
                                    this.tituloModal = 'Actualizar Almacen';
                                    this.tipoAccion = 2;
                                    this.datosFormulario = {
                                        id: data['id'],
                                        nombre_almacen: data['nombre_almacen'],
                                        ubicacion: data['ubicacion'],
                                        encargado: data['encargado'],
                                        telefono: data['telefono'],
                                        sucursal: data['sucursal'],
                                        sucursal2: data['sucursal'],
                                        encargado2: data['encargado'],
                                        observacion: data['observacion'] == null ? '' : data['observacion']
                                    };
                                    this.sucursalSeleccionado = data['nombre_sucursal'];
                                    this.usuarioSeleccionado = data['nombre_encargado'];

                                    this.errores = {};

                                    break;
                                }
                        }
                    }
            }
        }
    },
    mounted() {
        this.listarAlmacenes(1, this.buscar, this.criterio);
    }
}
</script>
<style>
.table-responsive {
    margin: 20px 0;
    }

    .table-hover tbody tr:hover {
    background-color: #f1f1f1;
    }

    .btn-sm {
    padding: 0.25rem 0.5rem;
    }

    .thead-dark th {
    background-color: #343a40;
    color: white;
    }

    .table-bordered th,
    .table-bordered td {
    border: 1px solid #dee2e6;
    }

    .table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.05);
    }
</style>