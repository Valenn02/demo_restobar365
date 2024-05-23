<template>
    <div class="card">
        <div class="card-header  font-weight-bold">
            <i class="fa fa-th-large" aria-hidden="true"></i>
            TOP VENDEDORES
        </div>

        <div class="table-responsive"  v-if="topVendedoresOrdenados.length > 0">

        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">N°</th>

                    <th scope="col" @click="ordenar('nombreUsuario')" style="cursor: pointer;">
                        Nombre vendedor
                        <i class="fa fa-sort" v-if="criterioOrdenacion !== 'nombreUsuario'"></i>
                        <i class="fa fa-sort-asc" v-if="criterioOrdenacion === 'nombreUsuario' && ordenAscendente"></i>
                        <i class="fa fa-sort-desc" v-if="criterioOrdenacion === 'nombreUsuario' && !ordenAscendente"></i>
                    </th>

                    <th scope="col" @click="ordenar('cantidadVentas')" style="cursor: pointer;">
                        Cantidad de ventas
                        <i class="fa fa-sort" v-if="criterioOrdenacion !== 'cantidadVentas'"></i>
                        <i class="fa fa-sort-asc" v-if="criterioOrdenacion === 'cantidadVentas' && ordenAscendente"></i>
                        <i class="fa fa-sort-desc" v-if="criterioOrdenacion === 'cantidadVentas' && !ordenAscendente"></i>
                    </th>

                    <th scope="col" @click="ordenar('totalVentas')" style="cursor: pointer;">
                        Monto de ventas
                        <i class="fa fa-sort" v-if="criterioOrdenacion !== 'totalVentas'"></i>
                        <i class="fa fa-sort-asc" v-if="criterioOrdenacion === 'totalVentas' && ordenAscendente"></i>
                        <i class="fa fa-sort-desc" v-if="criterioOrdenacion === 'totalVentas' && !ordenAscendente"></i>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(vendedor, index) in topVendedoresOrdenados.slice(0, 5)" :key="index">
                    <th scope="row">{{ index + 1 }}</th>
                    <td>{{ vendedor.nombreUsuario }}</td>
                    <td>{{ vendedor.cantidadVentas }}</td>
                    <td>{{ (vendedor.totalVentas*moneda[0]).toFixed(2) }} {{ moneda[1] }}</td>
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
            topVendedores: [],
            criterioOrdenacion: '',
            ordenAscendente: true
        };
    },
    computed: {
        topVendedoresOrdenados() {
            // Hacer una copia de los topVendedores para no modificar el array original
            let topVendedoresCopia = [...this.topVendedores];

            // Aplicar la lógica de ordenación según el criterio y el orden
            if (this.criterioOrdenacion) {
                topVendedoresCopia.sort((a, b) => {
                    if (a[this.criterioOrdenacion] < b[this.criterioOrdenacion]) {
                        return this.ordenAscendente ? -1 : 1;
                    }
                    if (a[this.criterioOrdenacion] > b[this.criterioOrdenacion]) {
                        return this.ordenAscendente ? 1 : -1;
                    }
                    return 0;
                });
            }
            return topVendedoresCopia;
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
        obtenerTopVendedores() {
            const fechaInicioFormateada = this.formatoFecha(this.fechaInicio);
            const fechaFinFormateada = this.formatoFecha(this.fechaFin);

            axios.get('/top-vendedores', {
                params: {
                    fecha_inicio: fechaInicioFormateada,
                    fecha_fin: fechaFinFormateada
                }
            })
                .then(response => {
                    this.topVendedores = response.data.topVendedores;
                })
                .catch(error => {
                    console.error('Error al obtener el top de vendedores:', error);
                });
        },
    },
    watch: {
        fechaInicio: function (newValue) {
            this.obtenerTopVendedores();
        },
        fechaFin: function (newValue) {
            this.obtenerTopVendedores();
        }
    },
    mounted() {
        this.obtenerTopVendedores();
    }
};
</script>
