<!DOCTYPE>
<html>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de venta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #000000; 
            background: #FFFFFF; 
        }

        #logo {
            float: left;
            margin-top: 1%;
            margin-left: 2%;
            margin-right: 2%;
        }

        #imagen {
            width: 100px;
        }

        #datos {
            float: left;
            margin-top: 0%;
            margin-left: 2%;
            margin-right: 2%;
        }

        #encabezado {
            text-align: center;
            margin-left: 10%;
            margin-right: 35%;
            font-size: 15px;
        }

        #fact {
            float: right;
            margin-top: 2%;
            margin-left: 2%;
            margin-right: 2%;
            font-size: 15px;
        }

        
        .rectangulo {
            border: 1px solid #000000; 
            border-radius: 10px; 
            padding: 5px 10px; 
        }

        section {
            clear: left;
        }

        #cliente {
            text-align: left;
        }

        #facliente {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 15px;
        }

        #fac, #fv, #fa {
            color: #000000;
            font-size: 15px;
        }

        #facliente thead {
            padding: 20px;
            background: #FFFFFF; 
            color: #000000; 
            text-align: left;
            border-bottom: 1px solid #000000;
        }

        #facvendedor {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 15px;
        }

        #facvendedor thead {
            padding: 20px;
            background: #FFFFFF; 
            color: #000000; 
            text-align: center;
            border-bottom: 1px solid #000000;
        }

        #facarticulo {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 15px;
        }

        #facarticulo thead {
            padding: 20px;
            background: #FFFFFF; 
            color: #000000; 
            text-align: center;
            border-bottom: 1px solid #000000;
        }
    </style>
    <body>
        @foreach ($venta as $v)
        <header>
            <div id="logo">
                <img src="img/logo2.png" alt="CompartiendoCodigo" id="imagen">
            </div>
            <div id="datos">
                <p id="encabezado">
                    <b>365 Group</b><br>Cochabamba, Bolivia<br>Teléfono:(+591)9999999<br>Email: Group365@gmail.com
                </p>
            </div>
            <div class = 'rectangulo 'id="fact">
                <p>{{$v->tipo_comprobante}}<br>
            Numero: {{$v->num_comprobante}}<br>
            Fecha: {{$v->created_at}}</p>
            </div>
        </header>
        <br>
        <section>
            <div class = 'rectangulo '>
                <table id="facliente">
                    <thead>                        
                        <tr>
                            <th id="fac">Cliente</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th><p id="cliente">Email: {{$v->email}}<br>Dirección: {{$v->direccion}}<br></</p></th>
                            <th><p id="cliente">Teléfono: {{$v->telefono}}</</p></th>                           
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        @endforeach
        <br>
        <section>
            <div class = 'rectangulo '>
                <table id="facvendedor">
                    <thead>
                        <tr id="fv">
                            <th>VENDEDOR</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{$v->usuario}}</td>
                            
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <br>
        <section>
            <div class = 'rectangulo '>
                <table id="facarticulo">
                    <thead>
                        <tr id="fa">
                            <th>CANT</th>
                            <th>DESCRIPCION</th>
                            <th>PRECIO UNIT</th>
                            <th>DESC.</th>
                            <th>PRECIO TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detalles as $det)
                        <tr>
                            <td>{{$det->cantidad}}</td>
                            <td>{{$det->articulo}}</td>
                            <td>{{$det->precio}}</td>
                            <td>{{$det->descuento}}</td>
                            <td>{{$det->cantidad*$det->precio-$det->descuento}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        @foreach ($venta as $v)
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>SUBTOTAL</th>
                            <td>$ {{round($v->total-($v->total*$v->impuesto),2)}}</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Impuesto</th>
                            <td>$ {{round($v->total*$v->impuesto,2)}}</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>TOTAL</th>
                            <td>$ {{$v->total}}</td>
                        </tr>
                        @endforeach
                    </tfoot>
                </table>
            </div>
        </section>
        <br>
        <br>
        <footer>
            <div id="gracias">
                <p><b>Gracias por su compra!</b></p>
            </div>
        </footer>
    </body>
</html>