<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idcliente')->unsigned(); //new

            $table->integer('numeroFactura');
            $table->string('cuf', 255);
            $table->dateTime('fechaEmision');
            $table->integer('codigoMetodoPago');
            $table->decimal('montoTotal', 10, 2);
            $table->decimal('montoTotalSujetoIva', 10, 2);
            $table->decimal('descuentoAdicional', 10, 2);
            $table->text('productos');
            $table->boolean('estado')->default(1);
            $table->foreign('idcliente')->references('id')->on('personas');
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
        Schema::dropIfExists('facturas');
    }
}
