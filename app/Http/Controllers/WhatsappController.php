<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use FPDF;

class WhatsappController extends Controller
{
    public function enviaReporte(Request $request)
    {
        $fecha = $request->input('fecha');
        $ventas = $request->input('ventas');
        $totalGanado = $request->input('totalGanado');

        $pdf = new FPDF();
        $pdf->AddPage();

        function convertirCaracteres($texto) {
            return iconv('UTF-8', 'ISO-8859-1', $texto);
        }

        $pdf->SetFont('Arial', 'B', 16);
        $titulo = 'Reporte de Ventas Diarias';
        $textWidth = $pdf->GetStringWidth($titulo);
        $pageWidth = $pdf->GetPageWidth();
        $xPosition = ($pageWidth - $textWidth) / 2;
        $pdf->Text($xPosition, 10, convertirCaracteres($titulo));

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Text(15, 20, convertirCaracteres("Fecha: $fecha"));

        $columnHeaders = ['Cliente', 'Producto', 'Cantidad', 'Precio', 'Total', 'Número Factura'];
        $columnsWidths = array_fill(0, count($columnHeaders), 0);

        foreach ($columnHeaders as $index => $header) {
            $columnsWidths[$index] = max($columnsWidths[$index], $pdf->GetStringWidth(convertirCaracteres($header)));
        }

        foreach ($ventas as $venta) {
            $columnsWidths[0] = max($columnsWidths[0], $pdf->GetStringWidth(convertirCaracteres($venta['cliente'])));
            $columnsWidths[1] = max($columnsWidths[1], $pdf->GetStringWidth(convertirCaracteres($venta['articulo'])));
            $columnsWidths[2] = max($columnsWidths[2], $pdf->GetStringWidth($venta['cantidad']));
            $columnsWidths[3] = max($columnsWidths[3], $pdf->GetStringWidth($venta['precio']));
            $columnsWidths[4] = max($columnsWidths[4], $pdf->GetStringWidth(number_format($venta['precio'] * $venta['cantidad'], 2)));
            $columnsWidths[5] = max($columnsWidths[5], $pdf->GetStringWidth(convertirCaracteres($venta['num_comprobante'])));
        }

        $columnsWidths = array_map(function($width) {
            return $width + 2;
        }, $columnsWidths);

        $pdf->SetY(30);
        $pdf->SetFont('Arial', 'B', 12);

        $pdf->SetFillColor(200, 200, 200);

        foreach ($columnHeaders as $index => $header) {
            $pdf->Cell($columnsWidths[$index], 10, convertirCaracteres($header), 1, 0, 'C', true);
        }
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);
        $pdf->SetFillColor(255, 255, 255);

        foreach ($ventas as $venta) {
            $pdf->Cell($columnsWidths[0], 10, convertirCaracteres($venta['cliente']), 1, 0, 'C', true);
            $pdf->Cell($columnsWidths[1], 10, convertirCaracteres($venta['articulo']), 1, 0, 'C', true);
            $pdf->Cell($columnsWidths[2], 10, $venta['cantidad'], 1, 0, 'C', true);
            $pdf->Cell($columnsWidths[3], 10, $venta['precio'], 1, 0, 'C', true);
            $pdf->Cell($columnsWidths[4], 10, number_format($venta['precio'] * $venta['cantidad'], 2), 1, 0, 'C', true);
            $pdf->Cell($columnsWidths[5], 10, convertirCaracteres($venta['num_comprobante']), 1, 1, 'C', true);
        }


        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Text(15, $pdf->GetY() + 10, convertirCaracteres("Total Ganado: Bs. $totalGanado"));

        $publicPath = public_path('docs/reporteVentasDiarias.pdf');
        $pdf->Output('F', $publicPath);

        $pdfUrl = url('public/docs/reporteVentasDiarias.pdf');

        // Datos para el envío por WhatsApp
        $token = 'EAALJ0bAhaD0BOxSs0dfU8xDpZBoj5O1bW7AkLH8HLZBvAHx8UCO0bf3rQgELNY5qUq6tWJVRKShy9S0f9zrBDzXScpGEBPuScAkfr4X6WBnvcsGFWJVQo5hQP1IWQxexzE3yqDC1rtI2ITgh1iLR2b8DimfYZCbCoLHEZAJ9BGKCikWNamlKYuZC02MfVSWQFFvX1YRmsItRpwZBrx';
        
        //$telefono = '+591 72785387';
        $telefono ='+591 '.$request->input('telefono');
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
