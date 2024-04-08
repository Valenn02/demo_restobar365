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
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link" href="#" :class="{ active: activeTab === 0 }" @click="activeTab = 0">
                                <i class="fa fa-align-justify"></i> Modo de trabajo
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" :class="{ active: activeTab === 1 }" @click="activeTab = 1">
                                <i class="fa fa-cogs"></i> Valores por omisión
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" :class="{ active: activeTab === 2 }" @click="activeTab = 2">
                                <i class="fa fa-file"></i> Datos fiscales
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- contenido1 -->
                <div class="card-body" v-show="activeTab === 0">
                    <div class="row">
                        <div class="col">
                            <label for="yearInput">Gestion:</label>
                            <div class="input-group">
                                <input type="number" id="yearInput" class="form-control" v-model="selectedYear" min="1900"
                                    max="2100">
                                <!-- <div class="input-group-append">
                        <span class="input-group-text">Ejemplo</span>
                    </div> -->
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="opcion2">Codigo productos:</label>
                                <select v-model="codigoProducto" class="form-control">
                                    <option value="00000">00000</option>
                                    <option value="55555">55555</option>
                                    <option value="33333">33333</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="opcion3">Consultas almacenes: </label>

                            <div class="form-group">

                                <label> 
                                    <input type="checkbox" v-model="consultarAlmacenes"
                                        true-value="1"
                                        false-value="0"
                                        >
                                        
                                    {{ consultarAlmacenes }}
                                </label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="opcion2">Limite de descuento:</label>
                                <select id="opcion2" class="form-control" v-model="limiteDescuento">
                                    <option value="Precio mayorista">Precio mayorista</option>
                                    <option value="Precio minorista">Precio minorista</option>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="col">
                            <label for="maximoDescuento">Máximo de descuento:</label>

                            <div class="form-group">
                                <input type="number" class="form-control" id="maximoDescuento" v-model="maximoDescuento" />
                            </div>
                        </div> -->
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="opcion1">Valuación inventario:</label>
                                <select class="form-control" v-model="valuacionInventario">
                                    <option value="Ninguno">Ninguno</option>
                                    <option value="opcion1_valor2">Valor 2</option>
                                    <option value="opcion1_valor3">Valor 3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="opcion2">Backup automático:</label>
                                <select id="opcion2" class="form-control" v-model="backupAutomatico">
                                    <option value="Nunca">Nunca</option>
                                    <option value="opcion2_valor2">Valor 2</option>
                                    <option value="opcion2_valor3">Valor 3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <label for="rutaBackup">Ruta de backup:</label>

                            <div class="form-group">
                                <input class="form-control" type="text" id="rutaBackup" v-model="rutaBackup" />
                            </div>

                            <button type="button" @click="sacarBackupBaseDatos()" class="btn btn-info">
                                <i class="icon-doc"></i>&nbsp;Backup
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="opcion1">Saldos negativos:</label>
                                <select id="opcion1" class="form-control" v-model="saldosNegativos">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="opcion1">Permitir devolución:</label>
                                <select id="opcion1" class="form-control" v-model="devolucion">
                                    <option value="1">Si</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="col">
                            <div class="form-group">
                                <label for="opcion2">Moneda de trabajo:</label>
                                <select id="opcion2" class="form-control" v-model="monedaTrabajo">
                                    <option value="Bs">Bs</option>
                                    <option value="USD">USD</option>
                                </select>
                            </div>
                        </div> -->
                        <!-- <div class="col">
                            <label for="opcion3">Separador de decimales:</label>

                            <div class="form-group">
                                <select id="separadorDecimales" class="form-control" v-model="separadorDecimales">
                                    <option value="Punto">Punto</option>
                                    <option value="Coma">Coma</option>
                                </select>
                            </div>
                        </div> -->
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                           <button type="button" @click="abrirModal('precioss', 'registrar')" class="btn btn-secondary">
                                <i class="icon-plus"></i>&nbsp;Nuevo Precio
                            </button>
                        </div>
                    </div> 

                    <div v-for="precio in precios" :key="precio.id" class="row">
                        <div class="col-md-4">
                            <label>Mostrar:</label>
                            <div class="input-group" style="width: 150px">
                                <select class="form-control" v-model="precio.condicion">
                                    <option :value=1>Sí</option>
                                    <option :value=0>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>Etiqueta Nro de Precio:</label>
                            <div class="input-group" style="width: 200px">
                                <input type="text" class="form-control" placeholder="Porcentaje" :value="precio.nombre_precio">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>% Margen:</label>
                            <div class="input-group" style="width: 60px">
                                <input type="number" class="form-control" :value="precio.porcentage">
                            </div>
                        </div>
                    </div>                

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="opcion1">Mostrar costos:</label>
                                <select id="opcion1" class="form-control" v-model="mostrarCostos">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="opcion2">Mostrar proveedor:</label>
                                <select id="opcion2" class="form-control" v-model="mostrarProveedor">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <label for="opcion3">Mostrar saldos stock:</label>

                            <div class="form-group">
                                <select id="separadorDecimales" class="form-control" v-model="mostrarSaldosStock">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <label for="opcion3">Actualizar Iva:</label>

                            <div class="form-group">
                                <select id="separadorDecimales" class="form-control" v-model="actualizarIVA">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                            </div>
                        </div>
                    </div>



                    <!-- <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="opcion1">Vendedor asignado:</label>
                                <select id="opcion1" class="form-control" v-model="vendedorAsignado">
                                    <option value="No permite cambiar el vendedor">No permite cambiar el vendedor</option>
                                    <option value="opcion1_valor2">Valor 2</option>
                                    <option value="opcion1_valor3">Valor 3</option>
                                </select>
                            </div>
                        </div>
                    </div> -->
                    <!-- <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="opcion1">Permitir devolución:</label>
                                <select id="opcion1" class="form-control" v-model="devolucion">
                                    <option value="1">Si</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div> -->
