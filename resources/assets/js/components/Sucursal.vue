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
          <i class="fa fa-align-justify"></i> Sucursales
          <button type="button" @click="abrirModal('sucursal','registrar')" class="btn btn-secondary">
              <i class="icon-plus"></i>&nbsp;Nuevo
            </button>        </div>
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
                <input type="text" v-model="buscar" @keyup.enter="listarSucursal(1, buscar, criterio)"
                  class="form-control" placeholder="Texto a buscar">
                <button type="submit" @click="listarSucursal(1, buscar, criterio)" class="btn btn-primary"><i
                    class="fa fa-search"></i> Buscar</button>
              </div>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
              <thead class="thead-dark">
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
            <ul class="pagination mt-2">
              <li class="page-item" v-if="pagination.current_page > 1">
                <a class="page-link" href="#"
                  @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Anterior</a>
              </li>
              <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page"></a>
              </li>
              <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                <a class="page-link" href="#"
                  @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Siguiente</a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!-- Inicio del modal agregar/actualizar -->
    <div class="modal" tabindex="-1" :class="{ 'mostrar': modal }" role="dialog" aria-labelledby="myModalLabel"
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

          <div class="modal-body" >
            <div>
                <div class="form-group row">
                  <div class="col-md-8">
                    <label for="" class="font-weight-bold">Nombre de la sucursal <span
                        class="text-danger">*</span></label>
                    <input type="text" v-model="datosFormulario.nombre" class="form-control"
                      placeholder="Ej. Sucursal CODENSA - Punto A"  :class="{ 'is-invalid': errores.nombre }"  @input="validarCampo('nombre')"/>
                    <p class="text-danger" v-if="errores.nombre">{{ errores.nombre }}</p>
                  </div>
   
                </div>

                <div class="form-group row">
                  <div class="col-md-6">
                    <label for="" class="font-weight-bold">Correo electronico <span class="text-danger">*</span></label>
                    <input type="text" v-model="datosFormulario.correo" class="form-control"
                      placeholder="Ej. nombre@dominio.com" :class="{ 'is-invalid': errores.correo }" @input="validarCampo('correo')"/> 
                    <p class="text-danger" v-if="errores.correo">{{ errores.correo }}</p>
                  </div>
                  <div class="col-md-6">
                    <label for="" class="font-weight-bold">Telefono <span class="text-danger">*</span></label>
                    <input type="number" v-model="datosFormulario.telefono" class="form-control" placeholder="Ej. 123456789" :class="{ 'is-invalid': errores.telefono }"  @input="validarCampo('telefono')"/>
                    <p class="text-danger" v-if="errores.telefono">{{ errores.telefono }}</p>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <label for="" class="font-weight-bold">Dirección <span class="text-danger">*</span></label>
                    <input v-model="datosFormulario.direccion" class="form-control" placeholder="Ej. Calle XXX-XXX, Barrio YYY" :class="{ 'is-invalid': errores.direccion }" @input="validarCampo('direccion')"/>
                    <p class="text-danger" v-if="errores.direccion">{{ errores.direccion }}</p>
                  </div>
                  <div class="col-md-6">
                    <label for="" class="font-weight-bold">Departamento <span class="text-danger">*</span></label>
                    <div >
                      <select class="form-control" v-model="datosFormulario.departamento" :class="{ 'is-invalid': errores.departamento }" @change="validarCampo('departamento')">
                        <option value="" disabled selected>Seleccione</option>
                        <option v-for="departamento in arrayDepartamentos" :key="departamento" :value="departamento"
                          v-text="departamento"></option>
                      </select>
                    </div>
                    <p class="text-danger" v-if="errores.departamento">{{ errores.departamento }}</p>
                  </div>

                </div>
                <p v-if="tipoAccion == 1"><strong>Código de Sucursal:</strong> {{ codigoSucursal }}</p>
                <p v-if="tipoAccion == 2"><strong>Código de Sucursal:</strong> {{ datosFormulario.codigoSucursal }}</p>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" @click="cerrarModal()">Cerrar</button>
            <button type="submit" v-if="tipoAccion == 1" class="btn btn-success"
              >Guardar</button>
            <button type="submit" v-if="tipoAccion == 2" class="btn btn-success"
            >Actualizar</button>
          </div>
        </form>

        </div>
      </div>
    </div>
    <!-- Fin del modal -->
  </main>
</template>
  
