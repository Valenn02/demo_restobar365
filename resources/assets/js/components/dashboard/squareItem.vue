<template>
    <div class="col-md-4 col-sm-6">
      <div :style="{ background: fondoDegradado }" class="degradado border p-3 mb-3">
        <div class="row justify-content-between align-items-center">
          <div class="col-auto">
            <label class="mb-0">{{ titulo }}</label>
          </div>
          <div class="col-auto">
            <i :class="icono + ' ml-2'" aria-hidden="true"></i>
          </div>
        </div>
        <div class="d-flex align-items-end">
          <h2>{{ cantidad }}</h2>
          <h4 class="ml-2">{{ moneda }}</h4>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      cantidad: {
        type: null,
        required: true
      },
      icono: {
        type: String,
        required: true
      },
      titulo: {
        type: String,
        required: true
      },
      moneda: {
        type: String,
        required: false
      },
      fondoDegradado: {
        type: String,
        default: 'linear-gradient(50deg, #028bd2, #6dd3dd)'
      }
    }
  }
  </script>
  
  <style>
  .degradado {
    border-radius: 6px;
    box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
    color: white;
    padding: 20px;
  }
  </style>
  