<!--
                        <div class="col">
                            <div class="form-group">
                                <label for="opcion1">Editar Nro Doc:</label>
                                <select id="opcion1" class="form-control" v-model="editarNroDoc">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label for="opcion1">Registro de cliente obligatorio:</label>
                                <select id="opcion1" class="form-control" v-model="registroClienteObligatorio">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="opcion1">Buscar cliente por codigo:</label>
                                <select id="opcion1" class="form-control" v-model="buscarClientePorCodigo">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                            </div>
                        </div>
-->
                    <!-- </div>  -->


                    <div class="row">
                        <div class="col">
                            <button class="btn btn-primary" @click="guardar()">Guardar</button>
                            <button class="btn btn-secondary" @click="cancelar">Cancelar</button>
                        </div>
                    </div>
                </div>




                <!-- fin del contenido 1 -->
                <!-- contenido 2 -->
                <div class="card-body" v-show="activeTab === 1">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="opcion1">Opción 1:</label>
                                <select id="opcion1" class="form-control" v-model="opcion1">
                                    <option value="">Seleccionar</option>
                                    <option value="opcion1_valor1">Valor 1</option>
                                    <option value="opcion1_valor2">Valor 2</option>
                                    <option value="opcion1_valor3">Valor 3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="opcion2">Opción 2:</label>
                                <select id="opcion2" class="form-control" v-model="opcion2">
                                    <option value="">Seleccionar</option>
                                    <option value="opcion2_valor1">Valor 1</option>
                                    <option value="opcion2_valor2">Valor 2</option>
                                    <option value="opcion2_valor3">Valor 3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="opcion3">Opción 3:</label>
                                <select id="opcion3" class="form-control" v-model="opcion3">
                                    <option value="">Seleccionar</option>
                                    <option value="opcion3_valor1">Valor 1</option>
                                    <option value="opcion3_valor2">Valor 2</option>
                                    <option value="opcion3_valor3">Valor 3</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <button class="btn btn-primary" @click="guardar">Guardar</button>
                            <button class="btn btn-secondary" @click="cancelar">Cancelar</button>
                        </div>
                    </div>
                </div>

                <!-- fin del contenido 2-->
                <!-- contenido 3 -->
                <div class="card-body" v-show="activeTab === 2">
                    <!-- 
               <div class="row">
                   <div class="col">
                       <div class="form-group">
                       <label for="opcion1">Opción 1:</label>
                       <select id="opcion1" class="form-control" v-model="opcion1">
                           <option value="">Seleccionar</option>
                           <option value="opcion1_valor1">Valor 1</option>
                           <option value="opcion1_valor2">Valor 2</option>
                           <option value="opcion1_valor3">Valor 3</option>
                       </select>
                       </div>
                   </div>
                   <div class="col">
                       <div class="form-group">
                       <label for="opcion2">Opción 2:</label>
                       <select id="opcion2" class="form-control" v-model="opcion2">
                           <option value="">Seleccionar</option>
                           <option value="opcion2_valor1">Valor 1</option>
                           <option value="opcion2_valor2">Valor 2</option>
                           <option value="opcion2_valor3">Valor 3</option>
                       </select>
                       </div>
                   </div>
                   <div class="col">
                       <div class="form-group">
                       <label for="opcion3">Opción 3:</label>
                       <select id="opcion3" class="form-control" v-model="opcion3">
                           <option value="">Seleccionar</option>
                           <option value="opcion3_valor1">Valor 1</option>
                           <option value="opcion3_valor2">Valor 2</option>
                           <option value="opcion3_valor3">Valor 3</option>
                       </select>
                       </div>
                   </div>
                </div>
                
                <div class="row">
                   <div class="col">
                       <button class="btn btn-primary" @click="guardar">Guardar</button>
                       <button class="btn btn-secondary" @click="cancelar">Cancelar</button>
                   </div>
                </div> -->
                </div>

                <!-- fin del contenido 3-->
            </div>
        </div>
         <!--Inicio del modal agregar/actualizar-->
         <div class="modal fade" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre Precio(*)</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombre_precio" class="form-control" placeholder="Nombre Precio"> 
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Porcentaje(*)</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="porcentage" class="form-control" placeholder="Valor de porcentaje"> 
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPrecio()">Guardar</button>
                    </div>
                </div>
            </div>
        </div>       

    </main>
