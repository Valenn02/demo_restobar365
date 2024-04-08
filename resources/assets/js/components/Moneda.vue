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
                <i class="fa fa-align-justify"></i> Moneda
                <button type="button" @click="abrirModal('moneda','registrar')" class="btn btn-secondary">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control col-md-3" v-model="criterio">
                              <option value="nombre">Nombre</option>
                              <option value="pais">País</option>
                              <option value="simbolo">Símbolo</option>
                              <option value="compra">Compra</option>
                              <option value="venta">Venta</option>
                            </select>
                            <input type="text" v-model="buscar" @keyup.enter="listarMoneda(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                            <button type="submit" @click="listarMoneda(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Empresa</th>
                            <th>Nombre</th>
                            <th>País</th>
                            <th>Símbolo Venta</th>
                            <th>Compra</th>
                            <th>Venta</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="moneda in arrayMoneda" :key="moneda.id">
                            <td>
                                <button type="button" @click="abrirModal('moneda','actualizar',moneda)" class="btn btn-warning btn-sm">
                                  <i class="icon-pencil"></i>
                                </button> &nbsp;
                                <template v-if="moneda.activo">
                                    <button type="button" class="btn btn-danger btn-sm" @click="desactivarMoneda(moneda.id)">
                                        <i class="icon-trash"></i>
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="btn btn-info btn-sm" @click="activarMoneda(moneda.id)">
                                        <i class="icon-check"></i>
                                    </button>
                                </template>
                            </td>
                            <td v-text="moneda.nombre_empresa"></td>
                            <td v-text="moneda.nombre"></td>
                            <td v-text="moneda.pais"></td>
                            <td v-text="moneda.simbolo"></td>
                            <td v-text="moneda.compra"></td>
                            <td v-text="moneda.venta"></td>
                            <td>
                                <div v-if="moneda.activo">
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
                            <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                            <div class="col-md-9">
                                <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de la moneda">                                        
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">País</label>
                            <div class="col-md-9">
                                <input type="text" v-model="pais" class="form-control" placeholder="País que pertenece la moneda">                                        
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Símbolo</label>
                            <div class="col-md-9">
                                <input type="text" v-model="simbolo" class="form-control" placeholder="Símbolo de la moneda">                                        
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="number-input">Compra</label>
                            <div class="col-md-9">
                                <input type="number" v-model="compra" class="form-control" step=".01" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="number-input">Venta</label>
                            <div class="col-md-9">
                                <input type="number" v-model="venta" class="form-control" step=".01" placeholder="">
                            </div>
                        </div>

                        <div v-show="errorMoneda" class="form-group row div-error">
                            <div class="text-center text-error">
                                <div v-for="error in errorMostrarMsjMoneda" :key="error" v-text="error">

                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarMoneda()">Guardar</button>
                    <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarMoneda()">Actualizar</button>
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
import VueBarcode from 'vue-barcode';
export default {
data (){
    return {
        moneda_id: 0,
        idempresa : 0,
        nombre_empresa : '',
        nombre : '',
        pais : 0,
        simbolo : 0,
        compra : '',
        venta : '',
        arrayMoneda : [],
        modal : 0,
        tituloModal : '',
        tipoAccion : 0,
        errorMoneda : 0,
        errorMostrarMsjMoneda : [],
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
        arrayEmpresa :[],
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
    listarMoneda (page,buscar,criterio){
        let me=this;
        var url= '/moneda?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
        axios.get(url).then(function (response) {
            var respuesta= response.data;
            me.arrayMoneda = respuesta.monedas.data;
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
        me.listarMoneda(page,buscar,criterio);
    },
    registrarMoneda(){
        if (this.validarMoneda()){
            return;
        }
        
        let me = this;
        let formData = new FormData();

        formData.append('nombre', this.nombre);
        formData.append('pais', this.pais);
        formData.append('simbolo', this.simbolo);
        formData.append('compra', this.compra);
        formData.append('venta', this.venta);

        axios.post('/moneda/registrar', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }

        }).then(function (response) {
            me.cerrarModal();
            me.listarMoneda(1,'','nombre');
        }).catch(function (error) {
            alert("La moneda ya existe, no se puede registrar");

            console.log(error);
        });
        
    },
    actualizarMoneda(){
       if (this.validarMoneda()){
            return;
        }
        
        let me = this;

        axios.put('/moneda/actualizar',{
            'idempresa': this.idempresa,
            'nombre_empresa': this.nombre_empresa,
            'nombre': this.nombre,
            'pais': this.pais,
            'simbolo': this.simbolo,
            'compra': this.compra,
            'venta': this.venta,
            'activo':this.activo,
            'id': this.moneda_id
        }).then(function (response) {
            me.cerrarModal();
            me.listarMoneda(1,'','nombre');
        }).catch(function (error) {
            console.log(error);
        }); 
    },
    desactivarMoneda(id){
       swal({
        title: 'Esta seguro de desactivar esta moneda?',
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

            axios.put('/moneda/desactivar',{
                'id': id
            }).then(function (response) {
                me.listarMoneda(1,'','nombre');
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
    activarMoneda(id){
       swal({
        title: 'Esta seguro de activar esta moneda?',
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

            axios.put('/moneda/activar',{
                'id': id
            }).then(function (response) {
                me.listarMoneda(1,'','nombre');
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
    validarMoneda(){
        this.errorMoneda=0;
        this.errorMostrarMsjMoneda =[];
        console.log(this.nombre);
        if (this.nombre=="") this.errorMostrarMsjMoneda.push("El nombre de la moneda no puede estar vacío.");
        if (this.simbolo=="") this.errorMostrarMsjMoneda.push("El simbolo de la moneda no puede estar vacío.");
        if (this.pais=="") this.errorMostrarMsjMoneda.push("El nombre del pais no puede estar vacío.");
        
        if (!this.compra) this.errorMostrarMsjMoneda.push("El precio de compra de la moneda debe ser un número y no puede estar vacío.");
        if (!this.venta) this.errorMostrarMsjMoneda.push("El precio de venta de la moneda debe ser un número y no puede estar vacío.");
        if (this.errorMostrarMsjMoneda.length) this.errorMoneda = 1;

        return this.errorMoneda;
    },
    cerrarModal(){
        this.modal=0;
        this.tituloModal='';
        this.idempresa= 0;
        this.nombre_empresa = '';
        this.nombre = '';
        this.pais = '';
        this.simbolo = '';
        this.compra = 0;
        this.venta = 0;
        this.errorMoneda=0;
    },
    abrirModal(modelo, accion, data = []){
        switch(modelo){
            case "moneda":
            {
                switch(accion){
                    case 'registrar':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Registrar Moneda';
                        this.nombre= '';
                        this.pais='';
                        this.simbolo='';
                        this.compra=0;
                        this.venta = 0;
                        this.tipoAccion = 1;
                        break;
                    }
                    case 'actualizar':
                    {
                        this.modal=1;
                        this.tituloModal='Actualizar Moneda';
                        this.tipoAccion=2;
                        this.moneda_id=data['id'];
                        this.idempresa=data['idempresa'];
                        this.nombre = data['nombre'];
                        this.pais=data['pais'];
                        this.simbolo=data['simbolo'];
                        this.compra= data['compra'];
                        this.venta=data['venta'];
                        
                        break;
                    }
                }
            }
        }
    }
},
mounted() {
    this.listarMoneda(1,this.buscar,this.criterio);
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
