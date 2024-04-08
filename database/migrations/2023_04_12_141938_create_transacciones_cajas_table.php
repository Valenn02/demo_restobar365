<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaccionesCajasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transacciones_cajas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idcaja')->unsigned();
            $table->integer('idusuario')->unsigned();
            $table->dateTime('fecha');
            $table->string('transaccion', 50);
            $table->decimal('importe', 11, 2);
            $table->timestamps();

            $table->foreign('idcaja')->references('id')->on('cajas');
            $table->foreign('idusuario')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transacciones_cajas');
    }
}
