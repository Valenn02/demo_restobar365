<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Persona;
use App\Sucursal;
use App\Exports\UserExport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $personas = User::join('personas','users.id','=','personas.id')
            ->join('roles','users.idrol','=','roles.id')
            ->join('sucursales','users.idsucursal','=','sucursales.id')
            ->select('personas.id','personas.nombre','personas.tipo_documento','personas.num_documento','personas.direccion','personas.telefono','personas.email','personas.fotografia','users.usuario','users.password','users.condicion','users.idrol','roles.nombre as rol', 'users.idsucursal', 'sucursales.nombre as sucursal')
            ->orderBy('personas.id', 'desc')->paginate(3);
        }
        else{
            $personas = User::join('personas','users.id','=','personas.id')
            ->join('roles','users.idrol','=','roles.id')
            ->select('personas.id','personas.nombre','personas.tipo_documento','personas.num_documento','personas.direccion','personas.telefono','personas.email','personas.fotografia','users.usuario','users.password','users.condicion','users.idrol','roles.nombre as rol', 'users.idsucursal', 'sucursales.nombre as sucursal')
            ->where('personas.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(3);
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

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();

            $persona = new Persona();
            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            
            if($request->hasFile('fotografia'))
            {
                if($request->hasFile('fotografia'))
                {
                    $imagen = $request->file("fotografia");
                    $nombreimagen = Str::slug($request->nombre).".".$imagen->guessExtension();
                    $ruta = public_path("img/usuarios/");

                    // Crear el directorio si no existe
                    if (!File::isDirectory($ruta)) {
                        File::makeDirectory($ruta, 0755, true);
                    }

                    // Copiar la imagen al directorio
                    copy($imagen->getRealPath(), $ruta . $nombreimagen);

                    $persona->fotografia = $nombreimagen;
                }
            }

            $persona->save();

            $user = new User();
            $user->id = $persona->id;
            $user->idrol = $request->idrol;
            $user->idsucursal = $request->idsucursal;
            $user->usuario = $request->usuario;
            $user->password = bcrypt( $request->password);
            $user->condicion = '1';            
            $user->save();

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/'); 

        try{
            DB::beginTransaction();

            $user = User::findOrFail($request->id);
            $persona = Persona::findOrFail($user->id);
            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;

            
            $nombreimagen = "";
            if($request->hasFile('fotografia'))
            {
                // Eliminar imagen anterior si existe
                if($persona->fotografia != '' && Storage::exists('public/img/usuarios/' . $persona->fotografia)){
                    Storage::delete('public/img/usuarios/' . $persona->fotografia);
                }

                $imagen = $request->file("fotografia");
                $nombreimagen = Str::slug($request->nombre).".".$imagen->guessExtension();
                $imagen->storeAs('public/img/usuarios', $nombreimagen);

                $ruta = public_path("img/usuarios/");

                // Copiar la imagen al directorio
                copy($imagen->getRealPath(), $ruta . $nombreimagen);


                $persona->fotografia = $nombreimagen;
            }
            
            Log::info('Datos actualizados', [
                'nombre' => $request->nombre, 
                'tipo_documento' => $request->tipo_documento,
                'num_documento' => $request->num_documento,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'fotografia' => $nombreimagen,
                'usuario' => $request->usuario,
                'password' => $request->password,
                'idrol' => $request->idrol,
                'id' => $request->id
                
            ]);


            $persona->save();
            
            $user->usuario = $request->usuario;
            $user->password = bcrypt($request->password);
            $user->condicion = '1';
            
            if($request->idrol != ''){
                $user->idrol = $request->idrol;
            }

            if($request->idsucursal != ''){
                $user->idsucursal = $request->idsucursal;
            }
            
            $user->save();

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condicion = '0';
        $user->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condicion = '1';
        $user->save();
    }

    public function listarReporteUsuariosExcel()
    {
        return Excel::download(new UserExport, 'usuarios.xlsx');
    }

    public function editarPersona(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $persona = User::join('personas','users.id','=','personas.id')
            ->join('roles','users.idrol','=','roles.id')
            ->join('sucursales','users.idsucursal','=','sucursales.id')
            ->select('personas.id','personas.nombre','personas.tipo_documento','personas.num_documento','personas.direccion','personas.telefono','personas.email','personas.fotografia','users.usuario','users.password','users.condicion','users.idrol','roles.nombre as rol', 'users.idsucursal', 'sucursales.nombre as sucursal')
            ->where('personas.id', $request->id);
    
        return ['persona' => $persona->first()];
    }
}
