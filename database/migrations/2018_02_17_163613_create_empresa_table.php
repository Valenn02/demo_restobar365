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
            $table->boolean('monedaPrincipal')->default(1);
            $table->decimal('valorMaximoDescuento', 6);
            $table->decimal('tipoCambio1', 10)->nullable();
            $table->decimal('tipoCambio2', 10)->nullable();
            $table->decimal('tipoCambio3', 10)->nullable();
            $table->string('licencia', 20);

            $table->timestamps();
        });

        DB::table('empresas')->insert(array('id' => '1', 'nombre' => 'Empresa', 'direccion' => 'NA', 'telefono' => '00000000', 'email' => 'empresa@gmail.com', 'valorMaximoDescuento' => '0', 'licencia' => 'si'));

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