<template>
    <main class="main">
        <!-- Breadcrumb -->
        <!-- <ol class="breadcrumb">
            <li class="breadcrumb-item"><a class="text-decoration-none" href="/">Escritorio</a></li>
        </ol> -->
        <div class="container-fluid">
            <div class="row d-flex mt-2 mb-2 justify-content-end align-items-center">
                <label class="col-auto text-primary">Periodo</label>
                <div class="col-md-2 col-auto">
                    <select class="form-select" v-model="tipoPeriodo">
                        <option selected value="Mes">Este mes</option>
                        <option value="Año">Este año</option>
                        <option value="Personalizado">Personalizado</option>
                    </select>
                </div>
                <template v-if="tipoPeriodo == 'Personalizado'">

                    <div class="col-auto">
                        <label for="fechaInicio">Fecha de Inicio:</label>

                        <input type="date" class="form-control" id="fechaInicio" @change="fetchData()"
                            v-model="fechaInicio">
                    </div>

                    <div class="col-auto">
                        <label for="fechaFin">Fecha de Fin:</label>

                        <input type="date" class="form-control" id="fechaFin" @change="fetchData()" v-model="fechaFin">
                    </div>
                    <!-- <div class="col-auto">
        <button class="btn btn-primary" @click="obtenerTopVendedores">Obtener Top de Vendedores</button>
    </div> -->
                </template>

            </div>

            <div class="row d-flex justify-content-between">
                <square-item :icono="'fa fa-usd'" :titulo="'Ventas'" :moneda="'BS'"
                    :cantidad="(sumaVentas).toFixed(2)"
                    :fondoDegradado="'linear-gradient(35deg, #028bd2, #6dd3dd)'" />
                <square-item :icono="'fa fa-shopping-cart'" :titulo="'Gastos'" :moneda="'BS'"
                    :cantidad="(sumaCompras).toFixed(2)"
                    :fondoDegradado="'linear-gradient(35deg, #f67318, #f9ca38)'" />
                <square-item :icono="'fa fa-angle-double-up'" :titulo="'Ganancias'" :moneda="'BS'"
                    :cantidad="sumaVentas - sumaCompras" :fondoDegradado="'linear-gradient(35deg, #3b9c3f, #41d445)'" />

            </div>

            <div class="">

                <div class="car-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-chart">
                                <div class="card-header">
                                    <h4>Compras</h4>
                                </div>
                                <div class="card-content">
                                    <div class="ct-chart">
                                        <canvas id="ingresos">
                                        </canvas>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <p>Compras de los últimos meses.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-chart">
                                <div class="card-header">
                                    <h4>Ventas</h4>
                                </div>
                                <div class="card-content">
                                    <div class="ct-chart">
                                        <canvas id="ventas">
                                        </canvas>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <p>Ventas de los últimos meses.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--<div class="row">
                    <div class="col-md-4">
                        <TopArticulos :fechaInicio="fechaInicio" :fechaFin="fechaFin" />
                    </div>
                    <div class="col-md-4">
                        <TopClientes :fechaInicio="fechaInicio" :fechaFin="fechaFin" :moneda="monedaPrincipal"/>
                    </div>
                    <div class="col-md-4">
                        <TopVendedores :fechaInicio="fechaInicio" :fechaFin="fechaFin" :moneda="monedaPrincipal"/>
                    </div>
                </div>-->

                <div class="row">
                    <div class="col-md-6">
                        <productosbajostock></productosbajostock>

                    </div>

                    <div class="col-md-6">
                        <productosporvencerse></productosporvencerse>

                    </div>
                </div>


            </div>
        </div>

    </main>
</template>
<script>
import axios from 'axios';

export default {
    data() {

        const today = new Date();
        const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
        const lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

        const formattedStartDate = this.formatDate(firstDayOfMonth);
        const formattedEndDate = this.formatDate(lastDayOfMonth);

        return {
            monedaPrincipal: [],
            tipoPeriodo: "Mes",
            sumaVentas: 0,

            charIngreso: null,
            ingresos: [],

            sumaCompras: 0,
            charVenta: null,
            ventas: [],

            fechaInicio: formattedStartDate,
            fechaFin: formattedEndDate
        }
    },
    watch: {
        tipoPeriodo(newValue) {
            this.obtenerDiaFechaActual();
            this.fetchData();

        },
    },

    methods: {
        async datosConfiguracion() {
            try {
                const response = await axios.get('/configuracion');
                const respuesta = response.data;
                this.monedaPrincipal = [respuesta.configuracionTrabajo.valor_moneda_principal, respuesta.configuracionTrabajo.simbolo_moneda_principal];
            } catch (error) {
                console.log(error);
            }
        },
        formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        },
        obtenerDiaFechaActual() {
            const fechaActual = new Date();
            if (this.tipoPeriodo === 'Mes') {
                this.fechaInicio = (new Date(fechaActual.getFullYear(), fechaActual.getMonth(), 1)).toISOString().split('T')[0];;
                this.fechaFin = (new Date(fechaActual.getFullYear(), fechaActual.getMonth() + 1, 0)).toISOString().split('T')[0];;
            } else if (this.tipoPeriodo === 'Año') {
                this.fechaInicio = (new Date(fechaActual.getFullYear(), 0, 1)).toISOString().split('T')[0];;
                this.fechaFin = (new Date(fechaActual.getFullYear(), 11, 31)).toISOString().split('T')[0];;
            }

        },
        fetchData() {

            axios.get('/dashboard', {
                params: {
                    fecha_inicio: this.fechaInicio,
                    fecha_fin: this.fechaFin
                }
            })
                .then(response => {
                    const respuesta = response.data;
                    this.ingresos = respuesta.ingresos.map(item => {
                        item.total;
                        return item;
                    });
                    console.log(this.ingresos);
                    this.ventas = respuesta.ventas.map(item => {
                        item.total;
                        return item;
                    });
                    this.loadIngresos();
                    this.loadVentas();
                })
                .catch(error => {
                    console.log(error);
                });
        },

        loadChart(tipo, data, chartElement, color) {
            const arrayMes = [];
            const arrayTotal = [];
            data.forEach(item => {
                arrayMes.push(item.mes);
                arrayTotal.push(item.total);
            });
            const nombresMeses = [
                'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
            ];
            const meses = arrayMes.map(numero => nombresMeses[numero - 1]);
            if (tipo == "compras") {
                this.sumaCompras = arrayTotal.reduce((total, valor) => total + parseFloat(valor), 0);
            } else {
                this.sumaVentas = arrayTotal.reduce((total, valor) => total + parseFloat(valor), 0);

            }
            return new Chart(chartElement, {
                type: 'bar',
                data: {
                    labels: meses,
                    datasets: [{
                        label: 'Total de ' + tipo,
                        data: arrayTotal,
                        backgroundColor: color,
                        borderColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        },
        loadIngresos() {

            this.charIngreso = this.loadChart('compras',
                this.ingresos,
                document.getElementById('ingresos').getContext('2d'),
                '#fec71f'
            );

        },
        loadVentas() {

            this.charVenta = this.loadChart(
                "ventas",
                this.ventas,
                document.getElementById('ventas').getContext('2d'),
                'rgb(54, 162, 235)'
            );
        }
    },
    mounted() {
        this.datosConfiguracion();
        this.fetchData();
    }
}
</script>
