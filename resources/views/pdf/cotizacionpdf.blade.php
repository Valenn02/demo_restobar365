<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .customer-details {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        
        tfoot td {
            font-weight: bold;
        }
        
        .total {
            text-align: right;
        }
         
        #logo{
        float: left;
        margin-top: 1%;
        margin-left: 2%;
        margin-right: 2%;
        }
 
        #imagen{
        width: 100px;
        }
 
        #datos{
        float: left;
        margin-top: 0%;
        margin-left: 2%;
        margin-right: 2%;
        /*text-align: justify;*/
        }
         
        #fact{
        /*position: relative;*/
        float: right;
        margin-top: 2%;
        margin-left: 2%;
        margin-right: 2%;
        font-size: 20px;
        }
    </style>
</head>
<body>
    <header>
            <div id="logo">
                <img src="img/logocodensa.png" alt="CompartiendoCodigo" id="imagen">
            </div>
            <div id="datos">
                <p id="encabezado">
                    <b>365 Group</b><br>Cochabamba, Bolivia<br>Telefono:(+591)999999999<br>Email: Group365@gmail.com
                </p>
            </div>
            <div id="fact">
                <p>COTIZACION<br>
                0000001</p>
            </div>
    </header>   
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    
    <div class="customer-details">
        <p><strong>Nombre:</strong> {{ $venta[0]->nombre }}  </p>
        <p><strong>Tipo de documento:</strong> {{ $venta[0]->tipo_documento }}</p>
        <p><strong>Número de documento:</strong> {{ $venta[0]->num_documento }}</p>
        <p><strong>Dirección:</strong> {{ $venta[0]->direccion }}</p>
        <p><strong>Email:</strong> {{ $venta[0]->email }}</p>
        <p><strong>Teléfono:</strong> {{ $venta[0]->telefono }}</p>
    </div>
    
    <h2>Detalles de la cotización:</h2>
    <table>
        <thead>
            <tr>
                <th>Artículo</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Descuento</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detalles as $detalle)
                <tr>
                    <td>{{ $detalle->articulo }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>{{ $detalle->precio }}</td>
                    <td>{{ $detalle->descuento }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <h2>Total:</h2>
    <p class="total">{{ $venta[0]->total }}</p>
</body>
</html>
