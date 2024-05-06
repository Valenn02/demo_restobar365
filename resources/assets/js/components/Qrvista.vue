<template>
  <div>
    <label for="alias">Alias:</label>
    <input type="text" id="alias" v-model="alias">
    <br>
    <label for="monto">Monto:</label>
    <input type="text" id="monto" v-model.number="monto">
    <br>      
    <button @click="generarQr">Generar QR</button>

    <!-- Espacio para mostrar la imagen del código QR -->
    <div v-if="qrImage">
      <img :src="qrImage" alt="Código QR">
    </div>

    <!-- Botón para verificar estado -->
    <button @click="verificarEstado" v-if="qrImage">Verificar Estado de Pago</button>

    <!-- Mostrar estado de transacción -->
    <div v-if="estadoTransaccion">
      <p>Estado Actual: {{ estadoTransaccion.objeto.estadoActual }}</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      alias: '',
      monto: 0,
      qrImage: '', // Variable para almacenar la URL de la imagen QR
      aliasverificacion: '',
      estadoTransaccion: null // Variable para almacenar el estado de la transacción
    };
  },
  methods: {
    verificarEstado() {
      console.log(this.aliasverificacion);

      // Realizar una solicitud POST al endpoint en tu backend Laravel
      axios.post('/qr/verificarestado', {
        alias: this.aliasverificacion,
      })
      .then(response => {
        // Manejar la respuesta aquí
        this.estadoTransaccion = response.data; // Asignar el estado de la transacción a la variable
        console.log(response.data);
      })
      .catch(error => {
        // Manejar cualquier error de solicitud aquí
        console.error(error);
      });
    },

    generarQr() {
      this.aliasverificacion = this.alias;
      // Realizar una solicitud POST al endpoint para generar el código QR
      axios.post('/qr/generarqr', {
        alias: this.alias,
        monto: this.monto
      })
      .then(response => {
        // Extraer la imagen del código QR de la respuesta
        const imagenBase64 = response.data.objeto.imagenQr;

        // Establecer la URL de la imagen base64 en qrImage
        this.qrImage = `data:image/png;base64,${imagenBase64}`;

        // Manejar la respuesta aquí, por ejemplo, mostrar el resultado en la consola
        console.log(response.data);
        console.log(this.aliasverificacion);

      })
      .catch(error => {
        // Manejar cualquier error de solicitud aquí
        console.error(error);
      });
      
      // Limpiar los campos alias y monto después de generar el QR
      this.alias = '';
      this.monto = 0;
    }
  }
}
</script>
