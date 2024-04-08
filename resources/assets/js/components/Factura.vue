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
                <i class="fa fa-align-justify"></i> Facturas
                <button type="button" @click="" class="btn btn-secondary">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
                <span class="badge bg-secondary" id="comunicacionSiat" style="color: white;">Desconectado</span>
                <span class="badge bg-secondary" id="cuis">CUIS: Inexistente</span>
                <span class="badge bg-secondary" id="cufd">No existe cufd vigente</span>
                <span class="badge bg-secondary" id="direccion" v-show="mostrarDireccion">No hay dirección registrada</span>
                <span class="badge bg-primary" id="cufdValor" v-show="mostrarCUFD">No hay CUFD</span>
            </div>
            <div class="table-responsive">
                        
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Fecha</th>
                                    <th>Razón Social</th>
                                    <th>NIT/CI</th>
                                    <th>Email</th>
                                    <th>Total</th>
                                    <th>Descuento</th>
                                    <th>Estado</th>
                                    <th> </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="factura in arrayFactura" :key="factura.id">
                                    <td v-text="factura.numeroFactura"></td>
                                    <td v-text="factura.fechaEmision"></td>
                                    <td v-text="factura.razonSocial"></td>
                                    <td v-text="factura.documentoid"></td>
                                    <td v-text="factura.email"></td>
                                    <td v-text="factura.montoTotal"></td>
                                    <td v-text="factura.descuentoAdicional"></td>
                                    <td>
                                        <div v-if="factura.estado">
                                            <span class="badge badge-success">VALIDADA</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">RECHAZADA</span>
                                        </div> 
                                    </td>
                                    <td>
                                        <button class="btn btn-primary" type="button" @click="imprimirFactura(factura.id)"><i class="icon-printer"></i></button>
                                        <a @click="verificarFactura(factura.cuf, factura.numeroFactura)" target="_blank" class="btn btn-info"><i class="icon-note"></i></a>
                                        <button class="btn btn-danger" type="button" @click="anularFactura(factura.id, factura.cuf)"><i class="icon-close"></i></button>
                                    </td>
                                </tr>                                                              
                            </tbody>
                        </table>
                        
                    </div>
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
        <!-- Fin ejemplo de tabla Listado -->
</main>
</template>

<script>
    export default {
        data (){
            return {
                id: 0,
                arrayFactura : [],
                arrayMotivosAnulacion : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorCategoria : 0,
                errorMostrarMsjFactura : [],
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
            listarFactura (page){
                let me=this;
                var url= '/factura?page=' + page;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayFactura = respuesta.facturas.data;
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

            verificarFactura(cuf, numeroFactura){
                var url = 'https://pilotosiat.impuestos.gob.bo/consulta/QR?nit=5153610012&cuf='+cuf+'&numero='+numeroFactura+'&t=2';
                window.open(url);
                
            },

            anularFactura(id, cuf) {
            swal({
                title: '¿Está seguro de anular la factura?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                let me = this;
                axios.get('/factura/obtenerDatosMotivoAnulacion')
                    .then(function(response) {
                    var respuesta = response.data;
                    me.arrayMotivosAnulacion = respuesta.motivo_anulaciones;
                    
                    console.log('Motivos obtenidos:', me.arrayMotivosAnulacion);

                    let options = {};
                    me.arrayMotivosAnulacion.forEach(function(motivo) {
                        options[motivo.codigo] = motivo.descripcion;
                    });

                    // Muestra un segundo modal para seleccionar el motivo
                    swal({
                        title: 'Seleccione un motivo de anulación',
                        input: 'select',
                        inputOptions: options,
                        inputPlaceholder: 'Seleccione un motivo',
                        showCancelButton: true,
                        inputValidator: function (value) {
                        return new Promise(function (resolve, reject) {
                            if (value !== '') {
                            resolve();
                            } else {
                            reject('Debe seleccionar un motivo');
                            }
                        });
                        }
                    }).then((result) => {
                        if (result.value) {
                        // Aquí obtienes el motivo seleccionado y puedes realizar la solicitud para anular la factura
                        const motivoSeleccionado = result.value;
                        axios.get('/factura/anular/' + cuf +"/" + motivoSeleccionado)
                            .then(function(response) {
                            const data = response.data;
                            if (data === 'ANULACION CONFIRMADA') {
                                swal(
                                'FACTURA ANULADA',
                                data,
                                'success'
                                );
                            } else {
                                swal(
                                'ANULACION RECHAZADA',
                                data,
                                'warning'
                                );
                            }
                            })
                            .catch(function(error) {
                            console.log(error);
                            });
                        }
                    });
                    })
                    .catch(function(error) {
                    console.log(error);
                    });
                }
            });
            },


            imprimirFactura(id) {
            axios.get('/factura/imprimir/'+id, { responseType: 'blob' })
                .then(function(response) {
                window.location.href = "docs/factura.pdf";
                console.log("Se generó el PDF correctamente");
                })
                .catch(function(error) {
                console.log(error);
                });
            },

            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nombre='';
                this.descripcion='';
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "categoria":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Categoría';
                                this.nombre= '';
                                this.descripcion = '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar categoría';
                                this.tipoAccion=2;
                                this.categoria_id=data['id'];
                                this.nombre = data['nombre'];
                                this.descripcion= data['descripcion'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarFactura(1);
            this.verificarComunicacion();
            this.cuis();
            this.cufd();
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