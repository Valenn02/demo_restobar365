<template>
    <main class="main">
        <!-- Breadcrumb -->
        
            <!-- Ejemplo de tabla Listado -->
        <Panel header="Ventas Fuera de Línea" >
            <span class="badge bg-secondary" id="comunicacionSiat" style="color: white;" v-show="mostrarComunicacionSiat">Desconectado</span>
                    <span class="badge bg-secondary" id="cuis" v-show="mostrarCuis">CUIS: Inexistente</span>
                    <span class="badge bg-secondary" id="cufd" v-show="mostrarCufd">No existe cufd vigente</span>
                    <span class="badge bg-secondary" id="direccion" v-show="mostrarDireccion">No hay dirección
                        registrada</span>
                    <span class="badge bg-primary" id="cufdValor" v-show="mostrarCUFD">No hay CUFD</span>
                    <span class="badge bg-primary" id="codigoControl" v-show="mostrarCodigoControl">No hay Codigo Control</span>
            <template #icons>
                    
                <Button label="Nueva Venta" icon="pi pi-plus" class="p-button-sm p-button-raised p-button-success" @click="mostrarDetalle" :style="buttonStyle"/>
            </template>

                
                <!-- Listado-->
                <template v-if="listado == 1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-3">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                        <option value="tipo_comprobante">Tipo Comprobante</option>
                                        <option value="num_comprobante">Número Comprobante</option>
                                        <option value="fecha_hora">Fecha-Hora</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup="listarVenta(1, buscar, criterio)"
                                        class="form-control" placeholder="Texto a buscar">
                                    <!--button type="submit" @click="listarVenta(1, buscar, criterio)" class="btn btn-primary"><i
                                            class="fa fa-search"></i> Buscar</button-->
                                </div>
                            </div>
                        </div>
                        <div class="spinner-container" v-if="mostrarSpinner">
                            <div class="spinner-message"><strong>REGISTRANDO VENTA...</strong></div>
                            <TileSpinner color="blue"/>

                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Cliente</th>
                                        <th>Documento</th>
                                        <th>Número Factura</th>
                                        <th>Fecha Hora</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(venta, index) in arrayVenta" :key="venta.id">
                                    <td>
                                    <button type="button" @click="verVenta(venta.id)" class="btn btn-success btn-sm">
                                        <i class="icon-eye"></i>
                                    </button> &nbsp;

                                    <template v-if="venta.estado == 'Registrado'">
                                        <button type="button" class="btn btn-danger btn-sm" @click="desactivarVenta(venta.id)">
                                        <i class="icon-trash"></i>
                                        </button> &nbsp;
                                    </template>

                                    <button type="button" @click="imprimirTicket(venta.idventa)" class="btn btn-info btn-sm" >
                                        Imprimir Ticket
                                    </button>
                                    <button type="button" @click="enviarPaquete()" class="btn btn-success btn-sm" v-if="index === 0 && mostrarEnviarPaquete">
                                        Enviar Paquete
                                    </button>   
                                    <button v-if="index === 0 && mostrarValidarPaquete" type="button" @click="validarPaquete()" class="btn btn-warning btn-sm">
                                        Validar Paquete
                                    </button>

                                    </td>
                                    <td v-text="venta.id"></td>
                                    <td v-text="venta.usuario"></td>
                                    <td v-text="venta.razonSocial"></td>
                                    <td v-text="venta.documentoid"></td>
                                    <td v-text="venta.num_comprobante"></td>
                                    <td v-text="venta.fecha_hora"></td>
                                    <td v-text="venta.total"></td>
                                    <td>
                                        <a @click="verificarFactura(venta.cuf, venta.numeroFactura)" target="_blank" class="btn btn-info"><i class="icon-note"></i></a>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary" type="button" @click="imprimirFactura(venta.id, venta.correo)"><i class="icon-printer"></i></button>
                                            <button class="btn btn-danger" type="button" @click="anularFactura(venta.id, venta.cuf)"><i class="icon-close"></i></button>
                                    </td>
                                </tr>
                                </tbody>

                            </table>
                        </div>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page"
                                    :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)"
                                        v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </template>
                <!--Fin Listado-->
                <!-- Detalle-->
                <template v-else-if="listado == 0">

                    <div>
                        <DataView :value="arrayMenu" layout="grid" :paginator="true" :rows="23">
                            <template #grid="slotProps">
                                <div class="product-container" style="padding-right: 6px; padding-left: 6px; padding-bottom: 10px;">
                                <Button class="p-button-text product-button" type="button" @click="agregarDetalleModal(slotProps.data)">
                                    <Card class="project-card" @click="console.log('Producto seleccionado:')">
                                        <template #header>
                                            <div class="image-container">
                                                <img :src="'/img/menu/' + slotProps.data.fotografia" alt="Product Image" class="product-image">
                                            </div>
                                        </template>

                                        <template #title>
                                            <div class="product-name">{{ truncateAndCapitalize(slotProps.data.nombre) }}</div>
                                        </template>
                                        
                                        <template #footer>
                                            <div class="footer-content">
                                                <div class="price">Bs {{ slotProps.data.precio_venta }}</div>
                                                <Button icon="pi pi-pencil" class="p-button-sm p-button-warning rounded-bottom-right" @click.stop="visibleRight = true" />
                                            </div>
                                        </template>
                                    </Card>
                                </Button>
                                </div>
                            </template>

                            <template #empty>Menu vacio</template>
                        </DataView>
                    </div>

                    <div>
                        <DataView :value="arrayProductos" layout="grid" :paginator="true" :rows="23">
                            <template #grid="slotProps">
                                <div class="product-container" style="padding-right: 6px; padding-left: 6px; padding-bottom: 10px;">
                                <Button class="p-button-text product-button" type="button" @click="agregarDetalleModal(slotProps.data)">
                                    <Card class="project-card" @click="console.log('Producto seleccionado:')">
                                        <template #header>
                                            <div class="image-container">
                                                <img :src="'/img/articulo/' + slotProps.data.fotografia" alt="Product Image" class="product-image">
                                            </div>
                                        </template>

                                        <template #title>
                                            <div class="product-name">{{ truncateAndCapitalize(slotProps.data.nombre) }}</div>
                                        </template>
                                        
                                        <template #footer>
                                            <div class="footer-content">
                                                <div class="price">Bs {{ slotProps.data.precio_venta }}</div>
                                                <Button icon="pi pi-pencil" class="p-button-sm p-button-warning rounded-bottom-right" @click.stop="visibleRight = true" />
                                            </div>
                                        </template>
                                    </Card>
                                </Button>
                                </div>
                            </template>

                            <template #empty>Menu vacio</template>
                        </DataView>
                    </div>

                        <div class="floating-buttons">
                            <Button class="p-button-lg p-button-success floating-button" @click.stop="visibleFull = true">
                                <i class="pi pi-shopping-cart" style="font-size: 3rem" ></i>
                                <Badge :value="arrayDetalle.length" size="large" severity="danger"></Badge>
                            </Button>
                        </div>
                        
                        <Sidebar :visible.sync="visibleFull" :baseZIndex="1000" position="full">
                            <template #header>
                                <div class="sidebar-full">
                                    <i class="pi pi-shopping-cart" style="font-size: 2rem" ></i>
                                    <h4>Carrito</h4>
                                </div>
                            </template>

                            <div class="col-md-4 " style="max-width: none ;margin: 0 auto;">
                            <h6 class="mt-3">DATOS PARA LA FACTURACIÓN</h6>
                            <div class="form-group row border">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for=""><strong>Razón Social(*)</strong></label>
                                        <input type="text" id="cliente" class="form-control" placeholder="Nombre del Cliente" v-model="cliente" ref="nombreRef">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label><strong>Documento</strong></label>
                                        <input type="text" id="documento" class="form-control" placeholder="Número de Documento" v-model="documento" @keyup.enter="fetchClienteData" ref="documentoRef">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><strong>Correo Electrónico</strong></label>
                                        <input type="text" id="email" class="form-control" placeholder="Ingrese su Correo electrónico" v-model="email" ref="email">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="" class="font-weight-bold">Casos especiales</label>
                                    <div class="form-check" style="margin-left: 20px;">
                                        <input class="form-check-input" type="checkbox" v-model="casosEspeciales" id="casosEspecialesCheckbox" @change="habilitarNombreCliente">
                                        <label class="form-check-label" for="casosEspecialesCheckbox">Habilitar</label>
                                    </div>
                                </div>
                            <div class="col-md-3">
                                <div class="form-group" v-if="scodigorecepcion === 5 || scodigorecepcion === 6 || scodigorecepcion === 7">
                                    <label for="" class="font-weight-bold">Código CAFC</label>
                                    <input type="text" id="cafc" class="form-control" v-model="cafc" ref="cafc">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group" v-if="scodigorecepcion === 5 || scodigorecepcion === 6 || scodigorecepcion === 7">
                                    <label for="" class="font-weight-bold">Fecha y Hora</label>
                                    <input type="datetime-local" id="fecha" class="form-control" v-model="fecha" ref="fecha">
                                </div>
                            </div>
                            </div>
                        </div>    

                        <div class="col-md-4 " style="max-width: none ;margin: 0 auto;">
                            <h6 class="mt-3">DETALLES DE LA VENTA</h6>
                            <div class="form-group row border">
                                <div class="col-md-3">
                                    <div v-show="!paraLlevar" class="form-group">
                                        <label for=""><strong>Mesero(*)</strong></label>
                                        <input type="text" id="mesero" class="form-control" placeholder="Nombre del Mesero"
                                        v-model="usuario_autenticado" ref="mesero" readonly>
                                    </div>
                                </div>
                                <input type="hidden" id="tipo_documento" class="form-control" readonly value="5">
                                <input type="hidden" id="complemento_id" class="form-control" v-model="complemento_id" ref="complementoIdRef" readonly>
                                <input type="hidden" id="usuarioAutenticado" class="form-control" v-model="usuarioAutenticado" readonly>
                                <input type="hidden" id="idAlmacen" class="form-control" readonly value="1">
                                <input type="hidden" id="complemento_id" class="form-control" v-model="complemento_id"
                                ref="complementoIdRef" readonly>
                                <input type="hidden" id="puntoVentaAutenticado" class="form-control" v-model="puntoVentaAutenticado"
                                readonly>

                                <div  v-show="!paraLlevar" class="col-md-2">
                                    <div class="form-group">
                                        <label><strong>Num Mesa(*)</strong></label>
                                        <input type="number" id="mesa" class="form-control" v-model="mesa">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><strong>Número Ticket</strong></label>
                                        <input type="text" id="num_comprobante" class="form-control" v-model="num_comprob" :readonly="scodigorecepcion !== 5 && scodigorecepcion !== 6 && scodigorecepcion !== 7">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><strong>Tipo Comprobante(*)</strong></label>
                                        <select class="form-control" v-model="tipo_comprobante" ref="tipoComprobanteRef">
                                            <option value="0">Seleccione</option>
                                            <option value="TICKET">Ticket</option>
                                            <option value="FACTURA">Factura</option>
                                            <option value="BOLETA">Boleta</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="font-weight-bold">Para llevar:
                                            <span class="text-danger">*</span>

                                        </label>
                                    </div>
                                    <div><InputSwitch v-model="paraLlevar" style="transform: scale(0.75);"/></div>
                                </div>

                                <div class="col-md-12">
                                    <div v-show="errorVenta" class="form-group row div-error">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMostrarMsjVenta" :key="error" v-text="error"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row border">
                                <div class="table-responsive col-md-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Opciones</th>
                                                <th>Artículo</th>
                                                <th>Cantidad</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>

                                        <tbody v-if="arrayDetalle.length">
                                            <tr v-for="(detalle, index) in arrayDetalle" :key="detalle.id">
                                                <td>
                                                    <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                                        <i class="icon-close"></i>
                                                    </button>
                                                </td>
                                                <td v-text="detalle.articulo">
                                                </td>
                                                <td>
                                                    <span style="color:red;" v-show="detalle.cantidad > detalle.stock">Stock: {{ detalle.stock }}</span>
                                                    <input v-model="detalle.cantidad" type="number" class="form-control" @change="actualizarArrayProductos(index)">
                                                </td>
                                                <td>
                                                    {{ (detalle.precio * detalle.cantidad - detalle.descuento).toFixed(2) }}
                                                </td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="3" align="right"><strong>Sub Total: Bs.</strong></td>
                                                <td id="subTotal">{{ totalParcial=(calcularSubTotal).toFixed(2) }}</td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="3" align="right"><strong>Descuento Adicional: Bs.</strong></td>
                                                <input id="descuentoAdicional" v-model="descuentoAdicional" type="number"
                                                    class="form-control">
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="3" align="right"><strong>Total Neto: Bs.</strong></td>
                                                <td id="montoTotal">{{ total=(calcularTotal).toFixed(2) }}</td>
                                            </tr>
                                        </tbody>

                                        <tbody v-else>
                                            <tr>
                                                <td colspan="6">
                                                    No hay articulos agregados
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="button" @click.stop="visibleFull = false" class="btn btn-secondary">Atrás</button>
                                    <button type="button" class="btn btn-primary" @click.stop="visiblePago = true" >Pagar</button>
                                </div>
                            </div>
                        </div>
                        </Sidebar>

                        <Sidebar :visible.sync="visibleRight" :baseZIndex="1000" position="right">
                            <h3>Right Sidebar</h3>
                        </Sidebar>

                        <Sidebar :visible.sync="visiblePago" :baseZIndex="1000" position="full">
                        <template #header>
                            <div class="sidebar-full">
                                <i class="pi pi-money-bill" style="font-size: 2rem"></i>
                                <h4>Detalle de Pago</h4>
                            </div>
                        </template>

                        <TabView>
                            <TabPanel header="Efectivo">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="card shadow-sm">
                                                <div class="card-body">

                                                    <form>
                                                        <div class="form-group">
                                                            <label for="montoEfectivo"><i class="fa fa-money mr-2"></i>
                                                                Monto
                                                                Recibido:</label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        {{
                                                                        monedaVenta[1] }}
                                                                    </span>
                                                                </div>
                                                                <input type="number" class="form-control"
                                                                    id="montoEfectivo" v-model="recibido"
                                                                    placeholder="Ingrese el monto recibido">
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="cambioRecibir"><i
                                                                    class="fa fa-exchange mr-2"></i>
                                                                Cambio a Entregar:</label>
                                                            <input type="text" class="form-control" id="cambioRecibir"
                                                                placeholder="Se calculará automáticamente"
                                                                :value="recibido - calcularTotal.toFixed(2)"
                                                                readonly>
                                                        </div>


                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="card shadow-sm">
                                                <div class="card-body">
                                                    <div class=" mb-3">
                                                        <h5 class="mb-0"> Detalle
                                                            de Venta
                                                        </h5>

                                                    </div>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span><i class="fa fa-dollar mr-2"></i> Monto Total:</span>
                                                        <span class="font-weight-bold">
                                                            {{ total=(calcularTotal).toFixed(2) }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span><i class="fa fa-tag mr-2 text-success"></i>
                                                            Descuento:</span>
                                                        <span class="font-weight-bold text-success">0.00</span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="codigoDescuento"><i class="fa fa-gift mr-2"></i>
                                                            Código de Descuento Gift Card:</label>
                                                        <div class="input-group mb-3">
                                                            <input type="number" class="form-control" id="descuentoGiftCard" v-model="descuentoGiftCard" min="0">    
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="numeroTarjeta"><i class="fa fa-credit-card mr-2"></i> Número de Tarjeta:</label>
                                                        <div class="input-group mb-3">
                                                            <input type="text" class="form-control" id="numeroTarjeta" v-model="numeroTarjeta" placeholder="Ingrese el número de tarjeta">
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="d-flex justify-content-between">
                                                        <span><i class="fa fa-money mr-2"></i> Total a Pagar:</span>
                                                        <span class="font-weight-bold h5">
                                                            {{ total=(calcularTotal).toFixed(2) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" @click="aplicarDescuento" 
                                            class="btn btn-success btn-block"><i class="fa fa-check mr-2"></i> 
                                            Registrar Pago</button>
                                        </div>
                                    </div>
                                </div>
                            </TabPanel>

                            <TabPanel header="Gift Card">
                                <div>
                                    <div class="mt-4">
                                        <form>
                                            <div class="form-group">
                                                <label for="descuentoGiftCard"><i class="fa fa-tag mr-2"></i> Monto de la Gift Card:</label>
                                                <div class="input-group mb-3">
                                                    <input type="number" class="form-control" id="descuentoGiftCard" v-model="descuentoGiftCard" min="0">
                                                </div>
                                            </div>
                                            <button type="button" @click="registrarVenta(27)" class="btn btn-success btn-block"><i class="fa fa-check mr-2"></i> Confirmar</button>
                                        </form>
                                    </div>
                                </div>
                            </TabPanel>

                            <TabPanel header="Tarjeta">
                                <div>
                                    <div class="mt-4">
                                        <form>
                                            <div class="form-group">
                                                <label for="numeroTarjeta"><i class="fa fa-credit-card mr-2"></i> Número de Tarjeta:</label>
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" id="numeroTarjeta" v-model="numeroTarjeta" placeholder="Ingrese el número de tarjeta">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="codigoDescuento"><i class="fa fa-gift mr-2"></i>
                                                    Código de Descuento Gift Card:</label>
                                                <div class="input-group mb-3">
                                                    <input type="number" class="form-control" id="descuentoGiftCard" v-model="descuentoGiftCard" min="0">    
                                                </div>
                                            </div>
                                            <button type="button" @click="aplicarCombinacion" class="btn btn-success btn-block"><i class="fa fa-check mr-2"></i> Confirmar</button>
                                        </form>
                                    </div>
                                </div>
                            </TabPanel>

                            <TabPanel header="QR">
                                <div class="d-flex justify-content-center align-items-center">
                                    <div>
                                        <label for="alias">Alias:</label>
                                        <InputText v-model="alias" />
                                        <br>
                                        <label for="montoQR">Monto:</label>
                                        <span class="font-weight-bold">
                                                            {{ total=(calcularTotal).toFixed(2) }}</span>                                        <br>      
                                        <Button @click="generarQr" label="Generar QR" />
                                        
                                        <!-- Espacio para mostrar la imagen del código QR -->
                                        <div v-if="qrImage">
                                            <img :src="qrImage" alt="Código QR" />
                                        </div>

                                        <!-- Botón para verificar estado -->
                                        <Button @click="verificarEstado" v-if="qrImage" label="Verificar Estado de Pago" />

                                        <!-- Mostrar estado de transacción -->
                                        <div v-if="estadoTransaccion" class="p-card p-p-2">
                                            <div class="p-text-bold">Estado Actual:</div>
                                            <div>
                                                <Badge :value="estadoTransaccion.objeto.estadoActual" :severity="badgeSeverity" />
                                            </div>
                                        </div>

                                        <button type="button" @click="registrarVenta(7)" class="btn btn-success btn-block"><i class="fa fa-check mr-2"></i> Confirmar</button>

                                    </div>
                                </div>
                            </TabPanel>

                            <TabPanel header="Otros">
                                <div>
                                <div class="mt-4">
                                    <form>
                                    <div class="form-group">
                                        <label for="otroMetodoPago"><i class="fa fa-tag mr-2"></i> Seleccione un Método de Pago:</label>
                                        <div class="input-group mb-3">
                                        <select class="custom-select" id="otroMetodoPago" v-model="metodoPago">
                                            <option value="">Seleccione...</option>
                                            <option value="32">BILLETERA MOVIL</option>
                                            <option value="81">BILLETERA MOVIL – PAGO ONLINE</option>
                                            <option value="31">CANAL DE PAGO</option>
                                            <option value="79">CANAL DE PAGO – BILLETERA MOVIL</option>
                                            <option value="80">CANAL DE PAGO – PAGO ONLINE</option>
                                            <option value="294">CANAL DE PAGO – BILLETERA MOVIL  – PAGO ONLINE</option>
                                            <option value="3">CHEQUE</option>
                                            <option value="51">CHEQUE – BILLETERA</option>
                                            <option value="213">CHEQUE – BILLETERA MOVIL  – PAGO ONLINE</option>
                                            <option value="50">CHEQUE – CANAL PAGO</option>
                                            <option value="211">CHEQUE – CANAL PAGO - BILLETERA MOVIL</option>
                                            <option value="212">CHEQUE – CANAL PAGO - PAGO ONLINE</option>
                                            <option value="47">CHEQUE – DEPOSITO</option>
                                            <option value="202">CHEQUE – DEPOSITO EN CUENTA - BILLETERA MOVIL</option>
                                            <option value="201">CHEQUE – DEPOSITO EN CUENTA - CANAL DE PAGO</option>
                                            <option value="203">CHEQUE – DEPOSITO EN CUENTA - PAGO ONLINE</option>
                                            <option value="199">CHEQUE – DEPOSITO EN CUENTA - TRANSFERENCIA SWIFT</option>
                                            <option value="38">EFECTIVO – PAGO ONLINE</option>
                                            <option value="39">TARJETA – PAGO POSTERIOR</option>
                                            <option value="191">CHEQUE – PAGO POSTERIOR - BILLETERA MOVIL</option>
                                            <option value="190">CHEQUE – PAGO POSTERIOR - CANAL DE PAGO</option>
                                            <option value="187">CHEQUE – PAGO POSTERIOR - DEPOSITO EN CUENTA</option>
                                            <option value="192">CHEQUE – PAGO POSTERIOR - PAGO ONLINE</option>
                                            <option value="186">CHEQUE – PAGO POSTERIOR - TRANSFERENCIA BANCARIA</option>
                                            <option value="188">CHEQUE – PAGO POSTERIOR - TRANSFERENCIA SWIFT</option>
                                            <option value="48">CHEQUE – SWIFT</option>
                                            <option value="206">CHEQUE – SWIFT - BILLETERA MOVIL</option>
                                            <option value="207">CHEQUE – SWIFT - PAGO ONLINE</option>
                                            <option value="208">CHEQUE – GIFT - CANAL DE PAGO</option>
                                            <option value="46">CHEQUE – TRANSFERENCIA BANCARIA</option>
                                            <option value="197">CHEQUE – TRANSFERENCIA BANCARIA – BILLETERA MOVIL</option>
                                            <option value="196">CHEQUE – TRANSFERENCIA BANCARIA – CANAL DE PAGO</option>
                                            <option value="193">CHEQUE – TRANSFERENCIA BANCARIA – DEPOSITO EN CUENTA</option>
                                            <option value="198">CHEQUE – TRANSFERENCIA BANCARIA – PAGO ONLINE</option>
                                            <option value="194">CHEQUE – TRANSFERENCIA BANCARIA – TRANSFERENCIA SWIFT</option>
                                            <option value="44">CHEQUE – VALES</option>
                                            <option value="178">CHEQUE – VALES - PAGO POSTERIOR</option>
                                            <option value="179">CHEQUE – VALES - TRANSFERENCIA BANCARIA</option>
                                            <option value="180">CHEQUE – VALES - DEPOSITO EN CUENTA</option>
                                            <option value="181">CHEQUE – VALES - TRANSFERENCIA SWIFT</option>
                                            <option value="183">CHEQUE – VALES - CANAL DE PAGO</option>
                                            <option value="184">CHEQUE – VALES - BILLETERA MOVIL</option>
                                            <option value="185">CHEQUE – VALES - PAGO ONLINE</option>
                                            <option value="295">DEBITO AUTOMATICO</option>
                                            <option value="296">DEBITO AUTOMATICO – EFECTIVO</option>
                                            <option value="297">DEBITO AUTOMATICO -TARJETA</option>
                                            <option value="298">DEBITO AUTOMATICO – CHEQUE</option>
                                            <option value="299">DEBITO AUTOMATICO - VALE</option>
                                            <option value="300">DEBITO AUTOMATICO - PAGO POSTERIOR</option>
                                            <option value="301">DEBITO AUTOMATICO - TRANSFERENCIA BANCARIA</option>
                                            <option value="302">DEBITO AUTOMATICO - DEPOSITO EN CUENTA</option>
                                            <option value="303">DEBITO AUTOMATICO - TRANSFERENCIA SWIFT</option>
                                            <option value="304">DEBITO AUTOMATICO - GIFT CARD</option>
                                            <option value="305">DEBITO AUTOMATICO - CANAL DE PAGO</option>
                                            <option value="306">DEBITO AUTOMATICO - BILLETERA MOVIL</option>
                                            <option value="307">DEBITO AUTOMATICO - PAGO ONLINE</option>
                                            <option value="308">DEBITO AUTOMATICO – OTRO</option>
                                            <option value="8">DEPOSITO EN CUENTA</option>
                                            <option value="71">DEPOSITO EN CUENTA – PAGO ONLINE</option>
                                            <option value="276">DEPOSITO EN CUENTA – SWIFT – CANAL DE PAGO</option>
                                            <option value="277">DEPOSITO EN CUENTA – SWIFT – BILLETERA MOVIL</option>
                                            <option value="278">DEPOSITO EN CUENTA – SWIFT – PAGO ONLINE</option>
                                            <option value="282">DEPOSITO EN CUENTA – CANAL DE PAGO – BILLETERA MOVIL</option>
                                            <option value="70">DEPOSITO EN CUENTA – BILLETERA MOVIL</option>
                                            <option value="284">DEPOSITO EN CUENTA – BILLETERA MOVIL – PAGO ONLINE</option>
                                            <option value="69">DEPOSITO EN CUENTA – CANAL DE PAGO</option>
                                            <option value="283">DEPOSITO EN CUENTA – CANAL DE PAGO – PAGO ONLINE</option>
                                            <option value="5">OTROS</option>
                                            <option value="33">PAGO ONLINE</option>
                                            <option value="6">PAGO POSTERIOR</option>
                                            <option value="62">PAGO POSTERIOR – BILLETERA</option>
                                            <option value="259">PAGO POSTERIOR – BILLETERA MOVIL - PAGO ONLINE</option>
                                            <option value="61">PAGO POSTERIOR – CANAL</option>
                                            <option value="257">PAGO POSTERIOR – CANAL DE PAGO - BILLETERA MOVIL</option>
                                            <option value="258">PAGO POSTERIOR – CANAL DE PAGO - PAGO ONLINE</option>
                                            <option value="58">PAGO POSTERIOR – DEPOSITO EN CUENTA</option>
                                            <option value="245">PAGO POSTERIOR – DEPOSITO EN CUENTA – TRANSFERENCIA SWIFT</option>
                                            <option value="247">PAGO POSTERIOR – DEPOSITO EN CUENTA – CANAL DE PAGO</option>
                                            <option value="248">PAGO POSTERIOR – DEPOSITO EN CUENTA – BILLETERA MOVIL</option>
                                            <option value="249">PAGO POSTERIOR – DEPOSITO EN CUENTA – PAGO ONLINE</option>
                                            <option value="63">PAGO POSTERIOR – PAGO ONLINE</option>
                                            <option value="59">PAGO POSTERIOR – SWIFT</option>
                                            <option value="251">PAGO POSTERIOR – SWIFT - CANAL DE PAGO</option>
                                            <option value="252">PAGO POSTERIOR – SWIFT - BILLETERA MOVIL</option>
                                            <option value="253">PAGO POSTERIOR – SWIFT - PAGO ONLINE</option>
                                            <option value="57">PAGO POSTERIOR – TRANSFERENCIA BANCARIA</option>
                                            <option value="239">PAGO POSTERIOR – TRANSFERENCIA BANCARIA – DEPOSITO EN CUENTA</option>
                                            <option value="240">PAGO POSTERIOR – TRANSFERENCIA BANCARIA – TRANSFERENCIA SWIFT</option>
                                            <option value="242">PAGO POSTERIOR – TRANSFERENCIA BANCARIA – CANAL DE PAGO</option>
                                            <option value="243">PAGO POSTERIOR – TRANSFERENCIA BANCARIA – BILLETERA MOVIL</option>
                                            <option value="244">PAGO POSTERIOR – TRANSFERENCIA BANCARIA – PAGO ONLINE</option>
                                            <option value="74">SWIFT – BILLETERA MOVIL</option>
                                            <option value="290">SWIFT – BILLETERA MOVIL  – PAGO ONLINE</option>
                                            <option value="291">GIFT-CARD – CANAL DE PAGO  – BILLETERA MOVIL</option>
                                            <option value="292">GIFT-CARD – CANAL DE PAGO  – PAGO ONLINE</option>
                                            <option value="73">SWIFT – CANAL DE PAGO</option>
                                            <option value="75">SWIFT – PAGO ONLINE</option>
                                            <option value="16">TARJETA-CHEQUE</option>
                                            <option value="17">TARJETA-VALES</option>
                                            <option value="18">TARJETA-TRANSFERENCIA BANCARIA</option>
                                            <option value="19">TARJETA-DEPOSITO EN CUENTA</option>
                                            <option value="7">TRANSFERENCIA BANCARIA</option>
                                            <option value="13">EFECTIVO – TRANSFERENCIA BANCARIA</option>
                                            <option value="66">TRANSFERENCIA BANCARIA – BILLETERA MOVIL</option>
                                            <option value="274">TRANSFERENCIA BANCARIA – BILLETERA MOVIL – PAGO ONLINE</option>
                                            <option value="65">TRANSFERENCIA BANCARIA – CANAL DE PAGO</option>
                                            <option value="272">TRANSFERENCIA BANCARIA – CANAL DE PAGO – BILLETERA MOVIL</option>
                                            <option value="273">TRANSFERENCIA BANCARIA – CANAL DE PAGO – PAGO ONLINE</option>
                                            <option value="260">TRANSFERENCIA BANCARIA – DEPOSITO EN CUENTA  – TRANSFERENCIA SWIFT</option>
                                            <option value="262">TRANSFERENCIA BANCARIA – DEPOSITO  EN CUENTA – CANAL DE PAGO</option>
                                            <option value="263">TRANSFERENCIA BANCARIA – DEPOSITO EN CUENTA   – BILLETERA MOVIL</option>
                                            <option value="67">TRANSFERENCIA BANCARIA – PAGO ONLINE</option>
                                            <option value="266">TRANSFERENCIA BANCARIA – SWIFT  – CANAL DE PAGO</option>
                                            <option value="267">TRANSFERENCIA BANCARIA – SWIFT  – BILLETERA MOVIL</option>
                                            <option value="268">TRANSFERENCIA BANCARIA – SWIFT  – PAGO ONLINE</option>
                                            <option value="24">TRANSFERENCIA BANCARIA-DEPOSITO EN CUENTA</option>
                                            <option value="25">TRANSFERENCIA BANCARIA-TRANSFERENCIA SWIFT</option>
                                            <option value="264">TRANSFERENCIA BANCARIA– DEPOSITO  EN CUENTA – PAGO ONLINE</option>
                                            <option value="9">TRANSFERENCIA SWIFT</option>
                                            <option value="4">VALES</option>
                                            <option value="55">VALES – BILLETERA MOVIL</option>
                                            <option value="233">VALES – BILLETERA MOVIL – CANAL DE PAGO</option>
                                            <option value="234">VALES – BILLETERA MOVIL – BILLETERA MOVIL</option>
                                            <option value="235">VALES – BILLETERA MOVIL – PAGO ONLINE</option>
                                            <option value="54">VALES – CANAL DE PAGO</option>
                                            <option value="227">VALES – CANAL DE PAGO  – TRANSFERENCIA SWIFT</option>
                                            <option value="229">VALES – CANAL DE PAGO  – CANAL DE PAGO</option>
                                            <option value="230">VALES – CANAL DE PAGO  – BILLETERA MOVIL</option>
                                            <option value="231">VALES – CANAL DE PAGO  – PAGO ONLINE</option>
                                            <option value="22">VALES – DEPOSITO EN CUENTA</option>
                                            <option value="56">VALES – PAGO ONLINE</option>
                                            <option value="236">VALES – PAGO ONLINE - CANAL DE PAGO</option>
                                            <option value="237">VALES – PAGO ONLINE - BILLETERA MOVIL</option>
                                            <option value="238">VALES – PAGO ONLINE - PAGO ONLINE</option>
                                            <option value="214">VALES – SWIFT - TRANSFERENCIA BANCARIA</option>
                                            <option value="215">VALES – SWIFT - DEPOSITO EN CUENTA</option>
                                            <option value="216">VALES – SWIFT - TRANSFERENCIA SWIFT</option>
                                            <option value="218">VALES – SWIFT - CANAL DE PAGO</option>
                                            <option value="219">VALES – SWIFT - BILLETERA MOVIL</option>
                                            <option value="220">VALES – SWIFT - PAGO ONLINE</option>
                                            <option value="21">VALES-TRANSFERENCIA BANCARIA</option>
                                            <option value="23">VALES-TRANSFERENCIA SWIFT</option>
                                        </select>
                                        </div>
                                    </div>
                                    <button type="button" @click="otroMetodo(metodoPago)" class="btn btn-success btn-block"><i class="fa fa-check mr-2"></i> Confirmar</button>
                                    </form>
                                </div>
                                </div>
                            </TabPanel>

                        </TabView>
                    </Sidebar>


                </template>

                <!--<template v-else-if="listado == 3">
                    <div class="col-md-4 " style="max-width: none ;margin: 0 auto;">
                            <div class="form-group row border">
                                <div class="col-md-4">
                                    <div v-show="paraLlevar" class="form-group">
                                        <label for="">Cliente(*)</label>
                                        <input type="text" id="cliente" class="form-control" placeholder="Nombre del Cliente" v-model="cliente" ref="cliente">
                                    </div>
                                    <div v-show="!paraLlevar" class="form-group">
                                        <label for="">Mesero(*)</label>
                                        <input type="text" id="mesero" class="form-control" placeholder="Nombre del Mesero"
                                        v-model="usuario_autenticado" ref="mesero" readonly>
                                    </div>
                                </div>
                                <input type="hidden" id="nombreCliente" class="form-control" readonly value="Sin Nombre">
                                <input type="hidden" id="idcliente" class="form-control" readonly value="7">
                                <input type="hidden" id="tipo_documento" class="form-control" readonly value="1">
                                <input type="hidden" id="complemento_id" class="form-control" v-model="complemento_id" ref="complementoIdRef" readonly>
                                <input type="hidden" id="usuarioAutenticado" class="form-control" v-model="usuarioAutenticado" readonly>
                                <input type="hidden" id="documento" class="form-control" readonly value="0000">
                                <input type="hidden" id="email" class="form-control" readonly value="sinnombre@gmail.com">
                                <input type="hidden" id="idAlmacen" class="form-control" readonly value="1">
                                <div  v-show="!paraLlevar" class="col-md-5">
                                    <div class="form-group">
                                        <label>Num Mesa(*)</label>
                                        <input type="number" id="mesa" class="form-control" v-model="mesa">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Número Ticket</label>
                                        <input type="text" id="num_comprobante" class="form-control" v-model="num_comprob" ref="numeroComprobanteRef" readonly>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Tipo Comprobante(*)</label>
                                        <select class="form-control" v-model="tipo_comprobante" ref="tipoComprobanteRef">
                                            <option value="0">Seleccione</option>
                                            <option value="TICKET">Ticket</option>
                                            <option value="FACTURA">Factura</option>
                                            <option value="BOLETA">Boleta</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="font-weight-bold">Para llevar:
                                            <span class="text-danger">*</span>

                                        </label>
                                    </div>
                                    <div><InputSwitch v-model="paraLlevar" style="transform: scale(0.75);"/></div>
                                </div>
                                

                                <div class="col-md-3">
                                    <div class="form-group" v-if="scodigorecepcion === 5 || scodigorecepcion === 6 || scodigorecepcion === 7">
                                        <label>Código CAFC</label>
                                        <input type="text" id="cafc" class="form-control" v-model="cafc" ref="cafc">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div v-show="errorVenta" class="form-group row div-error">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMostrarMsjVenta" :key="error" v-text="error"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row border">
                                <div class="table-responsive col-md-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Opciones</th>
                                                <th>Artículo</th>
                                                <th>Cantidad</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>

                                        <tbody v-if="arrayDetalle.length">
                                            <tr v-for="(detalle, index) in arrayDetalle" :key="detalle.id">
                                                <td>
                                                    <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                                        <i class="icon-close"></i>
                                                    </button>
                                                </td>
                                                <td v-text="detalle.articulo">
                                                </td>
                                                <td>
                                                    <span style="color:red;" v-show="detalle.cantidad > detalle.stock">Stock: {{ detalle.stock }}</span>
                                                    <input v-model="detalle.cantidad" type="number" class="form-control">
                                                </td>
                                                <td>
                                                    {{ (detalle.precio * detalle.cantidad - detalle.descuento).toFixed(2) }}
                                                </td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="3" align="right"><strong>Sub Total: Bs.</strong></td>
                                                <td id="subTotal">{{ totalParcial=(calcularSubTotal).toFixed(2) }}</td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="3" align="right"><strong>Descuento Adicional: Bs.</strong></td>
                                                <input id="descuentoAdicional" v-model="descuentoAdicional" type="number"
                                                    class="form-control">
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="3" align="right"><strong>Total Neto: Bs.</strong></td>
                                                <td id="montoTotal">{{ total=(calcularTotal).toFixed(2) }}</td>
                                            </tr>
                                        </tbody>

                                        <tbody v-else>
                                            <tr>
                                                <td colspan="6">
                                                    No hay articulos agregados
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                                    <button type="button" class="btn btn-primary" @click="registrar()">Registrar Venta</button>
                                </div>
                            </div>
                        </div>
                </template>-->


                <!-- Fin Detalle-->
                <!--Ver ingreso-->
                <template v-else-if="listado == 2">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Cliente</label>
                                    <p v-text="cliente"></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">Impuesto</label>
                                <p v-text="impuesto"></p>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Comprobante</label>
                                    <p v-text="tipo_comprobante"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie Comprobante</label>
                                    <p v-text="serie_comprobante"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número Comprobante</label>
                                    <p v-text="num_comprobante"></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Artículo</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Descuento</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                            <td v-text="detalle.articulo">
                                            </td>
                                            <td v-text="detalle.medida">
                                            </td>
                                            <td v-text="detalle.precio">
                                            </td>
                                            <td v-text="detalle.cantidad">
                                            </td>
                                            <td v-text="detalle.descuento">
                                            </td>
                                            <td>
                                                {{ detalle.precio * detalle.cantidad - detalle.descuento }}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Parcial:</strong></td>
                                            <td>$ {{ totalParcial=(total - totalImpuesto).toFixed(2) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Impuesto:</strong></td>
                                            <td>$ {{ totalImpuesto=(total * impuesto).toFixed(2) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Neto:</strong></td>
                                            <td>$ {{ total }}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="5">
                                                No hay articulos agregados
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </template>
                <!--Fin ver ingreso-->
            <!-- Fin ejemplo de tabla Listado -->
        </Panel>
    </main>
</template>

<script>

import InputSwitch from 'primevue/inputswitch';
import vSelect from 'vue-select';
import Button from 'primevue/button';
import Dropdown from 'primevue/dropdown';
import DataView from 'primevue/dataview';
import DataViewLayoutOptions from 'primevue/dataviewlayoutoptions';
import Badge from 'primevue/badge';
import Dialog from 'primevue/dialog';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Panel from 'primevue/panel';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Sidebar from 'primevue/sidebar';
import Card from 'primevue/card';
import { TileSpinner } from 'vue-spinners';


export default {
    data() {
        return {

            //qr
            alias: '',
            montoQR: 0,
            qrImage: '',
            aliasverificacion: '',
            estadoTransaccion: null,
            currency: 'BOB', // Define tu moneda

            // primeVue variables
            paraLlevar: false,
            categoria_busqueda: '',
            arrayCategoriasMenu: [],
            arrayCategoriasProducto: [],
            arrayMenu: [],
            arrayProductos: [],
            layout: 'grid',
            activeIndex: 0,
            visibleFull: false,
            visiblePago: false,
            visibleRight: false,

            buttonStyle: {
                width: '200px',
            },

            ejemploCarrito: 9,

            // -----------------------

            venta_id: 0,
            idcliente: 0,
            usuarioAutenticado: null,
            puntoVentaAutenticado: null,
            cliente: '',
            email: '',
            mesa: 0,
            nombreCliente: '',
            documento: '',
            tipo_documento: '',
            complemento_id: '',
            descuentoAdicional: 0.00,
            descuentoGiftCard: '',
            tipo_comprobante: 'TICKET',
            observacion: '',
            serie_comprobante: '',
            last_comprobante: 0,
            num_comprob: "",
            impuesto: 0.18,
            total: 0.0,
            totalImpuesto: 0.0,
            totalParcial: 0.0,
            arrayVenta: [],
            arrayCliente: [],
            arrayDetalle: [],
            arrayFactura: [],
            mostrarTipoComprobante: false,
            listado: 1,
            modal: 0,
            tituloModal: '',
            tipoAccion: 0,
            modal2: 0,
            tituloModal2: '',
            tipoAccion2: '',
            errorVenta: 0,
            errorMostrarMsjVenta: [],
            pagination: {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0,
            },
            offset: 3,
            criterio: '',
            buscar: '',
            criterioA: '0',
            buscarA: '',
            arrayArticulo: [],
            codigoComida: 0,
            codigo: '',
            articulo: '',
            medida: '',
            codigoClasificador: '',
            codigoProductoSin: '',
            precio: 0,
            cantidad: 1,
            descuento: 0,
            descuentoProducto: 0,
            sTotal: 0,
            stock: 0,
            valorMaximoDescuento: '',
            mostrarComunicacionSiat: true,
            mostrarCuis: true,
            mostrarCufd: true,
            mostrarDireccion: true,
            mostrarCUFD: true,
            mostrarCodigoControl: true,
            mostrarEnviarPaquete: true,
            mostrarValidarPaquete: false,
            cafc: '',
            fecha: '',
            scodigomotivo: null,
            numeroTarjeta: null,
            casosEspeciales: false,
            mostrarCampoCorreo: false,
            leyendaAl: '',
            codigoExcepcion: 0,
            mostrarSpinner: false,
            metodoPago: '',
            monedaVenta: [],
            usuario_autenticado: '',
            //almacenes
            arrayAlmacenes: [],
            idAlmacen: 1,
            recibido: 0,
            efectivo: 0,
            cambio: 0,
            faltante: 0,
            idtipo_pago: '',
            idtipo_venta: '1',
            tiempo_diaz: '',
            numero_cuotas: '',
            cuotas: [],//---para almacenar las fechas
            estadocrevent: 'activo',
            primera_cuota: '',
            habilitarPrimeraCuota: false,
            tipoPago: 'EFECTIVO',
            tiposPago: {
                        EFECTIVO: 1,
                        TARJETA: 2,
                        QR: 3
                        },
        }
    },
    components: {
        TileSpinner,
        vSelect,
        Button,
        Dropdown,
        DataView,
        DataViewLayoutOptions,
        Badge,
        Dialog,
        InputSwitch,
        TabView,
        TabPanel,
        Panel,
        InputText,
        InputNumber,
        Sidebar,
        Card
    },

    watch: {
        'detalle.cantidad': function(newValue, oldValue) {
            // Aquí puedes agregar la lógica para actualizar la cantidad en arrayProductos
            // Puedes acceder al índice de arrayDetalle utilizando indexOf
            const index = this.arrayDetalle.indexOf(this.detalle);
            // Asegúrate de que el índice sea válido y luego actualiza la cantidad en arrayProductos
            if (index !== -1) {
            this.arrayFactura[index].cantidad = newValue;
            }
        },
        codigo(newValue) {
            if (newValue) {
                this.buscarArticulo();
            }
        },
        documento(newDocumento) {
            this.mostrarCampoCorreo = (newDocumento === '99002' || newDocumento === '99003');
        }
    },

    computed: {
        isActived: function () {
            return this.pagination.current_page;
        },

        //Calcula los elementos de la paginación
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
        },

        calcularTotal: function () {
            var resultado = 0.0;
            for (var i = 0; i < this.arrayDetalle.length; i++) {
                resultado += (this.arrayDetalle[i].precio * this.arrayDetalle[i].cantidad - this.arrayDetalle[i].descuento)
            }
            resultado -= this.descuentoAdicional;
            return resultado;
        },

        calcularSubTotal: function () {
            var resultado = 0.0;
            for (var i = 0; i < this.arrayDetalle.length; i++) {
                resultado = resultado + (this.arrayDetalle[i].precio * this.arrayDetalle[i].cantidad - this.arrayDetalle[i].descuento)
            }
            return resultado;
        },

        badgeSeverity() {
            if (this.estadoTransaccion && this.estadoTransaccion.objeto.estadoActual === 'PENDIENTE') {
                return 'danger'; // Rojo para estado PENDIENTE
            } else if (this.estadoTransaccion && this.estadoTransaccion.objeto.estadoActual === 'PAGADO') {
                return 'success'; // Verde para estado PAGADO
            } else {
                return 'info'; // Otros estados
            }
            }
    
    },
    methods: {

        verificarEstado() {
      axios.post('/qr/verificarestado', {
        alias: this.aliasverificacion,
      })
      .then(response => {
        this.estadoTransaccion = response.data;
      })
      .catch(error => {
        console.error(error);
      });
    },

    generarQr() {
      this.aliasverificacion = this.alias;
      axios.post('/qr/generarqr', {
        alias: this.alias,
        monto: this.calcularTotal
      })
      .then(response => {
        const imagenBase64 = response.data.objeto.imagenQr;
        this.qrImage = `data:image/png;base64,${imagenBase64}`;
      })
      .catch(error => {
        console.error(error);
      });

      this.alias = '';
      this.montoQR = 0;
    },

    verDetalle(producto) {
        console.log('PULSADO');
        console.log('Producto pulsado:', producto);
    },

    truncateAndCapitalize(text) {
        const maxLength = 14;
        text = text.length > maxLength ? text.substring(0, maxLength - 3) + '...' : text;
        return text.replace(/\b\w/g, (char) => char.toUpperCase());
    },

    editarMenu(event) {
        event.stopPropagation();
        console.log('EDITANDO MENU');
    },

        updateButtonStyle() {
            const windowWidth = window.innerWidth;
            //console.log(windowWidth);

            if (windowWidth <= 576) {
                this.buttonStyle.width = '145px';
            } else {
                this.buttonStyle.width = '200px';
            }
        },

        abrirCarrito() {
            console.log('abriendo carrito');
            this.abrirVentanaVenta();
            this.goToNextTab();
        },


        abrirVentanaVenta() {
            let me = this;

            me.listado = 3;
        },

        getCategoriasMenu() {
            let me = this;

            var url = '/categorias_menu/getAll';
            axios.get(url).then(function (response) {

                let respuesta = response.data;
                me.arrayCategoriasMenu = respuesta.categorias_menu;
                console.log('menu categorias:', me.arrayCategoriasMenu);
            })
            .catch(function (error) {
                console.log(error);
            });
        },

        getCategoriasProductos() {
            let me = this;
            var url = '/categoria/selectCategoria';
            axios.get(url).then(function (response) {

                let respuesta = response.data;
                me.arrayCategoriasProducto = respuesta.categorias;
                console.log('productos categorias:', me.arrayCategoriasProducto);
            })
            .catch(function (error) {
                console.log(error);
            });
        },

        scrollToSection() {
            $('html, body').animate({
                scrollTop: $('#seccionObjetivo').offset().top
            }, 50);
        },
        scrollToTop() {
            $('html, body').animate({
                scrollTop: 0
            }, 50);
        },

        atajoButton: function (event) {
            //console.log(event.keyCode);
            //console.log(event.ctrlKey);
            if (event.shiftKey && event.keyCode === 81) {
                event.preventDefault();
                this.$refs.impuestoRef.focus();
            }
            if (event.shiftKey && event.keyCode === 87) {
                event.preventDefault();
                this.$refs.serieComprobanteRef.focus();
            }
            if (event.shiftKey && event.keyCode === 69) {
                event.preventDefault();
                this.$refs.numeroComprobanteRef.focus();
            }
            if (event.shiftKey && event.keyCode === 82) {
                event.preventDefault();
                this.$refs.articuloRef.focus();
            }
            if (event.shiftKey && event.keyCode === 84) {
                event.preventDefault();
                this.$refs.precioRef.focus();
            }
            if (event.shiftKey && event.keyCode === 89) {
                event.preventDefault();
                this.$refs.cantidadRef.focus();
            }
            if (event.shiftKey && event.keyCode === 85) {
                event.preventDefault();
                this.$refs.descuentoRef.focus();
            }
        },
        actualizarDetalle(index) {
            this.arrayDetalle[index].total = (this.arrayDetalle[index].precioseleccionado * this.arrayDetalle[index].cantidad).toFixed(2);
        },
        actualizarDetalleDescuento(index) {
            this.calcularTotal(index);
        },
        validarDescuentoAdicional() {
            if (this.descuentoAdicional >= this.totalParcial) {
                this.descuentoAdicional = 0;
                alert("El descuento adicional no puede ser mayor o igual al total.");
            }
        }, 
        validarDescuentoGiftCard() {
            
            if (this.descuentoGiftCard >= this.calcularTotal) {
                this.descuentoGiftCard = 0;
                alert("El descuento Gift Card no puede ser mayor o igual al total.");
            }
        },

        async fetchClienteData(){
            if(this.documento){
                try{
                    const response = await axios.get(`/api/clientes?documento=${this.documento}`);
                    if(response.data.success){
                        this.cliente = response.data.cliente.nombre;
                        this.email = response.data.cliente.email;
                    } else {
                        alert('Cliente no encontrado');
                        this.cliente = '';
                        this.email = '';
                    }
                }catch (error){
                    console.error('Error al buscar los datos del cliente:', error);
                }
            }
        },

        habilitarNombreCliente() {
            if (this.casosEspeciales) {
                this.$refs.documentoRef.setAttribute("readonly", true);
                this.documento = "99001";
                //this.idcliente = "2";
                this.tipo_documento = "5"; 
            } else {
                this.$refs.documentoRef.removeAttribute("readonly");
                this.documento = "";
                //this.idcliente = "";
                this.tipo_documento = "";
            }
        },

        verificarComunicacion() {
            axios.post('/venta/verificarComunicacion')
                .then(function (response) {
                    if (response.data.RespuestaComunicacion.transaccion === true) {
                        document.getElementById("comunicacionSiat").innerHTML = response.data.RespuestaComunicacion.mensajesList.descripcion;
                        document.getElementById("comunicacionSiat").className = "badge bg-success";
                        // Actualiza el valor de scuis
                        //this.scuis = response.data.scuis;
                    } else {
                        document.getElementById("comunicacionSiat").innerHTML = "Desconectado";
                        document.getElementById("comunicacionSiat").className = "badge bg-secondary";
                        // Actualiza el valor de scuis
                        //this.scuis = "Inexistente";
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
                        document.getElementById("codigoControl").innerHTML = response.data.codigoControl;
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

        obtenerNuevoCUFD() {
            axios.post('/venta/nuevoCufd')
                .then(function (response) {
                    console.log(response);
                    if (response.data.RespuestaCufd.transaccion != false) {
                        document.getElementById("cufd").innerHTML = "CUFD vigente: " + response.data.RespuestaCufd.fechaVigencia.substring(0, 16);
                        document.getElementById("direccion").innerHTML = response.data.RespuestaCufd.direccion;
                        document.getElementById("cufdValor").innerHTML = response.data.RespuestaCufd.codigo;
                        document.getElementById("codigoControl").innerHTML = response.data.codigoControl;
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

        nextNumber() {
            if (!this.num_comprob || this.num_comprob === "") {
                this.last_comprobante++;
                // Completa con ceros a la izquierda hasta alcanzar 5 dígitos
                this.num_comprob = this.last_comprobante.toString().padStart(5, "0");
            }
        },

        listarVenta(page, buscar, criterio) {
            let me = this;
            var url = '/venta/offline?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayVenta = respuesta.facturasOffline.data;
                me.pagination = respuesta.pagination;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },

        selectCliente(search, loading) {
            let me = this;
            loading(true)
            var url = '/cliente/selectCliente?filtro=' + search;
            axios.get(url).then(function (response) {
                //console.log(response.clientes);
                let respuesta = response.data;
                q: search
                me.arrayCliente = respuesta.clientes;
                loading(false)
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getDatosCliente(val1) {
            let me = this;
            me.loading = true;
            me.idcliente = val1.id;
            //console.log(val1);
            this.email = val1.email;
            this.nombreCliente = val1.nombre;
            this.documento = val1.num_documento;
            this.tipo_documento = val1.tipo_documento;
            this.complemento_id = val1.complemento_id;

        },

        buscarArticulo() {
            clearTimeout(this.timer);
            this.timer = setTimeout(() => {
                let me = this;
                var url = '/articulo/buscarArticuloVenta?filtro=' + me.codigo;

                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    me.arraySeleccionado = respuesta.articulos[0];
                    console.log(me.arraySeleccionado)
                })
                    .catch(function (error) {
                        console.log(error);
                    });
            }, 1000);
        },

        enviarPaquete() {
        let me = this;
        var tzoffset = (new Date()).getTimezoneOffset() * 60000;
        let fechaEmision = (new Date(Date.now() - tzoffset)).toISOString().slice(0, -1);

        axios.post('/venta/enviarPaquete', {
            fechaEmision: fechaEmision
        }).then(function(response) {
            var data = response.data;
            if(data === "PENDIENTE"){
                swal(
                'PAQUETE ENVIADO CORRECTAMENTE',
                 data + ' A REVISIÓN - Seleccione "Validar Paquete"',
                'success'
            );
            me.mostrarEnviarPaquete = false;
            me.mostrarValidarPaquete = true;
            }else{
                swal(
                'ERROR AL ENVIAR PAQUETE',
                 data,
                'error'
                );
                /*if (Array.isArray(data)) {
                    let errorMessage = 'Errores al enviar el paquete:<br>';

                    data.forEach((descripcion, index) => {
                        errorMessage += `${index + 1}: ${descripcion}<br>`;
                    });*/
                    /*swal({
                        title: 'ERROR AL ENVIAR PAQUETE',
                        html: errorMessage,
                        type: 'warning',
                        showCloseButton: true,
                        allowOutsideClick: false,
                    });*/
                    }
            me.mostrarEnviarPaquete = false;
            me.mostrarValidarPaquete = true;
        }).catch(function(error) {
            if(error.response.data.message === "Trying to get property 'RespuestaServicioFacturacion' of non-object"){
                            swal('INTENTE DE NUEVO', 'Comunicación con SIAT fallida', 'error');
                            }else{
                            swal('ERROR', error, 'error');
                            }
        });
    },
    
    validarPaquete() {
        let me = this;

        axios.post('/venta/validarPaquete')
            .then(function(response) {
                var data = response.data;
                if (data === "VALIDADA") {
                    swal(
                        'ESTADO DEL PAQUETE',
                        data,
                        'success'
                    );
                    me.mostrarEnviarPaquete = true;
                    me.mostrarValidarPaquete = false;
                } else {
                    if (Array.isArray(data)) {
                        let errorMessage = 'Errores al validar el paquete:<br>';

                        data.forEach((descripcion, index) => {
                            errorMessage += `${index + 1}: ${descripcion}<br>`;
                        });

                        swal({
                            title: 'ERROR AL VALIDAR PAQUETE',
                            html: errorMessage,
                            type: 'warning',
                            showCloseButton: true,
                            allowOutsideClick: false,
                        });
                    }
                    me.mostrarEnviarPaquete = false;
                    me.mostrarValidarPaquete = true;
                }
            }).catch(function(error) {
                swal(
                    'ERROR AL ENVIAR EL PAQUETE DE FACTURAS:',
                    'Intente de Nuevo',
                    'warning'
                );
            });
    },

        aplicarDescuento() {
            const descuentoGiftCard = this.descuentoGiftCard;
            const numeroTarjeta = this.numeroTarjeta;
            let idtipo_pago;

            if (numeroTarjeta && descuentoGiftCard) {
                idtipo_pago = 86;
            } else if (numeroTarjeta && !descuentoGiftCard) {
                idtipo_pago = 10;
            } else {
                idtipo_pago = descuentoGiftCard ? 35 : 1;
            }

            this.registrarVenta(idtipo_pago);
        },

        aplicarCombinacion() {
            const descuentoGiftCard = this.descuentoGiftCard
            const idtipo_pago = descuentoGiftCard ? 40 : 2; 

            this.registrarVenta(idtipo_pago);
        },

        otroMetodo(metodoPago){
            const idtipo_pago = metodoPago;
            this.registrarVenta(idtipo_pago);
        },

        buscarArticulo() {
            let me = this;
            var url = '/articulo/buscarArticuloVenta?filtro=' + me.codigo;

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                console.log(respuesta);
                me.arrayArticulo = respuesta.articulos;

                if (me.arrayArticulo.length > 0) {
                    me.articulo = me.arrayArticulo[0]['nombre'];
                    me.codigoComida = me.arrayArticulo[0]['codigo'];
                    me.codigo = me.arrayArticulo[0]['codigo'];
                    me.precio = me.arrayArticulo[0]['precio_venta'];
                    me.stock = me.arrayArticulo[0]['stock'];
                    me.medida = me.arrayArticulo[0]['medida'];
                    me.codigoProductoSin = me.arrayArticulo[0]['codigoProductoSin'];
                }
                else {
                    me.articulo = 'No existe este articulo';
                    me.codigoComida = 0;
                }
            })
                .catch(function (error) {
                    console.log(error);
                });
        },

        abrirTipoVenta() {
                this.modal2 = 1;
                //this.cliente = this.nombreCliente;
                this.tipoAccion2 = 1;
                this.scrollToTop()
        },

        imprimirTicket(id) {
            axios.get('/venta/imprimir/'+id, { responseType: 'blob' })
                .then(function(response) {
                window.location.href = "docs/ticket.pdf";
                const fileURL = 'docs/ticket.pdf';
                window.open(fileURL, '_blank');
                console.log("Se generó el Ticket correctamente");

                })
                .catch(function(error) {
                console.log(error);
                });
            },

        cambiarPagina(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            //Envia la petición para visualizar la data de esa página
            me.listarVenta(page, buscar, criterio);
        },
        cambiarPaginaA(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            //Envia la petición para visualizar la data de esa página
            me.listarArticulo(page, buscar, criterio);
        },
        encuentra(id) {
            var sw = 0;
            for (var i = 0; i < this.arrayDetalle.length; i++) {
                if (this.arrayDetalle[i].codigoComida == id) {
                    sw = true;
                }
            }
            return sw;
        },
        eliminarDetalle(index) {
            let me = this;
            me.arrayDetalle.splice(index, 1);
        },
        agregarDetalle() {
            let me = this;

            let actividadEconomica = 749000;
            let codigoProductoSin = document.getElementById("codigoProductoSin").value;
            let codigoProducto = document.getElementById("codigo").value;
            let descripcion = document.getElementById("nombre_producto").value;
            let cantidad = document.getElementById("cantidad").value;
            let unidadMedida = 57;
            let precioUnitario = document.getElementById("precio").value;
            let montoDescuento = document.getElementById("descuento").value;
            let subTotalValor = document.getElementById("sTotal");
            let subTotal = subTotalValor.textContent;
            let numeroSerie = null;
            let numeroImei = null;


            if (me.codigoComida == 0 || me.cantidad == 0 || me.precio == 0) {

            } else {
                if (me.encuentra(me.codigoComida)) {
                    swal({
                        type: 'error',
                        title: 'Error...',
                        text: 'Este Artículo ya se encuentra agregado!',
                    })
                } else {
                    if (me.cantidad > me.stock) {
                        swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'No hay stock disponible!',
                        })
                    } else {
                        me.arrayDetalle.push({
                            codigoComida: me.codigoComida,
                            articulo: me.articulo,
                            medida: me.medida,
                            cantidad: me.cantidad,
                            precio: me.precio,
                            descuento: me.descuento,
                            stock: me.stock,
                        });
                        console.log("Estoy entrando s arraydetalle")

                        me.arrayFactura.push({
                            actividadEconomica: actividadEconomica,
                            codigoProductoSin: codigoProductoSin,
                            codigoProducto: codigoProducto,
                            descripcion: descripcion,
                            cantidad: cantidad,
                            unidadMedida: unidadMedida,
                            precioUnitario: precioUnitario,
                            montoDescuento: montoDescuento,
                            subTotal: subTotal,
                            numeroSerie: numeroSerie,
                            numeroImei: numeroImei
                        });

                        me.codigo = '';
                        me.codigoComida = 0;
                        me.articulo = '';
                        me.medida = '';
                        me.cantidad = 0;
                        me.precio = 0;
                        me.descuento = 0;
                        me.stock = 0;
                        me.sTotal = 0;
                    }
                }

            }

        },
        agregarDetalleModal(data = []) {
            let me = this;

            let actividadEconomica = 749000;
            //let codigoProductoSin = document.getElementById("codigoProductoSin").value;
            //let codigoProducto = document.getElementById("codigo").value;
            //let descripcion = document.getElementById("nombre_producto").value;
            //let cantidad = document.getElementById("cantidad").value;
            let unidadMedida = 57;
            //let precioUnitario = document.getElementById("precio").value;
            //let montoDescuento = document.getElementById("descuento").value;
            let montoDescuento = 0;
            //let subTotalValor = document.getElementById("sTotal");
            //let subTotal = subTotalValor.textContent;
            let numeroSerie = null;
            let numeroImei = null;
            //let descuento = ((this.precioseleccionado * this.cantidad) * (this.descuentoProducto / 100)).toFixed(2);

            
            if (me.encuentra(data['codigo'])) {
                swal({
                    type: 'error',
                    title: 'Error...',
                    text: 'Este Artículo ya se encuentra agregado!',
                })
            } else {
                me.arrayDetalle.push({
                    codigoComida: data['codigo'],
                    articulo: data['nombre'],
                    cantidad: 1,
                    precio: data['precio_venta'],
                    descuento: 0,
                    stock: data['stock'],
                    medida: data['medida'],
                });
                console.log("ArrayDetalle:" + me.arrayDetalle);
                me.arrayFactura.push({
                            actividadEconomica: actividadEconomica,
                            codigoProductoSin: data['codigoProductoSin'],
                            codigoProducto: data['codigo'],
                            descripcion: data['nombre'],
                            cantidad: 1,
                            unidadMedida: unidadMedida,
                            precioUnitario: data['precio_venta'],
                            montoDescuento: montoDescuento,
                            subTotal: data['precio_venta'],
                            numeroSerie: numeroSerie,
                            numeroImei: numeroImei
                        });
                        console.log("Para la Factura: " + me.arrayFactura);
            }
        },

        actualizarArrayProductos(index) {
            let detalle = this.arrayDetalle[index];
            let producto = this.arrayFactura[index];

            producto.cantidad = detalle.cantidad;
            producto.subTotal = detalle.cantidad * producto.precioUnitario;

        },

        listarArticulo(page, criterioA) {
            let me = this;
            var url = '/articulo/listarArticuloVenta?page=' + page + '&criterio='+ criterioA + '&idAlmacen='+ this.idAlmacen;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                console.log(respuesta);
                me.arrayArticulo = respuesta.articulos.data;
                me.pagination = respuesta.pagination;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },

        listarMenu(buscar, criterio) {
            let me = this;
            var url = '/menu/getAllMenu?buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayMenu = respuesta.articulos;
                me.pagination = respuesta.pagination;
                console.log('lista menu: ', me.arrayMenu);
            })
            .catch(function (error) {
                console.log(error);
            });
        },

        listarProducto(page, buscar, criterio) {
            let me = this;
            var url = '/articulo?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayProductos = respuesta.articulos.data;
                me.pagination = respuesta.pagination;
                console.log("lista productos", me.arrayProductos);
            })
                .catch(function (error) {
                    console.log(error);
                });
        },

        obtenerDatosUsuario() {
            axios.get('/venta')
                .then(response => {
                    this.usuarioAutenticado = response.data.usuario.usuario;
                    this.usuario_autenticado = this.usuarioAutenticado;
                    this.puntoVentaAutenticado = response.data.codigoPuntoVenta;
                })
                .catch(error => {
                    console.error(error);
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
                        axios.get('/facturaOffline/anular/' + cuf +"/" + motivoSeleccionado)
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

            imprimirFactura(id, correo) {
            swal({
                title: 'Selecciona un tamaño de factura',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'CARTA',
                cancelButtonText: 'ROLLO',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    console.log("El boton CARTA fue presionado");
                    axios.get('/factura/imprimirCartaOffline/' + id + '/' + correo, { responseType: 'blob' })
                        .then(function (response) {
                            window.location.href = "docs/facturaCarta.pdf";
                            console.log("Se generó el factura en Carta correctamente");
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                } else if (result.dismiss === swal.DismissReason.cancel) {
                    console.log("El boton ROLLO fue presionado");
                    axios.get('/factura/imprimirRolloOffline/' + id + '/' + correo, { responseType: 'blob' })
                        .then(function (response) {
                            window.location.href = "docs/facturaRollo.pdf";
                            console.log("Se generó el la factura en Rollo correctamente");
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                }
            }).catch((error) => {
                console.error('Error al mostrar el diálogo:', error);
            });
        },

        selectAlmacen() {
            let me = this;
            var url = '/almacen/selectAlmacen';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayAlmacenes = respuesta.almacenes;
                console.log(me.arrayAlmacenes);

            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getAlmacenProductos(almacen) {
            let me = this;
            me.idAlmacen = 1;
            console.log(me.idAlmacen);
        },
        validarVenta() {
            let me = this;
            me.errorVenta = 0;
            me.errorMostrarMsjVenta = [];
            var art;

            me.arrayDetalle.map(function (x) {
                if (x.cantidad > x.stock) {
                    art = x.articulo + " Stock insuficiente";
                    me.errorMostrarMsjVenta.push(art);
                }
            });

            if (!me.cliente) me.errorMostrarMsjVenta.push("Ingrese el Nombre de un Cliente");
            if (me.tipo_comprobante == 0) me.errorMostrarMsjVenta.push("Seleccione el Comprobante");
            if (!me.impuesto) me.errorMostrarMsjVenta.push("Ingrese el impuesto de compra");
            if (me.arrayDetalle.length <= 0) me.errorMostrarMsjVenta.push("Ingrese detalles");

            if (me.errorMostrarMsjVenta.length) me.errorVenta = 1;

            return me.errorVenta;
        },

        aplicarDescuento() {
            const descuentoGiftCard = this.descuentoGiftCard;
            const numeroTarjeta = this.numeroTarjeta;
            let idtipo_pago;

            if (numeroTarjeta && descuentoGiftCard) {
                idtipo_pago = 86;
            } else if (numeroTarjeta && !descuentoGiftCard) {
                idtipo_pago = 10;
            } else {
                idtipo_pago = descuentoGiftCard ? 35 : 1;
            }

            this.registrarVenta(idtipo_pago);
        },

        aplicarCombinacion() {
            const descuentoGiftCard = this.descuentoGiftCard
            const idtipo_pago = descuentoGiftCard ? 40 : 2; 

            this.registrarVenta(idtipo_pago);
        },

        otroMetodo(metodoPago){
            const idtipo_pago = metodoPago;
            this.registrarVenta(idtipo_pago);
        },
        //-------------REGISTRAR VENTA ------
        /*registrarVenta(idtipo_pago) {
            if (this.validarVenta()) {
                return;
            }

            let me = this;
            this.mostrarSpinner = true;
            this.idtipo_pago = idtipo_pago;

            for (let i = 0; i < me.cuotas.length; i++) {                
                console.log('LLEGA ARRAYDATA!', me.cuotas[i]);
            }

            console.log("hola");
            console.log(this.primer_precio_cuota);
            axios.post('/venta/registrar', {
                'idcliente': this.idcliente,
                'tipo_comprobante': this.tipo_comprobante,
                'serie_comprobante': this.serie_comprobante,
                'num_comprobante': this.num_comprob,
                'impuesto': this.impuesto,
                'total': this.calcularTotal,
                'idAlmacen': this.idAlmacen,
                'idtipo_pago': idtipo_pago,
                'idtipo_venta': this.idtipo_venta,
                'primer_precio_cuota': this.primer_precio_cuota,
                // Datos para el crédito de venta
                'cliente': this.cliente,
                'documento': this.documento,
                'tipoEntrega': this.mesa,
                'observacion': this.observacion,
                'numero_cuotasCredito': this.numero_cuotas, // Cambio de nombre
                'tiempo_dias_cuotaCredito': this.tiempo_diaz, // Cambio de nombre
                'totalCredito': this.primera_cuota ? this.calcularTotal - this.cuotas[0].totalCancelado : this.calcularTotal, // Asegúrate de tener esta variable
                'estadoCredito': "Pendiente", // Asegúrate de tener esta variable

                // Cuotas del crédito
                'cuotaspago': this.cuotas,
                'data': this.arrayDetalle

            }).then((response) => {
                let idVentaRecienRegistrada = response.data.id;
                console.log("El ID es: "+ idVentaRecienRegistrada);
                me.paqueteFactura(idVentaRecienRegistrada);

                if (response.data.id > 0) {
                    // Restablecer valores después de una venta exitosa
                    me.listado = 1;
                    me.listarVenta(1, '', 'num_comprob');
                    me.cerrarModal2();
                    me.idproveedor = 0;
                    me.tipo_comprobante = 'FACTURA';
                    me.nombreCliente = '';
                    me.idcliente = 0;
                    me.tipo_documento = 0;
                    me.complemento_id = '';
                    me.cliente = '';
                    me.documento = '';
                    me.email = '';
                    me.imagen = '';
                    me.serie_comprobante = '';
                    me.num_comprob = '';
                    me.impuesto = 0.18;
                    me.total = 0.0;
                    me.codigoComida = 0;
                    me.articulo = '';
                    me.cantidad = 0;
                    me.precio = 0;
                    me.stock = 0;
                    me.codigo = '';
                    me.descuento = 0;
                    me.arrayDetalle = [];
                    me.primer_precio_cuota = 0;
                    me.recibido = 0;

                    //window.open('/factura/imprimir/' + response.data.id);
                } else {
                    console.log(response)
                    if (response.data.valorMaximo) {
                        this.visiblePago = false;
                        this.visibleFull = false;
                        //alert('El valor de descuento no puede exceder el '+ response.data.valorMaximo);
                        swal(
                            'Aviso',
                            'El valor de descuento no puede exceder el ' + response.data.valorMaximo,
                            'warning'
                        )
                        return;
                    } else {
                        this.visiblePago = false;
                        this.visibleFull = false;

                        //alert(response.data.caja_validado); 
                        swal(
                            'Aviso',
                            response.data.caja_validado,
                            'warning'
                        )
                        return;
                    }
                    //console.log(response.data.valorMaximo)
                }

            }).catch((error) => {
                console.log(error);
            });
        },*/

        async registrarVenta(idtipo_pago) {
            if (this.validarVenta()) {
                return;
            }

            let tipoEntregaValor = this.mesa;

            this.mostrarSpinner = true;
            this.idtipo_pago = idtipo_pago;

            try {
                const response = await axios.get(`/api/clientes/existe?documento=${this.documento}`);
                if (!response.data.existe) {
                    const nuevoClienteResponse = await axios.post('/cliente/registrar', {
                        'nombre': this.cliente,
                        'num_documento': this.documento,
                        'email': this.email
                    });
                    this.idcliente = nuevoClienteResponse.data.id;
                } else {
                    this.idcliente = response.data.cliente.id;
                }

                const ventaResponse = await axios.post('/venta/registrar', {
                    'idcliente': this.idcliente,
                    'tipo_comprobante': this.tipo_comprobante,
                    'serie_comprobante': this.serie_comprobante,
                    'num_comprobante': this.num_comprob,
                    'impuesto': this.impuesto,
                    'total': this.calcularTotal,
                    'idAlmacen': this.idAlmacen,
                    'idtipo_pago': idtipo_pago,
                    'idtipo_venta': this.idtipo_venta,
                    'primer_precio_cuota': this.primer_precio_cuota,
                    'cliente': this.cliente,
                    'documento': this.documento,
                    'tipoEntrega': tipoEntregaValor,
                    'observacion': this.observacion,
                    'numero_cuotasCredito': this.numero_cuotas,
                    'tiempo_dias_cuotaCredito': this.tiempo_diaz,
                    'totalCredito': this.primera_cuota ? this.calcularTotal - this.cuotas[0].totalCancelado : this.calcularTotal,
                    'estadoCredito': "Pendiente",
                    'cuotaspago': this.cuotas,
                    'data': this.arrayDetalle
                });

                let idVentaRecienRegistrada = ventaResponse.data.id;
                console.log("El ID es: " + idVentaRecienRegistrada);
                this.paqueteFactura(idVentaRecienRegistrada);
                //this.actualizarFechaHora();

                if (ventaResponse.data.id > 0) {
                    this.listado = 1;
                    this.cerrarModal2();
                    this.idproveedor = 0;
                    this.tipo_comprobante = 'FACTURA';
                    this.nombreCliente = '';
                    this.idcliente = 0;
                    this.tipo_documento = 0;
                    this.complemento_id = '';
                    this.cliente = '';
                    this.documento = '';
                    this.email = '';
                    this.imagen = '';
                    this.serie_comprobante = '';
                    this.num_comprob = '';
                    this.impuesto = 0.18;
                    this.total = 0.0;
                    this.codigoComida = 0;
                    this.articulo = '';
                    this.cantidad = 0;
                    this.precio = 0;
                    this.stock = 0;
                    this.codigo = '';
                    this.descuento = 0;
                    this.arrayDetalle = [];
                    this.primer_precio_cuota = 0;
                    this.recibido = 0;

                    //window.open('/factura/imprimir/' + ventaResponse.data.id);
                } else {
                    console.log(ventaResponse);
                    if (ventaResponse.data.valorMaximo) {
                        this.visiblePago = false;
                        this.visibleFull = false;
                        swal(
                            'Aviso',
                            'El valor de descuento no puede exceder el ' + ventaResponse.data.valorMaximo,
                            'warning'
                        )
                        return;
                    } else {
                        this.visiblePago = false;
                        this.visibleFull = false;
                        swal(
                            'Aviso',
                            ventaResponse.data.caja_validado,
                            'warning'
                        )
                        return;
                    }
                }
            } catch (error) {
                console.log(error);
            } 
        },

        async paqueteFactura(idVentaRecienRegistrada) {

        let me = this;

        let idventa = idVentaRecienRegistrada;
        let numeroFactura = document.getElementById("num_comprobante").value;
        let cuf = "464646464";
        let direccionValor = document.getElementById("direccion");
        let direccion = direccionValor.textContent;
        var tzoffset = (new Date()).getTimezoneOffset() * 60000;
        //let fechaEmision = (new Date(Date.now() - tzoffset)).toISOString().slice(0, -1);
        //let id_cliente = document.getElementById("idcliente").value;
        let nombreRazonSocial = document.getElementById("cliente").value;
        let numeroDocumento = document.getElementById("documento").value;
        let complemento = document.getElementById("complemento_id").value;
        let tipoDocumentoIdentidad = document.getElementById("tipo_documento").value;
        let montoTotal = (this.calcularTotal.toFixed(2));
        let descuentoAdicional = document.getElementById("descuentoAdicional").value;
        let usuario = document.getElementById("usuarioAutenticado").value;
        let codigoPuntoVenta = document.getElementById("puntoVentaAutenticado").value;
        //let codigoPuntoVenta = this.puntoVentaAutenticado;
        let montoGiftCard = document.getElementById("descuentoGiftCard").value;
        let codigoMetodoPago = this.idtipo_pago;
        let montoTotalSujetoIva = montoTotal - this.descuentoGiftCard;
        let correo = document.getElementById("email").value;

        let cafc = this.scodigorecepcion === 5 || this.scodigorecepcion === 6 || this.scodigorecepcion === 7
        ? document.getElementById("cafc").value
        : null;

        let fechaEmision;
            if (this.scodigorecepcion === 5 || this.scodigorecepcion === 6 || this.scodigorecepcion === 7) {
                let fechaSeleccionada = new Date(this.fecha);
                let tzoffset = fechaSeleccionada.getTimezoneOffset() * 60000;
                fechaEmision = new Date(fechaSeleccionada - tzoffset).toISOString().slice(0, -1);
            } else {
                let tzoffset = (new Date()).getTimezoneOffset() * 60000;
                fechaEmision = new Date(Date.now() - tzoffset).toISOString().slice(0, -1);
            }

        console.log("El monto de Descuento de Gift Card es: " + this.descuentoGiftCard);
        console.log("El tipo de documento es: " + tipoDocumentoIdentidad);
        console.log("El complemento de documento es: " + complemento);
        console.log("hola monto toal: " + this.calcularTotal.toFixed(2));

        try {
            const response = await axios.get('/factura/obtenerLeyendaAleatoria');
            this.leyendaAl = response.data.descripcionLeyenda;
            console.log("El dato de leyenda llegado es: " + this.leyendaAl);
        } catch (error) {
            console.error(error);
            return '"Ley N° 453: Los servicios deben suministrarse en condiciones de inocuidad, calidad y seguridad."';
        }

        try {
                if (tipoDocumentoIdentidad === '5') {
                    const response = await axios.post('/factura/verificarNit/' + numeroDocumento);
                    if (response.data === 'NIT ACTIVO') {
                        me.codigoExcepcion = 0;
                        alert("NIT VÁLIDO.");
                    } else {
                        me.codigoExcepcion = 1;
                        alert("NIT INVÁLIDO.");
                    }
                }else{
                    me.codigoExcepcion = 0;
                }
            } catch (error) {
                console.error(error);
                return 'No se pudo verificar el NIT';
            }

            let codigoControl1 = document.getElementById("codigoControl");
            let codigoControl = codigoControl1.textContent;    
            let cufdValor = document.getElementById("cufdValor");
            let cufd = cufdValor.textContent;
            if (this.scodigorecepcion === 5 || this.scodigorecepcion === 6 || this.scodigorecepcion === 7) {
                    try {
                        const response = await axios.get('/api/facturas/ultimo');
                        const data = response.data;
                        
                        cufd = data.cufd;
                        codigoControl = data.codigoControl;
                    } catch (error) {
                        console.error('Error al obtener el último registro de facturas:', error);
                    }
            }
            console.log("El cufd es: " + cufd);
            console.log("El codigo control es: " + codigoControl);

        var factura = [];
        factura.push({
            cabecera: {
                nitEmisor: "5153610012",
                razonSocialEmisor: "365 SOFT",
                municipio: "Cochabamba",
                telefono: "77490451",
                numeroFactura: numeroFactura,
                cuf: cuf,
                cufd: cufd,
                codigoSucursal: 0,
                direccion: direccion,
                codigoPuntoVenta: codigoPuntoVenta,
                fechaEmision: fechaEmision,
                nombreRazonSocial: nombreRazonSocial,
                codigoTipoDocumentoIdentidad: tipoDocumentoIdentidad,
                numeroDocumento: numeroDocumento,
                complemento: complemento,
                codigoCliente: numeroDocumento,
                codigoMetodoPago: codigoMetodoPago,
                numeroTarjeta: this.numeroTarjeta,
                montoTotal: montoTotal,
                montoTotalSujetoIva: montoTotalSujetoIva,
                codigoMoneda: 1,
                tipoCambio: 1,
                montoTotalMoneda: montoTotal,
                montoGiftCard: this.descuentoGiftCard,
                descuentoAdicional: descuentoAdicional,
                codigoExcepcion: this.codigoExcepcion,
                cafc: cafc,
                leyenda: this.leyendaAl,
                usuario: usuario,
                codigoDocumentoSector: 1
            }
        })
        me.arrayFactura.forEach(function (prod) {
            factura.push({ detalle: prod })
        })

        var datos = { factura };

        axios.post('/venta/paqueteFactura', {
            factura: datos,
            //id_cliente: id_cliente,
            fechaEmision: fechaEmision,
            cufd: cufd,
            cafc: cafc,
            idventa: idventa,
            correo: correo,
            codigoControl: codigoControl
        })
            .then(function (response) {
                var data = response.data;
                console.log(data);

                if (data.message === "Factura registrada correctamente") {
                me.visiblePago = false;
                me.visibleFull = false;
                swal(
                    'FACTURA REGISTRADA',
                    'Correctamente',
                    'success'
                )
                me.arrayProductos = [];
                me.codigoExcepcion = 0;
                me.idtipo_pago = '';
                me.descuentoGiftCard = '';
                me.numeroTarjeta =  null;
                me.recibido = '';
                me.email = '';
                me.metodoPago = '';
                me.cerrarModal2();
                me.listarVenta(1, '', 'id');
                me.mostrarSpinner = false;
            }
            })
            .catch(function (error) {
                console.error(error);
                me.visiblePago = false;
                me.visibleFull = false;
                me.arrayFactura = [];
                me.codigoExcepcion = 0;
                swal(
                    'INTENTE DE NUEVO',
                    'Comunicacion con SIAT fallida',
                    'error');
                me.mostrarSpinner = false;
                me.idtipo_pago = '';
                me.numeroTarjeta =  null;
                me.descuentoGiftCard = '';
                me.recibido = '';
                me.email = '';
                me.metodoPago = '';
                swal({
                title: 'ERROR AL REGISTRAR LA FACTURA',
                type: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
            })
            });
        },

        mostrarDetalle() {
            let me = this;
            me.selectAlmacen();
            me.listado = 0;
            
            me.nombreCliente = '';
            me.idcliente = 0;
            me.tipo_documento = '';
            me.complemento_id = '';
            me.documento = '';
            me.email = '';
            me.cafc = '';
            me.idproveedor = 0;
            me.tipo_comprobante = 'TICKET';
            me.serie_comprobante = '';
            me.nextNumber();
            //me.num_comprobante = '';
            me.impuesto = 0.18;
            me.total = 0.0;
            me.codigoComida = 0;
            me.articulo = '';
            me.cantidad = 0;
            me.precio = 0;
            me.arrayDetalle = [];
        },

        ocultarDetalle() {
            this.listado = 1;
            this.codigo = null;
            this.arrayArticulo.length = 0;
            this.precioseleccionado = null;
            this.medida = null;
            this.nombreCliente = null;
            this.documento = null;
            this.email = null;

        },

        verVenta(id) {
            let me = this;
            me.listado = 2;

            //Obtener datos del ingreso
            var arrayVentaT = [];
            var url = '/venta/obtenerCabecera?id=' + id;

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                arrayVentaT = respuesta.venta;

                me.cliente = arrayVentaT[0]['nombre'];
                me.tipo_comprobante = arrayVentaT[0]['tipo_comprobante'];
                me.serie_comprobante = arrayVentaT[0]['serie_comprobante'];
                me.num_comprobante = arrayVentaT[0]['num_comprobante'];
                me.impuesto = arrayVentaT[0]['impuesto'];
                me.total = arrayVentaT[0]['total'];
            })
                .catch(function (error) {
                    console.log(error);
                });

            //obtener datos de los detalles
            var url = '/venta/obtenerDetalles?id=' + id;

            axios.get(url).then(function (response) {
                //console.log(response);
                var respuesta = response.data;
                me.arrayDetalle = respuesta.detalles;

            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        cerrarModal() {
            this.modal = 0;
            this.tituloModal = '';
            me.idproveedor = 0;
                    me.cliente = '';
                    me.tipo_comprobante = 'TICKET';
                    me.serie_comprobante = '';
                    me.num_comprob = '';
                    me.impuesto = 0.18;
                    me.total = 0.0;
                    me.codigoComida = 0;
                    me.articulo = '';
                    me.cantidad = 0;
                    me.precio = 0;
                    me.stock = 0;
                    me.codigo = '';
                    me.descuento = 0;
                    me.arrayDetalle = [];
        },
        cerrarModal2() {
            this.modal2 = 0;
            this.tituloModal2 = '';
            this.idtipo_pago = '';
            this.tipoPago = '';
        },
        abrirModal() {
            if (this.idAlmacen == 0) {
                return;
            }
            //this.listarArticulo();
            this.modal = 1;
            this.tituloModal = 'Seleccione los articulos que desee';

        },

        calcularCambio() {
            // Convierte this.recibido a un número
            // cambio /parseFloat(monedaVenta[0])).toFixed(2)
            const recibidoNumero = parseFloat(this.recibido / parseFloat(this.monedaVenta[0]));
            if (recibidoNumero === 0) {
                this.efectivo = recibidoNumero;
                console.log('EFECTIVO', this.efectivo);
                this.cambio = 0;
                this.faltante = 0;
            } else if (recibidoNumero < this.calcularTotal) {
                this.efectivo = recibidoNumero;
                this.faltante = (this.calcularTotal - this.efectivo).toFixed(2);
            } else if (recibidoNumero === this.calcularTotal) {
                this.efectivo = recibidoNumero;
                this.cambio = 0;
                this.faltante = 0;
            } else {
                this.efectivo = recibidoNumero;
                this.cambio = (this.efectivo - this.calcularTotal).toFixed(2);
                this.faltante = 0;
            }
        },

        desactivarVenta(id) {
            swal({
                title: 'Esta seguro de anular esta venta?',
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

                    axios.put('/venta/desactivar', {
                        'id': id
                    }).then(function (response) {
                        me.listarVenta(1, '', 'num_comprobante');
                        swal(
                            'Anulado!',
                            'La venta ha sido anulado con éxito.',
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



    created() {
        // Realiza una solicitud AJAX para obtener los datos de sesión
        axios.get('/obtener-datos-sesion')
        .then(response => {
            this.scodigorecepcion = response.data.scodigorecepcion;
            console.log('Valor de scodigorecepcion:', this.scodigorecepcion);
            return axios.get('/ruta-a-tu-endpoint-laravel-para-obtener-ultimo-comprobante');
        })
        .then(response => {
            const lastComprobante = response.data.last_comprobante;
            this.last_comprobante = lastComprobante;
            this.nextNumber();
        })
        .catch(error => {
            console.error('Error al obtener datos de sesión o el último comprobante:', error);
        });
    },


    mounted() {
        this.listarVenta(1, this.buscar, this.criterio);
        window.addEventListener('keydown', this.atajoButton);
        this.verificarComunicacion();
        this.cuis();
        this.cufd();
        this.obtenerDatosUsuario();
        //this.listarArticulo(1, this.buscar, this.criterio);

        this.listarMenu(this.buscar, this.criterio);
        this.listarProducto(1, this.buscar, this.criterio);
        this.getCategoriasMenu();
        this.getCategoriasProductos();

        this.updateButtonStyle();
        window.addEventListener('resize', this.updateButtonStyle);
    },

    beforeDestroy() {
        window.removeEventListener('resize', this.updateButtonStyle);
    }
}
</script>

<style >

/* Sidebar */
.p-panel .p-panel-header {
    padding: 10px;
}

.p-panel .p-panel-content {
    padding: 10px 3px;
}
/* --------------------------------------------*/

/* Botones -> Card */
.project-card {
    border: 2px solid #ccc;
    border-radius: 25px;
    padding: 0px;
    margin-bottom: 0px;
    cursor: pointer;

    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.project-card:hover {
    border-color: #3b82f6;
}

.p-button {
    padding: 0px;
}

.p-card .p-card-content {
    padding: 0px;
}

.p-card .p-card-body {
    padding: 2px;
}

.p-card .p-card-footer {
    padding: 0px 1px 1px 1px;
}

.product-name {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.product-button {
    border: 0px solid #ff0000;
    border-radius: 25px;
    padding: 0;
    margin: 0;
}

.image-container {
    overflow: hidden;
    border-radius: 25px 25px 0 0;
}

.product-image {
    width: 100%;
    height: auto;
}

.footer-content {
    display: flex;
    justify-content: space-between;
    padding-top: 0;
    margin-left: 15px;
}

.price {
    margin-top: 10px;
    font-weight: bold;
    font-size: larger;
}

.rounded-bottom-right {
    border-bottom-right-radius: 20px;
}

@media (min-width: 1368px) {
    .product-container {
        width: calc(100% / 8);
    }
}

@media (max-width: 1367px) and (min-width: 992px) {
    .product-container {
        width: calc(100% / 6);
    }
}

@media (max-width: 991px) and (min-width: 768px) {
    .product-container {
        width: calc(100% / 4);
    }
}

@media (max-width: 767px) {
    .product-container {
        width: calc(100% / 2);
    }
}

.floating-buttons {
    position: fixed;
    bottom: 90px;
    right: 20px;
    z-index: 1000;
}

.floating-button {
    padding: 25px 35px;
    border: none;
    border-radius: 8px;
    font-size: 28px;
    cursor: pointer;
}

/* Sidebar full*/
.sidebar-full {
    padding-right: 20px;
}

.spinner-container {
        position: relative;
    }

    .spinner-container > * {
        position: absolute; 
        top: 50%; 
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .spinner-message {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translate(-50%, -170%);
        z-index: 1;
    }

    .refresh-button {
        position: absolute;
        top: 0;
        right: 0;
        margin-top: 10px; /* Ajusta el margen superior según sea necesario */
        margin-right: 10px; /* Ajusta el margen derecho según sea necesario */
    }

    .refresh-button button {
        background-color: transparent;
        border: none;
        padding: 0;
        cursor: pointer;
        font-size: 18px; /* Ajusta el tamaño del icono según sea necesario */
    }

</style>
