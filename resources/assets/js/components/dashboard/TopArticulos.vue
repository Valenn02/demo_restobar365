<template>
    <div class="card">
        <div class="card-header  font-weight-bold">
            <i class="fa fa-th-large" aria-hidden="true"></i>
            ARTICULOS MAS VENDIDOS
        </div>
        <div class="table-responsive"  v-if="topArticulosOrdenados.length > 0">


        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">N°</th>

                    <th scope="col" @click="ordenar('nombreArticulo')" style="cursor: pointer;">
                        Nombre del articulo
                        <i class="fa fa-sort" v-if="criterioOrdenacion !== 'nombreArticulo'"></i>
                        <i class="fa fa-sort-asc" v-if="criterioOrdenacion === 'nombreArticulo' && ordenAscendente"></i>
                        <i class="fa fa-sort-desc" v-if="criterioOrdenacion === 'nombreArticulo' && !ordenAscendente"></i>
                    </th>

                    <th scope="col" @click="ordenar('cantidadTotal')" style="cursor: pointer;">
                        Cantidad vendida
                        <i class="fa fa-sort" v-if="criterioOrdenacion !== 'cantidadTotal'"></i>
                        <i class="fa fa-sort-asc" v-if="criterioOrdenacion === 'cantidadTotal' && ordenAscendente"></i>
                        <i class="fa fa-sort-desc" v-if="criterioOrdenacion === 'cantidadTotal' && !ordenAscendente"></i>
                    </th>

                    <th scope="col" @click="ordenar('vecesVendido')" style="cursor: pointer;">
                        Veces vendidas
                        <i class="fa fa-sort" v-if="criterioOrdenacion !== 'vecesVendido'"></i>
                        <i class="fa fa-sort-asc" v-if="criterioOrdenacion === 'vecesVendido' && ordenAscendente"></i>
                        <i class="fa fa-sort-desc" v-if="criterioOrdenacion === 'vecesVendido' && !ordenAscendente"></i>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(articulo, index) in topArticulosOrdenados.slice(0, 5)" :key="index">
                    <th scope="row">{{ index + 1 }}</th>
                    <td>{{ articulo.nombreArticulo }}</td>
                    <td>{{ articulo.cantidadTotal }}</td>
                    <td>{{ articulo.vecesVendido  }}</td>
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
        }
    },
    data() {
        return {
            topArticulos: [],
            criterioOrdenacion: '',
            ordenAscendente: true
        };
    },
    computed: {
        topArticulosOrdenados() {
            // Hacer una copia de los topArticulos para no modificar el array original
            let topArticulosCopia = [...this.topArticulos];

            // Aplicar la lógica de ordenación según el criterio y el orden
            if (this.criterioOrdenacion) {
                topArticulosCopia.sort((a, b) => {
                    if (a[this.criterioOrdenacion] < b[this.criterioOrdenacion]) {
                        return this.ordenAscendente ? -1 : 1;
                    }
                    if (a[this.criterioOrdenacion] > b[this.criterioOrdenacion]) {
                        return this.ordenAscendente ? 1 : -1;
                    }
                    return 0;
                });
            }
            return topArticulosCopia;
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
        obtenerTopArticulos() {
            const fechaInicioFormateada = this.formatoFecha(this.fechaInicio);
            const fechaFinFormateada = this.formatoFecha(this.fechaFin);

            axios.get('/top-articulos', {
                params: {
                    fecha_inicio: fechaInicioFormateada,
                    fecha_fin: fechaFinFormateada
                }
            })
                .then(response => {
                    this.topArticulos = response.data.topProductos;
                })
                .catch(error => {
                    console.error('Error al obtener el top de articulos:', error);
                });
        },
    },
    watch: {
        fechaInicio: function (newValue) {
            this.obtenerTopArticulos();
        },
        fechaFin: function (newValue) {
            this.obtenerTopArticulos();
        }
    },
    mounted() {
        this.obtenerTopArticulos();
    }
};
</script>
  

  