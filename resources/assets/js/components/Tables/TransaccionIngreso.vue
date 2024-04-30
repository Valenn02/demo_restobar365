
<template>
    <div class="table-responsive">
        <TableBase :items="items" :rows="rows" :per-page="perPage" :fields="fields"
            @change-pagination="changePaginationCurrent" />
    </div>
</template>

<script >
import TableBase from './TableBase.vue'
import axios from 'axios';
export default {
    props: {
        data: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            paginated: {
                current_page: null,
                first_page_url: null,
                from: 1,
                last_page: null,
                last_page_url: null,
                next_page_url: null,
                path: null,
                per_page: null,
                prev_page_url: null,
                to: null,
                total: null,
            },

            perPage: 0,
            currentPage: 1,
            // fields: ['N', 'Fecha', 'Proveedor', 'Tipo Comprobante', 'Número Comprobante', 'importe', 'importe', 'Usuario'],
            fields: [
                {
                    key: 'id',
                    label: 'N',
                },
                {
                    key: 'fecha_hora',
                    label: 'Fecha',

                },
                {
                    key: 'nombre',
                    label: 'Cliente',

                },
                {
                    key: 'tipo_comprobante',
                    label: 'Tipo Comprobante',
                },
                {
                    key: 'num_comprobante',
                    label: 'Número Comprobante',
                },
                {
                    key: 'total',
                    label: 'Importe',

                },
                {
                    key: 'usuario',
                    label: 'Usuario',
                }
            ],
            items: []
        }
    },
    created() {
        // this.paginated = this.data.paginated;
        this.items = this.data.data;
        this.perPage = this.data.per_page;
    },
    components: {
        TableBase
    },
    computed: {
        rows() {
            // return this.items.length
            return this.data.total;
        }
    },
    methods: {
        changePaginationCurrent(pageNum) {
            this.getDatawithPage(pageNum);
        },
        getDatawithPage(pageNumber) {
            const url = new URL(this.data.path);
            const ruta = url.pathname.substring(1); // Elimina 
            console.log(`${ruta}?page=${pageNumber}`);
            axios.get(`${ruta}?page=${pageNumber}`)
                .then((response) => {
                    const respuesta = response.data;
                    // me.arrayVenta = respuesta.ventas.data;
                    // me.pagination = respuesta.pagination;
                    this.items = response.data.ingresos.data;
                    this.perPage = response.data.ingresos.per_page;

                })
                .catch((error) => {
                    console.log(error);
                });
        }
    },

}
</script>

