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
                <i class="fa fa-align-justify"></i> Puntos de Venta
                <button type="button" @click="abrirModal('puntoVenta','registrar')" class="btn btn-secondary">
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
                                <input type="text" v-model="buscar" @keyup.enter="listarPuntoVenta(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarPuntoVenta(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Tipo Punto de Venta</th>
                                    <th>Sucursal</th>
                                    <th>Estado</th>
                                    <th> </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="puntoVenta in arrayPuntoVenta" :key="puntoVenta.id">
                                    <td v-text="puntoVenta.id"></td>
                                    <td v-text="puntoVenta.nombre"></td>
                                    <td v-text="puntoVenta.descripcion"></td>
                                    <td v-text="puntoVenta.descripcionTipo"></td>
                                    <td v-text="puntoVenta.nombreSucursal"></td>
                                    <td>
                                        <div v-if="puntoVenta.estado">
                                            <span class="badge badge-success">DISPONIBLE</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">CERRADA</span>
                                        </div> 
                                    </td>
                                    <td>
                                        <button v-if="puntoVenta.estado === 1" class="btn btn-danger" type="button" @click="cerrarPuntoVenta(puntoVenta.id, puntoVenta.idsucursal, puntoVenta.nit)"><i class="icon-close"></i></button>
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
        <!--Inicio del modal agregar/actualizar-->
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
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input"><strong>Nombre</strong></label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del Punto de Venta">
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input"><strong>Descripción</strong></label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="descripcion" class="form-control" placeholder="Ingrese una descripción">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input"><strong>NIT</strong></label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nit" class="form-control" placeholder="Ingrese NIT de la empresa">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="idtipopuntoventa"><strong>Tipo de Punto de Venta</strong></label>
                                    <div class="col-md-9">
                                        <select id="idtipopuntoventa" v-model="idtipopuntoventa" class="form-control">
                                        <option value="" disabled>Selecciona un tipo de punto de venta</option>
                                        <option v-for="tipo_punto_ventas in arrayTipoPuntoVenta" :key="tipo_punto_ventas.id" :value="tipo_punto_ventas.codigo">{{ tipo_punto_ventas.descripcion }}</option>
                                    </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="idsucursal"><strong>Sucursal</strong></label>
                                    <div class="col-md-9">
                                        <select id="idsucursal" v-model="idsucursal" class="form-control">
                                        <option value="" disabled>Selecciona una sucursal</option>
                                        <option v-for="sucursales in arraySucursal" :key="sucursales.id" :value="sucursales.id">{{ sucursales.nombre }}</option>
                                    </select>
                                    </div>
                                </div>  
                                <div v-show="errorPuntoVenta" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjPuntoVenta" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPuntoVenta()">Guardar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
</main>
</template>

