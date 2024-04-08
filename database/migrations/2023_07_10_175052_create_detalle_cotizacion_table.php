<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleCotizacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_cotizacion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idcotizacion')->unsigned();
            $table->foreign('idcotizacion')->references('id')->on('cotizacion_venta')->onDelete('cascade');
            $table->integer('idarticulo')->unsigned();
            $table->foreign('idarticulo')->references('id')->on('articulos');
            $table->integer('cantidad');
            $table->decimal('precio', 11, 2);
            $table->decimal('descuento', 11, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}