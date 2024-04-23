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
                <i class="fa fa-align-justify"></i> Apertura/Cierre de Caja
                <button type="button" @click="abrirModal('caja','registrar')" class="btn btn-secondary">
                    <i class="icon-plus"></i>&nbsp;Abrir Caja
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">

                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Fecha Apertura</th>
                            <th>Fecha Cierre</th>
                            <th>Saldo Inicial</th>
                            <th>Ventas Totales</th>
                            <th>Ventas al Contado</th>
                            <th>Ventas a Credito </th>
                            <th>Pagos en Efectivo Ventas</th>
                            <th>Pagos de Cuotas</th>
                            <th>Pagos de Cuotas Efectivo</th>
                            <th>Saldo de faltante </th>
                            <th>Depósitos Extras</th>
                            <th>Salidas Extras</th>
                            <th>Saldo Caja</th>
                            <th>Estado</th>
                            <th>Acciones</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(caja, index) in arrayCaja" :key="caja.id">
                            <td v-text="index + 1"></td>
                            <td v-text="caja.fechaApertura" width="90"></td>
                            <td v-text="caja.fechaCierre"></td>
                            <td v-text="caja.saldoInicial"></td>
                          
                            <td v-text="caja.ventas"></td>
                            <td v-text="caja.ventasContado"></td>
                            <td v-text="caja.ventasCredito"></td>
                            <td v-text="caja.pagosEfectivoVentas"></td>
                            <td v-text="caja.cuotasventasCredito"></td>
                            <td v-text="caja.PagoCuotaEfectivo"></td>
                            <td v-text="caja.saldoFaltante"></td>
                           
                            <td v-text="caja.depositos"></td>
                            <td v-text="caja.salidas"></td>
                            <td v-text="caja.saldoCaja"></td>
                            <td>
                                <div v-if="caja.estado">
                                    <span class="badge badge-success">ABIERTO</span>
                                </div>
                                <div v-else>
                                    <span class="badge badge-danger">CERRADO</span>
                                </div>        
                            </td>
                            <td>
                                <template v-if = caja.estado>
                                        <template v-if="!mostrarBotonesSecundarios">
                                        <button type="button" @click="abrirModal2('cajaDepositar', 'depositar', caja)" class="btn btn-primary btn-sm">
                                            <i class="icon-plus"></i>
                                        </button> &nbsp;

                                        <button type="button" @click="abrirModal3('cajaRetirar', 'retirar', caja)" class="btn btn-danger btn-sm">
                                            <i class="icon-minus"></i>
                                        </button> &nbsp;

                                        <button type="button" @click="abrirModal4('cajaVer', 'ver', caja.id)" class="btn btn-warning btn-sm">
                                            <i class="icon-eye"></i>
                                        </button> &nbsp;

                                        <button type="button" @click="abrirModal5('arqueoCaja', 'contar', caja.id)" class="btn btn-success btn-sm">
                                            <i class="icon-calculator"></i>
                                        </button> &nbsp;
                                        
                                        </template>

                                        <template v-else>
                                        <button type="button" @click="abrirModal4('cajaVer', 'ver', caja.id)" class="btn btn-warning btn-sm">
                                            <i class="icon-eye"></i>
                                        </button> &nbsp;

                                        <button type="button" class="btn btn-danger btn-sm" @click="cerrarCaja(caja.id)">
                                            <i class="icon-lock"></i>
                                        </button>
                                        </template>
                                </template>

                                <template v-else>
                                    <button type="button" @click="" class="btn btn-success btn-sm">
                                        <i class="icon-printer"></i>
                                    </button>

                                    <button type="button" @click="" class="btn btn-danger btn-sm">
                                        <i class="icon-printer"></i>
                                    </button>
                                </template>

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
    <!--Inicio del modal aperturar caja-->
    <div class="modal " tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                                    <label class="col-md-3 form-control-label" for="text-input">Saldo Inicial</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="saldoInicial" class="form-control" placeholder="0.00">   
                                    </div>
                                </div>
                                <div v-show="errorCaja" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjCaja" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarCaja()">Guardar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
    <!--Fin del modal-->
    <!--Inicio del modal Depósitos-->
    <div class="modal " tabindex="-1" :class="{'mostrar' : modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal2"></h4>
                            <button type="button" class="close" @click="cerrarModal2()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Importe</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="depositos" class="form-control" placeholder="0.00">   
                                    </div>
                                
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Descripcion de Importe</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="Desdepositos" class="form-control" placeholder="Descripcion">   
                                    </div>
                                
                                </div>
                                <div v-show="errorCaja" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjCaja" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal2()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="depositar()">Depositar <i class="icon-arrow-right"></i></button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

            <!--Inicio del modal Salidas-->
    <div class="modal " tabindex="-1" :class="{'mostrar' : modal3}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal3"></h4>
                            <button type="button" class="close" @click="cerrarModal3()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Importe</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="salidas" class="form-control" placeholder="0.00">   
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Descripcion de Importe</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="Dessalidas" class="form-control" placeholder="Descripcion">   
                                    </div>
                                </div>
                                <div v-show="errorCaja" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjCaja" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal3()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==3" class="btn btn-primary" @click="retirar()">Retirar <i class="icon-arrow-right"></i></button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

            <!--Inicio del modal Ver Transacciones-->
            <div class="modal " tabindex="-1" :class="{ 'mostrar': modal4 }" role="dialog" aria-labelledby="myModalLabel"
            style="display: none; overflow-y: auto" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content m-2">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal4"></h4>
                        <button type="button" class="close" @click="cerrarModal4()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <b-tabs content-class="mt-3">
                                <b-tab title="Compras Realizadas (egresos)" active>

                                    <TransaccionErgeso v-if="egreso" :data="egreso" />
                                    <!-- <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>N°</th>
                                                    <th>Fecha</th>
                                                    <th>Proveedor</th>
                                                    <th>Tipo Comprobante</th>
                                                    <th>Número Comprobante</th>
                                                    <th>importe</th>
                                                    <th>Usuario</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-if="ArrayEgresos.length <= 0">
                                                    <td colspan="100">
                                                        Parece que aún no has realizado ningún egreso.
                                                    </td>
                                                </tr>
                                                <tr v-for="(ingreso, index) in ArrayEgresos" :key="ingreso.id">
                                                    <td v-text="index + 1"></td>
                                                    <td v-text="ingreso.fecha_hora"></td>
                                                    <td v-text="ingreso.nombre"></td>
                                                    <td v-text="ingreso.tipo_comprobante"></td>
                                                    <td v-text="ingreso.num_comprobante"></td>
                                                    <td v-text="ingreso.total"></td>
                                                    <td v-text="ingreso.usuario"></td>

                                                </tr>
                                            </tbody>
                                        </table>
                                    </div> -->
                                </b-tab>
                                <b-tab title="Ventas Realizadas (ingresos)">
                                    <div class="table-responsive">
                                        <TransaccionIngreso v-if="ingreso" :data="ingreso" />

                                        <!-- <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>N°</th>
                                                    <th>Fecha</th>
                                                    <th>Cliente</th>
                                                    <th>Tipo Comprobante</th>
                                                    <th>Número Comprobante</th>
                                                    <th>importe</th>
                                                    <th>Usuario</th>


                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-if="ArrayIngresos.length <= 0">
                                                    <td colspan="100">
                                                        Parece que aún no has realizado ningún ingreso.
                                                    </td>
                                                </tr>
                                                <tr v-for="(venta, index) in ArrayIngresos" :key="venta.id">
                                                    <td v-text="index + 1"></td>
                                                    <td v-text="venta.fecha_hora"></td>
                                                    <td v-text="venta.nombre"></td>
                                                    <td v-text="venta.tipo_comprobante"></td>
                                                    <td v-text="venta.num_comprobante"></td>
                                                    <td v-text="venta.total"></td>
                                                    <td v-text="venta.usuario"></td>
                                                </tr>
                                            </tbody>
                                        </table> -->
                                    </div>
                                </b-tab>
                                <b-tab title="Transacciones Extras">
                                    <TransaccionExtra v-if="extra" :data="extra" />
                                    <!-- <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>N°</th>
                                                    <th>Fecha</th>
                                                    <th>Transacción</th>
                                                    <th>Importe</th>
                                                    <th>Usuario</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-if="arrayTransacciones.length <= 0">
                                                    <td colspan="100">
                                                        Parece que aún no has realizado ninguna transacción.
                                                    </td>
                                                </tr>
                                                <tr v-for="(transacciones, index) in arrayTransacciones"
                                                    :key="transacciones.id">
                                                    <td v-text="index + 1"></td>
                                                    <td v-text="transacciones.fecha" width="90"></td>
                                                    <td v-text="transacciones.transaccion"></td>
                                                    <td v-text="transacciones.importe"></td>
                                                    <td v-text="transacciones.usuario"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div> -->
                                </b-tab>
                            </b-tabs>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal4()">Cerrar</button>
                        </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
            <!--Fin del modal-->

            <!--Inicio del modal Arqueo de Caja-->
    <div class="modal " tabindex="-1" :class="{'mostrar' : modal5}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal5"></h4>
                            <button type="button" class="close" @click="cerrarModal5()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4>Billetes</h4>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Bs. 200:</label>
                                            <div class="col-sm-8">
                                                <input type="number" v-model="billete200" class="form-control form-control-sm" placeholder="0">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Bs. 100:</label>
                                            <div class="col-sm-8">
                                                <input type="number" v-model="billete100" class="form-control form-control-sm" placeholder="0">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Bs. 50:</label>
                                            <div class="col-sm-8">
                                                <input type="number" v-model="billete50" class="form-control form-control-sm" placeholder="0">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Bs. 20:</label>
                                            <div class="col-sm-8">
                                                <input type="number" v-model="billete20" class="form-control form-control-sm" placeholder="0">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Bs. 10:</label>
                                            <div class="col-sm-8">
                                                <input type="number" v-model="billete10" class="form-control form-control-sm" placeholder="0">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Total Billetes Bs.</label>
                                            <div class="col-sm-8">
                                                <span v-text="totalBilletes" class="font-weight-bold"></span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <h4>Monedas</h4>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Bs. 5:</label>
                                            <div class="col-sm-8">
                                                <input type="number" v-model="moneda5" class="form-control form-control-sm" id="" name="" placeholder="0">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Bs. 2:</label>
                                            <div class="col-sm-8">
                                                <input type="number" v-model="moneda2" class="form-control form-control-sm" id="" name="" placeholder="0">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Bs. 1:</label>
                                            <div class="col-sm-8">
                                                <input type="number" v-model="moneda1" class="form-control form-control-sm" id="" name="" placeholder="0">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Bs. 0.50:</label>
                                            <div class="col-sm-8">
                                                <input type="number" v-model="moneda050" class="form-control form-control-sm" id="" name="" placeholder="0">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Bs. 0.20:</label>
                                            <div class="col-sm-8">
                                                <input type="number" v-model="moneda020" class="form-control form-control-sm" id="" name="" placeholder="0">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Bs. 0.10:</label>
                                            <div class="col-sm-8">
                                                <input type="number" v-model="moneda010" class="form-control form-control-sm" id="" name="" placeholder="0">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Total Monedas Bs.</label>
                                        <div class="col-sm-8">
                                            <span v-text="totalMonedas" class="font-weight-bold"></span>
                                        </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Total Efectivo Bs.</label>
                                        <div class="col-sm-8">
                                            <strong>{{ totalEfectivo }}</strong>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" @click="cerrarModal5()">Cancelar</button>
                            <button type="button" v-if="tipoAccion==5" class="btn btn-primary" @click="registrarArqueo()">Guardar</button>
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
import TransaccionErgeso from "./Tables/TransaccionEgreso.vue";
import TransaccionIngreso from "./Tables/TransaccionIngreso.vue";
import TransaccionExtra from "./Tables/TransaccionExtra.vue";

