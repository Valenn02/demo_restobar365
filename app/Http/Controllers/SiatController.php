<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SoapFault;
use App\Factura;

class SiatController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $facturas = Factura::join('personas', 'facturas.idcliente', '=', 'personas.id')
            ->select('facturas.*','personas.nombre as razonSocial', 'personas.email as email', 'personas.num_documento as documentoid', 'personas.complemento_id as complementoid')
            ->orderBy('facturas.id', 'desc')->paginate(3);
        }
        else{
            $facturas = Factura::join('personas', 'facturas.idcliente', '=', 'personas.id')
            ->select('facturas.*','personas.nombre', 'personas.email', 'personas.num_documento', 'personas.complemento.id')
            ->orderBy('facturas.id', 'desc')->paginate(3);
        }
        

        return [
            'pagination' => [
                'total'        => $facturas->total(),
                'current_page' => $facturas->currentPage(),
                'per_page'     => $facturas->perPage(),
                'last_page'    => $facturas->lastPage(),
                'from'         => $facturas->firstItem(),
                'to'           => $facturas->lastItem(),
            ],
            'facturas' => $facturas
        ];
    }

    public function getFactura($id)
    {
            $facturas = Factura::join('personas', 'facturas.idcliente', '=', 'personas.id')
            ->select('facturas.*','personas.nombre as razonSocial', 'personas.email as email', 'personas.num_documento as documentoid', 'personas.complemento_id as complementoid')
            ->where('facturas.id', '=', $id)
            ->orderBy('facturas.id', 'desc')->paginate(3);

        return [    
            'facturas' => $facturas
        ];
    }

    public function verificarComunicacion()
    {
        $wsdl="https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionCodigos?wsdl";
        $options = array(
            'http' => array(
                'header' => "apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJHdWljaGkxLiIsImNvZGlnb1Npc3RlbWEiOiI3NzUzNTU0NkI3MTJERDQwOUQ3QTM4NyIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTFORFUyTXpRd01EUUNBTWdwRkpRS0FBQUEiLCJpZCI6MzAxNTc4OCwiZXhwIjoxNzAzOTgwODAwLCJpYXQiOjE2OTE2MDMxMzIsIm5pdERlbGVnYWRvIjo1MTUzNjEwMDEyLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.Y61q9_pZiOG49HYRQ5OfXRHvDCh1V8hoviWuA472DgV5f3CdV-MOxz9y4u07AVB-bMByebK_wskxUWXf6cliQQ",
                'timeout' => 5
            )
        );

        $context = stream_context_create($options);
        try {
            $client =  new \SoapClient($wsdl, [
                'stream_context' => $context,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);
            $result = $client->verificarComunicacion();
        }catch (SoapFault $fault){
            $result =false;
        }
        return $result;
    }

    public function cuis($puntoVenta, $codSucursal){
        $wsdl="https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionCodigos?wsdl";
        $codigoAmbiente = 2;
        $codigoModalidad = 2;
        $codigoPuntoVenta = $puntoVenta;
        $codigoSistema = "77535546B712DD409D7A387";
        $codigoSucursal = $codSucursal;
        $nit = "5153610012";

        $params = array('SolicitudCuis' => array(
            'codigoAmbiente' => $codigoAmbiente,
            'codigoModalidad' => $codigoModalidad,
            'codigoPuntoVenta' => $codigoPuntoVenta,
            'codigoSistema' => $codigoSistema,
            'codigoSucursal' => $codigoSucursal,
            'nit' => $nit
        ));

        $options = array(
            'http' => array(
                'header' => "apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJHdWljaGkxLiIsImNvZGlnb1Npc3RlbWEiOiI3NzUzNTU0NkI3MTJERDQwOUQ3QTM4NyIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTFORFUyTXpRd01EUUNBTWdwRkpRS0FBQUEiLCJpZCI6MzAxNTc4OCwiZXhwIjoxNzAzOTgwODAwLCJpYXQiOjE2OTE2MDMxMzIsIm5pdERlbGVnYWRvIjo1MTUzNjEwMDEyLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.Y61q9_pZiOG49HYRQ5OfXRHvDCh1V8hoviWuA472DgV5f3CdV-MOxz9y4u07AVB-bMByebK_wskxUWXf6cliQQ",
                'timeout' => 5
            )
        );
        $context = stream_context_create($options);
        try {
            $client = new \SoapClient($wsdl, [
                'stream_context' => $context,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);
            $result = $client->cuis($params);
        }catch (SoapFault $fault){
            $result = "TOKEN NO VÁLIDO";
        }
        return $result;
    }

    public function cufd($puntoVenta, $codSucursal){
        $wsdl="https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionCodigos?wsdl";
        $codigoAmbiente = 2;
        $codigoModalidad = 2;
        $codigoPuntoVenta = $puntoVenta;
        $codigoSistema = "77535546B712DD409D7A387";
        $codigoSucursal = $codSucursal;
        $cuis = $_SESSION['scuis'];
        $nit = "5153610012";

        $params = array('SolicitudCufd' => array(
            'codigoAmbiente' => $codigoAmbiente,
            'codigoModalidad' => $codigoModalidad,
            'codigoPuntoVenta' => $codigoPuntoVenta,
            'codigoSistema' => $codigoSistema,
            'codigoSucursal' => $codigoSucursal,
            'cuis' => $cuis, 
            'nit' => $nit
        ));

        $options = array(
            'http' => array(
                'header' => 'apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJHdWljaGkxLiIsImNvZGlnb1Npc3RlbWEiOiI3NzUzNTU0NkI3MTJERDQwOUQ3QTM4NyIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTFORFUyTXpRd01EUUNBTWdwRkpRS0FBQUEiLCJpZCI6MzAxNTc4OCwiZXhwIjoxNzAzOTgwODAwLCJpYXQiOjE2OTE2MDMxMzIsIm5pdERlbGVnYWRvIjo1MTUzNjEwMDEyLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.Y61q9_pZiOG49HYRQ5OfXRHvDCh1V8hoviWuA472DgV5f3CdV-MOxz9y4u07AVB-bMByebK_wskxUWXf6cliQQ',
                'timeout' => 5
            )
        );

        $context = stream_context_create($options);
        try {
            $client = new \SoapClient($wsdl, [
                'stream_context' => $context,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);
            $result = $client->cufd($params);
        }catch (SoapFault $fault){
            $result = "TOKEN NO VÁLIDO";
        }
        return $result;
    }

    public function sincronizarActividades($puntoVenta, $codSucursal){
        $wsdl="https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionSincronizacion?wsdl";
        $codigoAmbiente = 2;
        $codigoPuntoVenta = $puntoVenta;
        $codigoSistema = "77535546B712DD409D7A387";
        $codigoSucursal = $codSucursal;
        $cuis = "D7960634";
        $nit = "5153610012";

        $params = array('SolicitudSincronizacion' => array(
            'codigoAmbiente' => $codigoAmbiente,
            'codigoPuntoVenta' => $codigoPuntoVenta,
            'codigoSistema' => $codigoSistema,
            'codigoSucursal' => $codigoSucursal,
            'cuis' => $cuis, 
            'nit' => $nit
        ));

        $options = array(
            'http' => array(
                'header' => 'apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJHdWljaGkxLiIsImNvZGlnb1Npc3RlbWEiOiI3NzUzNTU0NkI3MTJERDQwOUQ3QTM4NyIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTFORFUyTXpRd01EUUNBTWdwRkpRS0FBQUEiLCJpZCI6MzAxNTc4OCwiZXhwIjoxNzAzOTgwODAwLCJpYXQiOjE2OTE2MDMxMzIsIm5pdERlbGVnYWRvIjo1MTUzNjEwMDEyLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.Y61q9_pZiOG49HYRQ5OfXRHvDCh1V8hoviWuA472DgV5f3CdV-MOxz9y4u07AVB-bMByebK_wskxUWXf6cliQQ',
                'timeout' => 5
            )
        );

        $context = stream_context_create($options);
        try {
            $client = new \SoapClient($wsdl, [
                'stream_context' => $context,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);
            $result = $client->sincronizarActividades($params);
        }catch (SoapFault $fault){
            $result = "TOKEN NO VÁLIDO";
        }
        return $result;
    }

    public function sincronizarParametricaTiposFactura($puntoVenta, $codSucursal){
        $wsdl="https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionSincronizacion?wsdl";
        $codigoAmbiente = 2;
        $codigoPuntoVenta = $puntoVenta;
        $codigoSistema = "77535546B712DD409D7A387";
        $codigoSucursal = $codSucursal;
        $cuis = "D7960634";
        $nit = "5153610012";

        $params = array('SolicitudSincronizacion' => array(
            'codigoAmbiente' => $codigoAmbiente,
            'codigoPuntoVenta' => $codigoPuntoVenta,
            'codigoSistema' => $codigoSistema,
            'codigoSucursal' => $codigoSucursal,
            'cuis' => $cuis, 
            'nit' => $nit
        ));

        $options = array(
            'http' => array(
                'header' => 'apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJHdWljaGkxLiIsImNvZGlnb1Npc3RlbWEiOiI3NzUzNTU0NkI3MTJERDQwOUQ3QTM4NyIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTFORFUyTXpRd01EUUNBTWdwRkpRS0FBQUEiLCJpZCI6MzAxNTc4OCwiZXhwIjoxNzAzOTgwODAwLCJpYXQiOjE2OTE2MDMxMzIsIm5pdERlbGVnYWRvIjo1MTUzNjEwMDEyLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.Y61q9_pZiOG49HYRQ5OfXRHvDCh1V8hoviWuA472DgV5f3CdV-MOxz9y4u07AVB-bMByebK_wskxUWXf6cliQQ',
                'timeout' => 5
            )
        );

        $context = stream_context_create($options);
        try {
            $client = new \SoapClient($wsdl, [
                'stream_context' => $context,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);
            $result = $client->sincronizarParametricaTiposFactura($params);
        }catch (SoapFault $fault){
            $result = "TOKEN NO VÁLIDO";
        }
        return $result;
    }

    public function sincronizarListaProductosServicios($puntoVenta, $codSucursal){
        $wsdl="https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionSincronizacion?wsdl";
        $codigoAmbiente = 2;
        $codigoPuntoVenta = $puntoVenta;
        $codigoSistema = "77535546B712DD409D7A387";
        $codigoSucursal = $codSucursal;
        $cuis = $_SESSION['scuis'];
        $nit = "5153610012";

        $params = array('SolicitudSincronizacion' => array(
            'codigoAmbiente' => $codigoAmbiente,
            'codigoPuntoVenta' => $codigoPuntoVenta,
            'codigoSistema' => $codigoSistema,
            'codigoSucursal' => $codigoSucursal,
            'cuis' => $cuis, 
            'nit' => $nit
        ));

        $options = array(
            'http' => array(
                'header' => 'apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJHdWljaGkxLiIsImNvZGlnb1Npc3RlbWEiOiI3NzUzNTU0NkI3MTJERDQwOUQ3QTM4NyIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTFORFUyTXpRd01EUUNBTWdwRkpRS0FBQUEiLCJpZCI6MzAxNTc4OCwiZXhwIjoxNzAzOTgwODAwLCJpYXQiOjE2OTE2MDMxMzIsIm5pdERlbGVnYWRvIjo1MTUzNjEwMDEyLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.Y61q9_pZiOG49HYRQ5OfXRHvDCh1V8hoviWuA472DgV5f3CdV-MOxz9y4u07AVB-bMByebK_wskxUWXf6cliQQ',
                'timeout' => 5
            )
        );

        $context = stream_context_create($options);
        try {
            $client = new \SoapClient($wsdl, [
                'stream_context' => $context,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);
            $result = $client->sincronizarListaProductosServicios($params);
        }catch (SoapFault $fault){
            $result = "TOKEN NO VÁLIDO";
        }
        return $result;
    }

    public function sincronizarListaLeyendasFactura($puntoVenta, $codSucursal){
        $wsdl="https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionSincronizacion?wsdl";
        $codigoAmbiente = 2;
        $codigoPuntoVenta = $puntoVenta;
        $codigoSistema = "77535546B712DD409D7A387";
        $codigoSucursal = $codSucursal;
        $cuis = $_SESSION['scuis'];
        $nit = "5153610012";

        $params = array('SolicitudSincronizacion' => array(
            'codigoAmbiente' => $codigoAmbiente,
            'codigoPuntoVenta' => $codigoPuntoVenta,
            'codigoSistema' => $codigoSistema,
            'codigoSucursal' => $codigoSucursal,
            'cuis' => $cuis, 
            'nit' => $nit
        ));

        $options = array(
            'http' => array(
                'header' => 'apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJHdWljaGkxLiIsImNvZGlnb1Npc3RlbWEiOiI3NzUzNTU0NkI3MTJERDQwOUQ3QTM4NyIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTFORFUyTXpRd01EUUNBTWdwRkpRS0FBQUEiLCJpZCI6MzAxNTc4OCwiZXhwIjoxNzAzOTgwODAwLCJpYXQiOjE2OTE2MDMxMzIsIm5pdERlbGVnYWRvIjo1MTUzNjEwMDEyLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.Y61q9_pZiOG49HYRQ5OfXRHvDCh1V8hoviWuA472DgV5f3CdV-MOxz9y4u07AVB-bMByebK_wskxUWXf6cliQQ',
                'timeout' => 5
            )
        );

        $context = stream_context_create($options);
        try {
            $client = new \SoapClient($wsdl, [
                'stream_context' => $context,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);
            $result = $client->sincronizarListaLeyendasFactura($params);
        }catch (SoapFault $fault){
            $result = "TOKEN NO VÁLIDO";
        }
        return $result;
    }

    public function sincronizarParametricaMotivoAnulacion($puntoVenta, $codSucursal){
        $wsdl="https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionSincronizacion?wsdl";
        $codigoAmbiente = 2;
        $codigoPuntoVenta = $puntoVenta;
        $codigoSistema = "77535546B712DD409D7A387";
        $codigoSucursal = $codSucursal;
        $cuis = $_SESSION['scuis'];
        $nit = "5153610012";

        $params = array('SolicitudSincronizacion' => array(
            'codigoAmbiente' => $codigoAmbiente,
            'codigoPuntoVenta' => $codigoPuntoVenta,
            'codigoSistema' => $codigoSistema,
            'codigoSucursal' => $codigoSucursal,
            'cuis' => $cuis, 
            'nit' => $nit
        ));

        $options = array(
            'http' => array(
                'header' => 'apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJHdWljaGkxLiIsImNvZGlnb1Npc3RlbWEiOiI3NzUzNTU0NkI3MTJERDQwOUQ3QTM4NyIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTFORFUyTXpRd01EUUNBTWdwRkpRS0FBQUEiLCJpZCI6MzAxNTc4OCwiZXhwIjoxNzAzOTgwODAwLCJpYXQiOjE2OTE2MDMxMzIsIm5pdERlbGVnYWRvIjo1MTUzNjEwMDEyLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.Y61q9_pZiOG49HYRQ5OfXRHvDCh1V8hoviWuA472DgV5f3CdV-MOxz9y4u07AVB-bMByebK_wskxUWXf6cliQQ',
                'timeout' => 5
            )
        );

        $context = stream_context_create($options);
        try {
            $client = new \SoapClient($wsdl, [
                'stream_context' => $context,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);
            $result = $client->sincronizarParametricaMotivoAnulacion($params);
        }catch (SoapFault $fault){
            $result = "TOKEN NO VÁLIDO";
        }
        return $result;
    }

    public function sincronizarParametricaEventosSignificativos($puntoVenta, $codSucursal){
        $wsdl="https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionSincronizacion?wsdl";
        $codigoAmbiente = 2;
        $codigoPuntoVenta = $puntoVenta;
        $codigoSistema = "77535546B712DD409D7A387";
        $codigoSucursal = $codSucursal;
        $cuis = $_SESSION['scuis'];
        $nit = "5153610012";

        $params = array('SolicitudSincronizacion' => array(
            'codigoAmbiente' => $codigoAmbiente,
            'codigoPuntoVenta' => $codigoPuntoVenta,
            'codigoSistema' => $codigoSistema,
            'codigoSucursal' => $codigoSucursal,
            'cuis' => $cuis, 
            'nit' => $nit
        ));

        $options = array(
            'http' => array(
                'header' => 'apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJHdWljaGkxLiIsImNvZGlnb1Npc3RlbWEiOiI3NzUzNTU0NkI3MTJERDQwOUQ3QTM4NyIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTFORFUyTXpRd01EUUNBTWdwRkpRS0FBQUEiLCJpZCI6MzAxNTc4OCwiZXhwIjoxNzAzOTgwODAwLCJpYXQiOjE2OTE2MDMxMzIsIm5pdERlbGVnYWRvIjo1MTUzNjEwMDEyLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.Y61q9_pZiOG49HYRQ5OfXRHvDCh1V8hoviWuA472DgV5f3CdV-MOxz9y4u07AVB-bMByebK_wskxUWXf6cliQQ',
                'timeout' => 5
            )
        );

        $context = stream_context_create($options);
        try {
            $client = new \SoapClient($wsdl, [
                'stream_context' => $context,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);
            $result = $client->sincronizarParametricaEventosSignificativos($params);
        }catch (SoapFault $fault){
            $result = "TOKEN NO VÁLIDO";
        }
        return $result;
    }

    public function sincronizarParametricaUnidadMedida($puntoVenta, $codSucursal){
        $wsdl="https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionSincronizacion?wsdl";
        $codigoAmbiente = 2;
        $codigoPuntoVenta = $puntoVenta;
        $codigoSistema = "77535546B712DD409D7A387";
        $codigoSucursal = $codSucursal;
        $cuis = $_SESSION['scuis'];
        $nit = "5153610012";

        $params = array('SolicitudSincronizacion' => array(
            'codigoAmbiente' => $codigoAmbiente,
            'codigoPuntoVenta' => $codigoPuntoVenta,
            'codigoSistema' => $codigoSistema,
            'codigoSucursal' => $codigoSucursal,
            'cuis' => $cuis, 
            'nit' => $nit
        ));

        $options = array(
            'http' => array(
                'header' => 'apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJHdWljaGkxLiIsImNvZGlnb1Npc3RlbWEiOiI3NzUzNTU0NkI3MTJERDQwOUQ3QTM4NyIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTFORFUyTXpRd01EUUNBTWdwRkpRS0FBQUEiLCJpZCI6MzAxNTc4OCwiZXhwIjoxNzAzOTgwODAwLCJpYXQiOjE2OTE2MDMxMzIsIm5pdERlbGVnYWRvIjo1MTUzNjEwMDEyLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.Y61q9_pZiOG49HYRQ5OfXRHvDCh1V8hoviWuA472DgV5f3CdV-MOxz9y4u07AVB-bMByebK_wskxUWXf6cliQQ',
                'timeout' => 5
            )
        );

        $context = stream_context_create($options);
        try {
            $client = new \SoapClient($wsdl, [
                'stream_context' => $context,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);
            $result = $client->sincronizarParametricaUnidadMedida($params);
        }catch (SoapFault $fault){
            $result = "TOKEN NO VÁLIDO";
        }
        return $result;
    }

    public function recepcionFactura($archivo, $fechaEnvio, $hashArchivo, $puntoVenta, $codSucursal){
        $wsdl="https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl";
        $codigoAmbiente = 2;
        $codigoDocumentoSector = 1;
        $codigoEmision = 1;
        $codigoModalidad = 2;
        $codigoPuntoVenta = $puntoVenta;
        $codigoSistema = "77535546B712DD409D7A387";
        $codigoSucursal = $codSucursal;
        $cufd = $_SESSION['scufd'];
        $cuis = $_SESSION['scuis'];
        $nit = "5153610012";
        $tipoFacturaDocumento = 1;
        $archivo = $archivo;
        $fechaEnvio = $fechaEnvio;
        $hashArchivo = $hashArchivo;

        $params = array('SolicitudServicioRecepcionFactura' => array(
            'codigoAmbiente' => $codigoAmbiente,
            'codigoDocumentoSector' => $codigoDocumentoSector,
            'codigoEmision' => $codigoEmision,
            'codigoModalidad' => $codigoModalidad,
            'codigoPuntoVenta' => $codigoPuntoVenta,
            'codigoSistema' => $codigoSistema,
            'codigoSucursal' => $codigoSucursal,
            'cufd' => $cufd,
            'cuis' => $cuis, 
            'nit' => $nit,
            'tipoFacturaDocumento' => $tipoFacturaDocumento,
            'archivo' => $archivo,
            'fechaEnvio' => $fechaEnvio,
            'hashArchivo' => $hashArchivo
        ));

        $options = array(
            'http' => array(
                'header' => 'apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJHdWljaGkxLiIsImNvZGlnb1Npc3RlbWEiOiI3NzUzNTU0NkI3MTJERDQwOUQ3QTM4NyIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTFORFUyTXpRd01EUUNBTWdwRkpRS0FBQUEiLCJpZCI6MzAxNTc4OCwiZXhwIjoxNzAzOTgwODAwLCJpYXQiOjE2OTE2MDMxMzIsIm5pdERlbGVnYWRvIjo1MTUzNjEwMDEyLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.Y61q9_pZiOG49HYRQ5OfXRHvDCh1V8hoviWuA472DgV5f3CdV-MOxz9y4u07AVB-bMByebK_wskxUWXf6cliQQ',
                'timeout' => 5
            )
        );

        //dd($params);
        $context = stream_context_create($options);
        try {
            $client = new \SoapClient($wsdl, [
                'stream_context' => $context,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);
            $result = $client->recepcionFactura($params);
        }catch (SoapFault $fault){
            $result = "TOKEN NO VÁLIDO";
        }
        return $result;
    }

    public function anulacionFactura($cuf, $motivoSeleccionado, $puntoVenta, $codSucursal){
        $wsdl="https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl";
        $codigoAmbiente = 2;
        $codigoDocumentoSector = 1;
        $codigoEmision = 1;
        $codigoModalidad = 2;
        $codigoPuntoVenta = $puntoVenta;
        $codigoSistema = "77535546B712DD409D7A387";
        $codigoSucursal = $codSucursal;
        $cufd = $_SESSION['scufd'];
        $cuis = $_SESSION['scuis'];
        $nit = "5153610012";
        $tipoFacturaDocumento = 1;
        $codigoMotivo = $motivoSeleccionado;
        $cuf = $cuf;

        $params = array('SolicitudServicioAnulacionFactura' => array(
            'codigoAmbiente' => $codigoAmbiente,
            'codigoDocumentoSector' => $codigoDocumentoSector,
            'codigoEmision' => $codigoEmision,
            'codigoModalidad' => $codigoModalidad,
            'codigoPuntoVenta' => $codigoPuntoVenta,
            'codigoSistema' => $codigoSistema,
            'codigoSucursal' => $codigoSucursal,
            'cufd' => $cufd,
            'cuis' => $cuis, 
            'nit' => $nit,
            'tipoFacturaDocumento' => $tipoFacturaDocumento,
            'codigoMotivo' => $codigoMotivo,
            'cuf' => $cuf
        ));

        $options = array(
            'http' => array(
                'header' => 'apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJHdWljaGkxLiIsImNvZGlnb1Npc3RlbWEiOiI3NzUzNTU0NkI3MTJERDQwOUQ3QTM4NyIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTFORFUyTXpRd01EUUNBTWdwRkpRS0FBQUEiLCJpZCI6MzAxNTc4OCwiZXhwIjoxNzAzOTgwODAwLCJpYXQiOjE2OTE2MDMxMzIsIm5pdERlbGVnYWRvIjo1MTUzNjEwMDEyLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.Y61q9_pZiOG49HYRQ5OfXRHvDCh1V8hoviWuA472DgV5f3CdV-MOxz9y4u07AVB-bMByebK_wskxUWXf6cliQQ',
                'timeout' => 5
            )
        );

        //dd($params);

        $context = stream_context_create($options);
        try {
            $client = new \SoapClient($wsdl, [
                'stream_context' => $context,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);
            $result = $client->anulacionFactura($params);
        }catch (SoapFault $fault){
            $result = "TOKEN NO VÁLIDO";
        }
        return $result;        
    }

    public function recepcionPaqueteFactura($archivo, $fechaEnvio, $hashArchivo, $numeroFacturas, $puntoVenta, $codSucursal){
        $wsdl="https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl";
        $codigoAmbiente = 2;
        $codigoDocumentoSector = 1;
        $codigoEmision = 2;
        $codigoModalidad = 2;
        $codigoPuntoVenta = $puntoVenta;
        $codigoSistema = "77535546B712DD409D7A387";
        $codigoSucursal = $codSucursal;
        $cufd = $_SESSION['scufd'];
        $cuis = $_SESSION['scuis'];
        $nit = "5153610012";
        $tipoFacturaDocumento = 1;
        $archivo = $archivo;
        $fechaEnvio = $fechaEnvio;
        $hashArchivo = $hashArchivo;
        $cafc = $_SESSION['scafc'];
        //$cafc = "c";
        $cantidadFacturas = $numeroFacturas;
        $codigoEvento = $_SESSION['scodigoevento'];;
        $descripcion = "CORTE DEL SERVICIO DE INTERNET";
        $codigoRecepcionEvento = 1;
        $codigoRecepcion = 1;

        $params = array('SolicitudServicioRecepcionPaquete' => array(
            'codigoAmbiente' => $codigoAmbiente,
            'codigoDocumentoSector' => $codigoDocumentoSector,
            'codigoEmision' => $codigoEmision,
            'codigoModalidad' => $codigoModalidad,
            'codigoPuntoVenta' => $codigoPuntoVenta,
            'codigoSistema' => $codigoSistema,
            'codigoSucursal' => $codigoSucursal,
            'cufd' => $cufd,
            'cuis' => $cuis, 
            'nit' => $nit,
            'tipoFacturaDocumento' => $tipoFacturaDocumento,
            'archivo' => $archivo,
            'fechaEnvio' => $fechaEnvio,
            'hashArchivo' => $hashArchivo,
            'cafc' => $cafc,
            'cantidadFacturas' => $cantidadFacturas,
            'codigoEvento' => $codigoEvento
        ));

        $options = array(
            'http' => array(
                'header' => 'apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJHdWljaGkxLiIsImNvZGlnb1Npc3RlbWEiOiI3NzUzNTU0NkI3MTJERDQwOUQ3QTM4NyIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTFORFUyTXpRd01EUUNBTWdwRkpRS0FBQUEiLCJpZCI6MzAxNTc4OCwiZXhwIjoxNzAzOTgwODAwLCJpYXQiOjE2OTE2MDMxMzIsIm5pdERlbGVnYWRvIjo1MTUzNjEwMDEyLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.Y61q9_pZiOG49HYRQ5OfXRHvDCh1V8hoviWuA472DgV5f3CdV-MOxz9y4u07AVB-bMByebK_wskxUWXf6cliQQ',
                'timeout' => 5
            )
        );

        //dd($params);

        $context = stream_context_create($options);
        try {
            $client = new \SoapClient($wsdl, [
                'stream_context' => $context,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);
            $result = $client->recepcionPaqueteFactura($params);
        }catch (SoapFault $fault){
            $result = "TOKEN NO VÁLIDO";
        }
        return $result;
    }

    public function registroEventoSignificativo($descripcion, $cufdEvento, $codigoMotivoEvento, $inicioEvento, $finEvento, $puntoVenta, $codSucursal){
        $wsdl="https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionOperaciones?wsdl";
        $codigoAmbiente = 2;
        $codigoMotivoEvento = $codigoMotivoEvento;
        $codigoPuntoVenta = $puntoVenta;
        $codigoSistema = "77535546B712DD409D7A387";
        $codigoSucursal = $codSucursal;
        $cufd = $_SESSION['scufd'];
        $cufdEvento = $cufdEvento;
        $cuis = $_SESSION['scuis'];
        $descripcion = $descripcion;
        $fechaHoraFinEvento = $finEvento;
        $fechaHoraInicioEvento = $inicioEvento;
        $nit = "5153610012";

        $params = array('SolicitudEventoSignificativo' => array(
            'codigoAmbiente' => $codigoAmbiente,
            'codigoMotivoEvento' => $codigoMotivoEvento,
            'codigoPuntoVenta' => $codigoPuntoVenta,
            'codigoSistema' => $codigoSistema,
            'codigoSucursal' => $codigoSucursal,
            'cufd' => $cufd,
            'cufdEvento' => $cufdEvento,
            'cuis' => $cuis, 
            'descripcion' => $descripcion,
            'fechaHoraFinEvento' => $fechaHoraFinEvento,
            'fechaHoraInicioEvento' => $fechaHoraInicioEvento,
            'nit' => $nit
        ));

        $options = array(
            'http' => array(
                'header' => 'apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJHdWljaGkxLiIsImNvZGlnb1Npc3RlbWEiOiI3NzUzNTU0NkI3MTJERDQwOUQ3QTM4NyIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTFORFUyTXpRd01EUUNBTWdwRkpRS0FBQUEiLCJpZCI6MzAxNTc4OCwiZXhwIjoxNzAzOTgwODAwLCJpYXQiOjE2OTE2MDMxMzIsIm5pdERlbGVnYWRvIjo1MTUzNjEwMDEyLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.Y61q9_pZiOG49HYRQ5OfXRHvDCh1V8hoviWuA472DgV5f3CdV-MOxz9y4u07AVB-bMByebK_wskxUWXf6cliQQ',
                'timeout' => 5
            )
        );

        $context = stream_context_create($options);
        try {
            $client = new \SoapClient($wsdl, [
                'stream_context' => $context,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);
            $result = $client->registroEventoSignificativo($params);
        }catch (SoapFault $fault){
            $result = "TOKEN NO VÁLIDO";
        }
        return $result;        
    }

    public function validacionRecepcionPaqueteFactura($puntoVenta, $codSucursal){
        $wsdl="https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl";
        $codigoAmbiente = 2;
        $codigoDocumentoSector = 1;
        $codigoEmision = 2;
        $codigoModalidad = 2;
        $codigoPuntoVenta = $puntoVenta;
        $codigoSistema = "77535546B712DD409D7A387";
        $codigoSucursal = $codSucursal;
        $cufd = $_SESSION['scufd'];
        $cuis = $_SESSION['scuis'];
        $nit = "5153610012";
        $tipoFacturaDocumento = 1;
        $codigoRecepcion = $_SESSION['scodigorecepcion'];
        

        $params = array('SolicitudServicioValidacionRecepcionPaquete' => array(
            'codigoAmbiente' => $codigoAmbiente,
            'codigoDocumentoSector' => $codigoDocumentoSector,
            'codigoEmision' => $codigoEmision,
            'codigoModalidad' => $codigoModalidad,
            'codigoPuntoVenta' => $codigoPuntoVenta,
            'codigoSistema' => $codigoSistema,
            'codigoSucursal' => $codigoSucursal,
            'cufd' => $cufd, 
            'cuis' => $cuis,
            'nit' => $nit,
            'tipoFacturaDocumento' => $tipoFacturaDocumento,
            'codigoRecepcion' => $codigoRecepcion
        ));

        $options = array(
            'http' => array(
                'header' => 'apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJHdWljaGkxLiIsImNvZGlnb1Npc3RlbWEiOiI3NzUzNTU0NkI3MTJERDQwOUQ3QTM4NyIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTFORFUyTXpRd01EUUNBTWdwRkpRS0FBQUEiLCJpZCI6MzAxNTc4OCwiZXhwIjoxNzAzOTgwODAwLCJpYXQiOjE2OTE2MDMxMzIsIm5pdERlbGVnYWRvIjo1MTUzNjEwMDEyLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.Y61q9_pZiOG49HYRQ5OfXRHvDCh1V8hoviWuA472DgV5f3CdV-MOxz9y4u07AVB-bMByebK_wskxUWXf6cliQQ',
                'timeout' => 5
            )
        );

        //dd($params);

        $context = stream_context_create($options);
        try {
            $client = new \SoapClient($wsdl, [
                'stream_context' => $context,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);
            $result = $client->validacionRecepcionPaqueteFactura($params);
        }catch (SoapFault $fault){
            $result = "TOKEN NO VÁLIDO";
        }
        return $result;        
    }

    public function registroPuntoVenta($nombre, $descripcion, $nit, $idtipopuntoventa, $idsucursal, $puntoVenta, $codSucursal){
        $wsdl="https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionOperaciones?wsdl";
        $codigoAmbiente = 2;
        $codigoModalidad = 2;
        $codigoSistema = "77535546B712DD409D7A387";
        $codigoSucursal = $codSucursal;
        $codigoTipoPuntoVenta = $idtipopuntoventa;
        $cuis = $_SESSION['scuis'];
        $descripcion = $descripcion;
        $nit = $nit;
        $nombrePuntoVenta = $nombre;
        

        $params = array('SolicitudRegistroPuntoVenta' => array(
            'codigoAmbiente' => $codigoAmbiente,
            'codigoModalidad' => $codigoModalidad,
            'codigoSistema' => $codigoSistema,
            'codigoSucursal' => $codigoSucursal,
            'codigoTipoPuntoVenta' => $codigoTipoPuntoVenta,
            'cuis' => $cuis,
            'descripcion' => $descripcion,
            'nit' => $nit,
            'nombrePuntoVenta' => $nombrePuntoVenta
        ));

        $options = array(
            'http' => array(
                'header' => 'apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJHdWljaGkxLiIsImNvZGlnb1Npc3RlbWEiOiI3NzUzNTU0NkI3MTJERDQwOUQ3QTM4NyIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTFORFUyTXpRd01EUUNBTWdwRkpRS0FBQUEiLCJpZCI6MzAxNTc4OCwiZXhwIjoxNzAzOTgwODAwLCJpYXQiOjE2OTE2MDMxMzIsIm5pdERlbGVnYWRvIjo1MTUzNjEwMDEyLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.Y61q9_pZiOG49HYRQ5OfXRHvDCh1V8hoviWuA472DgV5f3CdV-MOxz9y4u07AVB-bMByebK_wskxUWXf6cliQQ',
                'timeout' => 5
            )
        );

        //dd($params);

        $context = stream_context_create($options);
        try {
            $client = new \SoapClient($wsdl, [
                'stream_context' => $context,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);
            $result = $client->registroPuntoVenta($params);
        }catch (SoapFault $fault){
            $result = "TOKEN NO VÁLIDO";
        }
        return $result;        
    }

    public function cierrePuntoVenta($codigoPuntoVenta, $nit, $codSucursal){
        $wsdl="https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionOperaciones?wsdl";
        $codigoAmbiente = 2;
        $codigoPuntoVenta = $codigoPuntoVenta;
        $codigoSistema = "77535546B712DD409D7A387";
        $codigoSucursal = $codSucursal;
        $cuis = $_SESSION['scuis'];
        $nit = $nit;
        

        $params = array('SolicitudCierrePuntoVenta' => array(
            'codigoAmbiente' => $codigoAmbiente,
            'codigoPuntoVenta' => $codigoPuntoVenta,
            'codigoSistema' => $codigoSistema,
            'codigoSucursal' => $codigoSucursal,
            'cuis' => $cuis,
            'nit' => $nit
        ));

        $options = array(
            'http' => array(
                'header' => 'apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJHdWljaGkxLiIsImNvZGlnb1Npc3RlbWEiOiI3NzUzNTU0NkI3MTJERDQwOUQ3QTM4NyIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTFORFUyTXpRd01EUUNBTWdwRkpRS0FBQUEiLCJpZCI6MzAxNTc4OCwiZXhwIjoxNzAzOTgwODAwLCJpYXQiOjE2OTE2MDMxMzIsIm5pdERlbGVnYWRvIjo1MTUzNjEwMDEyLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.Y61q9_pZiOG49HYRQ5OfXRHvDCh1V8hoviWuA472DgV5f3CdV-MOxz9y4u07AVB-bMByebK_wskxUWXf6cliQQ',
                'timeout' => 5
            )
        );

        //dd($params);

        $context = stream_context_create($options);
        try {
            $client = new \SoapClient($wsdl, [
                'stream_context' => $context,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);
            $result = $client->cierrePuntoVenta($params);
        }catch (SoapFault $fault){
            $result = "TOKEN NO VÁLIDO";
        }
        return $result;        
    }
    
}
