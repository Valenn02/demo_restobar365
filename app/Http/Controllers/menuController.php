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
        
        //$request->validate([
            //'nombre' => 'required|unique:menu|max:100',
            //'precio_venta' => 'nullable|numeric',
            //'descripcion' => 'nullable|max:256',
            //'idcategoria_menu' => 'required|exists:categoria_menu,id',
        //]);

        
        $menu = new menu();
        $menu->nombre = $request->nombre;
        $menu->precio_venta = $request->precio_venta;
        $menu->descripcion = $request->descripcion;
        $menu->idcategoria_menu = $request->idcategoria_menu;
        $menu->condicion = true;
        if ($request->hasFile('fotografia')) {
            if ($request->hasFile('fotografia')) {
                $imagen = $request->file("fotografia");
                $nombreimagen = Str::slug($request->nombre) . "." . $imagen->guessExtension();
                $ruta = public_path("img/menu/");

                // Crear el directorio si no existe
                if (!File::isDirectory($ruta)) {
                    File::makeDirectory($ruta, 0755, true);
                }

                // Copiar la imagen al directorio
                copy($imagen->getRealPath(), $ruta . $nombreimagen);

                $menu->fotografia = $nombreimagen;
            }
        }
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
