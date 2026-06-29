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
        // 7. Mesa Técnica
        Schema::create('mesa_tecnica', function (Blueprint $table) {
            $table->id('mesa_tecnica_id');
            $table->string('nombre', 255)->nullable();
            $table->date('fecha_creacion')->nullable();
            $table->string('direccion', 255)->nullable();
            $table->integer('numero_integrantes')->nullable();
            $table->string('estado', 50)->nullable();
            $table->unsignedInteger('consejo_comunal_id')->nullable();
            $table->unsignedInteger('centro_asociado_id')->nullable();
            $table->foreign('consejo_comunal_id')->references('consejo_comunal_id')->on('consejo_comunal');
            $table->foreign('centro_asociado_id')->references('centro_asociado_id')->on('centro_asociado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesa_tecnica');
    }
};
