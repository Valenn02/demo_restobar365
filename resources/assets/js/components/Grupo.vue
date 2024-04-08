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
                <i class="fa fa-align-justify"></i> Grupo
                <button type="button" @click="abrirModal('grupos','registrar')" class="btn btn-secondary">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control col-md-3" v-model="criterio">
                              <option value="nombre_grupo">Nombre Grupo</option>
                              <!-- <option value="descripcion">Descripción</option> -->
                            </select>
                            <input type="text" v-model="buscar" @keyup.enter="listarGrupo(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                            <button type="submit" @click="listarGrupo(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Nombre Grupo</th>
                            <!-- <th>Estado</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="grupo in arrayGrupo" :key="grupo.id">
                            <td>
                                <button type="button" @click="abrirModal('grupos','actualizar',grupo)" class="btn btn-warning btn-sm">
                                  <i class="icon-pencil"></i>
                                </button> &nbsp;
                                <!-- <template v-if="industria.estado">
                                    <button type="button" class="btn btn-danger btn-sm" @click="desactivarIndustria(industria.id)">
                                        <i class="icon-trash"></i>
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="btn btn-info btn-sm" @click="activarIndustria(industria.id)">
                                        <i class="icon-check"></i>
                                    </button>
                                </template> -->
                            </td>
                            <td v-text="grupo.nombre_grupo"></td>
                            <!-- <td>
                                <div v-if="industria.estado">
                                    <span class="badge badge-success">Activo</span>
                                </div>
                                <div v-else>
                                    <span class="badge badge-danger">Desactivado</span>
                                </div>
                                
                            </td> -->
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
                            <label class="col-md-3 form-control-label" for="text-input">Nombre Grupo(*)</label>
                            <div class="col-md-9">
                                <input type="text" v-model="nombre_grupo" class="form-control" placeholder="Nombre de Grupo"> 
                            </div>
                        </div>
                        <div v-show="errorGrupo" class="form-group row div-error">
                            <div class="text-center text-error">
                                <div v-for="error in errorMostrarMsjGrupo" :key="error" v-text="error"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarGrupo()">Guardar</button>
                    <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarGrupo()">Actualizar</button>
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
        grupo_id: 0,
        nombre_grupo : '',
        arrayGrupo: [],
        modal : 0,
        tituloModal : '',
        tipoAccion : 0,
        errorGrupo : 0,
        errorMostrarMsjGrupo : [],
        pagination : {
            'total' : 0,
            'current_page' : 0,
            'per_page' : 0,
            'last_page' : 0,
            'from' : 0,
            'to' : 0,
        },
        offset : 3,
        criterio : 'nombre_grupo',
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
    listarGrupo (page,buscar,criterio){
        let me=this;
        //var url= '/industria?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
        var url= '/grupos?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
        axios.get(url).then(function (response) {
            var respuesta= response.data;
            me.arrayGrupo = respuesta.grupos.data;
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
        me.listarGrupo(page,buscar,criterio);
    },
    //registrarCategoria(){
    registrarGrupo(){
        if (this.validarGrupo()){
            return;
        }
        let me = this;

        axios.post('/grupos/registrar',{
            'nombre_grupo': this.nombre_grupo           
        }).then(function (response) {
            me.cerrarModal();
            console.log(response)
            me.listarGrupo(1,'','nombre_grupo');
        }).catch(function (error) {
            console.log(error);
        });
    },
    actualizarGrupo(){
       if (this.validarGrupo()){
            return;
        }
        
        let me = this;

        axios.put('/grupos/actualizar',{
            'id' : this.grupo_id,
            'nombre_grupo': this.nombre_grupo
        }).then(function (response) {
            me.cerrarModal();
            me.listarGrupo(1,'','nombre_grupo');
        }).catch(function (error) {
            console.log(error);
        }); 
    },
    
    validarGrupo(){
        this.errorGrupo=0;
        this.errorMostrarMsjGrupo =[];

        if (!this.nombre_grupo) this.errorMostrarMsjGrupo.push("El Campo de nombre_grupo no puede estar vacío.");
        if (this.errorMostrarMsjGrupo.length) this.errorGrupo = 1;

        return this.errorGrupo;
    },
    cerrarModal(){
        this.modal=0;
        this.tituloModal='';
        this.nombre_grupo='';
        //this.descripcion='';
    },
    abrirModal(modelo, accion, data = []){
        switch(modelo){
            case "grupos":
            {
                switch(accion){
                    case 'registrar':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Registrar Grupo';
                        this.tipoAccion = 1;
                        this.nombre_grupo = '';
                        break;
                    }
                    case 'actualizar':
                    {
                        //console.log(data);
                        this.modal=1;
                        this.tituloModal='Actualizar Grupo';
                        this.tipoAccion=2;
                        this.grupo_id=data['id'];
                        this.nombre_grupo = data['nombre_grupo'];
                        break;
                    }
                }
            }
        }
    }
},
mounted() {
    this.listarGrupo(1,this.buscar,this.criterio);
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
