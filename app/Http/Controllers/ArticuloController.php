<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;
use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Inventario;
use App\Articulo;
use App\Precio;

class ArticuloController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $articulos = Articulo::join('categoria_producto', 'articulos.idcategoria_producto', '=', 'categoria_producto.id')
                ->join('proveedores', 'articulos.idproveedor', '=', 'proveedores.id')
                ->join('personas', 'proveedores.id', '=', 'personas.id')
                ->join('medidas', 'articulos.idmedida', '=', 'medidas.id')

                ->select(
                    'articulos.id',
                    'articulos.idcategoria_producto',
                    'articulos.idproveedor',
                    //aumente 7 julio
                    'articulos.idmedida',
                    'articulos.nombre',
                    'articulos.nombre_generico',

                    'categoria_producto.nombre as nombre_categoria',
                    
                    'medidas.descripcion_medida',
                    //aumente 5 julio
                    'articulos.precio_costo_unid',
                    'articulos.precio_costo_paq',
                    'articulos.precio_venta',
                    'articulos.stockmin',
                    'personas.nombre as nombre_proveedor',
                    'articulos.descripcion',
                    'articulos.condicion',
                    'articulos.fotografia',
                    'articulos.unidad_paquete'
                )
                ->orderBy('articulos.id', 'desc')->paginate(5);
        } else {
            $articulos = Articulo::join('proveedores', 'articulos.idproveedor', '=', 'proveedores.id')
                ->join('personas', 'proveedores.id', '=', 'personas.id')
                ->join('categoria_producto', 'articulos.idcategoria_producto', '=', 'categoria_producto.id')

                ->select(
                    'articulos.id',
                    'articulos.idcategoria_producto',
                    'articulos.nombre',
                    'categoria_producto.nombre as nombre_categoria',
                    'articulos.precio_costo_unid',
                    'articulos.precio_costo_paq',
                    'articulos.precio_venta',
                    'articulos.stockmin',
                    'personas.nombre as nombre_proveedor',
                    'articulos.descripcion',
                    'articulos.condicion',
                    'articulos.fotografia',
                    'articulos.unidad_paquete'
                )
                ->where('articulos.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderBy('articulos.id', 'desc')->paginate(5);
        }


        return [
            'pagination' => [
                'total' => $articulos->total(),
                'current_page' => $articulos->currentPage(),
                'per_page' => $articulos->perPage(),
                'last_page' => $articulos->lastPage(),
                'from' => $articulos->firstItem(),
                'to' => $articulos->lastItem(),
            ],
            'articulos' => $articulos
        ];
    }
    public function listarArticulo(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        Log::info('Data', [
            'idProveedorController' => $request->idProveedor,
        ]);


        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $idProveedor = $request->idProveedor;

        if ($buscar == '') {
            $articulos = Articulo::join('categorias', 'articulos.idcategoria', '=', 'categorias.id')
                ->join('proveedores', 'articulos.idproveedor', '=', 'proveedores.id')
                ->join('personas', 'proveedores.id', '=', 'personas.id')
                ->select('articulos.id', 'articulos.idcategoria', 'articulos.codigo', 'articulos.nombre', 'categorias.nombre as nombre_categoria', 'articulos.precio_costo_unid', 'articulos.stock', 'personas.nombre as nombre_proveedor', 'articulos.descripcion', 'articulos.condicion', 'articulos.unidad_envase', 'articulos.fotografia')
                ->where('proveedores.id', '=', $idProveedor)
                ->orderBy('articulos.id', 'desc')->paginate(10);
        } else {
            $articulos = Articulo::join('categorias', 'articulos.idcategoria', '=', 'categorias.id')
                ->join('proveedores', 'articulos.idproveedor', '=', 'proveedores.id')
                ->join('personas', 'proveedores.id', '=', 'personas.id')
                ->select('articulos.id', 'articulos.idcategoria', 'articulos.codigo', 'articulos.nombre', 'categorias.nombre as nombre_categoria', 'articulos.precio_costo_unid', 'articulos.stock', 'personas.nombre as nombre_proveedor', 'articulos.descripcion', 'articulos.condicion', 'articulos.unidad_envase', 'articulos.fotografia')
                ->where('articulos.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderBy('articulos.id', 'desc')->paginate(10);
        }


        return ['articulos' => $articulos];
    }

    public function listarArticuloVenta(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $criterio = $request->input('criterio', '0');

        if ($criterio == '0') {
            $articulos = Articulo::join('medidas', 'articulos.idmedida', '=', 'medidas.id')
                ->join('categorias', 'articulos.idcategoria', '=', 'categorias.id')
                ->select('articulos.id', 'articulos.idcategoria', 'articulos.codigo', 'articulos.nombre','articulos.fotografia', 'categorias.nombre as nombre_categoria', 'articulos.precio_venta', 'articulos.stock', 'articulos.descripcion', 'articulos.condicion', 'medidas.descripcion_medida as medida')
                ->where('articulos.stock', '>', '0')
                ->orderBy('articulos.id', 'asc')->paginate(10);
        } else {
            $articulos = Articulo::join('categorias', 'articulos.idcategoria', '=', 'categorias.id')
                ->select('articulos.id', 'articulos.idcategoria', 'articulos.codigo', 'articulos.nombre', 'articulos.fotografia','categorias.nombre as nombre_categoria', 'articulos.precio_venta', 'articulos.stock', 'articulos.descripcion', 'articulos.condicion')
                ->where('articulos.idcategoria', '=', $criterio)
                ->where('articulos.stock', '>', '0')
                ->orderBy('articulos.id', 'asc')->paginate(10);
        }
        return [
            'pagination' => [
                'total' => $articulos->total(),
                'current_page' => $articulos->currentPage(),
                'per_page' => $articulos->perPage(),
                'last_page' => $articulos->lastPage(),
                'from' => $articulos->firstItem(),
                'to' => $articulos->lastItem(),
            ],
            'articulos' => $articulos
        ];
    }
    public function listarPdf()
    {
        return Excel::download(new ProductExport, 'articulos.xlsx');
    }
    public function buscarArticulo(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $filtro = $request->filtro;
        $articulos = Articulo::where('codigo', '=', $filtro)
            ->select('id', 'codigo', 'nombre', 'precio_costo_unid', 'fotografia', 'unidad_envase', 'descripcion')->orderBy('nombre', 'asc')->take(1)->get();

        return ['articulos' => $articulos];
    }
    public function buscarArticuloVenta(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $filtro = $request->filtro;

        $articulos = Articulo::join('medidas', 'articulos.idmedida', '=', 'medidas.id')
            ->join('categorias', 'articulos.idcategoria', '=', 'categorias.id')
            ->join('inventarios', 'inventarios.idarticulo', '=', 'articulos.id')
            ->select('articulos.id', 'articulos.nombre','articulos.stock','articulos.precio_costo_unid', 'articulos.precio_costo_paq', 'medidas.descripcion_medida as medida','articulos.precio_venta','categorias.codigoProductoSin', 'articulos.codigo',
                    'articulos.precio_uno',
                    'articulos.precio_dos',
                    'articulos.precio_tres',
                    'articulos.precio_cuatro',
                    'articulos.fotografia',
                    'articulos.condicion',
                    'categorias.nombre as nombre_categoria',
                    'unidad_envase',
                    'inventarios.fecha_vencimiento',
                    DB::raw('(SELECT SUM(inventarios.saldo_stock) FROM inventarios WHERE inventarios.idarticulo = articulos.id AND inventarios.fecha_vencimiento > NOW()) as saldo_stock')

            )
            ->where('articulos.codigo', '=', $filtro)
            // ->where('inventarios.saldo_stock', '>', 0)
            ->orderBy('articulos.nombre', 'asc')->take(1)->get();

        Log::info('ARTICULO:', [
            'DATA' => $articulos,
        ]);

        return ['articulos' => $articulos];
    }
    public function store(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');
        $articulo = new Articulo();
        $articulo->idcategoria_producto = $request->idcategoria;
        $articulo->idmedida = $request->idmedida; //new
        $articulo->nombre = $request->nombre;
        $articulo->nombre_generico = $articulo->nombre; //aumete 12julio

        $articulo->unidad_paquete = $request->unidad_paquete;
        $articulo->precio_venta = $request->precio_venta;
        //$articulo->costo_compra = '0.00'; //new

        $articulo->stockmin = $request->stock;
        $articulo->idproveedor = $request->idproveedor;
        $articulo->precio_costo_unid = $request->precio_costo_unid;
        $articulo->precio_costo_paq = $request->precio_costo_paq;
        $articulo->descripcion = $request->descripcion;
        //$articulo->fecha_vencimiento = $request->fecha_vencimiento;
        $articulo->condicion = '1';
        if ($request->hasFile('fotografia')) {
            if ($request->hasFile('fotografia')) {
                $imagen = $request->file("fotografia");
                $nombreimagen = Str::slug($request->nombre) . "." . $imagen->guessExtension();
                $ruta = public_path("img/articulo/");

                // Crear el directorio si no existe
                if (!File::isDirectory($ruta)) {
                    File::makeDirectory($ruta, 0755, true);
                }

                // Copiar la imagen al directorio
                copy($imagen->getRealPath(), $ruta . $nombreimagen);

                $articulo->fotografia = $nombreimagen;
            }
        }
        Log::info('DATOS REGISTRO ARTICULO:', [
            'idcategoria' => $request->idcategoria,
            'idmarca' => $request->idmarca,
            'idindustria' => $request->idindustria,
            'idgrupo' => $request->idgrupo,
            'idproveedor' => $request->idproveedor,
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'nombre_generico' => $request->nombre_generico,
            'precio_venta' => $request->precio_venta,
            'stock' => $request->stock,
            'descripcion' => $request->descripcion,
            'precio_uno' => $request->precio_uno,
            'precio_dos' => $request->precio_dos,
            'precio_tres' => $request->precio_tres,
            'precio_cuatro' => $request->precio_cuatro,
            'fotografia' => $nombreimagen,

        ]);
        $articulo->save();

    }
    public function update(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        try {
            DB::beginTransaction();

            $articulo = Articulo::findOrFail($request->id);
            $articulo->idcategoria_producto = $request->idcategoria;
            $articulo->nombre = $request->nombre;

            $articulo->nombre_generico = $request->nombre; //aumente esto 5 julio

            $articulo->precio_venta = $request->precio_venta;
            $articulo->stockmin = $request->stock;
            $articulo->descripcion = $request->descripcion;
            //$articulo->fecha_vencimiento = $request->fecha_vencimiento;
            $articulo->idproveedor = $request->idproveedor;
            $articulo->idmedida = $request->idmedida;
            //$articulo->condicion = '1';
            $articulo->unidad_paquete = $request->unidad_paquete;
            $articulo->precio_costo_unid = $request->precio_costo_unid;
            $articulo->precio_costo_paq = $request->precio_costo_paq;
            $nombreimagen = " ";
            if ($request->hasFile('fotografia')) {
                // Eliminar imagen anterior si existe
                if ($articulo->fotografia != '' && Storage::exists('public/img/articulo/' . $articulo->fotografia)) {
                    Storage::delete('public/img/articulo/' . $articulo->fotografia);
                }

                $imagen = $request->file("fotografia");
                $nombreimagen = Str::slug($request->nombre) . "." . $imagen->guessExtension();
                $imagen->storeAs('public/img/articulo', $nombreimagen);

                $ruta = public_path("img/articulo/");

                // Copiar la imagen al directorio
                copy($imagen->getRealPath(), $ruta . $nombreimagen);


                $articulo->fotografia = $nombreimagen;
            }
            Log::info('DATOS ACTUALIZADOS DE ARTICULO:', [
                'idcategoria' => $request->idcategoria,
                'idmarca' => $request->idmarca,
                'idindustria' => $request->idindustria,
                'idgrupo' => $request->idgrupo,
                'idproveedor' => $request->idproveedor,
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'nombre_generico' => $request->nombre_generico,
                //'unidad_envase'=>$request->unidad_envase,
                'precio_venta' => $request->precio_venta,
                'stock' => $request->stock,
                'descripcion' => $request->descripcion,
                'fotografia' => $nombreimagen,
                'idmedida' => $request->idmedida,
                'precio_uno' => $request->precio_uno,
                'precio_dos' => $request->precio_dos,
                'precio_tres' => $request->precio_tres,
                'precio_cuatro' => $request->precio_cuatro,

            ]);

            $articulo->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');
        $articulo = Articulo::findOrFail($request->id);
        $articulo->condicion = '0';
        $articulo->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');
        $articulo = Articulo::findOrFail($request->id);
        $articulo->condicion = '1';
        $articulo->save();
    }
    //--------LISTADO PARA PEDIDO PROVEEDOR---------------
    public function listPedProve(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        Log::info('Data', [
            'idProveedor' => $request->idProveedor,
            'buscar' => $request->buscar,
            'criterio' => $request->criterio,
        ]);

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $idProveedor = $request->idProveedor;

        $articulos = Articulo::join('categorias', 'articulos.idcategoria', '=', 'categorias.id')
            ->join('proveedores', 'articulos.idproveedor', '=', 'proveedores.id')
            ->join('personas', 'proveedores.id', '=', 'personas.id')
            ->select(
                'articulos.id',
                //'articulos.idcategoria', 
                'articulos.codigo',
                'articulos.nombre as articulo',
                //'categorias.nombre as nombre_categoria', 
                'articulos.precio_costo_unid as precio',
                'articulos.precio_costo_paq',
                //'articulos.stock', 
                'personas.nombre as nombre_proveedor',
                'articulos.fotografia',
                'articulos.descripcion',
                //'articulos.fecha_vencimiento', 
                //'articulos.condicion', 
                'articulos.unidad_envase as unidad_x_paquete'
            )
            ->where('proveedores.id', '=', $idProveedor);
        //->orderBy('articulos.id', 'desc')->paginate(10);
        if (!empty($buscar)) {
            $articulos = $articulos->where(function ($query) use ($criterio, $buscar) {
                $query->where('articulos.' . $criterio, 'like', '%' . $buscar . '%');
            });
        }
        $articulos = $articulos->orderBy('articulos.id', 'desc')->paginate(4);
        return [
            'pagination' => [
                'total' => $articulos->total(),
                'current_page' => $articulos->currentPage(),
                'per_page' => $articulos->perPage(),
                'last_page' => $articulos->lastPage(),
                'from' => $articulos->firstItem(),
                'to' => $articulos->lastItem(),
            ],
            'articulos' => $articulos
        ];
    }
}