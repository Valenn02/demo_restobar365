<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferenciasArticulos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articulos', function (Blueprint $table) {
            //
            $table->integer('idmarca')->unsigned();
            $table->integer('idindustria')->unsigned();

            $table->foreign('idmarca')->references('id')->on('marcas');
            $table->foreign('idindustria')->references('id')->on('industrias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articulos', function (Blueprint $table) {
            //
            Schema::dropIfExists('articulos'); 
        });
    }
}
