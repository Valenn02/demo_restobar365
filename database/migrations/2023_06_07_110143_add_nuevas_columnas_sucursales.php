<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNuevasColumnasSucursales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sucursales', function (Blueprint $table) {
            //
            $table->string('responsable', 50)->nullable();
            $table->string('sigla', 20);
            $table->string('nro_auth', 20)->nullable();
            $table->enum('facturacion', ['W','N'])->default('W');
            $table->enum('fac3ros', ['S','N'])->default('N');
            $table->string('nit3ros', 20)->nullable();
            $table->string('empresa3ros', 20)->nullable();
            $table->enum('casamatriz', ['S','N'])->default('N');
            $table->text('leyenda')->nullable();
            $table->string('cuenta', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sucursales', function (Blueprint $table) {
            //
            $table->dropColumn('responsable');
            $table->dropColumn('sigla');
            $table->dropColumn('nro_auth');
            $table->dropColumn('facturacion');
            $table->dropColumn('fac3ros');
            $table->dropColumn('nit3ros');
            $table->dropColumn('empresa3ros');
            $table->dropColumn('casamatriz');
            $table->dropColumn('leyenda');
            $table->dropColumn('cuenta');
        });
    }
}
