<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuotasCredito extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuotas_credito', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idcredito')->references('id')->on('credito_ventas');
            $table->dateTime('fecha_pago');
            $table->dateTime('fecha_cancelado')->nullable();
            $table->decimal('precio_cuota');
            $table->decimal('total_cancelado');
            $table->decimal('saldo'); 
            $table->string('estado');
        });
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cuotas_credito');
        //
    }
}
