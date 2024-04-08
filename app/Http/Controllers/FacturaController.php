<?php

namespace App\Http\Controllers;

use App\MotivoAnulacion;
use Illuminate\Http\Request;
use SoapClient;

class FacturaController extends Controller
{
    public function verificarComunicacion(){
        require "SiatController.php";
            $siat = new SiatController();
            $res = $siat->verificarComunicacion();
            if($res->RespuestaComunicacion->transaccion==true){
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
            }else{
                $msg="Falló la comunicación";
                echo json_encode($msg, JSON_UNESCAPED_UNICODE);
            }
    }

    public function cuis(){
        require "SiatController.php";
        $siat = new SiatController();
        $res = $siat->cuis();
        $res->RespuestaCuis->codigo;
        $_SESSION['scuis'] = $res->RespuestaCuis->codigo;
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    public function cufd(){
        if(!isset($_SESSION['scufd'])){
            require "SiatController.php";
            $siat = new SiatController();
            $res = $siat->cufd();
            if($res->RespuestaCufd->transaccion==true){
                $cufd = $res->RespuestaCufd->codigo;
                $codigoControl = $res->RespuestaCufd->codigoControl;
                $direccion = $res->RespuestaCufd->direccion;
                $fechaVigencia = $res->RespuestaCufd->fechaVigencia;
                $_SESSION['scufd'] = $cufd;
                $_SESSION['scodigoControl'] = $codigoControl;
                $_SESSION['sdireccion'] = $direccion;
                $_SESSION['sfechaVigenciaCufd'] = $fechaVigencia;
            }else{
                $res=false;
            }
        }else{
            $fechaVigencia = substr($_SESSION['sfechaVigenciaCufd'],0,16);
            $fechaVigencia = str_replace("T", " ", $fechaVigencia);
            if($fechaVigencia<date('Y-m-d H:i')){
                require "SiatController.php";
                $siat = new SiatController();
                $res = $siat->cufd();
                if($res->RespuestaCufd->transaccion==true){
                    $cufd = $res->RespuestaCufd->codigo;
                    $codigoControl = $res->RespuestaCufd->codigoControl;
                    $direccion = $res->RespuestaCufd->direccion;
                    $fechaVigencia = $res->RespuestaCufd->fechaVigencia;
                    $_SESSION['scufd'] = $cufd;
                    $_SESSION['scodigoControl'] = $codigoControl;
                    $_SESSION['sdireccion'] = $direccion;
                    $_SESSION['sfechaVigenciaCufd'] = $fechaVigencia;
                }else{
                    $res=false;
                }
                }else{
                    $res['transaccion'] = true;
                    $res['codigo'] = $_SESSION['scufd'];
                    $res['fechaVigencia'] = $_SESSION['sfechaVigenciaCufd'];
                    $res['direccion'] = $_SESSION['sdireccion'];
                }
                }
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    public function anulacionFactura($cuf){
        require "SiatController.php";
        $siat = new SiatController();
        $res = $siat->anulacionFactura($cuf);
        //echo json_encode($res, JSON_UNESCAPED_UNICODE);
        var_dump($res);
    }

    public function obtenerDatosMotivoAnulacion(Request $request){
        if (!$request->ajax()) return redirect('/');

        $motivo_anulaciones = MotivoAnulacion::select('id', 'descripcion', 'codigo')
        ->orderBy('id', 'desc')
        ->get();

        return ['motivo_anulaciones' => $motivo_anulaciones];
    }
}
