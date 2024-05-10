<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventosSignificativos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos_significativos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idmotivoevento')->unsigned();

            $table->string('descripcion', 250);
            $table->string('cufdEvento', 80);
            $table->string('nit', 50);
            $table->integer('codigoMotivoEvento');
            $table->string('cufd', 80)->nullable();
            $table->text('inicioEvento');
            $table->text('finEvento')->nullable();
            $table->boolean('estado')->default(1);
            $table->foreign('idmotivoevento')->references('id')->on('motivo_eventos');
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
        //
    }
}
