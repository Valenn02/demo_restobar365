<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->foreign('id')->references('id')->on('personas')->onDelete('cascade');

            $table->string('usuario')->unique();
            $table->string('password');
            $table->boolean('condicion')->default(1);

            $table->integer('idrol')->unsigned();
            $table->foreign('idrol')->references('id')->on('roles');

            $table->integer('idsucursal')->unsigned();
            $table->foreign('idsucursal')->references('id')->on('sucursales');

            $table->integer('idpuntoventa')->unsigned()->nullable();
            $table->foreign('idpuntoventa')->references('id')->on('punto_ventas');


            $table->rememberToken();
            //$table->timestamps();
        });
        DB::table('users')->insert(array('id' => '1',
            'usuario' => 'admin',
            'password' => bcrypt('admin'),
            'idrol' => '1',
            'idsucursal' => '1',
            'idpuntoventa' => '1',
            'remember_token' => 'vEHmcelujGXofHcFYAFCumGPd25mQ6Uw1ma5VHlO1dWbx8tHQMndvnaQZnjI'));

        DB::table('users')->insert(array('id' => '3',
            'usuario' => 'almacenero',
            'password' => bcrypt('almacenero'),
            'idrol' => '3',
            'idsucursal' => '1',
            'idpuntoventa' => '1',
            'remember_token' => 'vEHmcelujGXofHcFYAFCumGPd25mQ6Uw1ma5VHlO1dWbx8tHQMndvnaQZnjI')
        );

        DB::table('users')->insert(array('id' => '2',
            'usuario' => 'vendedor',
            'password' => bcrypt('vendedor'),
            'idrol' => '2',
            'idsucursal' => '1',
            'idpuntoventa' => '1',
            'remember_token' => 'vEHmcelujGXofHcFYAFCumGPd25mQ6Uw1ma5VHlO1dWbx8tHQMndvnaQZnjI'));
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}