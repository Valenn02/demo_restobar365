<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Ventas</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Restaurante
                    <button type="button" @click="estadoCampos()" class="btn btn-secondary" style="margin-left: 10px;">
                        <i class="icon-plus"></i>&nbsp;Editar
                    </button>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">&nbsp; Nombre del Restaurante</label>
                    <div class="col-md-6 mx-2">
                        <input type="text" v-model="nombre" class="form-control" placeholder="Ingrese el nombre de la empresa"
                            :readonly="this.estadoInputs">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="email-input">&nbsp; Dirección del Restaurante</label>
                    <div class="col-md-6 mx-2">
                        <input type="text" v-model="direccion" class="form-control" placeholder="Ingrese la direccion"
                            :readonly="this.estadoInputs" >
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="email-input">&nbsp; Teléfono del Restaurante</label>
                    <div class="col-md-6 mx-2">
                        <input type="text" v-model="telefono" class="form-control" placeholder="Ingrese el telefono"
                            :readonly="this.estadoInputs">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="email-input">&nbsp; Email del Restaurante</label>
                    <div class="col-md-6 mx-2">
                        <input type="email" v-model="email" class="form-control" placeholder="Ingrese el email"
                            :readonly="this.estadoInputs" @blur="validateEmail">
                            <!-- <p :style="{color:'red'}" v-if="validEmail === false">Por favor, ingrese un correo electronico de la empresa valido</p> -->
                        
                    </div>
                </div>

                <div class="form-group row" v-if="mostrarDivs">
                    <label class="col-md-3 form-control-label" for="text-input">&nbsp; Tipo de Moneda Principal</label>
                    <div class="col-md-6 mx-2">
                        <input type="radio" value="1" v-model="monedaPrincipal" :checked="monedaPrincipal === '1'" name="moneda" :disabled="this.estadoInputs"> Bolivianos
                        <input type="radio" value="2" v-model="monedaPrincipal" :checked="monedaPrincipal === '2'" name="moneda" :disabled="this.estadoInputs"> Dólar
                    </div>
                </div>

                <div class="form-group row" v-if="mostrarDivs">
                    <label class="col-md-3 form-control-label" for="text-input">&nbsp; Valor Máximo de Descuento</label>
                    <div class="col-md-6 mx-2">
                        <input type="number" v-model="valorMaximoDescuento" class="form-control" placeholder="Ingrese el valor maximo de descuento" 
                            :readonly="this.estadoInputs">
                    </div>
                </div>

                <div class="form-group row" v-if="mostrarDivs">
                    <label class="col-md-3 form-control-label" for="text-input">&nbsp; Licencia</label>
                    <div class="col-md-6 mx-2">
                        <input type="email" v-model="licencia" class="form-control" placeholder="Estado de licencia"
                            :readonly="this.estadoInputs">
                    </div>
                </div>
                <div class="table-responsive" v-if="mostrarDivs">

                <table class="table table-bordered mx-2">
                    <thead>
                        <tr>
                            <th></th>
                            <th>&nbsp; Nominación</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <td>&nbsp; T/C1</td>
                        <td>
                            <input type="email" class="form-control" placeholder="Dólar" readonly="readonly">
                        </td>
                        <td>
                            <input type="email" v-model="tipoCambio1" class="form-control" placeholder="0.0" :readonly="this.estadoInputs">
                        </td>
                        <td>Bs.</td>
                        </tr>
                        <tr>
                        <td>&nbsp; T/C2</td>
                        <td>
                            <input type="email" class="form-control" placeholder="Euro" readonly="readonly">
                        </td>
                        <td>
                            <input type="email" v-model="tipoCambio2" class="form-control" placeholder="0.0" :readonly="this.estadoInputs">
                        </td>
                        <td>Bs.</td>
                        </tr>
                        <tr>
                        <td>&nbsp; T/C3</td>
                        <td>
                            <input type="email" class="form-control" placeholder="Real" readonly="readonly">
                        </td>
                        <td>
                            <input type="email" v-model="tipoCambio3" class="form-control" placeholder="0.0" :readonly="this.estadoInputs">
                        </td>
                        <td>Bs.</td>
                        </tr>
                    </tbody>
                    </table>
                </div>
                <div v-show="errorEmpresa" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjEmpresa" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>


                <div class="form-group-row justify-content-center text-center mt-3 mb-3">
                    <button type="button" class="btn btn-primary" @click="actualizarEmpresa()">Actualizar</button>
                </div>

            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
    </main>
</template>

