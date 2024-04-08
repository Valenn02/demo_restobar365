<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UserExport implements FromQuery, WithHeadings, WithColumnWidths, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        return User::join('personas','users.id','=','personas.id')
                ->join('roles','users.idrol','=','roles.id')
                ->join('sucursales','users.idsucursal','=','sucursales.id')
                ->select('personas.id','personas.nombre','personas.tipo_documento','personas.num_documento','personas.direccion','personas.telefono','personas.email','users.usuario','roles.nombre as rol', 'sucursales.nombre as sucursal')
                ->orderBy('personas.id', 'desc');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Tipo Documento',
            'Nro Documento',
            'Direccion',
            'Telefono',
            'Email',
            'Usuario',
            'Rol',
            'Sucursal',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 25,
            'C' => 20,
            'D' => 18,
            'E' => 30,
            'F' => 10,
            'G' => 30,
            'H' => 20,
            'I' => 25,
            'J' => 30,
        ];  
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
