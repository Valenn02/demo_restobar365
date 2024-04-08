<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfiguracionesTrabajo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('configuracion_trabajos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('gestion', 10)->unique();
            $table->string('codigoProductos', 100);
            $table->boolean('consultasAlmacenes')->default(1);
            $table->string('limiteDescuento', 100)->nullable();
            $table->decimal('maximoDescuento', 6)->nullable();
            $table->string('valuacionInventario', 100);
            $table->string('backupAutomatico', 20);
            $table->string('rutaBackup', 100)->nullable();
            $table->boolean('saldosNegativos')->default(0);
            $table->string('monedaTrabajo', 15);
            $table->string('separadorDecimales', 15);

            $table->boolean('mostrarCostos')->default(1);
            $table->boolean('mostrarProveedores')->default(1);
            $table->boolean('mostrarSaldosStock')->default(1);
            $table->boolean('actualizarIva')->default(1);
            $table->string('vendedorAsignado', 50);
            $table->boolean('permitirDevolucion')->default(0);
            $table->boolean('editarNroDoc')->default(0);
            $table->boolean('registroClienteObligatorio')->default(1);
            $table->boolean('buscarClientePorCodigo')->default(1);

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
