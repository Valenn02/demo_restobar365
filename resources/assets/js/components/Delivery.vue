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
                <i class="fa fa-align-justify"></i> Deliverys
                <button type="button" @click="abrirModal('delivery','registrar')" class="btn btn-secondary">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control col-md-3" v-model="criterio">
                              <option value="nombre">Nombre</option>
                              <option value="num_documento">Teléfono</option>
                            </select>
                            <input type="text" v-model="buscar" @keyup="listarDelivery(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">

                <table class="table table-bordered table-striped table-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="delivery in arrayDelivery" :key="delivery.id">
                            <td>
                                <button type="button" @click="abrirModal('delivery','actualizar', delivery)" class="btn btn-warning btn-sm">
                                  <i class="icon-pencil"></i>
                                </button>&nbsp;
                                <template v-if="delivery.estado">
                                <button type="button" class="btn btn-danger btn-sm"
                                    @click="desactivarDelivery(delivery.id)">
                                    <i class="icon-trash"></i>
                                </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="btn btn-info btn-sm"
                                        @click="activarDelivery(delivery.id)">
                                        <i class="icon-check"></i>
                                    </button>
                                </template>
                            </td>
                            <td v-text="delivery.nombre"></td>
                            <td v-text="delivery.telefono"></td>
                            <td>
                                <div v-if="delivery.estado">
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
    </div>
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
                            <div class="col-md-6">
                                <label for="nombre" class="font-weight-bold">Nombre del Delivery <span
                                    class="text-danger">*</span></label>
                                    <input type="text" v-model="nombre" class="form-control" id="nombre" placeholder="Ej. Juan Pérez">                                        
                            </div>
                            <div class="col-md-6">
                                <label for="telefono" class="font-weight-bold">Teléfono <span
                                    class="text-danger">*</span></label>
                                    <input type="text" v-model="telefono" class="form-control" id="telefono" placeholder="Ej. 7XXXXXX">                                        
                            </div>
                        </div>
                        <div v-show="errorDelivery" class="form-group row div-error">
                            <div class="text-center text-error">
                                <div v-for="error in errorMostrarMsjDelivery" :key="error" v-text="error">

                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarDelivery()">Guardar</button>
                    <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarDelivery()">Actualizar</button>
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
        telefono : '',
        arrayDelivery : [],
        modal : 0,
        tituloModal : '',
        tipoAccion : 0,
        errorDelivery : 0,
        errorMostrarMsjDelivery : [],
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
        buscar : ''
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
    listarDelivery (page,buscar,criterio){
        let me=this;
        var url= '/delivery?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
        axios.get(url).then(function (response) {
            var respuesta= response.data;
            me.arrayDelivery = respuesta.deliverys.data;
            me.pagination= respuesta.pagination;
        })
        .catch(function (error) {
            console.log(error);
        });
    },
    cambiarPagina(page,buscar,criterio){
        let me = this;
        //Actualiza la página actual
        me.pagination.current_page = page;
        //Envia la petición para visualizar la data de esa página
        me.listarPersona(page,buscar,criterio);
    },
    registrarDelivery(){
        if (this.validarDelivery()){
            return;
        }
        
        let me = this;

        axios.post('/delivery/registrar',{
            'nombre': this.nombre,
            'telefono' : this.telefono
        }).then(function (response) {
            swal(
            'REGISTRO ÉXITOSO',
            'Delivery Añadido',
            'success'
            );
            me.cerrarModal();
            me.listarDelivery(1,'','nombre');
        }).catch(function (error) {
            swal(
            'REGISTRO FALLIDO',
            'Intente de Nuevo',
            'warning'
        );
            console.log(error);
        });
    },
    actualizarDelivery(){
       if (this.validarDelivery()){
            return;
        }
        
        let me = this;

        axios.put('/delivery/actualizar',{
            'nombre': this.nombre,
            'telefono' : this.telefono,
            'id': this.id
        }).then(function (response) {
            swal(
            'ACTUALIZACIÓN ÉXITOSA',
            'Delivery Actualizado',
            'success'
            );
            me.cerrarModal();
            me.listarDelivery(1,'','nombre');
        }).catch(function (error) {
            swal(
            'ACTUALIZACIÓN FALLIDA',
            'Intente de Nuevo',
            'warning'
        );
            console.log(error);
        }); 
    },            
    validarDelivery(){
        this.errorDelivery=0;
        this.errorMostrarMsjDelivery =[];

        if (!this.nombre) this.errorMostrarMsjDelivery.push("El nombre del delivery no puede estar vacío.");
        if (!this.telefono) this.errorMostrarMsjDelivery.push("El teléfono del delivery no puede estar vacío.");

        if (this.errorMostrarMsjDelivery.length) this.errorDelivery = 1;

        return this.errorDelivery;
    },
    cerrarModal(){
        this.modal=0;
        this.tituloModal='';
        this.nombre='';
        this.telefono='';
        this.errorDelivery=0;

    },
    abrirModal(modelo, accion, data = []){
        switch(modelo){
            case "delivery":
            {
                switch(accion){
                    case 'registrar':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Registrar Delivery';
                        this.nombre= '';
                        this.telefono='';
                        this.tipoAccion = 1;
                        break;
                    }
                    case 'actualizar':
                    {
                        //console.log(data);
                        this.modal=1;
                        this.tituloModal='Actualizar Delivery';
                        this.tipoAccion=2;
                        this.id=data['id'];
                        this.nombre = data['nombre'];
                        this.telefono = data['telefono'];
                        break;
                    }
                }
            }
        }
    },

    desactivarDelivery(id) {
    swal({
        title: 'Esta seguro de desactivar este delivery?',
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

            axios.put('/delivery/desactivar', {
                'id': id
            }).then(function (response) {
                me.listarDelivery(1, '', 'nombre');
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

activarDelivery(id) {
    swal({
        title: 'Esta seguro de activar este delivery?',
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

            axios.put('/delivery/activar', {
                'id': id
            }).then(function (response) {
                me.listarDelivery(1, '', 'nombre');
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
},
mounted() {
    this.listarDelivery(1,this.buscar,this.criterio);
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