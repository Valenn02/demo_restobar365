<template>
    <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
    </ol>
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Eventos Significativos
                <button type="button" @click="abrirModal('eventos','registrar')" class="btn btn-secondary">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
                <span class="badge bg-secondary" id="comunicacionSiat" style="color: white;">Desconectado</span>
                <span class="badge bg-secondary" id="cuis">CUIS: Inexistente</span>
                <span class="badge bg-primary" id="cufdValor" v-show="mostrarCUFD">No hay CUFD</span>
            </div>
            <div class="table-responsive">
                        
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Descripción</th>
                                    <th>Inicio Evento</th>
                                    <th>Fin Evento</th>
                                    <th>Estado</th>
                                    <th> </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="eventos_significativos in arrayEventos" :key="eventos_significativos.id">
                                    <td v-text="eventos_significativos.id"></td>
                                    <td v-text="eventos_significativos.descripcion"></td>
                                    <td v-text="eventos_significativos.inicioEvento"></td>
                                    <td v-text="eventos_significativos.finEvento"></td>
                                    <td>
                                        <div v-if="eventos_significativos.estado">
                                            <span class="badge badge-success">Evento Iniciado</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Evento Finalizado</span>
                                        </div>
                                        
                                    </td>
                                    <td>
                                        <button v-if="eventos_significativos.estado === 1" class="btn btn-danger" type="button" @click="finalizarEvento(eventos_significativos.id, eventos_significativos.descripcion, eventos_significativos.cufdEvento, eventos_significativos.codigoMotivoEvento, eventos_significativos.inicioEvento)"><strong>Finalizar Evento</strong></button>    
                                    </td>
                                </tr>                                                              
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
        <!-- Inicio del modal agregar/actualizar -->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                    <div class="form-group">
                        <label for="descripcion"><strong>Descripción</strong></label>
                        <select id="idMotivo" v-model="idMotivo" class="form-control" @change="actualizarCodigoEvento">
                            <option value="" disabled>Selecciona una descripción</option>
                            <option v-for="motivo_eventos in arrayMotivos" :key="motivo_eventos.id" :value="motivo_eventos.id">{{ motivo_eventos.descripcion }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="hidden" id="text-input" v-model="nit" class="form-control" readonly>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                        <label for="text-input"><strong>Inicio del Evento</strong></label>
                        <input type="text" id="inicioEvento" v-model="inicioEvento" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <input type="hidden" id="codigoEvento" v-model="codigoEvento" class="form-control" placeholder="Paramétrica que identifica el tipo de evento" readonly>
                        <input type="hidden" id="descripcion" v-model="descripcion" class="form-control" placeholder="Paramétrica la descripción del tipo de evento" readonly>
                    </div>
                    </div>
                    <div class="col-md-12">
                    <div class="form-group">
                        <label for="text-input"><strong>CUFD del Evento</strong></label>
                        <input type="text" id="cufdEvento" v-model="cufdEvento" class="form-control" readonly>
                    </div>
                    </div>
                </div>
                <div v-show="errorEvento" class="form-group row div-error">
                    <div class="text-center text-error">
                    <div v-for="error in errorMostrarMsjEvento" :key="error" v-text="error"></div>
                    </div>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarEvento()">Guardar</button>
                <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarSucursal()">Actualizar</button>
            </div>
            </div>
        </div>
        </div>
      <!-- Fin del modal -->
</main>
</template>

<script>
    export default {
        data (){
            return {
                id: 0,
                descripcion: '',
                nit: '',
                cufdEvento: '',
                valorCufd: '',
                codigoEvento: '',
                inicioEvento: '',
                finEvento: '',
                idMotivo: '',
                arrayEventos : [],
                arrayMotivos : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorEvento : 0,
                errorMostrarMsjEvento : [],
                mostrarDireccion: true,
                mostrarCUFD: true,
                mostrarEvento: true,
                mostrarBotonesSecundarios: false,
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3
            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
        },
        methods : {
            lsitarEventos (page){
                let me=this;
                var url= '/eventos?page=' + page;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayEventos = respuesta.eventos_significativos.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.lsitarEventos(page);
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
                            document.getElementById("cufdValor").innerHTML = response.data.codigo;
                            this.cufdEvento = response.data.codigo;
                        } else {
                            document.getElementById("cufd").innerHTML = "No existe CUFD vigente";
                            document.getElementById("cufd").className = "badge bg-secondary";
                        }
                    }.bind(this))
                    .catch(function (error) {
                        console.log(error);
                    });
            },

            obtenerDatosMotivo (){
                let me = this;
                var url = '/eventos/obtenerDatosMotivoEvento';
                axios.get(url).then(function(response) {
                    var respuesta = response.data;
                    me.arrayMotivos = respuesta.motivo_eventos;
                    console.log("MOTIVOS", me.arrayMotivos);
            })
            .catch(function(error) {
                console.log(error);
            });
            },

            actualizarCodigoEvento() {
                const motivoSeleccionado = this.arrayMotivos.find(motivo => motivo.id === this.idMotivo);
                
                if (motivoSeleccionado) {
                    this.codigoEvento = motivoSeleccionado.codigo;
                    this.descripcion = motivoSeleccionado.descripcion;
                } else {
                    this.codigoEvento = '';
                    this.descripcion = '';
                }
            },

            registrarEvento(){
                if(this.validarEvento()){
                    return;
                }

                let me = this;
                
                axios.post('/eventos/registrar', {
                    'descripcion' : this.descripcion,
                    'cufdEvento' : this.cufdEvento,
                    'nit' : this.nit,
                    'codigoMotivoEvento' : this.codigoEvento,
                    'inicioEvento' : this.inicioEvento,
                    'idmotivoevento' : this.idMotivo
                }).then(function(response) {
                    swal(
                            'EVENTO INICIADO',
                            'Correctamente',
                            'success'
                        );
                    me.cerrarModal();
                    me.lsitarEventos(1, '', 'descripcion');
                }).catch(function(error){
                    console.log(error);
                })
            },

            finalizarEvento(id, descripcion, cufdEvento, codigoEvento, inicioEvento){
                swal({
                title: 'Esta seguro de Finalizar el Evento?',
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
                    if(result.value){
                        let cufdValor = document.getElementById("cufdValor");
                        let cufd = cufdValor.textContent;
                        let fechaHoraActual = this.iniciarFechaHoraActual();

                        let me = this;

                        axios.post('/factura/eventosSignificativos', {
                            'descripcion': descripcion,
                            'cufdEvento': cufdEvento,
                            'codigoMotivoEvento': codigoEvento,
                            'inicioEvento': inicioEvento,
                            'finEvento': fechaHoraActual
                        }).then(function(response) {
                            var data = response.data;
                            if(!isNaN(data)){
                                axios.put('/eventos/finalizarEvento', {
                                    'id': id,
                                    'finEvento': fechaHoraActual,
                                    'cufd': cufd
                                }).then(function(response) {
                                    console.log('Evento finalizado con éxito')
                                }).catch(function(error){
                                    console.log(error);
                                });
                                swal(
                                    'EVENTO FINALIZADO CON ÉXITO',
                                    'Su código del Evento es: ' + data,
                                    'success'
                                )
                                axios.put('/eventos/cambioEstadoEvento', {
                                'id': id
                            }).then(function(response) {
                                me.lsitarEventos(1, '', 'descripcion');
                            }).catch(function(error){
                                console.log(error);
                            });
                            }else{
                                swal(
                                    'ERROR AL FINALIZAR EL EVENTO:',
                                    data,
                                    'warning'
                                )
                                axios.put('/eventos/errorEvento', {
                                'id': id
                            }).then(function(response) {
                                me.lsitarEventos(1, '', 'descripcion');
                            }).catch(function(error){
                                console.log(error);
                            });
                            }
                        })
                        .catch(function(error) {
                            //console.log(error);
                            // Aquí se mostrará el error de red
                            if(error.response.data.message === "Trying to get property 'RespuestaListaEventos' of non-object"){
                            swal('INTENTE DE NUEVO', 'Comunicación con SIAT fallida', 'error');
                            }else{
                            swal('ERROR', error, 'error');
                            }
                        });
                    }
                })
                
            }, 

            validarEvento(){
                this.errorEvento=0;
                this.errorMostrarMsjEvento =[];

                if (!this.idMotivo) this.errorMostrarMsjEvento.push("Debe seleccionar un Motivo de Evento.");

                if (this.errorMostrarMsjEvento.length) this.errorEvento = 1;

                return this.errorEvento;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nit= '5153610012'
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "eventos":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.cufd();

                                this.modal = 1;
                                this.tituloModal = 'Registrar Nuevo Evento Significativo';
                                this.nit = '5153610012';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                //this.modal=1;
                                //this.tituloModal='Actualizar categoría';
                                //this.tipoAccion=2;
                                //this.categoria_id=data['id'];
                                //this.nombre = data['nombre'];
                                //this.descripcion= data['descripcion'];
                                //break;
                            }
                        }
                    }
                }
            },
            iniciarFechaHoraActual(){
                var tzoffset = (new Date()).getTimezoneOffset() * 60000;
                let fechaHoraActual = (new Date(Date.now() - tzoffset)).toISOString().slice(0, -1);
                this.inicioEvento = fechaHoraActual;
                return fechaHoraActual;
            },
        },
        mounted() {
            this.lsitarEventos(1);
            this.verificarComunicacion();
            this.obtenerDatosMotivo();
            this.cuis();
            this.cufd();
            this.iniciarFechaHoraActual();
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>