<script>
import { esquemaSucursal } from '../constants/validations';
export default {
  data() {
    return {
      datosFormulario: {
        nombre: '',
        direccion: '',
        correo: '',
        telefono: '',
        departamento: '',
      },
      errores: {},

      codigoSucursal: 1,
      arraySucursal: [],
      arrayDepartamentos: ["Beni", "Chuquisaca", "Cochabamba", "La Paz", "Oruro", "Pando", "Potosí", "Santa Cruz", "Tarija"],
      modal: 0,
      tituloModal: '',
      tipoAccion: 0,
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
      arrayEmpresa: [],
      buscar: ''
    }
  },
  computed: {
    isActived: function () {
      return this.pagination.current_page;
    },
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
        await esquemaSucursal.validateAt(campo, this.datosFormulario);
        this.errores[campo] = null;
      } catch (error) {
        this.errores[campo] = error.message;
      }
    },
    async enviarFormulario() {
     
      await esquemaSucursal.validate(this.datosFormulario, { abortEarly: false })
        .then(() => {
          if (this.tipoAccion == 2) {
            this.actualizarSucursal(this.datosFormulario);
          } else {
            this.datosFormulario.codigoSucursal = this.codigoSucursal
            this.registrarSucursal(this.datosFormulario);
          }
        })
        .catch((error) => {
          const erroresValidacion = {};
          error.inner.forEach((e) => {
            erroresValidacion[e.path] = e.message;
          });

          this.errores = erroresValidacion;
        });
    },

    listarSucursal(page, buscar, criterio) {
      let me = this;
      var url = '/sucursal?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
      axios.get(url).then(function (response) {
        var respuesta = response.data;
        me.arraySucursal = respuesta.sucursales.data;
        me.pagination = respuesta.pagination;
      })
        .catch(function (error) {
          console.log(error);
        });
    },
    cambiarPagina(page, buscar, criterio) {
      let me = this;
      me.pagination.current_page = page;
      me.listarSucursal(page, buscar, criterio);
    },

    obtenerUltimoCodigoSucursal() {
      axios.get('/ruta-api-para-obtener-ultimo-codigo-sucursal')
        .then(response => {
          const ultimoCodigo = response.data.ultimoCodigo;
          this.codigoSucursal = ultimoCodigo + 1;
        })
        .catch(error => {
          console.error('Error al obtener el último código de sucursal:', error);
        });
    },

    registrarSucursal(datos) {
      axios.post('/sucursal/registrar', datos)
        .then((response) => {
          swal(
            'SUCURSAL REGISTRADA',
            'Correctamente',
            'success'
          );
          this.cerrarModal();
          this.listarSucursal(1, '', 'nombre');
        })
        .catch((error) => {
          console.log(error);
          swal(
            'ERROR AL REGISTRAR LA NUEVA SUCURSAL',
            'Intente de nuevo',
            'error'
          );
        });
    },


    actualizarSucursal(datos) {

      axios.put('/sucursal/actualizar', datos)
        .then((response) => {
          swal(
            'SUCURSAL ACTUALIZADA',
            'Correctamente',
            'success'
          );
          this.cerrarModal();
          this.listarSucursal(1, '', 'nombre');
        })
        .catch((error) => {
          console.log(error);
          swal(
            'ERROR AL ACTUALIZAR LA NUEVA SUCURSAL',
            'Intente de nuevo',
            'error'
          );
        });
    },
    desactivarSucursal(id) {
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

          axios.put('/sucursal/desactivar', {
            'id': id
          }).then(function (response) {
            me.listarSucursal(1, '', 'nombre');
            swal(
              'Desactivado!',
              'El registro ha sido desactivado con éxito.',
              'success'
            )
          }).catch(function (error) {
            console.log(error);
          });


        } else if (
          result.dismiss === swal.DismissReason.cancel
        ) {

        }
      })
    },
    activarSucursal(id) {
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

          axios.put('/sucursal/activar', {
            'id': id
          }).then(function (response) {
            me.listarSucursal(1, '', 'nombre');
            swal(
              'Activado!',
              'El registro ha sido activado con éxito.',
              'success'
            )
          }).catch(function (error) {
            console.log(error);
          });


        } else if (
          result.dismiss === swal.DismissReason.cancel
        ) {

        }
      })
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = '';
      this.errores = {};
    },
    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "sucursal":
          {
            switch (accion) {
              case 'registrar':
                {
                  this.modal = 1;
                  this.tituloModal = 'Registrar sucursal';
                  this.datosFormulario = {
                    nombre: '',
                    direccion: '',
                    correo: '',
                    telefono: '',
                    departamento: '',
                  },
                  this.tipoAccion = 1;
                  this.obtenerUltimoCodigoSucursal();

                  break;
                }
              case 'actualizar':
                {
                  this.modal = 1;
                  this.tituloModal = 'Actualizar sucursal';
                  this.tipoAccion = 2;
                  this.datosFormulario = {
                    id: data['id'],
                    idempresa: data['idempresa'],
                    nombre: data['nombre'],
                    direccion: data['direccion'],
                    correo: data['correo'],
                    telefono: data['telefono'],
                    departamento: data['departamento'],
                    codigoSucursal: data['codigoSucursal']
                  };
                  break;
                }
            }
          }
      }
    }
  },
  mounted() {
    this.listarSucursal(1, this.buscar, this.criterio);
  }
}
</script>
<style>    
    .modal-content {
        width: 100% !important;
        position: absolute !important;
    }

    .mostrar {
        overflow-y: scroll;

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
