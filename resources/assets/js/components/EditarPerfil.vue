<template>
    <main class="main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Editar Perfil
                </div>
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-group row">
                                        <div class="col-md-8">
                                            <div class="row">
                                                <figure class="col-md-6">
                                                    <img :src="imagen" width="129" height="129"
                                                        alt="Foto usuario">
                                                </figure>
                                                <div class="col-md-6">
                                                    <label class="form-control-label" for="email-input">Fotografia</label>
                                                    <input type="file" @change="obtenerFotografia" class="form-control" placeholder="fotografia usuario"
                                                        ref="fotografiaInput">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="text-input">Nombre(*)</label>
                                    <input type="text" v-model="nombre" class="form-control"
                                        placeholder="Nombre de la persona">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="text-input">Tipo documento</label>
                                    <select v-model="tipo_documento" class="form-control">
                                        <option value="DNI">DNI</option>
                                        <option value="RUC">RUC</option>
                                        <option value="CEDULA">CEDULA</option>
                                        <option value="PASS">PASS</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="email-input">Número documento</label>
                                    <input type="email" v-model="num_documento" class="form-control"
                                        placeholder="Número de documento">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="email-input">Teléfono</label>
                                    <input type="email" v-model="telefono" class="form-control" placeholder="Teléfono">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="email-input">Email</label>
                                    <input type="email" v-model="email" class="form-control" placeholder="Email">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="email-input">Dirección</label>
                                    <input type="email" v-model="direccion" class="form-control" placeholder="Dirección">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="email-input">Usuario</label>
                                    <input type="text" v-model="usuario" class="form-control"
                                        placeholder="Nombre del usuario">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="email-input">password</label>
                                    <input type="password" v-model="password" class="form-control"
                                        placeholder="password del usuario">
                                </div>
                            </div>
                        </div>
                        <div v-show="errorPersona" class="form-group row div-error">
                            <div class="text-center text-error">
                                <div v-for="error in errorMostrarMsjPersona" :key="error" v-text="error">

                                </div>
                            </div>
                        </div>
                        <div class="form-group-row justify-content-center text-center mt-3">
                            <button type="button" class="btn btn-primary" @click="actualizarPersona()">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</template>
<script>
export default {
    data() {
        return {
            persona_id: '',
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
            arrayPersona: [],
            tipoAccion: 0,
            errorPersona: 0,
            errorMostrarMsjPersona: [],
            id: '',
        }
    },
    computed: {
        imagen() {
            //console.log(this.fotoMuestra);
            return this.fotoMuestra;
        }
    },
    methods: {
        obtenerFotografia(event) {

            let file = event.target.files[0];
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
        editarPersona(id_persona) {
            let me = this;
            var url = '/user/editarpersona?id=' + id_persona;

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                console.log(respuesta);

                me.persona_id = respuesta.persona.id;
                me.nombre = respuesta.persona.nombre;
                me.tipo_documento = respuesta.persona.tipo_documento;
                me.num_documento = respuesta.persona.num_documento;
                me.direccion = respuesta.persona.direccion;
                me.telefono = respuesta.persona.telefono;
                me.email = respuesta.persona.email;
                me.usuario = respuesta.persona.usuario;
                me.password = respuesta.persona.password;
                me.fotografia = respuesta.persona.fotografia;
                me.fotoMuestra = respuesta.persona.fotografia ? 'img/usuarios/'+respuesta.persona.fotografia + '?t=' + new Date().getTime() : 'img/usuarios/defecto.jpg';
                me.idrol = respuesta.persona.idrol;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        actualizarPersona() {

            swal({
                title: 'Esta seguro que desea actualizar sus datos?',
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
                    formData.append('usuario', this.usuario);
                    formData.append('password', this.password);
                    formData.append('fotografia', this.fotografia);
                    formData.append('id', this.persona_id);

                    axios.post('/user/actualizar', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(function (response) {
                        //alert('Datos actualizados');
                        //location.reload();
                        swal({
                            title: 'Datos de perfil actualizados...!',
                            type: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Aceptar!',
                            confirmButtonClass: 'btn btn-success',
                            buttonsStyling: false,
                            reverseButtons: true
                        }).then((result) => {
                            if(result.value){
                                location.reload();
                            }
                        })
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
        validarPersona() {
            this.errorPersona = 0;
            this.errorMostrarMsjPersona = [];

            if (!this.nombre) this.errorMostrarMsjPersona.push("El nombre de la pesona no puede estar vacío.");
            if (!this.usuario) this.errorMostrarMsjPersona.push("El nombre de usuario no puede estar vacío.");
            if (!this.password) this.errorMostrarMsjPersona.push("La password del usuario no puede estar vacía.");
            if (this.errorMostrarMsjPersona.length) this.errorPersona = 1;

            return this.errorPersona;
        },
    },
    mounted() {
        axios.get('/api/session')
            .then(response => {
                this.id = response.data.id;
                this.editarPersona(this.id);
            })
            .catch(error => {
                console.log(error);
            });
    },
}
</script>
<style></style>