export default {
data (){
    return {
        id: 0,
        idsucursal : 0,
        nombre_sucursal:'',
        idusuario : 0,
        usuario : '',
        fechaApertura : '',
        fechaCierre : '',
        saldoInicial : '',
        depositos : '',
        Desdepositos:'',
        salidas : '',
        Dessalidas : '',
        ventasContado : '',
        ventasCredito : '',
        comprasContado : '',
        comprasCredito : '',
        saldoFaltante : '',
        PagoCuotaEfectivo : '',
        saldoCaja : '',
        arqueo_id: 0,
        billete200 : 0,
        billete100 : 0,
        billete50 : 0,
        billete20 : 0,
        billete10 : 0,
        totalBilletes: 0,
        moneda5 : 0,
        moneda2 : 0,
        moneda1 : 0,
        moneda050 : 0,
        moneda020 : 0,
        moneda010 : 0,
        totalMonedas : 0,
        arrayCaja : [],
        arrayTransacciones: [],
        ArrayIngresos:[],
        ArrayEgresos:[],
        egreso: null,
        ingreso: null,
        extra: null,
        modal : 0,
        modal2 : 0,
        modal3 : 0,
        modal4 : 0,
        modal5 : 0,
        tituloModal : '',
        tituloModal2: '',
        tituloModal3: '',
        tituloModal4: '',
        tituloModal5: '',
        tipoAccion : 0,
        arqueoRealizado : false,
        errorCaja : 0,
        errorMostrarMsjCaja : [],
        pagination : {
            'total' : 0,
            'current_page' : 0,
            'per_page' : 0,
            'last_page' : 0,
            'from' : 0,
            'to' : 0,
        },
        offset : 3,
        arraySucursal :[],
        arrayUser :[],
        buscar : '',
        criterio : '',
        mostrarBotonesSecundarios: false
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

    }, 

    totalEfectivo() {
    return this.totalBilletes + this.totalMonedas;
  }
},
methods : {
    listarCaja (page,buscar,criterio){
        let me=this;
        var url= '/caja?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
        axios.get(url).then(function (response) {
            var respuesta= response.data;
            console.log(respuesta);
            me.arrayCaja = respuesta.cajas.data;
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
        me.listarCaja(page,buscar,criterio);
    },
            registrarCaja(){
                if (this.validarCaja()){
                    return;
                }
                
                let me = this;
                let formData = new FormData();

                //formData.append('idempresa', 1);
                formData.append('saldoInicial', this.saldoInicial);

                axios.post('/caja/registrar', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }

                }).then(function (response) {
                    me.cerrarModal();
                    me.listarCaja(1,'','id');
                    swal(
                            'Aperturada!',
                            'Caja aperturada de forma satisfactoria!',
                            'success'
                        )
                }).catch(function (error) {
                    console.log(error);
                });
                
            },
    
        registrarArqueo(){
        let me = this;
        this.mostrarBotonesSecundarios = true;

        axios.post('/caja/arqueoCaja', {
            'idcaja':this.id,
            'billete200': this.billete200,
            'billete100': this.billete100,
            'billete50': this.billete50,
            'billete20': this.billete20,
            'billete10': this.billete10,
            'moneda5': this.moneda5,
            'moneda2': this.moneda2,
            'moneda1': this.moneda1,
            'moneda050': this.moneda050,
            'moneda020': this.moneda020,
            'moneda010': this.moneda010

            }).then(function (response) {
                console.log(response);
                me.cerrarModal5();
                    swal(
                        'Información!',
                        'Conteo de dinero registrado satisfactoriamente!',
                        'success'
                    );
            }).catch(function (error) {
                console.log(error);
            });
    },
    
    depositar(){
        let me = this;

        axios.put('/caja/depositar',{
            'depositos':this.depositos,
            'id':this.id,
            'transaccion':this.Desdepositos+'  (movimiento de ingreso )',

        }).then(function (response) {
            me.cerrarModal2();
            me.listarCaja(1,'','id');
            swal(
                'Información!',
                'Transacción de caja registrada satisfactoriamente!',
                'success'
                )
        }).catch(function (error) {
            console.log(error);
        }); 
    },

    retirar(){
        let me = this;

        axios.put('/caja/retirar',{
            'salidas':this.salidas,
            'transaccion':this.Dessalidas+' (movimiento de egreso  )',
            'id':this.id
        }).then(function (response) {
            me.cerrarModal3();
            me.listarCaja(1,'','id');
            swal(
                'Información!',
                'Transacción de caja registrada satisfactoriamente!',
                'success'
                )
        }).catch(function (error) {
            console.log(error);
        }); 
    },
    calcularTotalBilletes(){
        const billete200 = parseFloat(this.billete200) || 0;
        const billete100 = parseFloat(this.billete100) || 0;
        const billete50 = parseFloat(this.billete50) || 0;
        const billete20 = parseFloat(this.billete20) || 0;
        const billete10 = parseFloat(this.billete10) || 0;

        this.totalBilletes = billete200*200 + billete100*100 + billete50*50 + billete20*20 + billete10*10;
    },

    calcularTotalMonedas(){
        const moneda5 = parseFloat(this.moneda5) || 0;
        const moneda2 = parseFloat(this.moneda2) || 0;
        const moneda1 = parseFloat(this.moneda1) || 0;
        const moneda050 = parseFloat(this.moneda050) || 0;
        const moneda020 = parseFloat(this.moneda020) || 0;
        const moneda010 = parseFloat(this.moneda010) || 0;

        this.totalMonedas = moneda5*5 + moneda2*2 + moneda1*1 + moneda050*0.50 + moneda020*0.20 + moneda010*0.10;
    },
    cerrarCaja(id){
        const total = this.totalEfectivo;
       swal({
        title: 'Esta seguro de cerrar la caja?',
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

            axios.put('/caja/cerrar',{
                'id': id,
                'saldoFaltante':total
            }).then(function (response) {
                me.listarCaja(1,'','id');
                swal(
                'Cerrada!',
                'La caja fue cerrada con éxito.',
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

    cajaAbierta(){
        for (let i = 0; i < this.arrayCaja.length; i++){
            if(this.arrayCaja[i].estado){
                return true;
            }
        }
        return false;
    },
    
    validarCaja(){
        this.errorCaja=0;
        this.errorMostrarMsjCaja =[];

        if (!this.saldoInicial) this.errorMostrarMsjCaja.push("El saldo inicial no puede estar vacío.");

        if (this.errorMostrarMsjCaja.length) this.errorCaja = 1;

        return this.errorCaja;
    },
    cerrarModal(){
        this.modal=0;
        this.tituloModal='';
        this.idsucursal= 0;
        this.sucursal='';
        this.saldoInicial='';
    },
    
    cerrarModal2(){
        this.modal2=0;
        this.depositos='';
        this.Desdepositos='';
    },

    cerrarModal3(){
        this.modal3=0;
        this.salidas='';
        this.Dessalidas='';
    },

    cerrarModal4(){
        this.modal4=0;
    },

    cerrarModal5(){
        this.modal5=0;
    },

    abrirModal(modelo, accion, data = []){
        switch(modelo){
            case "caja":
            {
                switch(accion){
                    case 'registrar':
                    {
                        if(this.cajaAbierta()){
                            swal(
                            'Ya existe una caja abierta!',
                            'Por favor realice el cierre de la caja e intente de nuevo.',
                            'error'
                            )
                            return;
                        }

                        this.modal = 1;
                        this.tituloModal = 'Apertura de Caja Sucursal: ';
                        this.saldoInicial= '';

                        this.tipoAccion = 1;
                        break;
                    }
                }
            }
        }
    },
    
    abrirModal2(modelo, accion, data = []){
        switch(modelo){
            case "cajaDepositar":
            {
                switch(accion){
                    case 'depositar':
                    {
                        this.modal2 = 1;
                        this.tituloModal2='Depositar Dinero';
                        this.id=data['id'];

                        this.tipoAccion=2;

                        break;
                    }
                }
            }
        }
    },

    abrirModal3(modelo, accion, data = []){
        switch(modelo){
            case "cajaRetirar":
            {
                switch(accion){
                    case 'retirar':
                    {
                        this.modal3 = 1;
                        this.tituloModal3='Retirar Dinero';
                        this.id=data['id'];

                        this.tipoAccion=3;

                        break;
                    }
                }
            }
        }
    },

    abrirModal4(modelo, accion, id){
        switch(modelo){
            case "cajaVer":
            {
                switch(accion){
                    case 'ver':
                    {
                        this.modal4 = 1;
                        this.tituloModal4 = 'Transacciones Caja';

                        let me=this;
                        var url= '/transacciones/' + id;
                        axios.get(url).then(function (response) {
                            var respuesta= response.data;
                            
                            console.log(respuesta);
                            me.arrayTransacciones = respuesta.transacciones.data;
                            // me.pagination= respuesta.pagination;
                            me.ArrayEgresos=respuesta.ingresos;
                            me.ArrayIngresos=respuesta.ventas.data;

                            me.egreso = respuesta.ingresos;
                            me.ingreso = respuesta.ventas;
                            me.extra = respuesta.transacciones;
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                        
                        break;
                    }
                }
            }
        }
    },

    abrirModal5(modelo, accion, id){
        switch(modelo){
            case "arqueoCaja":
            {
                switch(accion){
                    case 'contar':
                    {
                        this.modal5 = 1;
                        this.tituloModal5 = 'Arqueo de Caja';
                        this.id=id;
                        this.tipoAccion = 5;
                        break;
                    }
                }
            }
        }
    }
}, 

watch: {
    'billete200': 'calcularTotalBilletes',
    'billete100': 'calcularTotalBilletes',
    'billete50': 'calcularTotalBilletes',
    'billete20': 'calcularTotalBilletes',
    'billete10': 'calcularTotalBilletes',
    'moneda5': 'calcularTotalMonedas',
    'moneda2': 'calcularTotalMonedas',
    'moneda1': 'calcularTotalMonedas',
    'moneda050': 'calcularTotalMonedas',
    'moneda020': 'calcularTotalMonedas',
    'moneda010': 'calcularTotalMonedas'
},

mounted() {
    this.listarCaja(1,this.buscar,this.criterio);
    
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
