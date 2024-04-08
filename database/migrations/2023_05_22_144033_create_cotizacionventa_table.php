<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCotizacionventaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cotizacion_venta', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('idcliente')->unsigned();
            $table->foreign('idcliente')->references('id')->on('personas');

            $table->integer('idusuario')->unsigned();
            $table->foreign('idusuario')->references('id')->on('users');

            $table->dateTime('fecha_hora')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->decimal('impuesto', 4, 2);

            $table->decimal('total', 11, 2);

            $table->string('estado', 20)->nullable();

            $table->dateTime('validez')->nullable();
            $table->string('plazo_entrega', 20)->nullable();
            $table->string('tiempo_entrega', 50);
            $table->string('lugar_entrega', 20)->nullable();
            $table->string('forma_pago', 20)->nullable();
            $table->string('nota', 50)->nullable();

            $table->tinyInteger('condicion')->default(1);

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
        Schema::dropIfExists('cotizacionventa');
    }
}