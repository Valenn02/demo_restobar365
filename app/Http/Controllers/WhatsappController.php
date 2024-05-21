<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use FPDF;

class WhatsappController extends Controller
{
    public function enviaReporte(Request $request)
{
    // Obtener los datos para generar el reporte
    $fecha = $request->input('fecha');
    $ventas = $request->input('ventas');
    $totalGanado = $request->input('totalGanado');

    // Verificar que $ventas es un array
    if (!is_array($ventas)) {
        return response()->json(['error' => 'Invalid ventas format'], 400);
    }

    // Crear el PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Título centrado
    $titulo = 'Reporte de Ventas Diarias';
    $textWidth = $pdf->GetStringWidth($titulo);
    $pageWidth = $pdf->GetPageWidth();
    $xPosition = ($pageWidth - $textWidth) / 2;
    $pdf->Text($xPosition, 10, $titulo);

    // Fecha
    $pdf->SetFont('Arial', '', 12);
    $pdf->Text(15, 20, "Fecha: $fecha");

    // Tabla de ventas
    $pdf->SetY(30);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(30, 10, 'Cliente', 1);
    $pdf->Cell(30, 10, 'Producto', 1);
    $pdf->Cell(20, 10, 'Cantidad', 1);
    $pdf->Cell(20, 10, 'Precio', 1);
    $pdf->Cell(30, 10, 'Total', 1);
    $pdf->Cell(30, 10, 'Número Factura', 1);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    foreach ($ventas as $venta) {
        $cliente = $venta['cliente'];
        $articulo = $venta['articulo'];
        $cantidad = $venta['cantidad'];
        $precio = $venta['precio'];
        $total = $precio * $cantidad;
        $num_comprobante = $venta['num_comprobante'];

        $pdf->Cell(30, 10, $cliente, 1);
        $pdf->Cell(30, 10, $articulo, 1);
        $pdf->Cell(20, 10, $cantidad, 1);
        $pdf->Cell(20, 10, $precio, 1);
        $pdf->Cell(30, 10, $total, 1);
        $pdf->Cell(30, 10, $num_comprobante, 1);
        $pdf->Ln();
    }

    // Total ganado
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Text(15, $pdf->GetY() + 10, "Total Ganado: Bs. $totalGanado");

    // Guardar el PDF en una ruta temporal dentro del directorio public/docs
    $publicPath = public_path('docs/reporteVentasDiarias.pdf');
    $pdf->Output('F', $publicPath);

    // Generar la URL pública para el PDF
    $pdfUrl = url('public/docs/reporteVentasDiarias.pdf');

    // Datos para el envío por WhatsApp
    $token = 'EAALJ0bAhaD0BOxSs0dfU8xDpZBoj5O1bW7AkLH8HLZBvAHx8UCO0bf3rQgELNY5qUq6tWJVRKShy9S0f9zrBDzXScpGEBPuScAkfr4X6WBnvcsGFWJVQo5hQP1IWQxexzE3yqDC1rtI2ITgh1iLR2b8DimfYZCbCoLHEZAJ9BGKCikWNamlKYuZC02MfVSWQFFvX1YRmsItRpwZBrx';
    $telefono = '+591 71714343';
    $url = 'https://graph.facebook.com/v19.0/319231637939539/messages';

    // Configuración del mensaje con la plantilla
    $messageConfig = [
        'messaging_product' => 'whatsapp',
        'to' => $telefono,
        'type' => 'template',
        'template' => [
            'name' => 'plantilla_prueba',
            'language' => [
                'code' => 'es'
            ],
            'components' => [
                [
                    'type' => 'header',
                    'parameters' => [
                        [
                            'type' => 'document',
                            'document' => [
                                'link' => 'http://laniatita.restobar365.com/docs/reporteVentasDiarias.pdf'
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ];

    $messageJson = json_encode($messageConfig);

    // Configurar las cabeceras
    $headers = [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json'
    ];

    // Inicializar la sesión curl
    $curl = curl_init($url);

    // Establecer las opciones de curl
    curl_setopt($curl, CURLOPT_POSTFIELDS, $messageJson);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Ejecutar la solicitud curl
    $response = json_decode(curl_exec($curl), true);

    // Obtener el código de estado
    $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    // Cerrar la sesión curl
    curl_close($curl);


    // Devolver la respuesta del servidor de WhatsApp
    return response()->json($response);
}


}



//<?php
//
//namespace App\Http\Controllers;
//
//use Illuminate\Http\Request;
//use FPDF;
//class WhatsappController extends Controller
//{
//    public function envia()
//    {
//       
//        $token='EAALJ0bAhaD0BOxSs0dfU8xDpZBoj5O1bW7AkLH8HLZBvAHx8UCO0bf3rQgELNY5qUq6tWJVRKShy9S0f9zrBDzXScpGEBPuScAkfr4X6WBnvcsGFWJVQo5hQP1IWQxexzE3yqDC1rtI2ITgh1iLR2b8DimfYZCbCoLHEZAJ9BGKCikWNamlKYuZC02MfVSWQFFvX1YRmsItRpwZBrx';
//
//        
//        $telefono = '+591 71714343';
//
//        
//        $url = 'https://graph.facebook.com/v19.0/319231637939539/messages';
//
//        // Configuración del mensaje
//        $messageConfig = [
//            'messaging_product' => 'whatsapp',
//            'to' => $telefono,
//            'type' => 'template',
//            'template' => [
//                'name' => 'plantilla_prueba',
//                'language' => [
//                    'code' => 'es'
//                ],
//                'components' => [
//                    [
//                        'type' => 'header',
//                        'parameters' => [
//                            [
//                                'type' => 'document',
//                                'document' => [
//                                    'link' => 'http://127.0.0.1:8000/docs/reporteVentasDiarias.pdf'
//                                ]
//                            ]
//                        ]
//                    ]
//                ]
//            ]
//        ];
//
//        // Convertir la configuración del mensaje a JSON
//        $messageJson = json_encode($messageConfig);
//
//        // Configurar las cabeceras
//        $headers = [
//            'Authorization: Bearer ' . $token,
//            'Content-Type: application/json'
//        ];
//
//        // Inicializar la sesión curl
//        $curl = curl_init($url);
//
//        // Establecer las opciones de curl
//        curl_setopt($curl, CURLOPT_POSTFIELDS, $messageJson);
//        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//
//        // Ejecutar la solicitud curl
//        $response = json_decode(curl_exec($curl), true);
//
//        // Obtener el código de estado
//        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
//
//        // Cerrar la sesión curl
//        curl_close($curl);
//
//        // Imprimir la respuesta
//        print_r($response);
//    }
//    public function enviaReporte(Request $request)
//    {
//        $fecha = $request->input('fecha');
//        $ventas = $request->input('ventas');
//        $totalGanado = $request->input('totalGanado');
//
//        // Crear el PDF
//        $pdf = new FPDF();
//        $pdf->AddPage();
//        $pdf->SetFont('Arial', 'B', 16);
//
//        // Título centrado
//        $titulo = 'Reporte de Ventas Diarias';
//        $textWidth = $pdf->GetStringWidth($titulo);
//        $pageWidth = $pdf->GetPageWidth();
//        $xPosition = ($pageWidth - $textWidth) / 2;
//        $pdf->Text($xPosition, 10, $titulo);
//
//        // Fecha
//        $pdf->SetFont('Arial', '', 12);
//        $pdf->Text(15, 20, "Fecha: $fecha");
//
//        // Tabla de ventas
//        $pdf->SetY(30);
//        $pdf->SetFont('Arial', 'B', 12);
//        $pdf->Cell(30, 10, 'Cliente', 1);
//        $pdf->Cell(30, 10, 'Producto', 1);
//        $pdf->Cell(20, 10, 'Cantidad', 1);
//        $pdf->Cell(20, 10, 'Precio', 1);
//        $pdf->Cell(30, 10, 'Total', 1);
//        $pdf->Cell(30, 10, 'Número Factura', 1);
//        $pdf->Ln();
//
//        $pdf->SetFont('Arial', '', 12);
//        foreach ($ventas as $venta) {
//            $cliente = $venta['cliente'];
//            $articulo = $venta['articulo'];
//            $cantidad = $venta['cantidad'];
//            $precio = $venta['precio'];
//            $total = $precio * $cantidad;
//            $num_comprobante = $venta['num_comprobante'];
//
//            $pdf->Cell(30, 10, $cliente, 1);
//            $pdf->Cell(30, 10, $articulo, 1);
//            $pdf->Cell(20, 10, $cantidad, 1);
//            $pdf->Cell(20, 10, $precio, 1);
//            $pdf->Cell(30, 10, $total, 1);
//            $pdf->Cell(30, 10, $num_comprobante, 1);
//            $pdf->Ln();
//        }
//
//        // Total ganado
//        $pdf->SetFont('Arial', 'B', 12);
//        $pdf->Text(15, $pdf->GetY() + 10, "Total Ganado: Bs. $totalGanado");
//
//        // Guardar el PDF en la ruta especificada
//        $pdfPath = public_path('docs/reporteVentasDiarias.pdf');
//        $pdf->Output('F', $pdfPath);
//
//        return response()->download($pdfPath);
//    }
//}
