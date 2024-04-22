<?php

namespace App\Http\Controllers;

use App\categoria_menu;
use App\menu;

use Illuminate\Http\Request;

class categoriamenuController extends Controller
{
    public function index(Request $request){


        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $menuCate = categoria_menu::orderBy('id', 'desc')->paginate(3);
        }
        else{
            $menuCate = categoria_menu::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(3);
        }
        

        return [
            'pagination' => [
                'total'        => $menuCate->total(),
                'current_page' => $menuCate->currentPage(),
                'per_page'     => $menuCate->perPage(),
                'last_page'    => $menuCate->lastPage(),
                'from'         => $menuCate->firstItem(),
                'to'           => $menuCate->lastItem(),
            ],
            'categorias' => $menuCate
        ];
    }
    public function create(Request $request)
    {
        
        $request->validate([
            'nombre' => 'required|unique:menu|max:100',
            'descripcion' => 'nullable',
            'condicion' => 'nullable',
        ]);

        
        $menuCate = new categoria_menu();
        $menuCate->nombre = $request->nombre;
        $menuCate->descripcion = $request->descripcion;
        $menuCate->condicion = $request->condicion;
        $menuCate->save();

        return response()->json($menuCate);    
    }

    public function createCategoria(Request $request)
    {
        
        $request->validate([
            'nombre' => 'required|unique:menu|max:100',
            'descripcion' => 'nullable',
            'condicion' => 'nullable',
        ]);

        
        $menuCate = new categoria_menu();
        $menuCate->nombre = $request->nombre;
        $menuCate->descripcion = $request->descripcion;
        $menuCate->condicion = true;
        $menuCate->save();

        return response()->json($menuCate);    
    }

    public function update(Request $request)
    {  
        if (!$request->ajax()) return redirect('/');
        $menu = categoria_menu::findOrFail($request->id);
        $menu->nombre = $request->nombre;
        $menu->descripcion = $request->descripcion;
        $menu->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categoria = categoria_menu::findOrFail($request->id);
        $categoria->condicion = '0';
        $categoria->save();

        // Desactivar los artículos del menú que pertenecen a esta categoría
        $menu = menu::where('idcategoria_menu', $categoria->id)->get();
        foreach ($menu as $plato) {
            $plato->condicion = '0';
            $plato->save();
        }
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categoria = categoria_menu::findOrFail($request->id);
        $categoria->condicion = '1';
        $categoria->save();

        // Activas los artículos del menú que pertenecen a esta categoría
        $menu = menu::where('idcategoria_menu', $categoria->id)->get();
        foreach ($menu as $plato) {
            $plato->condicion = '1';
            $plato->save();
        }
    }

    public function destroy($id)
    {
        $menu = categoria_menu::findOrFail($id);
        $menu->delete();
        return response()->json(['message' => '¡menú ha sido eliminado correctamente!']);
    }
}
