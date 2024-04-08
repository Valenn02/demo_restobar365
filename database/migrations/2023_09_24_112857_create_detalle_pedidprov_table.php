<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetallePedidprovTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_pedidprov', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idpedidoprov')->unsigned();
            $table->integer('idarticulo')->unsigned();
            //$table->foreign('idtraspaso')->references('id')->on('traspasos')->onDelete('cascade');
            //$table->integer('idinventario')->unsigned();
            $table->foreign('idpedidoprov')->references('id')->on('pedido_proveedors');
            $table->foreign('idarticulo')->references('id')->on('articulos');
            $table->integer('cantidad_pedidoprov');
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_pedidprov');
    }
}
