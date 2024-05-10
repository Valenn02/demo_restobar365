<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateLeyendas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leyendas', function (Blueprint $table){
            $table->increments('id');
            $table->integer('codigoActividad')->unsigned();
            $table->string('descripcionLeyenda', 250)->nullable();
            $table->timestamps();
        });

        DB::table('leyendas')->insert([
            ['codigoActividad' => 001220, 'descripcionLeyenda' => 'Ley N° 453: Puedes acceder a la reclamación cuando tus derechos han sido vulnerados.'],
            ['codigoActividad' => 001220, 'descripcionLeyenda' => 'Ley N° 453: El proveedor debe brindar atención sin discriminación, con respeto, calidez y cordialidad a los usuarios y consumidores.'],
            ['codigoActividad' => 461021, 'descripcionLeyenda' => 'Ley N° 453: Tienes derecho a un trato equitativo sin discriminación en la oferta de servicios.'],
            ['codigoActividad' => 620100, 'descripcionLeyenda' => 'Ley N° 453: Tienes derecho a un trato equitativo sin discriminación en la oferta de servicios.'],
            ['codigoActividad' => 749000, 'descripcionLeyenda' => 'Ley N° 453: Tienes derecho a un trato equitativo sin discriminación en la oferta de servicios.'],
            ['codigoActividad' => 001220, 'descripcionLeyenda' => 'Ley N° 453: El proveedor de productos debe habilitar medios e instrumentos para efectuar consultas y reclamaciones.'],
            ['codigoActividad' => 474110, 'descripcionLeyenda' => 'Ley N° 453: Está prohibido importar, distribuir o comercializar productos expirados o prontos a expirar.'],
            ['codigoActividad' => 001220, 'descripcionLeyenda' => 'Ley N° 453: Los contratos de adhesión deben redactarse en términos claros, comprensibles, legibles y deben informar todas las facilidades y limitaciones.'],
            ['codigoActividad' => 001220, 'descripcionLeyenda' => 'Ley N° 453: Si se te ha vulnerado algún derecho puedes exigir la reposición o restauración.'],
            ['codigoActividad' => 461021, 'descripcionLeyenda' => 'Ley N° 453: En caso de incumplimiento a lo ofertado o convenido, el proveedor debe reparar o sustituir el servicio.'],
            ['codigoActividad' => 620100, 'descripcionLeyenda' => 'Ley N° 453: En caso de incumplimiento a lo ofertado o convenido, el proveedor debe reparar o sustituir el servicio.'],
            ['codigoActividad' => 749000, 'descripcionLeyenda' => 'Ley N° 453: En caso de incumplimiento a lo ofertado o convenido, el proveedor debe reparar o sustituir el servicio.'],
            ['codigoActividad' => 461021, 'descripcionLeyenda' => 'Ley N° 453: El proveedor debe exhibir certificaciones de habilitación o documentos que acrediten las capacidades u ofertas de servicios especializados.'],
            ['codigoActividad' => 620100, 'descripcionLeyenda' => 'Ley N° 453: El proveedor debe exhibir certificaciones de habilitación o documentos que acrediten las capacidades u ofertas de servicios especializados'],
            ['codigoActividad' => 749000, 'descripcionLeyenda' => 'Ley N° 453: El proveedor debe exhibir certificaciones de habilitación o documentos que acrediten las capacidades u ofertas de servicios especializados.'],
            ['codigoActividad' => 474110, 'descripcionLeyenda' => 'Ley N° 453: Los alimentos declarados de primera necesidad deben ser suministrados de manera adecuada, oportuna, continua y a precio justo'],
            ['codigoActividad' => 001220, 'descripcionLeyenda' => 'Ley N° 453: Se debe promover el consumo solidario, justo, en armonía con la Madre Tierra y precautelando el hábitat, en el marco del Vivir Bien.'],
            ['codigoActividad' => 461021, 'descripcionLeyenda' => 'Ley N° 453: Tienes derecho a recibir información sobre las características y contenidos de los servicios que utilices.'],
            ['codigoActividad' => 620100, 'descripcionLeyenda' => 'Ley N° 453: Tienes derecho a recibir información sobre las características y contenidos de los servicios que utilices.'],
            ['codigoActividad' => 749000, 'descripcionLeyenda' => 'Ley N° 453: Tienes derecho a recibir información sobre las características y contenidos de los servicios que utilices.'],
            ['codigoActividad' => 001220, 'descripcionLeyenda' => 'Ley N° 453: Tienes derecho a recibir información sobre las características y contenidos de los servicios que utilices.'],
            ['codigoActividad' => 001220, 'descripcionLeyenda' => 'Ley N° 453: El proveedor deberá dar cumplimiento a las condiciones ofertadas.'],
            ['codigoActividad' => 474110, 'descripcionLeyenda' => 'Ley N° 453: Tienes derecho a recibir información sobre las características y contenidos de los productos que consumes'],
            ['codigoActividad' => 474110, 'descripcionLeyenda' => 'Ley N° 453: Tienes derecho a un trato equitativo sin discriminación en la oferta de productos.'],
            ['codigoActividad' => 474110, 'descripcionLeyenda' => 'Ley N° 453: Está prohibido importar, distribuir o comercializar productos prohibidos o retirados en el país de origen por atentar a la integridad física y a la salud.'],
            ['codigoActividad' => 461021, 'descripcionLeyenda' => 'Ley N° 453: El proveedor de servicios debe habilitar medios e instrumentos para efectuar consultas y reclamaciones.'],
            ['codigoActividad' => 620100, 'descripcionLeyenda' => 'Ley N° 453: El proveedor de servicios debe habilitar medios e instrumentos para efectuar consultas y reclamaciones.'],
            ['codigoActividad' => 749000, 'descripcionLeyenda' => 'Ley N° 453: El proveedor de servicios debe habilitar medios e instrumentos para efectuar consultas y reclamaciones.'],
            ['codigoActividad' => 461021, 'descripcionLeyenda' => 'Ley N° 453: El proveedor deberá suministrar el servicio en las modalidades y términos ofertados o convenidos.'],
            ['codigoActividad' => 620100, 'descripcionLeyenda' => 'Ley N° 453: El proveedor deberá suministrar el servicio en las modalidades y términos ofertados o convenidos.'],
            ['codigoActividad' => 749000, 'descripcionLeyenda' => 'Ley N° 453: El proveedor deberá suministrar el servicio en las modalidades y términos ofertados o convenidos.'],
            ['codigoActividad' => 001220, 'descripcionLeyenda' => 'Ley N° 453: Están prohibidas las prácticas comerciales abusivas, tienes derecho a denunciarlas.'],
            ['codigoActividad' => 474110, 'descripcionLeyenda' => 'Ley N° 453: El proveedor deberá entregar el producto en las modalidades y términos ofertados o convenidos.'],
            ['codigoActividad' => 474110, 'descripcionLeyenda' => 'Ley N° 453: En caso de incumplimiento a lo ofertado o convenido, el proveedor debe reparar o sustituir el producto.'],
            ['codigoActividad' => 461021, 'descripcionLeyenda' => 'Ley N° 453: La interrupción del servicio debe comunicarse con anterioridad a las Autoridades que correspondan y a los usuarios afectados.'],
            ['codigoActividad' => 620100, 'descripcionLeyenda' => 'Ley N° 453: La interrupción del servicio debe comunicarse con anterioridad a las Autoridades que correspondan y a los usuarios afectados.'],
            ['codigoActividad' => 749000, 'descripcionLeyenda' => 'Ley N° 453: La interrupción del servicio debe comunicarse con anterioridad a las Autoridades que correspondan y a los usuarios afectados.'],
            ['codigoActividad' => 461021, 'descripcionLeyenda' => 'Ley N° 453: Los servicios deben suministrarse en condiciones de inocuidad, calidad y seguridad.'],
            ['codigoActividad' => 474110, 'descripcionLeyenda' => 'Ley N° 453: Los servicios deben suministrarse en condiciones de inocuidad, calidad y seguridad.'],
            ['codigoActividad' => 620100, 'descripcionLeyenda' => 'Ley N° 453: Los servicios deben suministrarse en condiciones de inocuidad, calidad y seguridad.'],
            ['codigoActividad' => 749000, 'descripcionLeyenda' => 'Ley N° 453: Los servicios deben suministrarse en condiciones de inocuidad, calidad y seguridad.'],

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
