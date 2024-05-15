<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WhatsappController extends Controller
{
    public function envia()
    {
        // Obtener el token de acceso de .env
        $token = env('WABA_API_TOKEN');

        // Número de teléfono del destinatario
        $telefono = '+591 71714343';

        // URL de la API de WhatsApp
        $url = 'https://graph.facebook.com/v19.0/319231637939539/messages';

        // Configuración del mensaje
        $messageConfig = [
            'messaging_product' => 'whatsapp',
            'to' => $telefono,
            'type' => 'template',
            'template' => [
                'name' => 'hello_world',
                'language' => [
                    'code' => 'en_US'
                ]
            ]
        ];

        // Convertir la configuración del mensaje a JSON
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

        // Imprimir la respuesta
        print_r($response);
    }
}
