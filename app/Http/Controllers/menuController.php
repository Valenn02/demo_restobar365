<?php

namespace App\Http\Controllers;

use App\menu;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class menuController extends Controller
{
    //
    public function index (){
        $menu = menu::all(); 
        return response()->json($menu); 
    }

    public function create(Request $request)
    {
        
        $request->validate([
            'nombre' => 'required|unique:menu|max:100',
            'precio_venta' => 'nullable|numeric',
            'descripcion' => 'nullable|max:256',
            'idcategoria_menu' => 'required|exists:categoria_menu,id',
        ]);

        
        $menu = new menu();
        $menu->nombre = $request->nombre;
        $menu->precio_venta = $request->precio_venta;
        $menu->descripcion = $request->descripcion;
        $menu->idcategoria_menu = $request->idcategoria_menu;
        $menu->save();

        return response()->json($menu);    
    }

    public function update(Request $request, $id)
    {  
        $menu = menu::findOrFail($id);
        $request->validate([
            'nombre' => 'required|unique:menu,nombre,'.$menu->id.'|max:100',
            'precio_venta' => 'nullable|numeric',
            'descripcion' => 'nullable|max:256',
            'idcategoria_menu' => 'required|exists:categoria_menu,id',
        ]);
    
        $menu->nombre = $request->nombre;
        $menu->precio_venta = $request->precio_venta;
        $menu->descripcion = $request->descripcion;
        $menu->idcategoria_menu = $request->idcategoria_menu;
        $menu->save();
    
        return response()->json($menu);
    }

    //eliminar
    public function destroy($id)
    {
        $menu = menu::findOrFail($id);
        $menu->delete();
        return response()->json(['message' => '¡menú ha sido eliminado correctamente!']);
    }


}