<script>
    export default {
        data (){
            return {
                id: 0,
                nombre : '',
                descripcion : '',
                nit : '',
                idsucursal : 0,
                idtipopuntoventa : 0, 
                arrayPuntoVenta : [],
                arrayTipoPuntoVenta : [],
                arraySucursal : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorPuntoVenta : 0,
                errorMostrarMsjPuntoVenta : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre',
                buscar : '',
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
            listarPuntoVenta (page,buscar,criterio){
                let me=this;
                var url= '/puntoVenta?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayPuntoVenta = respuesta.punto_ventas.data;
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

            obtenerDatosTipoPuntoVenta (){
                let me = this;
                var url = '/puntoVenta/obtenerDatosTipoPuntoVenta';
                axios.get(url).then(function(response) {
                    var respuesta = response.data;
                    me.arrayTipoPuntoVenta = respuesta.tipo_punto_ventas;
                    console.log("TIPOS P.V.", me.arrayTipoPuntoVenta);
            })
            .catch(function(error) {
                console.log(error);
            });
            },

            obtenerDatosSucursal (){
                let me = this;
                var url = '/puntoVenta/obtenerDatosSucursal';
                axios.get(url).then(function(response) {
                    var respuesta = response.data;
                    me.arraySucursal = respuesta.sucursales;
                    console.log("SUCURSALES", me.arraySucursal);
            })
            .catch(function(error) {
                console.log(error);
            });
            },

            registrarPuntoVenta(){
                if(this.validarPuntoVenta()){
                    return;
                }

                let me = this;

                axios.post('/puntoVenta/habilitar', {
                    'nombre' : me.nombre,
                    'descripcion' : me.descripcion,
                    'nit' : me.nit,
                    'idtipopuntoventa' : me.idtipopuntoventa,
                    'idsucursal' : me.idsucursal,
                }).then(function(response){
                    var data = response.data;
                    if(!isNaN(data)){
                        swal(
                                'PUNTO DE VENTA REGISTRADO',
                                'Su código del Punto de Venta es: ' + data,
                                'success'
                            );

                        axios.post('/puntoVenta/registrar', {
                        'nombre' : me.nombre,
                        'descripcion' : me.descripcion,
                        'nit' : me.nit,
                        'idtipopuntoventa' : me.idtipopuntoventa,
                        'idsucursal' : me.idsucursal
                    }).then(function(response) {
                        me.cerrarModal();
                        me.listarPuntoVenta(1, '', 'id');
                    }).catch(function(error){
                        console.log(error);
                        swal('ERROR AL REGISTRAR EL PUNTO DE VENTA', 'Revise los errores', 'error');
                    })
                    }else{
                        swal(
                            'ERROR AL REGISTRAR EL PUNTO DE VENTA', 
                            data, 
                            'warning'
                        );
                    }
                }).catch(function(error){
                    console.log(error);
                    swal('ERROR AL REGISTRAR EL PUNTO DE VENTA', 'Intente de Nuevo', 'error');
                });
            },

            cerrarPuntoVenta(id, idsucursal, nit){
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
                    if(result.value){
                        let me = this;

                        axios.post('/puntoVenta/cerrar', {
                            'codigoPuntoVenta': id,
                            'codigoSucursal': idsucursal,
                            'nit': nit
                        }).then(function(response) {
                            var data = response.data;
                            if(!isNaN(data)){
                                swal(
                                    'PUNTO DE VENTA CERRADO CON ÉXITO',
                                    'Su código del Punto de Venta es: ' + data,
                                    'success'
                                )
                                axios.put('/puntoVenta/cambioEstado', {
                                'id': id
                            }).then(function(response) {
                                me.listarPuntoVenta(1, '', 'id');
                            }).catch(function(error){
                                console.log(error);
                            });
                            }else{
                                swal(
                                    'ERROR AL CERRAR EL PUNTO DE VENTA:',
                                    data,
                                    'warning'
                                )
                            }
                        })
                        .catch(function(error) {
                            console.log(error);
                            swal('INTENTE DE NUEVO', 'Comunicación con SIAT fallida', 'error');                            
                        });
                    }
                })
                
            },

            validarPuntoVenta(){
                this.errorPuntoVenta=0;
                this.errorMostrarMsjPuntoVenta =[];

                if (!this.nombre) this.errorMostrarMsjPuntoVenta.push("El nombre del Punto de Venta no puede estar vacío.");

                if (this.errorMostrarMsjPuntoVenta.length) this.errorPuntoVenta = 1;

                return this.errorPuntoVenta;
            },

            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nombre='';
                this.descripcion='';
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "puntoVenta":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Punto de Venta';
                                this.nombre= '';
                                this.descripcion = '';
                                this.nit = '';
                                this.idtipopuntoventa = 0;
                                this.idsucursal = 0;
                                this.tipoAccion = 1;
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarPuntoVenta(1,this.buscar,this.criterio);
            this.verificarComunicacion();
            this.cuis();
            this.cufd();
            this.obtenerDatosTipoPuntoVenta();
            this.obtenerDatosSucursal();
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