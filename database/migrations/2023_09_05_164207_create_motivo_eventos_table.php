<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMotivoEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motivo_eventos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('codigo')->unsigned();
            $table->string('descripcion', 250)->nullable();
            $table->timestamps();
        });

        DB::table('motivo_eventos')->insert([
            ['codigo' => 7, 'descripcion' => 'CORTE DE SUMINISTRO DE ENERGIA ELECTRICA'],
            ['codigo' => 5, 'descripcion' => 'VIRUS INFORMÁTICO O FALLA DE SOFTWARE'],
            ['codigo' => 6, 'descripcion' => 'CAMBIO DE INFRAESTRUCTURA DEL SISTEMA INFORMÁTICO DE FACTURACIÓN O FALLA DE HARDWARE'],
            ['codigo' => 1, 'descripcion' => 'CORTE DEL SERVICIO DE INTERNET'],
            ['codigo' => 2, 'descripcion' => 'INACCESIBILIDAD AL SERVICIO WEB DE LA ADMINISTRACIÓN TRIBUTARIA'],
            ['codigo' => 3, 'descripcion' => 'INGRESO A ZONAS SIN INTERNET POR DESPLIEGUE DE PUNTO DE VENTA EN VEHICULOS AUTOMOTORES'],
            ['codigo' => 4, 'descripcion' => 'VENTA EN LUGARES SIN INTERNET']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('motivo_eventos');
    }
}
