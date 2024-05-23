<template>
    <div class="card border">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr class="table-light">
                        <th colspan="9">Productos con bajo stock (requieren reposición)

                            <button type="button" @click="cargarExcel()" class="btn btn-light">
                                <i class="fa fa-download" aria-hidden="true"></i>
                                &nbsp;Descargar
                            </button>
                        </th>

                    </tr>
                    <tr>
                        <th>Codigo</th>
                        <th>Nombre de articulo</th>
                        <th>Proveedor</th>
                        <th>Almacen</th>

                        <th>En Stock</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="inventario in arrayInventario" :key="inventario.id">
                        <td :style="{ backgroundColor: inventario.saldo_stock == 0 ? '#f3dedf' : '#fdf8e2' }"
                            v-text="inventario.codigo"></td>
                        <td :style="{ backgroundColor: inventario.saldo_stock == 0 ? '#f3dedf' : '#fdf8e2' }"
                            v-text="inventario.nombre_producto"></td>
                        <td :style="{ backgroundColor: inventario.saldo_stock == 0 ? '#f3dedf' : '#fdf8e2' }"
                            v-text="inventario.nombre_proveedor"></td>
                        <td :style="{ backgroundColor: inventario.saldo_stock == 0 ? '#f3dedf' : '#fdf8e2' }"
                            v-text="inventario.nombre_almacen"></td>
                        <td :style="{ backgroundColor: inventario.saldo_stock == 0 ? '#f3dedf' : '#fdf8e2' }"
                            v-text="inventario.saldo_stock"></td>
                        <td :style="{ backgroundColor: inventario.saldo_stock === 0 ? '#f3dedf' : '#fdf8e2' }">
                            <template v-if="inventario.saldo_stock === 0">
                                <span class="badge badge-pill badge-danger">No hay existencias</span>
                            </template>
                            <template v-else>
                                <span class="badge badge-pill badge-warning">Quedan pocas existencias</span>
                            </template>
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
                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page"></a>
                </li>
                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                    <a class="page-link" href="#"
                        @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                </li>
            </ul>
        </nav>
    </div>
</template>
<script>
export default {
    data() {
        return {
            arrayInventario: [],

            pagination: {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0,
            },
            offset: 3,
            criterio: 'nombre_almacen',
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
        cambiarPagina(page, buscar, criterio) {
            let me = this;
            me.pagination.current_page = page;
            me.listarInventario(page, buscar, criterio);
        },
        listarInventario(page, buscar, criterio) {
            let me = this;
            var url = '/inventarios/productosbajostock?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                //console.log("lista almacen:",respuesta);
                me.arrayInventario = respuesta.inventarios.data;
                me.pagination = respuesta.pagination;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        cargarExcel() {
            window.open('/inventarios/listarReporteBajoStockExcel', '_blank');
        },

    },
    mounted() {
        this.listarInventario(1, this.buscar, this.criterio);
    }
}
</script>