</template>
<script>
export default {
    data() {
        return {
            id: 0,
            selectedYear: '',
            codigoProducto: '',
            consultarAlmacenes: '',
            limiteDescuento: '',
            maximoDescuento: 0,
            valuacionInventario: '',
            backupAutomatico: '',
            rutaBackup: '',
            saldosNegativos: '',
            monedaTrabajo: '',
            separadorDecimales: 'Punto',
            etiquetaPrecio1: 'Por mayor',
            mostrarEtiquetaPrecio1: 'No',
            margenEtiquetaPrecio1: 30,
            etiquetaPrecio2: 'Por mayor',
            mostrarEtiquetaPrecio2: 'No',
            margenEtiquetaPrecio2: 30,
            etiquetaPrecio3: 'Por mayor',
            mostrarEtiquetaPrecio3: 'No',
            margenEtiquetaPrecio3: 30,
            etiquetaPrecio4: 'Por mayor',
            mostrarEtiquetaPrecio4: 'No',
            margenEtiquetaPrecio4: 30,
            mostrarCostos: '',
            mostrarProveedor: '',
            mostrarSaldosStock: '',
            actualizarIVA: '',
            vendedorAsignado: '',
            devolucion: '',
            registroClienteObligatorio: '',
            editarNroDoc: '',
            buscarClientePorCodigo: '',
            mensajeCheckBox: '',
            activeTab: 0, // Inicialmente, se muestra el contenido de la primera pestaña
            opcion1:'',
            opcion2:'',
            opcion3:'',
            precios:[],
            // modal: 0,
            modal : 0,
            tituloModal : '',
            tipoAccion : 0,
            nombre_precio : '',
            porcentage : '',
            condicion : 1,
        };
    },
    methods: {
        datosConfiguracion() {
            let me = this;
            var url = '/configuracion/editar';

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                console.log(respuesta);
                me.id = respuesta.configuracionTrabajo.id;
                me.selectedYear = respuesta.configuracionTrabajo.gestion;
                me.codigoProducto = respuesta.configuracionTrabajo.codigoProductos;
                me.consultarAlmacenes = respuesta.configuracionTrabajo.consultasAlmacenes;
                me.limiteDescuento = respuesta.configuracionTrabajo.limiteDescuento;
                me.maximoDescuento = respuesta.configuracionTrabajo.maximoDescuento;
                me.valuacionInventario = respuesta.configuracionTrabajo.valuacionInventario;
                me.backupAutomatico = respuesta.configuracionTrabajo.backupAutomatico;
                me.rutaBackup = respuesta.configuracionTrabajo.rutaBackup;
                me.saldosNegativos = respuesta.configuracionTrabajo.saldosNegativos;
                me.monedaTrabajo = respuesta.configuracionTrabajo.monedaTrabajo;
                me.separadorDecimales = respuesta.configuracionTrabajo.separadorDecimales;
                me.mostrarCostos = respuesta.configuracionTrabajo.mostrarCostos;
                me.mostrarProveedor = respuesta.configuracionTrabajo.mostrarProveedores;
                me.mostrarSaldosStock = respuesta.configuracionTrabajo.mostrarSaldosStock;
                me.actualizarIVA = respuesta.configuracionTrabajo.actualizarIva;
                me.vendedorAsignado = respuesta.configuracionTrabajo.vendedorAsignado;
                me.devolucion = respuesta.configuracionTrabajo.permitirDevolucion;
                me.editarNroDoc = respuesta.configuracionTrabajo.editarNroDoc;
                me.registroClienteObligatorio = respuesta.configuracionTrabajo.registroClienteObligatorio;
                me.buscarClientePorCodigo = respuesta.configuracionTrabajo.buscarClientePorCodigo;
                
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        guardar() {
            
            this.precios.forEach(precio => {
                this.guardarCambios(precio);
            });

            let me = this;

            axios.put('/configuracion/actualizar', {
                'selectedYear': this.selectedYear,
                'codigoProducto': this.codigoProducto,
                'consultarAlmacenes': this.consultarAlmacenes,
                'limiteDescuento': this.limiteDescuento,
                'maximoDescuento': this.maximoDescuento,
                'valuacionInventario': this.valuacionInventario,
                'backupAutomatico': this.backupAutomatico,
                'rutaBackup': this.rutaBackup,
                'saldosNegativos': this.saldosNegativos,
                'monedaTrabajo': this.monedaTrabajo,
                'separadorDecimales': this.separadorDecimales,
                'mostrarCostos': this.mostrarCostos,
                'mostrarProveedor': this.mostrarProveedor,
                'mostrarSaldosStock': this.mostrarSaldosStock,
                'actualizarIVA': this.actualizarIVA,
                'vendedorAsignado': this.vendedorAsignado,
                'devolucion': this.devolucion,
                'editarNroDoc': this.editarNroDoc,
                'registroClienteObligatorio': this.registroClienteObligatorio,
                'buscarClientePorCodigo': this.buscarClientePorCodigo,
                'id': this.id
            }).then(function (response) {
                alert('Datos de configuracion actualizado')
            }).catch(function (error) {
                console.log(error);
            });
        },
        sacarBackupBaseDatos()
        {
            //window.open('/backup', '_blank');
            axios.get('/backup').then(function(response) {
               // alert(response.data.exito);
                if(response.data.exito)
                {
                    swal(
                            'Exito!',
                            response.data.exito,
                            'success'
                        )
                }else{
                    swal(
                            'Error!',
                            response.data.error,
                            'error'
                        )
                }
            })
            .catch(function(error) {
                alert(error);
            });
        },
        cancelar() {
            // Lógica para cancelar la acción
        },
        listarPrecio() {
            let me = this;
            var url = '/preciosactivos';
            axios.get(url).then(function(response) {
                var respuesta = response.data;
                me.precios = respuesta.precio.data.map(precio => ({
                ...precio,
                habilitado: precio.habilitado === 1 // Convertir el valor numérico en un booleano
                }));
            }).catch(function(error) {
                console.log(error);
            });
        },
        registrarPrecio(){
            let me = this;

            axios.post('/precios/registrar',{
                'nombre_precio': this.nombre_precio,
                'porcentage': this.porcentage,
            }).then(function (response) {
                me.cerrarModal();
                console.log(response)
                me.listarPrecio();
                me.datosConfiguracion();
                //me.listarPrecio(1,'','nombre_precio');
            }).catch(function (error) {
                console.log(error);
            });
        },
        guardarCambios(precio) {
            //let me = this;
            let accion = precio.condicion ? 'activar' : 'desactivar';
            axios.put(`/precios/${precio.id}/${accion}`)
        },
        abrirModal(modelo, accion, data = []){
            switch(modelo){
                case "precioss":
                {
                    switch(accion){
                        case 'registrar':
                        {
                            this.modal = 1;
                            this.tituloModal = 'Registrar Precio';
                            this.tipoAccion = 1;
                            this.nombre_precio = '';
                            this.porcentage = '';
                            break;
                        }
                        case 'actualizar':
                        {
                            break;
                        }
                    }
                }
            }
        },
        cerrarModal(){
        this.modal=0;
        this.tituloModal='';
        this.nombre_precio='';
        this.porcentage='';
        },
    },
    mounted() {
        this.datosConfiguracion();
        this.listarPrecio();
    },
};
</script>

<style>

    .nav-link.active {
        background-color: #f8f9fa;
    }
    .mostrar{
    display: list-item !important;
    opacity: 1 !important;
    position: absolute !important;
    background-color: #3c29297a !important;
    }
</style>