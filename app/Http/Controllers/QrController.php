<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class QrController extends Controller
{
    // Función para generar el token
    public function generarToken()
    {
        $client = new Client();

        $url = 'https://sip.mc4.com.bo:8443/autenticacion/v1/generarToken';
        $headers = [
            'apikey' => '2977cb47ecc0fd3a326bd0c0cf57d04becaa59c2f101c3f7',
            'Content-Type' => 'application/json',
        ];
        $body = json_encode([
            'password' => '365Soft',
            'username' => 'dev365',
        ]);

        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'body' => $body,
            ]);

            $data = json_decode($response->getBody(), true); // Obtener los datos de la respuesta como arreglo asociativo
            
            // Devolver el token
            return $data['objeto']['token'];
        } catch (RequestException $e) {
            // Manejar errores de solicitud
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $errorData = json_decode($response->getBody(), true);
                throw new \Exception("Error $statusCode: " . json_encode($errorData));
            } else {
                // Error de conexión
                throw new \Exception("Error de conexión: " . $e->getMessage());
            }
        }
    }

    // Función para generar el código QR con el token obtenido
    public function generarQr(Request $request)
    {
        // Obtener el token
        $token = $this->generarToken();

        $url = 'https://sip.mc4.com.bo:8443/api/v1/generaQr';

        // Encabezados
        $headers = [
            'apikeyServicio' => '939aa1fcf73a32a737d495a059104a9a60a707074bceef68',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token, // Agregar el token como parte del encabezado de autorización
        ];

        // Cuerpo de la solicitud
        $body = json_encode([
            'alias' => $request->input('alias'),
            'callback' => '000',
            'detalleGlosa' => 'detalle',
            'monto' => $request->input('monto'),
            'moneda' => 'BOB',
            'fechaVencimiento' => '30/05/2024',
            'tipoSolicitud' => 'API',
            'unicoUso' => true
        ]);

        try {
            // Inicializar cliente GuzzleHttp
            $client = new Client();

            // Realizar la solicitud POST
            $response = $client->post($url, [
                'headers' => $headers,
                'body' => $body
            ]);

            // Decodificar y mostrar la respuesta
            $responseData = json_decode($response->getBody(), true);
            return response()->json($responseData, $response->getStatusCode());
        } catch (RequestException $e) {
            // Manejar errores de solicitud
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $errorData = json_decode($response->getBody(), true);
                return response()->json($errorData, $statusCode);
            } else {
                // Error de conexión
                return response()->json(['error' => 'Error de conexión'], 500);
            }
        }
    }

    public function verificarEstado(Request $request)
    {
        // Obtener el token
        $token = $this->generarToken();
    
        $url = 'https://sip.mc4.com.bo:8443/api/v1/estadoTransaccion';
    
        // Encabezados
        $headers = [
            'apikeyServicio' => '939aa1fcf73a32a737d495a059104a9a60a707074bceef68',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token, // Agregar el token como parte del encabezado de autorización
        ];
    
        // Cuerpo de la solicitud
        $body = json_encode([
            'alias' => $request->alias,
        ]);
    
        try {
            // Inicializar cliente GuzzleHttp
            $client = new Client();
    
            // Realizar la solicitud POST
            $response = $client->post($url, [
                'headers' => $headers,
                'body' => $body
            ]);
    
            // Decodificar y mostrar la respuesta
            $responseData = json_decode($response->getBody(), true);
            return response()->json($responseData, $response->getStatusCode());
        } catch (RequestException $e) {
            // Manejar errores de solicitud
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $errorData = json_decode($response->getBody(), true);
                return response()->json($errorData, $statusCode);
            } else {
                // Error de conexión
                return response()->json(['error' => 'Error de conexión'], 500);
            }
        }
    }
    
}
