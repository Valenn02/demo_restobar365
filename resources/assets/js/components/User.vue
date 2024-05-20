<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Ventas</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Usuarios
                    <button type="button" @click="abrirModal('persona', 'registrar')" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <button type="button" @click="cargarReporteUsuariosExcel()" class="btn btn-info">
                        <i class="icon-doc"></i>&nbsp;Reporte
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="nombre">Nombre</option>
                                    <option value="num_documento">Documento</option>
                                    <option value="email">Email</option>
                                    <option value="telefono">Teléfono</option>
                                    <option value="nombre">Sucursal</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup="listarPersona(1, buscar, criterio)"
                                    class="form-control" placeholder="Texto a buscar">
                                <!--button type="submit" @click="listarPersona(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button-->
                            </div>
                        </div>
                    </div>
                <div class="table-responsive">

                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Nombre</th>
                                <th>Tipo Documento</th>
                                <th>Número</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Usuario</th>
                                <th>Rol</th>
                                <th>Sucursal</th>
                                <th>Punto de Venta</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="persona in arrayPersona" :key="persona.id">
                                <td class="text-center">
                                    <img :src="'img/usuarios/' + persona.fotografia + '?t=' + new Date().getTime()" width="50" height="50"
                                        v-if="persona.fotografia" ref="imagen">
                                    <!--img :src="'img/usuarios/' + persona.fotografia" width="50" height="50"
                                        v-if="persona.fotografia" ref="imagen"-->
                                    <img :src="'img/usuarios/' + 'defecto.jpg'" width="50" height="50" v-else ref="imagen">
                                </td>
                                <td v-text="persona.nombre"></td>
                                <td v-text="getTipoDocumentoText(persona.tipo_documento)"></td>
                                <td v-text="persona.num_documento"></td>
                                <td v-text="persona.telefono"></td>
                                <td v-text="persona.email"></td>
                                <td v-text="persona.usuario"></td>
                                <td v-text="persona.rol"></td>
                                <td v-text="persona.sucursal"></td>
                                <td v-text="persona.puntoventa"></td>
                                <td>
                                    <button type="button" @click="abrirModal('persona', 'actualizar', persona)"
                                        class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                    <template v-if="persona.condicion">
                                        <button type="button" class="btn btn-danger btn-sm"
                                            @click="desactivarUsuario(persona.id)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" class="btn btn-info btn-sm"
                                            @click="activarUsuario(persona.id)">
                                            <i class="icon-check"></i>
                                        </button>
                                    </template>
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
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Columna izquierda -->
                            <div class="form-group">
                                <label class="form-control-label" for="text-input"><strong>Nombre(*)</strong></label>
                                <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de la persona">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="text-input"><strong>Tipo documento</strong></label>
                                <select v-model="tipo_documento" class="form-control">
                                            <option value="" disabled>Selecciona una tipo de documento</option>
                                            <option value="1">CI - CEDULA DE IDENTIDAD</option>
                                            <option value="2">CEX - CEDULA DE IDENTIDAD DE EXTRANJERO</option>
                                            <option value="5">NIT - NÚMERO DE IDENTIFICACIÓN TRIBUTARIA</option>
                                            <option value="3">PAS - PASAPORTE</option>
                                            <option value="4">OD - OTRO DOCUMENTO DE IDENTIDAD</option>   
                                        </select> 
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="email-input"><strong>Teléfono</strong></label>
                                <input type="email" v-model="telefono" class="form-control" placeholder="Teléfono">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="email-input"><strong>Rol</strong></label>
                                <select v-model="idrol" class="form-control">
                                    <option value="0" disabled>Seleccione</option>
                                    <option v-for="role in arrayRol" :key="role.id" :value="role.id"
                                        v-text="role.nombre"></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="email-input"><strong>Usuario</strong></label>
                                <input type="text" v-model="usuario" class="form-control" placeholder="Nombre del usuario">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="email-input"><strong>Clave</strong></label>
                                <input type="password" v-model="password" class="form-control" placeholder="Clave del usuario">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Columna derecha -->
                            <div class="form-group">
                                <label class="form-control-label" for="email-input"><strong>Dirección</strong></label>
                                <input type="email" v-model="direccion" class="form-control" placeholder="Dirección">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="email-input"><strong>Número documento</strong></label>
                                <input type="email" v-model="num_documento" class="form-control" placeholder="Número de documento">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="email-input"><strong>Email</strong></label>
                                <input type="email" v-model="email" class="form-control" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="email-input"><strong>Sucursal</strong></label>
                                <select v-model="idsucursal" class="form-control">
                                    <option value="0" disabled>Seleccione</option>
                                    <option v-for="sucursal in arraySucursal" :key="sucursal.id" :value="sucursal.id"
                                        v-text="sucursal.nombre"></option>
                                </select>
                            </div>
                            <div class="form-group" v-if="idsucursal !== '0'">
                                <label class="form-control-label" for="branch-input"><strong>Punto de Venta</strong></label>
                                <select v-model="idpuntoventa" class="form-control">
                                    <option value="0" disabled>Seleccione</option>
                                    <option v-for="puntoVenta in filteredPuntosVenta" :key="puntoVenta.id" :value="puntoVenta.id" 
                                    v-text="puntoVenta.nombre"></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="email-input"><strong>Fotografía</strong></label>
                                <div class="row">
                                    <div class="col-md-8">
                                        <input type="file" @change="obtenerFotografia" class="form-control" placeholder="fotografia usuario" ref="fotografiaInput">
                                    </div>
                                    <div class="col-md-4">
                                        <figure>
                                            <img :src="imagen" width="50" height="50" alt="Foto usuario">
                                        </figure>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div v-show="errorPersona" class="form-group div-error">
                        <div class="text-center text-error">
                            <div v-for="error in errorMostrarMsjPersona" :key="error" v-text="error"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="registrarPersona()">Guardar</button>
                <button type="button" v-if="tipoAccion == 2" class="btn btn-primary" @click="actualizarPersona()">Actualizar</button>
            </div>
        </div>
    </div>
