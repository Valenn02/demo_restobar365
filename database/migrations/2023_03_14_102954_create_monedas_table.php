<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonedasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monedas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idempresa')->unsigned();
            $table->string('nombre', 50)->unique();
            $table->string('pais', 30);
            $table->string('simbolo', 5);
            $table->decimal('compra', 10, 2);
            $table->decimal('venta', 10, 2);
            $table->boolean('activo')->default(1);
            $table->timestamps();

            $table->foreign('idempresa')->references('id')->on('empresas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monedas');
    }
}
