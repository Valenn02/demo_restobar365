<?php

namespace App\Exports;

use App\Persona;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class ClientExport implements FromCollection, WithHeadings, WithColumnWidths, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Persona::select('id','nombre','tipo_documento','num_documento','direccion','telefono','email')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Tipo Documento',
            'Numero Documento',
            'Direccion',
            'Telefono',
            'Email',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 30,
            'C' => 20,
            'D' => 25,
            'E' => 30,
            'F' => 20,
            'G' => 30,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
