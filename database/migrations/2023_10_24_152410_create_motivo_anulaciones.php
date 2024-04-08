<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMotivoAnulaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motivo_anulaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('codigo')->unsigned();
            $table->string('descripcion', 250)->nullable();
            $table->timestamps();
        });

        DB::table('motivo_anulaciones')->insert([
            ['codigo' => 1, 'descripcion' => 'FACTURA MAL EMITIDA'],
            ['codigo' => 2, 'descripcion' => 'NOTA DE CREDITO-DEBITO MAL EMITIDA'],
            ['codigo' => 3, 'descripcion' => 'DATOS DE EMISION INCORRECTOS'],
            ['codigo' => 4, 'descripcion' => 'FACTURA O NOTA DE CREDITO-DEBITO DEVUELTA'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
