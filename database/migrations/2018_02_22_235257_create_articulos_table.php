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
            $table->integer('codigo')->unique(); //codigo para factura

            $table->integer('idcategoria_producto')->unsigned(); //Linea
            $table->integer('idproveedor')->unsigned(); //aumente 5 juio
            $table->integer('idmedida')->unsigned(); //new

            $table->string('nombre', 100); //Nombre comercial
            $table->string('nombre_generico', 100); //aumente 5_julio
            $table->integer('unidad_paquete')->nullable(); //aumente

            $table->decimal('precio_costo_unid', 11, 2); //aumente
            $table->decimal('precio_costo_paq', 11, 2);
            $table->decimal('precio_venta', 11, 2)->nullable(); //precio presio2

            $table->integer('stockmin')->nullable(); //stock minimo
            $table->string('descripcion', 256)->nullable(); //stock maximo
            $table->boolean('condicion')->default(1); // Controlado
            $table->timestamps();

            $table->foreign('idcategoria_producto')->references('id')->on('categoria_producto');
            $table->foreign('idproveedor')->references('id')->on('proveedores');
            $table->foreign('idmedida')->references('id')->on('medidas');
        });

        DB::unprepared('
            CREATE TRIGGER before_insert_articulo
            BEFORE INSERT ON articulos FOR EACH ROW
            BEGIN
                SET NEW.codigo = (SELECT COALESCE(MAX(codigo), 19999) + 1 FROM articulos);
            END
        ');
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