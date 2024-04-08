<?php

namespace App\Exports;

use App\Inventario;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductosVencidosExport implements FromQuery, WithHeadings, WithColumnWidths, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        return Inventario::join('articulos', 'inventarios.idarticulo', '=', 'articulos.id')
        ->join('almacens', 'inventarios.idalmacen', '=', 'almacens.id')
        ->join('proveedores', 'articulos.idproveedor', '=', 'proveedores.id')
        ->join('personas', 'proveedores.id', '=', 'personas.id')
        ->select(
            'articulos.codigo',
            'articulos.nombre as nombre_producto',
            'articulos.unidad_envase',

            'inventarios.saldo_stock',

            'almacens.ubicacion',
            'almacens.nombre_almacen',

            'inventarios.fecha_vencimiento',

            'personas.nombre as nombre_proveedor',

        )
        ->whereDate('inventarios.fecha_vencimiento', '<=', now())
        ->orderBy('inventarios.id', 'desc');
    }

    public function headings(): array
    {
        return [
            'Codigo',
            'Producto',
            'Unidad X Paq.',
            'Saldo Stock',
            'Ubicacion',
            'Almacen',
            'Fecha Vencimiento',
            'Proveedor',
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
            'F' => 20,
            'G' => 20,
            'H' => 15,
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
