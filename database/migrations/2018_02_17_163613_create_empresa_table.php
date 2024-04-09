<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 50)->unique();
            $table->string('direccion', 150);
            $table->string('telefono', 8);
            $table->string('email', 50);
            $table->string('nit', 50);

            $table->string('licencia', 20);

            $table->timestamps();
        });

        DB::table('empresas')->insert(array('id' => '1', 'nombre' => 'Empresa', 'direccion' => 'NA', 'telefono' => '00000000', 'email' => 'empresa@gmail.com', 'nit' => '00000', 'licencia' => 'si'));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresa');
    }
}