<?php

namespace App\Exports;

use App\Inventario;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;

class ProductosPorVencerseExport implements FromQuery, WithHeadings, WithColumnWidths, WithStyles
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
            'articulos.nombre', 
            'almacens.ubicacion',
            'articulos.unidad_envase',
            'inventarios.saldo_stock',
            'almacens.nombre_almacen',
            DB::raw('DATEDIFF(inventarios.fecha_vencimiento, NOW()) AS dias_restantes'), 
            'inventarios.fecha_vencimiento',
            'personas.nombre as nombre_proveedor',
        )
        ->whereRaw('DATEDIFF(inventarios.fecha_vencimiento, NOW()) < 30')
        ->whereRaw('DATEDIFF(inventarios.fecha_vencimiento, NOW()) > 1')
        ->orderBy('inventarios.id', 'desc');
    }

    public function headings(): array
    {
        return [
            'Codigo',
            'Producto',
            'Ubicacion',
            'Unidad X Paq.',
            'Saldo Stock',
            'Almacen',
            'Dias vencimiento',
            'Fecha vencimiento',
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
            'F' => 15,
            'G' => 20,
            'H' => 20,
            'I' => 15,
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
