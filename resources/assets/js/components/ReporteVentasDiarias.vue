<template>
    <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        
    </ol>
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Reporte de Ventas Diarias
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <input type="date" v-model="fecha" class="form-control" />
                        <select v-model="idcategoria" class="form-control">
                            <option value="0" disabled>Seleccione</option>
                            <option value="all">Todas las categorías</option>
                            <option v-for="categoria in arrayCategoria" :key="categoria.id" :value="categoria.id"
                            v-text="categoria.nombre"></option>
                        </select>
                        <button type="submit" @click="generarReporte" class="btn btn-primary">
                            <i class="fa fa-search"></i> Generar Reporte
                        </button>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Total</th>
                            <th>Número Factura</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="venta in arrayVentas" :key="venta.id">
                            <td v-text="venta.cliente"></td>
                            <td v-text="venta.articulo"></td>
                            <td v-text="venta.cantidad"></td>
                            <td v-text="venta.precio"></td>
                            <td>
                            {{ (venta.precio * venta.cantidad).toFixed(2) }}
                            </td>
                            <td v-text="venta.num_comprobante"></td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-right">
                    <strong>Total Ganado: </strong>Bs. {{ totalGanado }} 
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <button @click="exportarPDF" class="btn btn-danger">Exportar a PDF</button>
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
</main>
</template>

<script>

import jsPDF from 'jspdf';
import 'jspdf-autotable';
import * as XLSX from 'xlsx';

export default {
data (){
    return {
        rol_id: 0,
        nombre : '',
        descripcion : '',
        fecha: '',
        idcategoria: null,
        arrayVentas: [],
        arrayCategoria: [],
        modal : 0,
        tituloModal : '',
        tipoAccion : 0,
        pagination : {
            'total' : 0,
            'current_page' : 0,
            'per_page' : 0,
            'last_page' : 0,
            'from' : 0,
            'to' : 0,
        },
        offset : 3,
        criterio : 'nombre',
        buscar : ''
    }
},
computed:{
    isActived: function(){
        return this.pagination.current_page;
    },

    totalGanado() {
      // Calcular la suma de los totales de todas las ventas
      return this.arrayVentas.reduce(
        (total, venta) => total + venta.precio * venta.cantidad,
        0
      ).toFixed(2);
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

    }
},
methods : {
        generarReporte() {
            let me = this;
            var url = '/ventas-diarias?fecha=' + me.fecha + '&idCategoria=' + me.idcategoria;

        axios.get(url)
            .then(function (response) {
            if ('mensaje' in response.data && response.data.mensaje === 'Ninguna Venta Realizada en la Fecha Indicada') {
                swal("Ninguna Venta", "No se realizaron ventas en la fecha seleccionada", "info");
            } else {
                var respuesta = response.data;
                me.arrayVentas = respuesta.ventas;
                me.totalGanado = respuesta.totalGanado;
            }
            })
            .catch(function (error) {
            console.log(error);
            });
        },


    selectCategoria() {
            let me = this;
            var url = '/categoria/selectCategoria';
            axios.get(url).then(function (response) {
                //console.log(response);
                var respuesta = response.data;
                me.arrayCategoria = respuesta.categorias;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },

    exportarPDF() {
    const pdf = new jsPDF();

    pdf.setFont('bold');
    const titulo = 'Reporte de Ventas Diarias';
    const fecha = `Fecha: ${this.fecha}`;
    pdf.setFont('normal');

    const pageSize = pdf.internal.pageSize;
    const pageWidth = pageSize.width;
    const textWidth = pdf.getStringUnitWidth(titulo) * pdf.internal.getFontSize() / pdf.internal.scaleFactor;

    const xPosition = (pageWidth - textWidth) / 2;

    pdf.text(titulo, xPosition, 10);
    pdf.text(fecha, 15, 20);

    const tableYPosition = 30;

    const columns = ['Cliente', 'Producto', 'Cantidad', 'Precio', 'Total', 'Número Factura'];
    const rows = this.arrayVentas.map(venta => [venta.cliente, venta.articulo, venta.cantidad, venta.precio, (venta.precio * venta.cantidad), venta.num_comprobante]);
    pdf.autoTable({ head: [columns], body: rows, startY: tableYPosition });

    pdf.setFont('bold');
    const totalGanado = `Total Ganado: Bs. ${this.totalGanado}`;
    pdf.text(totalGanado, 15, pdf.autoTable.previous.finalY + 10);
    pdf.setFont('normal');

    pdf.save('reporte_ventas_diarias.pdf');
    },



    cambiarPagina(page,buscar,criterio){
        let me = this;
        //Actualiza la página actual
        me.pagination.current_page = page;
        //Envia la petición para visualizar la data de esa página
        me.listarRol(page,buscar,criterio);
    }
},
mounted() {
    this.selectCategoria();
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
