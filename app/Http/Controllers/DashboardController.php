<?php
 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
 
use Illuminate\Http\Request;
 
class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
    
        $ingresos = DB::table('ingresos as i')
            ->select(DB::raw('MONTH(i.fecha_hora) as mes'),
                DB::raw('YEAR(i.fecha_hora) as anio'),
                DB::raw('SUM(i.total) as total'))
            ->whereBetween('i.fecha_hora', [$fechaInicio, $fechaFin])
            ->groupBy(DB::raw('MONTH(i.fecha_hora)'), DB::raw('YEAR(i.fecha_hora)'))
            ->get();
    
        $ventas = DB::table('ventas as v')
            ->select(DB::raw('MONTH(v.fecha_hora) as mes'),
                DB::raw('YEAR(v.fecha_hora) as anio'),
                DB::raw('SUM(v.total) as total'))
            ->whereBetween('v.fecha_hora', [$fechaInicio, $fechaFin])
            ->groupBy(DB::raw('MONTH(v.fecha_hora)'), DB::raw('YEAR(v.fecha_hora)'))
            ->get();
    
        return ['ingresos' => $ingresos, 'ventas' => $ventas];
    }
}