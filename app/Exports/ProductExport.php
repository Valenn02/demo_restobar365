<?php

namespace App\Exports;

use App\Articulo;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductExport implements FromQuery, WithHeadings, WithColumnWidths, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        return Articulo::join('categorias', 'articulos.idcategoria', '=', 'categorias.id')
        ->select(
            'articulos.id',
            'articulos.nombre',
            'categorias.nombre as nombre_categoria',
            'articulos.precio_venta',
            'articulos.stock',
            'articulos.descripcion',
            // 'articulos.fecha_vencimiento',
            \DB::raw('IF(articulos.condicion = 1, "activo", "desactivado") as estado')
        )
        ->orderBy('articulos.nombre', 'desc');
    }

    public function headings(): array
    {
        return [
            'Codigo',
            'Nombre',
            'Categoria',
            'Precio Venta',
            'Stock',
            'Descripcion',
            // 'Fecha Vencimiento',
            'Condicion',
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 25,
            'C' => 20,
            'D' => 15,
            'E' => 15,
            'F' => 30,
            'G' => 20,
            // 'H' => 15,
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
