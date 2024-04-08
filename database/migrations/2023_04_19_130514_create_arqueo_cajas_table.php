<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArqueoCajasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arqueo_cajas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idcaja')->unsigned();
            $table->integer('idusuario')->unsigned();
            $table->integer('billete200');
            $table->integer('billete100');
            $table->integer('billete50');
            $table->integer('billete20');
            $table->integer('billete10');
            $table->integer('moneda5');
            $table->integer('moneda2');
            $table->integer('moneda1');
            $table->integer('moneda050');
            $table->integer('moneda020');
            $table->integer('moneda010');
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
        Schema::dropIfExists('arqueo_cajas');
    }
}
