<?php

namespace App\Http\Controllers;

use App\categoria_menu;
use Illuminate\Http\Request;

class categoriamenuController extends Controller
{
    public function index (){
        $menuCate = categoria_menu::all(); 
        return response()->json($menuCate); 
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
    public function update(Request $request, $id)
    {  
        $menu = categoria_menu::findOrFail($id);
        $request->validate([
            'nombre' => 'required|unique:menu,nombre,'.$menu->id.'|max:100',
            'descripcion' => 'nullable',
            'condicion' => 'nullable',
        ]);
    
        $menu->nombre = $request->nombre;
        $menu->descripcion = $request->descripcion;
        $menu->condicion = $request->condicion;

        $menu->save();
    
        return response()->json($menu);
    }
    public function destroy($id)
    {
        $menu = categoria_menu::findOrFail($id);
        $menu->delete();
        return response()->json(['message' => '¡menú ha sido eliminado correctamente!']);
    }
}
