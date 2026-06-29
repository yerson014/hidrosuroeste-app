<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 12. Incidencia
        Schema::create('incidencia', function (Blueprint $table) {
            $table->id('incidencia_id');
            $table->unsignedInteger('mesa_id');
            $table->unsignedInteger('comunidad_id');
            $table->string('titulo', 255)->nullable();
            $table->string('tipo', 100)->nullable();
            $table->text('descripcion')->nullable();
            $table->string('prioridad', 50)->nullable();
            $table->date('fecha')->nullable();
            $table->string('estado', 50)->nullable();
            $table->unsignedInteger('usuario_id');
            $table->foreign('mesa_id')->references('mesa_tecnica_id')->on('mesa_tecnica');
            $table->foreign('comunidad_id')->references('comunidad_id')->on('comunidad');
            $table->foreign('usuario_id')->references('usuario_id')->on('usuario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidencia');
    }
};
