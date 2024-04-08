<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditoVenta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credito_ventas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idventa')->references('id')->on('ventas');
            $table->integer('idpersona')->references('id')->on('personas');
            $table->integer('numero_cuotas');
            $table->integer('tiempo_dias_cuota');
            $table->string('estado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credito_ventas');
        //
    }
}
