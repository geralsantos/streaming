<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->bigInteger('perfil_id')->unsigned();
            $table->foreign('perfil_id')->references('id')->on('perfil')->onDelete('cascade');
            $table->string('primer_nombre', 30)->nullable();
            $table->string('segundo_nombre', 30)->nullable();
            $table->string('primer_apellido', 30)->nullable();
            $table->string('segundo_apellido', 30)->nullable();
            $table->string('correo', 100)->nullable();
            $table->string('numero_documento', 20)->nullable();
            $table->string('usuario', 20)->unique();
            $table->string('password', 100);
            $table->unsignedInteger('estado')->default(1);
            $table->unsignedInteger('usuario_creacion');
            $table->unsignedInteger('usuario_edicion');
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_modificacion')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario');
    }
}
