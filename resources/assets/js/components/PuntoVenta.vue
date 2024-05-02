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
                    <i class="fa fa-align-justify"></i> Puntos de Venta
                    <button type="button" @click="abrirModal('puntoVenta', 'registrar')" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <span class="badge bg-secondary" id="comunicacionSiat" style="color: white;">Desconectado</span>
                    <span class="badge bg-secondary" id="cuis">CUIS: Inexistente</span>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="nombre">Nombre</option>
                                    <option value="descripcion">Descripción</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarPuntoVenta(1, buscar, criterio)"
                                    class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarPuntoVenta(1, buscar, criterio)"
                                    class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Tipo Punto de Venta</th>
                                    <th>Sucursal</th>
                                    <!--<th>Estado</th>-->
                                    <th> </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="puntoVenta in arrayPuntoVenta" :key="puntoVenta.id">
                                    <td v-text="puntoVenta.codigoPuntoVenta"></td>
                                    <td v-text="puntoVenta.nombre"></td>
                                    <td v-text="puntoVenta.descripcion"></td>
                                    <td v-text="puntoVenta.descripcionTipo"></td>
                                    <td v-text="puntoVenta.nombreSucursal"></td>
                                    <!--<td>
                                        <div v-if="puntoVenta.estado">
                                            <span class="badge badge-success">DISPONIBLE</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">CERRADA</span>
                                        </div>
                                    </td>-->
                                    <td>
                                        <button v-if="puntoVenta.estado === 1" class="btn btn-danger" type="button"
                                            @click="cerrarPuntoVenta(puntoVenta.codigoPuntoVenta, puntoVenta.idsucursal, puntoVenta.nit)"><i
                                                class="icon-close"></i></button>
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
        </div>
        <!-- Fin ejemplo de tabla Listado -->
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
                                    <label for="nombre" class="font-weight-bold">Nombre del Punto de Venta <span
                                            class="text-danger">*</span></label>
                                    <input type="text" v-model="datosFormulario.nombre" class="form-control" id="nombre"
                                        placeholder="Ej. Farmacia San José Sucursal Central"
                                        :class="{ 'is-invalid': errores.nombre }" @input="validarCampo('nombre')">
                                    <p class="text-danger" v-if="errores.nombre">{{ errores.nombre }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label for="nit" class="font-weight-bold">NIT de la Sucursal <span
                                            class="text-danger">*</span></label>
                                    <input disabled type="text" v-model="datosFormulario.nit" class="form-control"
                                        id="nit" placeholder="Ej. 123456-7" :class="{ 'is-invalid': errores.nit }"
                                        @input="validarCampo('nit')">
                                    <p class="text-danger" v-if="errores.nit">{{ errores.nit }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="descripcion" class="font-weight-bold">Descripción del Punto de Venta
                                        <span class="text-danger">*</span></label>
                                    <textarea v-model="datosFormulario.descripcion" class="form-control"
                                        id="descripcion"
                                        placeholder="Ej. Farmacia con atención 24 horas, venta de medicamentos y productos de cuidado personal."
                                        :class="{ 'is-invalid': errores.descripcion }"
                                        @input="validarCampo('descripcion')"></textarea>
                                    <p class="text-danger" v-if="errores.descripcion">{{ errores.descripcion }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="idtipopuntoventa" class="font-weight-bold">Tipo de Punto de Venta
                                        <span class="text-danger">*</span></label>
                                    <select id="idtipopuntoventa" v-model="datosFormulario.idtipopuntoventa"
                                        @change="validarCampo('idtipopuntoventa')" class="form-control"
                                        :class="{ 'is-invalid': errores.idtipopuntoventa }">
                                        <option value="" disabled selected>Selecciona un tipo de punto de venta</option>
                                        <option v-for="tipo_punto_venta in arrayTipoPuntoVenta"
                                            :key="tipo_punto_venta.id" :value="tipo_punto_venta.codigo">{{
                        tipo_punto_venta.descripcion }}</option>
                                    </select>
                                    <p class="text-danger" v-if="errores.idtipopuntoventa">{{ errores.idtipopuntoventa
                                        }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label for="idsucursal" class="font-weight-bold">Nombre de la Sucursal <span
                                            class="text-danger">*</span></label>
                                    <select id="idsucursal" v-model="datosFormulario.idsucursal" class="form-control"
                                        @change="validarCampo('idsucursal')"
                                        :class="{ 'is-invalid': errores.idsucursal }">
                                        <option value="" disabled selected>Selecciona una sucursal</option>
                                        <option v-for="sucursal in arraySucursal" :key="sucursal.id"
                                            :value="sucursal.id">{{ sucursal.nombre }}</option>
                                    </select>
                                    <p class="text-danger" v-if="errores.idsucursal">{{ errores.idsucursal }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" @click="cerrarModal()">Cerrar</button>
                            <button type="submit" v-if="tipoAccion == 1" class="btn btn-success">Guardar</button>
                        </div>
                    </form>

                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
        <!--Fin del modal-->
    </main>
</template>

<script>
import { esquemaPuntoDeVenta } from '../constants/validations';

export default {
    data() {
        return {
            datosFormulario: {
                nombre: "",
                descripcion: "",
                nit: "5153610012",
                idtipopuntoventa: "",
                idsucursal: "",
            },
            errores: {},


            id: 0,
            arrayPuntoVenta: [],
            arrayTipoPuntoVenta: [],
            arraySucursal: [],
            modal: 0,
            tituloModal: '',
            tipoAccion: 0,
            errorPuntoVenta: 0,
            errorMostrarMsjPuntoVenta: [],
            pagination: {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0,
            },
            offset: 3,
            criterio: 'nombre',
            buscar: '',
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
        async validarCampo(campo) {
            try {
                await esquemaPuntoDeVenta.validateAt(campo, this.datosFormulario);
                this.errores[campo] = null;
            } catch (error) {
                this.errores[campo] = error.message;
            }
        },
        async enviarFormulario() {

            await esquemaPuntoDeVenta.validate(this.datosFormulario, { abortEarly: false })
                .then(() => {
                    this.registrarPuntoVenta(this.datosFormulario)

                })
                .catch((error) => {
                    const erroresValidacion = {};
                    error.inner.forEach((e) => {
                        erroresValidacion[e.path] = e.message;
                    });

                    this.errores = erroresValidacion;
                });
        },

        listarPuntoVenta(page, buscar, criterio) {
            let me = this;
            var url = '/puntoVenta?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayPuntoVenta = respuesta.punto_ventas.data;
                me.pagination = respuesta.pagination;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        cambiarPagina(page) {
            let me = this;
            me.pagination.current_page = page;
            me.listarFactura(page);
        },

        verificarComunicacion() {
            axios.post('/venta/verificarComunicacion')
                .then(function (response) {
                    if (response.data.RespuestaComunicacion.transaccion === true) {
                        document.getElementById("comunicacionSiat").innerHTML = response.data.RespuestaComunicacion.mensajesList.descripcion;
                        document.getElementById("comunicacionSiat").className = "badge bg-success";
                    } else {
                        document.getElementById("comunicacionSiat").innerHTML = "Desconectado";
                        document.getElementById("comunicacionSiat").className = "badge bg-secondary";
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        cuis() {
            axios.post('/venta/cuis')
                .then(function (response) {
                    if (response.data.RespuestaCuis.transaccion === false) {
                        document.getElementById("cuis").innerHTML = "CUIS: " + response.data.RespuestaCuis.codigo;
                        document.getElementById("cuis").className = "badge bg-primary";
                    } else {
                        document.getElementById("cuis").innerHTML = "CUIS: Inexistente";
                        document.getElementById("cuis").className = "badge bg-secondary";
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        cufd() {
            axios.post('/venta/cufd')
                .then(function (response) {
                    if (response.data.transaccion != false) {
                        document.getElementById("cufd").innerHTML = "CUFD vigente: " + response.data.fechaVigencia.substring(0, 16);
                        document.getElementById("direccion").innerHTML = response.data.direccion;
                        document.getElementById("cufdValor").innerHTML = response.data.codigo;
                        document.getElementById("cufd").className = "badge bg-info";
                    } else {
                        document.getElementById("cufd").innerHTML = "No existe CUFD vigente";
                        document.getElementById("cufd").className = "badge bg-secondary";
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        obtenerDatosTipoPuntoVenta() {
            let me = this;
            var url = '/puntoVenta/obtenerDatosTipoPuntoVenta';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayTipoPuntoVenta = respuesta.tipo_punto_ventas;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },

        obtenerDatosSucursal() {
            let me = this;
            var url = '/puntoVenta/obtenerDatosSucursal';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arraySucursal = respuesta.sucursales;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },

        registrarPuntoVenta(datos) {

            let me = this;

            axios.post('/puntoVenta/habilitar', datos).then(function (response) {
                var data = response.data;
                if (!isNaN(data)) {
                    swal(
                        'PUNTO DE VENTA REGISTRADO',
                        'Su código del Punto de Venta es: ' + data,
                        'success'
                    );

                    axios.post('/puntoVenta/registrar', datos).then(function (response) {
                        me.cerrarModal();
                        me.listarPuntoVenta(1, '', 'id');
                    }).catch(function (error) {
                        console.log(error);
                        swal('ERROR AL REGISTRAR EL PUNTO DE VENTA', 'Revise los errores', 'error');
                    })
                } else {
                    swal(
                        'ERROR AL REGISTRAR EL PUNTO DE VENTA',
                        data,
                        'warning'
                    );
                }
            }).catch(function (error) {
                console.log(error);
                swal('ERROR AL REGISTRAR EL PUNTO DE VENTA', 'Intente de Nuevo', 'error');
            });
        },

        cerrarPuntoVenta(codigoPuntoVenta, idsucursal, nit) {
            swal({
                title: 'Esta seguro de Cerrar este Punto de Venta?',
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

                    axios.post('/puntoVenta/cerrar', {
                        'codigoPuntoVenta': codigoPuntoVenta,
                        'codigoSucursal': idsucursal,
                        'nit': nit
                    }).then(function (response) {
                        var data = response.data;
                        if (!isNaN(data)) {
                            swal(
                                'PUNTO DE VENTA CERRADO CON ÉXITO',
                                'Su código del Punto de Venta es: ' + data,
                                'success'
                            )
                            axios.put('/puntoVenta/cambioEstado', {
                                'id': id
                            }).then(function (response) {
                                me.listarPuntoVenta(1, '', 'codigoPuntoVenta');
                            }).catch(function (error) {
                                console.log(error);
                            });
                        } else {
                            swal(
                                'ERROR AL CERRAR EL PUNTO DE VENTA:',
                                data,
                                'warning'
                            )
                        }
                    })
                        .catch(function (error) {
                            console.log(error);
                            swal(
                                'PUNTO DE VENTA CERRADO CON ÉXITO',
                                'Su código del Punto de Venta es: ' + data,
                                'success'
                            )
                            axios.put('/puntoVenta/cambioEstado', {
                                'codigoPuntoVenta': codigoPuntoVenta
                            }).then(function (response) {
                                me.listarPuntoVenta(1, '', 'codigoPuntoVenta');
                            }).catch(function (error) {
                                console.log(error);
                            });
                        });
                }
            })

        },
        cerrarModal() {
            this.modal = 0;
        },
        abrirModal(modelo, accion, data = []) {
            switch (modelo) {
                case "puntoVenta":
                    {
                        switch (accion) {
                            case 'registrar':
                                {
                                    this.modal = 1;
                                    this.tituloModal = 'Registrar Punto de Venta';
                                    this.datosFormulario = {
                                        nombre: "",
                                        descripcion: "",
                                        nit: "5153610012",
                                        idtipopuntoventa: "",
                                        idsucursal: "",
                                    };
                                    this.errores = {};
                                    this.tipoAccion = 1;
                                    break;
                                }
                        }
                    }
            }
        }
    },
    mounted() {
        this.listarPuntoVenta(1, this.buscar, this.criterio);
        this.verificarComunicacion();
        this.cuis();
        this.cufd();
        this.obtenerDatosTipoPuntoVenta();
        this.obtenerDatosSucursal();
    }
}
</script>