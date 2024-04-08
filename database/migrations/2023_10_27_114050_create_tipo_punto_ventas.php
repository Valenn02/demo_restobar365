<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoPuntoVentas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_punto_ventas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('codigo')->unsigned();
            $table->string('descripcion', 250)->nullable();
            $table->timestamps();
        });

        DB::table('tipo_punto_ventas')->insert([
            ['codigo' => 1, 'descripcion' => 'PUNTO VENTA COMISIONISTA'],
            ['codigo' => 2, 'descripcion' => 'PUNTO VENTA VENTANILLA DE COBRANZA'],
            ['codigo' => 3, 'descripcion' => 'PUNTO DE VENTA MOVILES'],
            ['codigo' => 4, 'descripcion' => 'PUNTO DE VENTA YPFB'],
            ['codigo' => 5, 'descripcion' => 'PUNTO DE VENTA CAJEROS'],
            ['codigo' => 6, 'descripcion' => 'PUNTO DE VENTA CONJUNTA'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_punto_ventas');
    }
}
