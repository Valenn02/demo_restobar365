<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSucursalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sucursales', function (Blueprint $table) {
            /*$table->increments('id');
            $table->timestamps();*/
            $table->increments('id');
            $table->integer('idempresa')->unsigned();
            $table->string('nombre', 50)->nullable();
            //$table->integer('codigoSucursal', 100)->unsigned();
            $table->integer('codigoSucursal')->unsigned();
            $table->string('direccion', 100)->unique();
            $table->string('correo');
            $table->string('telefono');
            $table->string('departamento')->nullable();
            $table->boolean('condicion')->default(1);
            $table->timestamps();

            $table->foreign('idempresa')->references('id')->on('empresas');

        });
        DB::table('sucursales')->insert(array('id' => '1', 'idempresa' => '1', 'nombre' => 'Casa Matriz', 'codigoSucursal' => '0', 'direccion' => 'NA', 'telefono' => '00000000', 'correo' => 'empresa@gmail.com', 'departamento' => 'Cochabamba'));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sucursales');
    }
}