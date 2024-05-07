<template>
  <div>
    <label for="alias">Alias:</label>
    <InputText v-model="alias" />
    <br>
    <label for="montoQR">Monto:</label>
    <InputNumber v-model="montoQR" mode="currency" :currency="currency" />
    <br>      
    <Button @click="generarQr" label="Generar QR" />
    
    <!-- Espacio para mostrar la imagen del código QR -->
    <div v-if="qrImage">
      <img :src="qrImage" alt="Código QR" />
    </div>

    <!-- Botón para verificar estado -->
    <Button @click="verificarEstado" v-if="qrImage" label="Verificar Estado de Pago" />

    <!-- Mostrar estado de transacción -->
    <div v-if="estadoTransaccion" class="p-card p-p-2">
      <div class="p-text-bold">Estado Actual:</div>
      <div>
        <Badge :value="estadoTransaccion.objeto.estadoActual" severity="success" />
      </div>
    </div>
  </div>
</template>

<script>
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Badge from 'primevue/badge';

import axios from 'axios';

export default {
  components: {
    InputText,
    InputNumber,
    Button,
    Badge,
  },
  data() {
    return {
      alias: '',
      montoQR: 0,
      qrImage: '',
      aliasverificacion: '',
      estadoTransaccion: null,
      currency: 'BOB', // Define tu moneda
    };
  },
  methods: {
    verificarEstado() {
      axios.post('/qr/verificarestado', {
        alias: this.aliasverificacion,
      })
      .then(response => {
        this.estadoTransaccion = response.data;
      })
      .catch(error => {
        console.error(error);
      });
    },

    generarQr() {
      this.aliasverificacion = this.alias;
      axios.post('/qr/generarqr', {
        alias: this.alias,
        monto: this.montoQR
      })
      .then(response => {
        const imagenBase64 = response.data.objeto.imagenQr;
        this.qrImage = `data:image/png;base64,${imagenBase64}`;
      })
      .catch(error => {
        console.error(error);
      });

      this.alias = '';
      this.montoQR = 0;
    }
  }
}
</script>

<style scoped>
.p-card {
  margin-top: 20px;
}
</style>