<script>
export default {
    data() {
        return {
            empresa_id: 0,
            nombre: '',
            direccion: '',
            telefono: '',
            email: '',
            validEmail: null,
            monedaPrincipal: '',
            valorMaximoDescuento: '',
            tipoCambio1: '',
            tipoCambio2: '',
            tipoCambio3: '',
            licencia: '',
            errorEmpresa: 0,
            errorMostrarMsjEmpresa: [],
            estadoInputs: true,
            mostrarDivs: false,
            
        }
    },
    methods: {
        validateEmail() {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
            this.validEmail = regex.test(this.email)
        },
        estadoCampos(){
            this.estadoInputs = !this.estadoInputs;
        },
        datosEmpresa() {
            let me = this;
            var url = '/empresa';

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                console.log(respuesta);

                me.empresa_id = respuesta.empresa.id;
                me.nombre = respuesta.empresa.nombre;
                me.direccion = respuesta.empresa.direccion;
                me.telefono = respuesta.empresa.telefono;
                me.email = respuesta.empresa.email;
                me.monedaPrincipal = respuesta.empresa.monedaPrincipal;
                me.valorMaximoDescuento = respuesta.empresa.valorMaximoDescuento;
                me.tipoCambio1 = respuesta.empresa.tipoCambio1;
                me.tipoCambio2 = respuesta.empresa.tipoCambio2;
                me.tipoCambio3 = respuesta.empresa.tipoCambio3;
                me.licencia = respuesta.empresa.licencia;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        actualizarEmpresa() {
            if (this.validarEmpresa()) {
                return;
            }

            let me = this;

            /*let formData = new FormData();
            formData.append('nombre', this.nombre);
            formData.append('direccion', this.direccion);
            formData.append('telefono', this.telefono);
            formData.append('email', this.email);
            formData.append('monedaPrincipal', this.monedaPrincipal);
            formData.append('valorMaximoDescuento', this.valorMaximoDescuento);
            formData.append('tipoCambio1', this.tipoCambio1);
            formData.append('tipoCambio2', this.tipoCambio2);
            formData.append('tipoCambio3', this.tipoCambio3);
            formData.append('licencia', this.licencia);
            formData.append('id', this.empresa_id);

            for (let [key, value] of formData.entries()) 
                {
                    console.log(key, value);
            }*/

            axios.put('/empresa/actualizar', {
                'nombre': this.nombre,
                'direccion': this.direccion,
                'telefono': this.telefono,
                'email': this.email,
                'monedaPrincipal': this.monedaPrincipal,
                'valorMaximoDescuento': this.valorMaximoDescuento,
                'tipoCambio1': this.tipoCambio1,
                'tipoCambio2': this.tipoCambio2,
                'tipoCambio3': this.tipoCambio3,
                'licencia': this.licencia,
                'id': this.empresa_id
            }).then(function (response) {
                alert('Datos actualizados');
            }).catch(function (error) {
                console.log(error);
            });
        },
        validarEmpresa() {
            this.errorEmpresa = 0;
            this.errorMostrarMsjEmpresa = [];

            
            if (!this.nombre) this.errorMostrarMsjEmpresa.push("El nombre de la empresa no puede estar vacío.");
            if (!this.direccion) this.errorMostrarMsjEmpresa.push("La direccion de la empresa no puede estar vacío.");
            if (!this.telefono) this.errorMostrarMsjEmpresa.push("El telefono de la empresa no puede estar vacío.");
            if (!this.email) {
                this.errorMostrarMsjEmpresa.push("El email de la empresa no puede estar vacío.");
            }else{
                if (!this.validEmail) this.errorMostrarMsjEmpresa.push("El correo electronico de la empresa no es valido");

            }
            if (!this.monedaPrincipal) this.errorMostrarMsjEmpresa.push("La moneda de la empresa no puede estar vacío.");
            if (!this.valorMaximoDescuento) this.errorMostrarMsjEmpresa.push("El valor maximo de descuento de la empresa no puede estar vacío.");
            if (!this.licencia) this.errorMostrarMsjEmpresa.push("La licencia de la empresa no puede estar vacío.");

            if (!this.tipoCambio1) this.errorMostrarMsjEmpresa.push("El tipo de cambio Dolar de la empresa no puede estar vacío.");
            if (!this.tipoCambio2) this.errorMostrarMsjEmpresa.push("El tipo de cambio Euro de la empresa no puede estar vacío.");
            if (!this.tipoCambio3) this.errorMostrarMsjEmpresa.push("El tipo de cambio Real de la empresa no puede estar vacío.");
            
            if (this.errorMostrarMsjEmpresa.length) this.errorEmpresa = 1;

            return this.errorEmpresa;
        }
    },
    mounted() {
        this.datosEmpresa()
    }
}
</script>
<style>    .modal-content {
        width: 100% !important;
        position: absolute !important;
    }

    .mostrar {
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }

    .div-error {
        display: flex;
        justify-content: center;
    }

    .text-error {
        color: red !important;
        font-weight: bold;
    }</style>
