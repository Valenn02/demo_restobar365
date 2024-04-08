<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFotoarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('fotoar', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });*/
        Schema::table('articulos', function (Blueprint $table) {
            //
            $table->string('fotografia')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('fotoar');
        Schema::table('articulos', function (Blueprint $table) {
            //
            $table->dropColumn('imagen');
        });
    }
}
