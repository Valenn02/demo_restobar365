<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleTraspasosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_traspasos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idtraspaso')->unsigned();
            $table->foreign('idtraspaso')->references('id')->on('traspasos')->onDelete('cascade');
            $table->integer('idinventario')->unsigned();
            $table->foreign('idinventario')->references('id')->on('inventarios');
            $table->integer('cantidad_traspaso');
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
        Schema::dropIfExists('detalle_traspasos');
    }
}
