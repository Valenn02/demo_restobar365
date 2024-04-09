<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_pagos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_tipo_pago', 50)->nullable();
            $table->timestamps();
        });
        DB::table('tipo_pagos')->insert([
            [
                'nombre_tipo_pago' => 'Efectivo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_tipo_pago' => 'Tarjeta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_tipo_pago' => 'Transacion QR',
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
        Schema::dropIfExists('tipo_pagos');
    }
}
