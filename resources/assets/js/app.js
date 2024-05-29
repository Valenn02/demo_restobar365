
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vue from 'vue'

import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import Toasted from "vue-toasted";
import { BootstrapVue, IconsPlugin } from "bootstrap-vue";

import 'primeflex/primeflex.css';

import 'primevue/resources/themes/lara-light-blue/theme.css';
import 'primevue/resources/primevue.min.css';
import 'primeicons/primeicons.css';

//import "bootstrap/dist/css/bootstrap.css";
//import "bootstrap-vue/dist/bootstrap-vue.css";

Vue.use(PrimeVue);
Vue.use(ToastService);

require('./bootstrap');

window.$ = window.jQuery = require('jquery');
window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component("square-item", require("./components/dashboard/squareItem.vue"));
Vue.component(
  "TopVendedores",
  require("./components/dashboard/TopVendedores.vue")
);
Vue.component("TopClientes", require("./components/dashboard/TopClientes.vue"));
Vue.component(
  "TopArticulos",
  require("./components/dashboard/TopArticulos.vue")
);
Vue.component("icon-button", require("./components/buttons/IconButton.vue"));

Vue.component('empresa', require('./components/Empresa.vue'));
Vue.component('sucursal', require('./components/Sucursal.vue'));
Vue.component('moneda', require('./components/Moneda.vue'));
Vue.component('caja', require('./components/Caja.vue'));
Vue.component('categoria', require('./components/Categoria.vue'));
Vue.component('articulo', require('./components/Articulo.vue'));

Vue.component('ingreso', require('./components/Ingreso.vue'));
Vue.component('cliente', require('./components/Cliente.vue'));
Vue.component('proveedor', require('./components/Proveedor.vue'));
Vue.component('rol', require('./components/Rol.vue'));
Vue.component('user', require('./components/User.vue'));
Vue.component('venta', require('./components/Venta.vue'));

Vue.component('dashboard', require('./components/Dashboard.vue'));
Vue.component('consultaingreso', require('./components/ConsultaIngreso.vue'));
Vue.component('consultaventa', require('./components/ConsultaVenta.vue'));
Vue.component('notification', require('./components/Notification.vue'));

Vue.component('editarperfil', require('./components/EditarPerfil.vue'));
Vue.component('registrarcompra', require('./components/compras/RegistrarCompra.vue'));
Vue.component('modalagregarproductos', require('./components/productos/ModalAgregarProductos.vue'));
Vue.component('detallepedidosproveedor', require('./components/PedidosProveedor/DetallePedidoProveedor.vue'));

Vue.component('marca', require('./components/Marca.vue'));
Vue.component('linea', require('./components/Linea.vue'));
Vue.component('industria', require('./components/Industria.vue'));
Vue.component('configuracion', require('./components/Configuracion.vue'));
Vue.component('cotizacioncompras', require('./components/CotizacionCompras.vue'));
Vue.component('cotizacionventas', require('./components/CotizacionVentas.vue'));
Vue.component('almacenes', require('./components/Almacens.vue'));
Vue.component('inventarios', require('./components/Inventario.vue'));
Vue.component('grupos', require('./components/Grupo.vue'));
Vue.component('monitoreoproductos', require('./components/MonitoreoProductos.vue'));
Vue.component('productosbajostock', require('./components/ProductosBajoStock.vue'));
Vue.component('productosvencidos', require('./components/ProductosVencidos.vue'));
Vue.component('productosporvencerse', require('./components/ProductosPorVencerse.vue'));
Vue.component('traspasoproducto', require('./components/Traspaso.vue'));
Vue.component('medidas', require('./components/Medidas.vue'));
Vue.component('factura', require('./components/Factura.vue'));
Vue.component('sincronizacionactividades', require('./components/SincronizarActividades.vue'));
Vue.component('sincronizarparametricatiposfactura', require('./components/SincronizarTiposFactura.vue'));
Vue.component('sincronizarlistaleyendasfactura', require('./components/SincronizarLeyendasFactura.vue'));
Vue.component('sincronizarproductosservicios', require('./components/SincronizarProductosServicios.vue'));
Vue.component('sincronizarmotivoanulacion', require('./components/SincronizarMotivoAnulacion.vue'));
Vue.component('sincronizareventossignificativos', require('./components/SincronizarEventosSignificativos.vue'));
Vue.component('sincronizarunidadmedida', require('./components/SincronizarUnidadMedida.vue'));
Vue.component('eventossignificativos', require('./components/EventosSignificativos.vue'));
Vue.component('facturafueralinea', require('./components/FacturaFueraLinea.vue'));
Vue.component('puntoventa', require('./components/PuntoVenta.vue'));
Vue.component('rolventa', require('./components/RegistroVentas.vue'));
Vue.component('devoluciones', require('./components/Devoluciones.vue'));
Vue.component('reporteventas', require('./components/ReporteVentasDiarias.vue'));
Vue.component('menulist', require('./components/MenuLista.vue'));
Vue.component('categoriamenu', require('./components/CategoriaMenu.vue'));
Vue.component('qr', require('./components/Qrvista.vue'));
Vue.component('reporteventastabla', require('./components/ReporteVentasTabla.vue'));

Vue.component("reporteventasdetallado", require("./components/ReporteVentasDetallado.vue"));
Vue.component("reporteinventariofisico",require("./components/ReporteInventarioFisico.vue"));

Vue.use(BootstrapVue);
Vue.use(Toasted);

const app = new Vue({
    el: '#app',
    data: {
        menu: 0,
        notifications: [],
    },
    mounted() {
        console.log('Vue app mounted');
    },
    created() {
        let me = this;
        axios.post('notification/get').then(function(response) {
            me.notifications = response.data;
        }).catch(function(error) {
            console.log(error);
        });

        var userId = $('meta[name="userId"]').attr('content');

        Echo.private('App.User.' + userId).notification((notification) => {
            me.notifications.unshift(notification);
        });
    }
});
