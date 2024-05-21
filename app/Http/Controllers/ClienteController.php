<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Persona;
use App\Exports\ClientExport;
use Maatwebsite\Excel\Facades\Excel;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $personas = Persona::orderBy('id', 'desc')
                        ->paginate(3);
        }
        else{
            $personas = Persona::where($criterio, 'like', '%'. $buscar . '%')
                ->orderBy('id', 'desc')
                ->paginate(3);
        }
        

        return [
            'pagination' => [
                'total'        => $personas->total(),
                'current_page' => $personas->currentPage(),
                'per_page'     => $personas->perPage(),
                'last_page'    => $personas->lastPage(),
                'from'         => $personas->firstItem(),
                'to'           => $personas->lastItem(),
            ],
            'personas' => $personas
        ];
    }

    public function selectCliente(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $filtro = $request->filtro;
        $clientes = Persona::where('nombre', 'like', '%'. $filtro . '%')
        ->orWhere('num_documento', 'like', '%'. $filtro . '%')
        ->select('id','nombre','num_documento','email')
        ->orderBy('nombre', 'asc')->get();
 
        return ['clientes' => $clientes];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $persona = new Persona();
        $persona->nombre = $request->nombre;
        $persona->num_documento = $request->num_documento;
        $persona->email = $request->email;
        $persona->tipo_documento = 5;
        $persona->estadoCli = true;

        $persona->save();
    }

    public function store2(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $persona = new Persona();
        $persona->nombre = $request->nombre;
        $persona->direccion = $request->direccion;
        $persona->tipo_documento = $request->tipo_documento;
        $persona->num_documento = $request->num_documento;
        $persona->telefono = $request->telefono;
        $persona->email = $request->email;
        $persona->estadoCli = true;
        
        $persona->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $persona = Persona::findOrFail($request->id);
        $persona->nombre = $request->nombre;
        $persona->direccion = $request->direccion;
        $persona->tipo_documento = $request->tipo_documento;
        $persona->num_documento = $request->num_documento;
        $persona->telefono = $request->telefono;
        $persona->email = $request->email;
        $persona->save();
    }

    public function listarReporteClienteExcel()
    {
        return Excel::download(new ClientExport, 'clientes.xlsx');
    }
    public function desactivarCli(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $cliente = Persona::findOrFail($request->id);
        $cliente->estadoCli = '0';
        $cliente->save();
    }
    public function activarCli(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $persona = Persona::findOrFail($request->id);
        $persona->estadoCli = '1';
        $persona->save();
    }
    public function buscarPorDocumento(Request $request)
    {
        $documento = $request->query('documento');
        $cliente = Persona::where('num_documento', $documento)->first();

        if ($cliente) {
            return response()->json(['success' => true, 'cliente' => $cliente]);
        } else {
            return response()->json(['success' => false, 'message' => 'Cliente no encontrado']);
        }
    }
    public function verificarExistencia(Request $request)
    {
        $documento = $request->query('documento');
        $cliente = Persona::where('num_documento', $documento)->first();

        if ($cliente) {
            return response()->json(['existe' => true, 'cliente' => $cliente]);
        } else {
            return response()->json(['existe' => false]);
        }
    }
}
