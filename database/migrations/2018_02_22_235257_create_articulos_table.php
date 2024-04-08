<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idcategoria')->unsigned(); //Linea
            $table->integer('idgrupo')->unsigned(); //aumente 14 junio
            $table->integer('idproveedor')->unsigned(); //aumente 5 juio

            $table->integer('idmedida')->unsigned(); //new

            $table->string('codigo', 50)->nullable();
            $table->string('nombre', 100)->unique(); //Nombre comercial
            $table->string('nombre_generico', 100); //aumente 5_julio
            $table->integer('unidad_envase'); //aumente
            $table->decimal('precio_list_unid', 11, 2)->nullable(); //aumente
            $table->decimal('precio_costo_unid', 11, 2); //aumente
            $table->decimal('precio_costo_paq', 11, 2); //aumente
            $table->decimal('precio_venta', 11, 2); //precio presio2

            $table->decimal('precio_uno', 11, 2)->nullable();//AUMENTE 19/9/2023
            $table->decimal('precio_dos', 11, 2)->nullable();//AUMENTE 19/9/2023
            $table->decimal('precio_tres', 11, 2)->nullable();//AUMENTE 19/9/2023
            $table->decimal('precio_cuatro', 11, 2)->nullable();//AUMENTE 19/9/2023

            $table->integer('stock'); //stock minimo
            $table->string('descripcion', 256)->nullable(); //stock maximo
            $table->boolean('condicion')->default(1); // Controlado
            $table->timestamps();

            $table->foreign('idcategoria')->references('id')->on('categorias');
            $table->foreign('idgrupo')->references('id')->on('grupos');
            $table->foreign('idproveedor')->references('id')->on('proveedores');

            //new
            $table->decimal('costo_compra', 10, 2);
            $table->foreign('idmedida')->references('id')->on('medidas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articulos');
    }
}