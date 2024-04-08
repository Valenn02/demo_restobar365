<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidoProveedorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_proveedors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idusuario')->unsigned();
            $table->integer('idproveedor')->unsigned();
            $table->integer('idalmacen')->unsigned();
            $table->dateTime('fecha_pedido');
            $table->dateTime('fecha_entrega');
            $table->string('forma_pago', 80)->nullable();
            $table->string('observacion', 80)->nullable();
            $table->decimal('total', 11, 2);
            $table->string('estado', 18)->default('En espera');
            $table->foreign('idusuario')->references('id')->on('users');
            $table->foreign('idproveedor')->references('id')->on('proveedores');
            $table->foreign('idalmacen')->references('id')->on('almacens');
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
        Schema::dropIfExists('pedido_proveedors');
    }
}