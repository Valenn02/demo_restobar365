<?php

namespace App\Http\Controllers;

use App\EventosSignificativos;
use App\Factura;
use App\MotivoEvento;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventosSignificativosController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;

        if($buscar==''){
            $eventos_significativos = EventosSignificativos::join('motivo_eventos', 'eventos_significativos.idmotivoevento', '=', 'motivo_eventos.id')
            ->select('eventos_significativos.*', 'motivo_eventos.codigo as codigo', 'motivo_eventos.descripcion as descripcionEvento')
            ->orderBy('eventos_significativos.id', 'desc')->paginate(6);
        }
        else{
            $eventos_significativos = EventosSignificativos::join('motivo_eventos', 'eventos_significativos.idmotivoevento', '=', 'motivo_eventos.id')
            ->select('eventos_significativos.*', 'motivo_eventos.codigo', 'motivo_eventos.descripcion')
            ->orderBy('eventos_significativos.id', 'desc')->paginate(6);
        }

        return [
            'pagination' => [
                'total'        => $eventos_significativos->total(),
                'current_page' => $eventos_significativos->currentPage(),
                'per_page'     => $eventos_significativos->perPage(),
                'last_page'    => $eventos_significativos->lastPage(),
                'from'         => $eventos_significativos->firstItem(),
                'to'           => $eventos_significativos->lastItem(),
            ],
            'eventos_significativos' => $eventos_significativos
        ];
    }

    public function obtenerDatosMotivoEvento(Request $request){
        if (!$request->ajax()) return redirect('/');

        $motivo_eventos = MotivoEvento::select('id', 'descripcion', 'codigo')
        ->orderBy('id', 'desc')
        ->get();

        return ['motivo_eventos' => $motivo_eventos];
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $evento_significativo = new EventosSignificativos();
        $evento_significativo->descripcion = $request->descripcion;
        $evento_significativo->cufdEvento = $request->cufdEvento;
        $evento_significativo->nit = $request->nit;
        $evento_significativo->codigoMotivoEvento = $request->codigoMotivoEvento;
        $evento_significativo->inicioEvento = $request->inicioEvento;
        $evento_significativo->idmotivoevento = $request->idmotivoevento;
        $evento_significativo->estado = '1';

        $evento_significativo->save();
        
        // Establece la variable de sesión en la respuesta
        $request->session()->put('scodigorecepcion', $evento_significativo->codigoMotivoEvento);

        return response()->json(['message' => 'Evento registrado exitosamente']);
    }


    public function obtenerDatosSesion(Request $request)
    {
        return response()->json(['scodigorecepcion' => $request->session()->get('scodigorecepcion')]);
    }


    public function finalizarEvento(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $evento_significativo = EventosSignificativos::findOrFail($request->id);
        $evento_significativo->finEvento = $request->finEvento;
        $evento_significativo->cufd = $request->cufd;
        $evento_significativo->save();
    }

    public function errorEvento(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $evento_significativo = EventosSignificativos::findOrFail($request->id);
        $evento_significativo->estado = '1';
        $evento_significativo->save();
    }

    public function cambioEstadoEvento(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $evento_significativo = EventosSignificativos::findOrFail($request->id);
        $evento_significativo->estado = '0';
        $evento_significativo->save();
    }

    public function ultimoCufd()
    {
        $ayer = Carbon::yesterday();

        $ultimaFactura = Factura::whereDate('created_at', $ayer)->latest()->first();

        if ($ultimaFactura) {
            return response()->json([
                'cufd' => $ultimaFactura->cufd,
                'codigoControl' => $ultimaFactura->codigoControl,
            ]);
        } else {
            return response()->json(['error' => 'No se encontró ninguna factura del día anterior'], 404);
        }
    }
}
