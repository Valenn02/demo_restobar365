<?php

namespace App\Http\Controllers;

use App\Menu;
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
use Intervention\Image\Facades\Image;

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
            $menu = Menu::join('categoria_menu', 'menu.idcategoria_menu', '=', 'categoria_menu.id')

                ->select(
                    'menu.id',
                    'menu.idcategoria_menu',
                    'menu.codigo',
                    'menu.nombre',
                    'menu.precio_venta',
                    'menu.descripcion',
                    'menu.condicion',
                    'menu.fotografia',
                    'categoria_menu.nombre as nombre_categoria',

                )
                ->orderBy('menu.id', 'desc')
                ->paginate(8);
        } else {
            $menu = Menu::join('categoria_menu', 'menu.idcategoria_menu', '=', 'categoria_menu.id')

                ->select(
                    'menu.id',
                    'menu.idcategoria_menu',
                    'menu.codigo',
                    'menu.nombre',
                    'menu.precio_venta',
                    'menu.descripcion',
                    'menu.condicion',
                    'menu.fotografia',
                    'categoria_menu.nombre as nombre_categoria',

                )
                ->where('menu.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderBy('menu.id', 'desc')
                ->paginate(8);
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

    public function getAllMenu(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $menu = Menu::join('categoria_menu', 'menu.idcategoria_menu', '=', 'categoria_menu.id')

                ->select(
                    'menu.id',
                    'menu.idcategoria_menu',
                    'menu.codigo',
                    'menu.nombre',
                    'menu.precio_venta',
                    'menu.descripcion',
                    'menu.condicion',
                    'menu.fotografia',
                    'categoria_menu.nombre as nombre_categoria',
                    'categoria_menu.codigo as codigoProductoSin'

                )
                ->orderBy('menu.id', 'desc')->get();
        } else {
            $menu = Menu::join('categoria_menu', 'menu.idcategoria_menu', '=', 'categoria_menu.id')

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
                ->orderBy('menu.id', 'desc')->get();
        }

        return ['articulos' => $menu];
    }

    public function create(Request $request)
    {
        // Validar el formulario si es necesario
        // $request->validate([...]);

        $menu = new Menu();
        $menu->nombre = $request->nombre;
        $menu->codigo = $request->codigo;
        $menu->precio_venta = $request->precio_venta;
        $menu->descripcion = $request->descripcion;
        $menu->idcategoria_menu = $request->idcategoria_menu;
        $menu->condicion = true;

        if ($request->hasFile('fotografia')) {
            $imagen = $request->file('fotografia');
            $nombreimagen = Str::slug($request->nombre) . '.' . $imagen->getClientOriginalExtension();
            $ruta = public_path('img/menu/');

            // Crear instancia de la imagen
            $image = Image::make($imagen);

            // Obtener dimensiones originales de la imagen
            $width = $image->width();
            $height = $image->height();

            // Si la imagen es vertical
            if ($height > $width) {
                // Recortar la imagen a 500 píxeles de ancho y altura
                $image->fit(500, 500);
            } else {
                // Si la imagen es horizontal o cuadrada, recortarla a 500x500 píxeles manteniendo su centro
                $image->fit(500, 500);
            }

            // Guardar la imagen
            $image->save($ruta . $nombreimagen);

            $menu->fotografia = $nombreimagen;
        }

        $menu->save();

        return response()->json($menu);
    }

    public function update(Request $request)
    {

        if (!$request->ajax())
            return redirect('/');

        $menu = Menu::findOrFail($request->id);
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
        $menu = Menu::findOrFail($request->id);
        $menu->condicion = '0';
        $menu->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');
        $menu = Menu::findOrFail($request->id);
        $menu->condicion = '1';
        $menu->save();
    }

    //eliminar
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();
        return response()->json(['message' => '¡menú ha sido eliminado correctamente!']);
    }
}
