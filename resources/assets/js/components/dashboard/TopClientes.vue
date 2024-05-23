<template>
    <div class="card">
        <div class="card-header  font-weight-bold">
            <i class="fa fa-th-large" aria-hidden="true"></i>
            CLIENTES FRECUENTES
        </div>
        <div class="table-responsive" v-if="topClientesOrdenados.length > 0">

        <table class="table table-hover" >
            <thead>
                <tr>
                    <th scope="col">N°</th>

                    <th scope="col" @click="ordenar('nombreCliente')" style="cursor: pointer;">
                        Nombre cliente
                        <i class="fa fa-sort" v-if="criterioOrdenacion !== 'nombreCliente'"></i>
                        <i class="fa fa-sort-asc" v-if="criterioOrdenacion === 'nombreCliente' && ordenAscendente"></i>
                        <i class="fa fa-sort-desc" v-if="criterioOrdenacion === 'nombreCliente' && !ordenAscendente"></i>
                    </th>

                    <th scope="col" @click="ordenar('cantidadCompras')" style="cursor: pointer;">
                        Cantidad de compras
                        <i class="fa fa-sort" v-if="criterioOrdenacion !== 'cantidadCompras'"></i>
                        <i class="fa fa-sort-asc" v-if="criterioOrdenacion === 'cantidadCompras' && ordenAscendente"></i>
                        <i class="fa fa-sort-desc" v-if="criterioOrdenacion === 'cantidadCompras' && !ordenAscendente"></i>
                    </th>

                    <th scope="col" @click="ordenar('totalGastado')" style="cursor: pointer;">
                        Total gastado
                        <i class="fa fa-sort" v-if="criterioOrdenacion !== 'totalGastado'"></i>
                        <i class="fa fa-sort-asc" v-if="criterioOrdenacion === 'totalGastado' && ordenAscendente"></i>
                        <i class="fa fa-sort-desc" v-if="criterioOrdenacion === 'totalGastado' && !ordenAscendente"></i>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(cliente, index) in topClientesOrdenados.slice(0, 5)" :key="index">
                    <th scope="row">{{ index + 1 }}</th>
                    <td>{{ cliente.nombreCliente }}</td>
                    <td>{{ cliente.cantidadCompras }}</td>
                    <td>{{ (cliente.totalGastado*moneda[0]).toFixed(2) }} {{ moneda[1] }}</td>
                </tr>
            </tbody>
        </table>
    </div>

        <div v-else>
            <label>Muy pronto...</label>
        </div>
    </div>
</template>
  
<script>
import axios from 'axios';

export default {
    props: {
        fechaInicio: {
            type: String,
            required: true
        },
        fechaFin: {
            type: String,
            required: true
        },
        moneda: {
            type: Array,
            required: true
        },
    },
    data() {
        return {
            topClientes: [],
            criterioOrdenacion: '',
            ordenAscendente: true
        };
    },
    computed: {
        topClientesOrdenados() {
            // Hacer una copia de los topClientes para no modificar el array original
            let topClientesCopia = [...this.topClientes];

            // Aplicar la lógica de ordenación según el criterio y el orden
            if (this.criterioOrdenacion) {
                topClientesCopia.sort((a, b) => {
                    if (a[this.criterioOrdenacion] < b[this.criterioOrdenacion]) {
                        return this.ordenAscendente ? -1 : 1;
                    }
                    if (a[this.criterioOrdenacion] > b[this.criterioOrdenacion]) {
                        return this.ordenAscendente ? 1 : -1;
                    }
                    return 0;
                });
            }
            return topClientesCopia;
        }
    },
    methods: {
        ordenar(criterio) {
            // Si el criterio es el mismo, cambiar el orden
            if (this.criterioOrdenacion === criterio) {
                this.ordenAscendente = !this.ordenAscendente;
            } else {
                this.criterioOrdenacion = criterio;
                this.ordenAscendente = true;
            }
        },
        formatoFecha(fecha) {
            const fechaObj = new Date(fecha);
            const año = fechaObj.getFullYear();
            const mes = fechaObj.getMonth() + 1 < 10 ? `0${fechaObj.getMonth() + 1}` : fechaObj.getMonth() + 1;
            const dia = fechaObj.getDate() < 10 ? `0${fechaObj.getDate()}` : fechaObj.getDate();
            return `${año}-${mes}-${dia}`;
        },
        obtenerTopClientes() {
            const fechaInicioFormateada = this.formatoFecha(this.fechaInicio);
            const fechaFinFormateada = this.formatoFecha(this.fechaFin);

            axios.get('/top-clientes', {
                params: {
                    fecha_inicio: fechaInicioFormateada,
                    fecha_fin: fechaFinFormateada
                }
            })
                .then(response => {
                    this.topClientes = response.data.topClientes;
                })
                .catch(error => {
                    console.error('Error al obtener el top de clientes:', error);
                });
        },
    },
    watch: {
        fechaInicio: function (newValue) {
            this.obtenerTopClientes();
        },
        fechaFin: function (newValue) {
            this.obtenerTopClientes();
        }
    },
    mounted() {
        this.obtenerTopClientes();
    }
};
</script>
  

  