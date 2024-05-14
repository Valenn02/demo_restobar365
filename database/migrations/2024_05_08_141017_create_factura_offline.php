<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturaOffline extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factura_fuera_lineas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idcliente')->unsigned()->nullable();
            $table->integer('idventa')->unsigned(); 

            $table->integer('numeroFactura');
            $table->string('cuf', 255);
            $table->string('cufd', 255);
            $table->string('codigoControl', 255);
            $table->string('correo', 255);
            $table->dateTime('fechaEmision');
            $table->integer('codigoMetodoPago');
            $table->decimal('montoTotal', 10, 2);
            $table->decimal('montoTotalSujetoIva', 10, 2);
            $table->decimal('descuentoAdicional', 10, 2);
            $table->text('productos');
            $table->boolean('estado')->default(1);
            $table->foreign('idcliente')->references('id')->on('personas');
            $table->foreign('idventa')->references('id')->on('ventas');
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
        Schema::dropIfExists('facturas_offline');
    }
}
