<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePuntoVentas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('punto_ventas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idtipopuntoventa')->unsigned();
            $table->integer('idsucursal')->unsigned();
            $table->string('nombre', 80);
            $table->string('descripcion', 250);
            $table->string('nit', 50);
            $table->boolean('estado')->default(1);

            $table->foreign('idtipopuntoventa')->references('id')->on('tipo_punto_ventas');
            $table->foreign('idsucursal')->references('id')->on('sucursales');
            $table->timestamps();
        });

        DB::table('punto_ventas')->insert(array('idtipopuntoventa' => '1', 'idsucursal' => '1', 
            'nombre' => 'Punto_A', 'descripcion' => 'prueba', 'nit' => '00458', 'estado' => '1'));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('punto_ventas');
    }
}
