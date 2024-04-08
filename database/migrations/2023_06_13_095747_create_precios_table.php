<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('precios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_precio', 100);
            $table->float('porcentage',11,2);
            $table->boolean('condicion')->default(1);
            $table->timestamps();
        });
        // Inserta un valor en la tabla 'precios' después de crearla
        DB::table('precios')->insert([
            [
                'nombre_precio' => 'ESTUDIANTE',
                'porcentage' => 10.50,
                'condicion' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_precio' => 'SIN FACTURA',
                'porcentage' => 20.75,
                'condicion' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_precio' => 'PUBLICO',
                'porcentage' => 15.25,
                'condicion' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_precio' => 'FACTURA',
                'porcentage' => 30.00,
                'condicion' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('precios');
    }
}