</div>

        <!--Fin del modal-->
    </main>
</template>

<script>
export default {
    data() {
        return {
            persona_id: 0,
            nombre: '',
            tipo_documento: '',
            num_documento: '',
            direccion: '',
            telefono: '',
            email: '',
            usuario: '',
            password: '',
            fotografia: '',
            fotoMuestra: '',
            idrol: '',
            idsucursal: '',
            idpuntoventa: '',
            arrayPersona: [],
            arrayRol: [],
            arraySucursal: [],
            arrayPuntoVenta: [],
            modal: 0,
            tituloModal: '',
            tipoAccion: 0,
            errorPersona: 0,
            errorMostrarMsjPersona: [],
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

        },
        imagen() {
            console.log(this.fotoMuestra);
            return this.fotoMuestra;
        },
        filteredPuntosVenta(){
            if (this.idsucursal === 0) {
                return [];
            } else {
                return this.arrayPuntoVenta.filter(puntoventa => puntoventa.idsucursal === this.idsucursal);
            }
        }
    },
    methods: {
        listarPersona(page, buscar, criterio) {
            let me = this;
            var url = '/user?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayPersona = respuesta.personas.data;
                me.pagination = respuesta.pagination;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        selectRol() {
            let me = this;
            var url = '/rol/selectRol';
            axios.get(url).then(function (response) {
                //console.log(response);
                var respuesta = response.data;
                me.arrayRol = respuesta.roles;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        
        selectSucursal() {
            let me = this;
            var url = '/sucursal/selectSucursal';
            axios.get(url).then(function (response) {
                //console.log(response);
                var respuesta = response.data;
                me.arraySucursal = respuesta.sucursales;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },

        obtenerPuntosDeVenta(){
            if (this.idsucursal === '0'){
                return;
            }

            axios.get(`/api/puntosDeVenta/${this.idsucursal}`)
            .then(response => {
                this.arrayPuntoVenta = response.data;
            })
            .catch(error => {
                console.log('Error al obtener los puntos de venta: ', error);
            })
        },

        cambiarPagina(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            //Envia la petición para visualizar la data de esa página
            me.listarPersona(page, buscar, criterio);
        },
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
        registrarPersona() {
            if (this.validarPersona()) {
                return;
            }

            let me = this;
            let formData = new FormData();

            formData.append('nombre', this.nombre);
            formData.append('tipo_documento', this.tipo_documento);
            formData.append('num_documento', this.num_documento);
            formData.append('direccion', this.direccion);
            formData.append('telefono', this.telefono);
            formData.append('email', this.email);
            formData.append('idrol', this.idrol);
            formData.append('idsucursal', this.idsucursal);
            formData.append('idpuntoventa', this.idpuntoventa);
            formData.append('usuario', this.usuario);
            formData.append('password', this.password);
            formData.append('fotografia', this.fotografia);

            /*for (let [key, value] of formData.entries()) 
            {
                console.log(key, value);
            }*/

            axios.post('/user/registrar', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }

            }).then(function (response) {
                swal(
                    'REGISTRO ÉXITOSO',
                    'Usario Añadido',
                    'success'
                    );
                me.cerrarModal();
                me.listarPersona(1, '', 'nombre');
            }).catch(function (error) {
                swal(
                    'REGISTRO FALLIDO',
                    'Intente de Nuevo',
                    'warning'
                );
                console.log(error);
            });

        },
        actualizarPersona() {
            if (this.validarPersona()) {
                return;
            }

            console.log(this.fotografia);
            let me = this;
            let formData = new FormData();
            formData.append('nombre', this.nombre);
            formData.append('tipo_documento', this.tipo_documento);
            formData.append('num_documento', this.num_documento);
            formData.append('direccion', this.direccion);
            formData.append('telefono', this.telefono);
            formData.append('email', this.email);
            formData.append('idrol', this.idrol);
            formData.append('idsucursal', this.idsucursal);
            formData.append('idpuntoventa', this.idpuntoventa);
            formData.append('usuario', this.usuario);
            formData.append('password', this.password);
            formData.append('fotografia', this.fotografia);
            formData.append('id', this.persona_id);

            axios.post('/user/actualizar', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(function (response) {
                swal(
                    'ACTUALIZACIÓN ÉXITOSA',
                    'Usario Actualizado',
                    'success'
                    );
                me.cerrarModal();
                me.listarPersona(1, '', 'nombre');
            }).catch(function (error) {
                swal(
                    'ACTUALIZACIÓN FALLIDA',
                    'Intente de Nuevo',
                    'warning'
                );
                console.log(error);
            });
        },
        validarPersona() {
            this.errorPersona = 0;
            this.errorMostrarMsjPersona = [];

            if (!this.nombre) this.errorMostrarMsjPersona.push("El nombre de la pesona no puede estar vacío.");
            if (!this.usuario) this.errorMostrarMsjPersona.push("El nombre de usuario no puede estar vacío.");
            if (!this.password) this.errorMostrarMsjPersona.push("La password del usuario no puede estar vacía.");
            if (this.idrol == 0) this.errorMostrarMsjPersona.push("Seleccione una Role.");
            if (this.errorMostrarMsjPersona.length) this.errorPersona = 1;

            return this.errorPersona;
        },
        cerrarModal() {
            //Usando referencia en el file para limpiarlo al cerrar el modal
            let fileInput = this.$refs.fotografiaInput;
            fileInput.value = '';

            this.modal = 0;
            this.tituloModal = '';
            this.nombre = '';
            this.tipo_documento = 'DNI';
            this.num_documento = '';
            this.direccion = '';
            this.telefono = '';
            this.email = '';
            this.usuario = '';
            this.password = '';
            this.fotografia = fileInput; //Pasando el valor limpio de la referencia
            this.fotoMuestra = 'img/usuarios/defecto.jpg';
            this.idrol = 0;
            this.idsucursal = 0;
            this.idpuntoventa = '';
            this.errorPersona = 0;
        },
        abrirModal(modelo, accion, data = []) {
            this.selectRol();
            this.selectSucursal();
            switch (modelo) {
                case "persona":
                    {
                        switch (accion) {
                            case 'registrar':
                                {
                                    this.modal = 1;
                                    this.tituloModal = 'Registrar Usuario';
                                    this.nombre = '';
                                    this.tipo_documento = 'DNI';
                                    this.num_documento = '';
                                    this.direccion = '';
                                    this.telefono = '';
                                    this.email = '';
                                    this.usuario = '';
                                    this.password = '';
                                    this.fotografia = '';
                                    this.idrol = 0;
                                    this.idsucursal = 0;
                                    this.idpuntoventa = '';
                                    this.tipoAccion = 1;
                                    break;
                                }
                            case 'actualizar':
                                {
                                    //console.log(data['fotografia']);
                                    this.modal = 1;
                                    this.tituloModal = 'Actualizar Usuario';
                                    this.tipoAccion = 2;
                                    this.persona_id = data['id'];
                                    this.nombre = data['nombre'];
                                    this.tipo_documento = data['tipo_documento'];
                                    this.num_documento = data['num_documento'];
                                    this.direccion = data['direccion'];
                                    this.telefono = data['telefono'];
                                    this.email = data['email'];
                                    this.usuario = data['usuario'];
                                    this.password = data['password'];
                                    this.fotografia = data['fotografia'];
                                    this.fotoMuestra = data['fotografia'] ? 'img/usuarios/' + data['fotografia'] : 'img/usuarios/defecto.jpg';
                                    this.idrol = data['idrol'];
                                    this.idsucursal = data['idsucursal'];
                                    this.idpuntoventa = data['idpuntoventa'];
                                    break;
                                }
                        }
                    }
            }
        },
        desactivarUsuario(id) {
            swal({
                title: 'Esta seguro de desactivar este usuario?',
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

                    axios.put('/user/desactivar', {
                        'id': id
                    }).then(function (response) {
                        me.listarPersona(1, '', 'nombre');
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
        activarUsuario(id) {
            swal({
                title: 'Esta seguro de activar este usuario?',
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

                    axios.put('/user/activar', {
                        'id': id
                    }).then(function (response) {
                        me.listarPersona(1, '', 'nombre');
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
        cargarReporteUsuariosExcel()
        {
            window.open('/user/listarReporteUsuariosExcel', '_blank');
        },

        getTipoDocumentoText(value){
            switch (value) {
                case '1':
                    return 'CI';
                case '2':
                    return 'CEX';
                case '3':
                    return 'PAS';
                case '4':
                    return 'OD';
                case '5':
                    return 'NIT';    
                default:
                    return '';
            }
        }
    },
    watch: {
        idsucursal(){
            this.obtenerPuntosDeVenta();
        }
    },

    mounted() {
        this.listarPersona(1, this.buscar, this.criterio);
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
