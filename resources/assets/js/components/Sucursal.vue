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
            <i class="fa fa-align-justify"></i> Sucursales
            <button type="button" @click="abrirModal('sucursal','registrar')" class="btn btn-secondary">
              <i class="icon-plus"></i>&nbsp;Nuevo
            </button>
          </div>
          <div class="card-body">
            <div class="form-group row">
              <div class="col-md-6">
                <div class="input-group">
                  <select class="form-control col-md-3" v-model="criterio">
                    <option value="nombre">Nombres</option>
                    <option value="direccion">Dirección</option>
                    <option value="correo">Correo</option>
                    <option value="telefono">Teléfono</option>
                    <option value="departamento">Departamento</option>
                  </select>
                  <input type="text" v-model="buscar" @keyup.enter="listarSucursal(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                  <button type="submit" @click="listarSucursal(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>Acciones</th>
                    <th>Empresa</th>
                    <th>Nombre sucursal</th>
                    <th>Dirección</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Departamento</th>
                    <th>Estado</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="sucursal in arraySucursal" :key="sucursal.id">
                    <td>
                      <button type="button" @click="abrirModal('sucursal','actualizar',sucursal)" class="btn btn-warning btn-sm">
                        <i class="icon-pencil"></i>
                      </button> &nbsp;
                      <template v-if="sucursal.condicion">
                        <button type="button" class="btn btn-danger btn-sm" @click="desactivarSucursal(sucursal.id)">
                          <i class="icon-trash"></i>
                        </button>
                      </template>
                      <template v-else>
                        <button type="button" class="btn btn-info btn-sm" @click="activarSucursal(sucursal.id)">
                          <i class="icon-check"></i>
                        </button>
                      </template>
                    </td>
                    <td v-text="sucursal.nombre_empresa"></td>
                    <td v-text="sucursal.nombre"></td>
                    <td v-text="sucursal.direccion"></td>
                    <td v-text="sucursal.correo"></td>
                    <td v-text="sucursal.telefono"></td>
                    <td v-text="sucursal.departamento"></td>
                    <td>
                      <div v-if="sucursal.condicion">
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
              <ul class="pagination justify-content-center">
                <li class="page-item" v-if="pagination.current_page > 1">
                  <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Anterior</a>
                </li>
                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                  <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                </li>
                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                  <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Siguiente</a>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
      </div>
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
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                  <div class="col-md-9">
                    <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de la sucursal">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                  <div class="col-md-9">
                    <input type="text" v-model="direccion" class="form-control" placeholder="Ingrese la dirección de la sucursal">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="email-input">Correo</label>
                  <div class="col-md-9">
                    <input type="email" v-model="correo" class="form-control" placeholder="Ingrese el correo">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="text-input">Teléfono</label>
                  <div class="col-md-9">
                    <input type="text" v-model="telefono" class="form-control" placeholder="Ingrese el teléfono">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="text-input">Departamento</label>
                  <div class="col-md-9">
                    <select class="form-control" v-model="departamento">
                      <option value="" disabled selected>Seleccione</option>
                      <option v-for="departamento in arrayDepartamentos" :key="departamento" :value="departamento" v-text="departamento"></option>
                    </select>                                    
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="text-input">Código Sucursal</label>
                  <div class="col-md-9">
                    <input type="text" :value="codigoSucursal" class="form-control" readonly>
                  </div>
                </div>
                <div v-show="errorSucursal" class="form-group row div-error">
                  <div class="text-center text-error">
                    <div v-for="error in errorMostrarMsjSucursal" :key="error" v-text="error"></div>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
              <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarSucursal()">Guardar</button>
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
        sucursal_id: 0,
        idempresa : 0,
        nombre_empresa:'',
        nombre : '',
        direccion : '',
        correo : '',
        telefono : '',
        departamento : '',
        codigoSucursal: 1,
        arraySucursal : [],
        arrayDepartamentos:["Beni","Chuquisaca","Cochabamba","La Paz","Oruro","Pando","Potosí","Santa Cruz","Tarija"],
        modal : 0,
        tituloModal : '',
        tipoAccion : 0,
        errorSucursal : 0,
        errorMostrarMsjSucursal : [],
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
    listarSucursal (page,buscar,criterio){
        let me=this;
        var url= '/sucursal?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
        axios.get(url).then(function (response) {
            var respuesta= response.data;
            console.log(respuesta);
            me.arraySucursal = respuesta.sucursales.data;
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
        me.listarSucursal(page,buscar,criterio);
    },

    obtenerUltimoCodigoSucursal() {
      axios.get('/ruta-api-para-obtener-ultimo-codigo-sucursal')
        .then(response => {
          const ultimoCodigo = response.data.ultimoCodigo;
          console.log(ultimoCodigo);
          // Incrementa el último código en 1 para obtener el nuevo código
          this.codigoSucursal = ultimoCodigo + 1;
        })
        .catch(error => {
          console.error('Error al obtener el último código de sucursal:', error);
        });
    },

            registrarSucursal(){
                if (this.validarSucursal()){
                    return;
                }
                
                let me = this;
                let formData = new FormData();

                //formData.append('idempresa', 1);
                formData.append('nombre', this.nombre);

                formData.append('direccion', this.direccion);
                formData.append('correo', this.correo);
                formData.append('telefono', this.telefono);
                formData.append('departamento', this.departamento);
                formData.append('codigoSucursal', this.codigoSucursal);
                console.log(this.departamento);

                axios.post('/sucursal/registrar', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }

                }).then(function (response) {
                    swal(
                      'SUCURSAL REGISTRADA',
                      'Correctamente',
                      'success'
                    )
                    me.cerrarModal();
                    me.listarSucursal(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                    swal(
                        'ERROR AL REGISTRAR LA NUEVA SUCURSAL',
                        'Intente de nuevo',
                        'error');
                });
                
            },

    actualizarSucursal(){
       if (this.validarSucursal()){
            return;
        }
        
        let me = this;

        axios.put('/sucursal/actualizar',{
            'idempresa':this.idempresa,
            'nombre_empresa':this.nombre_empresa,
            'nombre': this.nombre,
            'direccion': this.direccion,
            'correo': this.correo,
            'telefono': this.telefono,
            'departamento': this.departamento,
            'codigoSucursal': this.codigoSucursal,
            'id': this.sucursal_id
        }).then(function (response) {
          swal(
             'SUCURSAL ACTUALIZADA',
              'Correctamente',
              'success'
            )
            me.cerrarModal();
            me.listarSucursal(1,'','nombre');
            me.obtenerUltimoCodigoSucursal();
        }).catch(function (error) {
            console.log(error);
            swal(
              'ERROR AL ACTUALIZAR LA SUCURSAL',
              'Intente de nuevo',
              'error');
        }); 
    },
    desactivarSucursal(id){
       swal({
        title: 'Esta seguro de desactivar esta sucursal?',
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

            axios.put('/sucursal/desactivar',{
                'id': id
            }).then(function (response) {
                me.listarSucursal(1,'','nombre');
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
    activarSucursal(id){
               swal({
                title: 'Esta seguro de activar esta sucursal?',
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

                    axios.put('/sucursal/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarSucursal(1,'','nombre');
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

    validarSucursal(){
        this.errorSucursal=0;
        this.errorMostrarMsjSucursal =[];

        if (!this.nombre) this.errorMostrarMsjSucursal.push("El nombre de la sucursal no puede estar vacío.");
        if (!this.direccion) this.errorMostrarMsjSucursal.push("La direccion de la sucursal no puede estar vacío.");
        if (!this.correo) this.errorMostrarMsjSucursal.push("El correo de la sucursal no puede estar vacío.");
        if (!this.telefono) this.errorMostrarMsjSucursal.push("El telefono de la sucursal no puede estar vacío.");
        if (!this.departamento) this.errorMostrarMsjSucursal.push("El departamento de la sucursal no puede estar vacío.");

        if (this.errorMostrarMsjSucursal.length) this.errorSucursal = 1;

        return this.errorSucursal;
    },
    cerrarModal(){
        this.modal=0;
        this.tituloModal='';
        this.idempresa= 0;
        this.nombre_empresa='';
        this.nombre='';
        this.direccion='';
        this.correo='';
        this.telefono='';

        this.departamento='';
    },
    abrirModal(modelo, accion, data = []){
        switch(modelo){
            case "sucursal":
            {
                switch(accion){
                    case 'registrar':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Registrar sucursal';
                        this.nombre= '';
                        this.direccion='';
                        this.correo='';
                        this.telefono='';
                        this.departamento = '';
                        //this.idempresa=0;

                        // this.departamento = '';

                        this.tipoAccion = 1;
                        break;
                    }
                    case 'actualizar':
                    {
                        //console.log(data);
                        this.modal=1;
                        this.tituloModal='Actualizar sucursal';
                        this.tipoAccion=2;
                        this.sucursal_id=data['id'];
                        this.idempresa=data['idempresa']
                        this.nombre = data['nombre'];
                        this.direccion=data['direccion'];
                        this.correo=data['correo'];
                        this.telefono=data['telefono'];
                        this.departamento= data['departamento'];
                        this.idempresa=data['idempresa'];
                        this.codigoSucursal=data['codigoSucursal'];

                        break;
                    }
                }
            }
        }
        //this.selectEmpresa();
    }
},
mounted() {
    this.listarSucursal(1,this.buscar,this.criterio);
    this.obtenerUltimoCodigoSucursal();
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
