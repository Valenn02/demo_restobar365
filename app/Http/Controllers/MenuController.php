<?php

namespace App\Http\Controllers;

use App\menu;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;
use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;

class MenuController extends Controller
{
    //
    public function index(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $menu = menu::join('categoria_menu', 'menu.idcategoria_menu', '=', 'categoria_menu.id')

                ->select(
                    'menu.id',
                    'menu.idcategoria_menu',
                    'menu.nombre',
                    'menu.precio_venta',
                    'menu.descripcion',
                    'menu.condicion',
                    'menu.fotografia',
                    'categoria_menu.nombre as nombre_categoria',

                )
                ->orderBy('menu.id', 'desc')->paginate(5);
        } else {
            $menu = menu::join('categoria_menu', 'menu.idcategoria_menu', '=', 'categoria_menu.id')

                ->select(
                    'menu.id',
                    'menu.idcategoria_menu',
                    'menu.nombre',
                    'menu.precio_venta',
                    'menu.descripcion',
                    'menu.condicion',
                    'menu.fotografia',
                    'categoria_menu.nombre as nombre_categoria',

                )
                ->where('menu.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderBy('menu.id', 'desc')->paginate(5);
        }


        return [
            'pagination' => [
                'total' => $menu->total(),
                'current_page' => $menu->currentPage(),
                'per_page' => $menu->perPage(),
                'last_page' => $menu->lastPage(),
                'from' => $menu->firstItem(),
                'to' => $menu->lastItem(),
            ],
            'articulos' => $menu
        ];
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

    public function update(Request $request)
    {

        if (!$request->ajax())
            return redirect('/');

        $menu = menu::findOrFail($request->id);
        $menu->nombre = $request->nombre;
        $menu->precio_venta = $request->precio_venta;
        $menu->descripcion = $request->descripcion;
        $menu->idcategoria_menu = $request->idcategoria_menu;

        $nombreimagen = " ";
        if ($request->hasFile('fotografia')) {
            // Eliminar imagen anterior si existe
            if ($menu->fotografia != '' && Storage::exists('public/img/menu/' . $menu->fotografia)) {
                Storage::delete('public/img/menu/' . $menu->fotografia);
            }

            $imagen = $request->file("fotografia");
            $nombreimagen = Str::slug($request->nombre) . "." . $imagen->guessExtension();
            $imagen->storeAs('public/img/menu', $nombreimagen);

            $ruta = public_path("img/menu/");

            // Copiar la imagen al directorio
            copy($imagen->getRealPath(), $ruta . $nombreimagen);
            $menu->fotografia = $nombreimagen;
        }
        $menu->save();
        return response()->json($menu);
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');
        $menu = menu::findOrFail($request->id);
        $menu->condicion = '0';
        $menu->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');
        $menu = menu::findOrFail($request->id);
        $menu->condicion = '1';
        $menu->save();
    }

    //eliminar
    public function destroy($id)
    {
        $menu = menu::findOrFail($id);
        $menu->delete();
        return response()->json(['message' => '¡menú ha sido eliminado correctamente!']);
    }
}
