<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idcategoria_menu')->unsigned(); //Linea
            $table->foreign('idcategoria_menu')->references('id')->on('categoria_menu');

            $table->integer('codigo')->unique(); //codigo para factura
            $table->string('nombre', 100)->unique(); //Nombre comercial
            $table->decimal('precio_venta', 11, 2)->nullable(); //precio presio2
            $table->string('descripcion', 256)->nullable(); //stock maximo
            $table->boolean('condicion');

            $table->integer('idsucursal')->unsigned();
            $table->foreign('idsucursal')->references('id')->on('sucursales');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu');
    }
}
