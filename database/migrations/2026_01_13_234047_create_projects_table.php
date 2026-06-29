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
        // 11. Proyecto
        Schema::create('proyecto', function (Blueprint $table) {
            $table->id('proyecto_id');
            $table->unsignedInteger('mesa_id');
            $table->date('fecha')->nullable();
            $table->string('titulo', 255)->nullable();
            $table->text('descripcion')->nullable();
            $table->string('ubicacion', 255)->nullable();
            $table->string('estado', 50)->nullable();
            $table->unsignedInteger('usuario_id');
            $table->foreign('mesa_id')->references('mesa_tecnica_id')->on('mesa_tecnica');
            $table->foreign('usuario_id')->references('usuario_id')->on('usuario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyecto');
    }
};
