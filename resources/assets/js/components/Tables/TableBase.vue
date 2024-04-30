
<template>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th v-for="(field) in fields" :key="field.key">{{ field.label }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="items.length <= 0">
                    <td colspan="100">
                        Parece que aún no has realizado ningún movimiento.
                    </td>
                </tr>
                <tr v-for="(ingreso, index) in items" :key="ingreso.id">
                    <td v-for="field in fields" :key="field.key" v-text="ingreso[field.key]"></td>
                </tr>
            </tbody>
        </table>

        <!-- <b-table id="my-table" striped hover bordered :items="items" :fields="fields" :per-page="perPage"
            :current-page="currentPage" small></b-table> -->
        <b-pagination v-model="currentPage" :total-rows="rows" :per-page="perPage" aria-controls="my-table"
            @change="changePagination"></b-pagination>
        <!-- <div class="overflow-auto">
            <b-pagination-nav :link-gen="linkGen" :number-of-pages="4" use-router></b-pagination-nav>
        </div> -->
    </div>
</template>

<script >
export default {
    props: {

        items: {
            type: Array,
            required: true
        },
        perPage: {
            type: Number,
            required: true
        },
        // currentPage: {
        //     type: Number,
        //     required: true
        // },
        rows: {
            type: Number,
            required: true
        },
        fields: {
            type: Array,
            required: true
        },
    },
    // emits: ['toggle-favorite'],
    data() {
        return {
            currentPage: 1,
        }
    },
    beforeUpdate() {
        console.log('beforeUpdate', this.$props);
    },
    created() {
        // this.paginated = this.data.paginated;
        // this.items = this.items;
        // this.fields = this.fields;
        // this.currentPage = this.currentPage;
    },
    components: {

    },
    computed: {
        // rows() {
        //     return this.items.length
        // }

    },
    methods: {
        changePagination(CurrentPage) {
            // console.log(CurrentPage);
            this.currentPage = CurrentPage;
            this.$emit('change-pagination', CurrentPage);
        },
        linkGen(pageNum) {
            return pageNum === 1 ? '?' : `?page=${pageNum}`
        }
    },
    watch: {

    }

}
</script>

