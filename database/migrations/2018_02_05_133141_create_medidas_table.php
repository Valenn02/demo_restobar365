<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medidas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descripcion_medida', 100);
            $table->string('descripcion_corta', 8);
            $table->boolean('estado')->default(1);
            $table->timestamps();
        });

        DB::table('medidas')->insert(array('id'=>'1','descripcion_medida'=>'Unidad', 'descripcion_corta'=>'U.'));
        DB::table('medidas')->insert(array('id'=>'2','descripcion_medida'=>'Litro', 'descripcion_corta'=>'Lt.'));
        DB::table('medidas')->insert(array('id'=>'3','descripcion_medida'=>'Centimetros', 'descripcion_corta'=>'Cm.'));
        DB::table('medidas')->insert(array('id'=>'4','descripcion_medida'=>'Caja', 'descripcion_corta'=>'Cj.'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medidas');
    }
}
