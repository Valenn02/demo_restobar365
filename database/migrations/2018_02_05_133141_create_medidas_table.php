<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medidas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descripcion_medida', 100);
            $table->string('codigoClasificador', 8);
            $table->boolean('estado')->default(1);
            $table->timestamps();
        });

        DB::table('medidas')->insert([
            ['descripcion_medida' => 'UNIDAD (BIENES)', 'codigoClasificador' => 57],
            ['descripcion_medida' => 'BOBINAS', 'codigoClasificador' => 1],
            ['descripcion_medida' => 'BALDE', 'codigoClasificador' => 2],
            ['descripcion_medida' => 'BARRILES', 'codigoClasificador' => 3],
            ['descripcion_medida' => 'BOLSA', 'codigoClasificador' => 4],
            ['descripcion_medida' => 'BOTELLAS', 'codigoClasificador' => 5],
            ['descripcion_medida' => 'CAJA', 'codigoClasificador' => 6],
            ['descripcion_medida' => 'CARTONES', 'codigoClasificador' => 7],
            ['descripcion_medida' => 'CENTIMETRO CUADRADO', 'codigoClasificador' => 8],
            ['descripcion_medida' => 'CENTIMETRO CUBICO', 'codigoClasificador' => 9],
            ['descripcion_medida' => 'CENTIMETRO LINEAL', 'codigoClasificador' => 10],
            ['descripcion_medida' => 'CIENTO DE UNIDADES', 'codigoClasificador' => 11],
            ['descripcion_medida' => 'CILINDRO', 'codigoClasificador' => 12],
            ['descripcion_medida' => 'CONOS', 'codigoClasificador' => 13],
            ['descripcion_medida' => 'DOCENA', 'codigoClasificador' => 14],
            ['descripcion_medida' => 'FARDO', 'codigoClasificador' => 15],
            ['descripcion_medida' => 'GALON INGLES', 'codigoClasificador' => 16],
            ['descripcion_medida' => 'GRAMO', 'codigoClasificador' => 17],
            ['descripcion_medida' => 'GRUESA', 'codigoClasificador' => 18],
            ['descripcion_medida' => 'HECTOLITRO', 'codigoClasificador' => 19],
            ['descripcion_medida' => 'HOJA', 'codigoClasificador' => 20],
            ['descripcion_medida' => 'JUEGO', 'codigoClasificador' => 21],
            ['descripcion_medida' => 'KILOGRAMO', 'codigoClasificador' => 22],
            ['descripcion_medida' => 'KILOMETRO', 'codigoClasificador' => 23],
            ['descripcion_medida' => 'KILOVATIO HORA', 'codigoClasificador' => 24],
            ['descripcion_medida' => 'KIT', 'codigoClasificador' => 25],
            ['descripcion_medida' => 'LATAS', 'codigoClasificador' => 26],
            ['descripcion_medida' => 'LIBRAS', 'codigoClasificador' => 27],
            ['descripcion_medida' => 'LITRO', 'codigoClasificador' => 28],
            ['descripcion_medida' => 'MEGAWATT HORA', 'codigoClasificador' => 29],
            ['descripcion_medida' => 'METRO', 'codigoClasificador' => 30],
            ['descripcion_medida' => 'METRO CUADRADO', 'codigoClasificador' => 31],
            ['descripcion_medida' => 'METRO CUBICO', 'codigoClasificador' => 32],
            ['descripcion_medida' => 'MILIGRAMOS', 'codigoClasificador' => 33],
            ['descripcion_medida' => 'MILILITRO', 'codigoClasificador' => 34],
            ['descripcion_medida' => 'MILIMETRO', 'codigoClasificador' => 35],
            ['descripcion_medida' => 'MILIMETRO CUADRADO', 'codigoClasificador' => 36],
            ['descripcion_medida' => 'MILIMETRO CUBICO', 'codigoClasificador' => 37],
            ['descripcion_medida' => 'MILLARES', 'codigoClasificador' => 38],
            ['descripcion_medida' => 'MILLON DE UNIDADES', 'codigoClasificador' => 39],
            ['descripcion_medida' => 'ONZAS', 'codigoClasificador' => 40],
            ['descripcion_medida' => 'PALETAS', 'codigoClasificador' => 41],
            ['descripcion_medida' => 'PAQUETE', 'codigoClasificador' => 42],
            ['descripcion_medida' => 'PAR', 'codigoClasificador' => 43],
            ['descripcion_medida' => 'PIES', 'codigoClasificador' => 44],
            ['descripcion_medida' => 'PIES CUADRADOS', 'codigoClasificador' => 45],
            ['descripcion_medida' => 'PIES CUBICOS', 'codigoClasificador' => 46],
            ['descripcion_medida' => 'PIEZAS', 'codigoClasificador' => 47],
            ['descripcion_medida' => 'PLACAS', 'codigoClasificador' => 48],
            ['descripcion_medida' => 'PLIEGO', 'codigoClasificador' => 49],
            ['descripcion_medida' => 'PULGADAS', 'codigoClasificador' => 50],
            ['descripcion_medida' => 'RESMA', 'codigoClasificador' => 51],
            ['descripcion_medida' => 'TAMBOR', 'codigoClasificador' => 52],
            ['descripcion_medida' => 'TONELADA CORTA', 'codigoClasificador' => 53],
            ['descripcion_medida' => 'TONELADA LARGA', 'codigoClasificador' => 54],
            ['descripcion_medida' => 'TONELADAS', 'codigoClasificador' => 55],
            ['descripcion_medida' => 'TUBOS', 'codigoClasificador' => 56],
            ['descripcion_medida' => 'UNIDAD (SERVICIOS)', 'codigoClasificador' => 58],
            ['descripcion_medida' => 'US GALON (3,7843 L)', 'codigoClasificador' => 59],
            ['descripcion_medida' => 'YARDA', 'codigoClasificador' => 60],
            ['descripcion_medida' => 'YARDA CUADRADA', 'codigoClasificador' => 61],
            ['descripcion_medida' => 'OTRO', 'codigoClasificador' => 62],
            ['descripcion_medida' => 'ONZA TROY', 'codigoClasificador' => 63],
            ['descripcion_medida' => 'LIBRA FINA', 'codigoClasificador' => 64],
            ['descripcion_medida' => 'DISPLAY', 'codigoClasificador' => 65],
            ['descripcion_medida' => 'BULTO', 'codigoClasificador' => 66],
            ['descripcion_medida' => 'DIAS', 'codigoClasificador' => 67],
            ['descripcion_medida' => 'MESES', 'codigoClasificador' => 68],
            ['descripcion_medida' => 'QUINTAL', 'codigoClasificador' => 69],
            ['descripcion_medida' => 'ROLLO', 'codigoClasificador' => 70],
            ['descripcion_medida' => 'HORAS', 'codigoClasificador' => 71],
            ['descripcion_medida' => 'AGUJA', 'codigoClasificador' => 72],
            ['descripcion_medida' => 'AMPOLLA', 'codigoClasificador' => 73],
            ['descripcion_medida' => 'BIDON', 'codigoClasificador' => 74],
            ['descripcion_medida' => 'BOLSA', 'codigoClasificador' => 75],
            ['descripcion_medida' => 'CAPSULA', 'codigoClasificador' => 76],
            ['descripcion_medida' => 'CARTUCHO', 'codigoClasificador' => 77],
            ['descripcion_medida' => 'COMPRIMIDO', 'codigoClasificador' => 78],
            ['descripcion_medida' => 'ESTUCHE', 'codigoClasificador' => 79],
            ['descripcion_medida' => 'FRASCO', 'codigoClasificador' => 80],
            ['descripcion_medida' => 'JERINGA', 'codigoClasificador' => 81],
            ['descripcion_medida' => 'MINI BOTELLA', 'codigoClasificador' => 82],
            ['descripcion_medida' => 'SACHET', 'codigoClasificador' => 83],
            ['descripcion_medida' => 'TABLETA', 'codigoClasificador' => 84],
            ['descripcion_medida' => 'TERMO', 'codigoClasificador' => 85],
            ['descripcion_medida' => 'TUBO', 'codigoClasificador' => 86],
            ['descripcion_medida' => 'BARRIL (EEUU) 60F', 'codigoClasificador' => 87],
            ['descripcion_medida' => 'BARRIL [42 GALONES(EEUU)]', 'codigoClasificador' => 88],
            ['descripcion_medida' => 'METRO CUBICO 68F VOL', 'codigoClasificador' => 89],
            ['descripcion_medida' => 'MIL PIES CUBICOS 14696 PSI', 'codigoClasificador' => 90],
            ['descripcion_medida' => 'MIL PIES CUBICOS 14696 PSI 68FAH', 'codigoClasificador' => 91],
            ['descripcion_medida' => 'MILLAR DE PIES CUBICOS (1000 PC)', 'codigoClasificador' => 92],
            ['descripcion_medida' => 'MILLONES DE PIES CUBICOS (1000000 PC)', 'codigoClasificador' => 93],
            ['descripcion_medida' => 'MILLONES DE BTU (1000000 BTU)', 'codigoClasificador' => 94],
            ['descripcion_medida' => 'UNIDAD TERMICA BRITANICA (TI)', 'codigoClasificador' => 95],
            ['descripcion_medida' => 'POMO', 'codigoClasificador' => 96],
            ['descripcion_medida' => 'VASO', 'codigoClasificador' => 97],
            ['descripcion_medida' => 'TETRAPACK', 'codigoClasificador' => 98],
            ['descripcion_medida' => 'CARTOLA', 'codigoClasificador' => 99],
            ['descripcion_medida' => 'JABA', 'codigoClasificador' => 100],
            ['descripcion_medida' => 'YARDA', 'codigoClasificador' => 101],
            ['descripcion_medida' => 'BANDEJA', 'codigoClasificador' => 102],
            ['descripcion_medida' => 'TURRIL', 'codigoClasificador' => 103],
            ['descripcion_medida' => 'BLISTER', 'codigoClasificador' => 104],
            ['descripcion_medida' => 'TIRA', 'codigoClasificador' => 105],
            ['descripcion_medida' => 'MEGAWATT', 'codigoClasificador' => 106],
            ['descripcion_medida' => 'KILOWATT', 'codigoClasificador' => 107],
            ['descripcion_medida' => 'AMORTIZACION', 'codigoClasificador' => 108],
            ['descripcion_medida' => 'OVULOS', 'codigoClasificador' => 109],
            ['descripcion_medida' => 'SUPOSITORIOS', 'codigoClasificador' => 110],
            ['descripcion_medida' => 'SOBRES', 'codigoClasificador' => 111],
            ['descripcion_medida' => 'VIAL', 'codigoClasificador' => 112],
            ['descripcion_medida' => 'HECTAREAS', 'codigoClasificador' => 113],
            ['descripcion_medida' => 'ARROBA', 'codigoClasificador' => 114],
            ['descripcion_medida' => 'AEROSOL', 'codigoClasificador' => 115],
            ['descripcion_medida' => 'BARRA', 'codigoClasificador' => 116],
            ['descripcion_medida' => 'CONJUNTO', 'codigoClasificador' => 117],
            ['descripcion_medida' => 'FANEGA', 'codigoClasificador' => 118],
            ['descripcion_medida' => 'PACK', 'codigoClasificador' => 119],
            ['descripcion_medida' => 'PIPETA', 'codigoClasificador' => 120],
            ['descripcion_medida' => 'POTE', 'codigoClasificador' => 121],
            ['descripcion_medida' => 'PASTILLA', 'codigoClasificador' => 122],
            ['descripcion_medida' => 'TONELADA METRICA', 'codigoClasificador' => 123],
            ['descripcion_medida' => 'EQUIPOS', 'codigoClasificador' => 124],
            ['descripcion_medida' => 'PIE TABLAR', 'codigoClasificador' => 125],
            ['descripcion_medida' => 'KILATES', 'codigoClasificador' => 126],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medidas');
    }
}
