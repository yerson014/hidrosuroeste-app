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
        // 14. Bitácora
        Schema::create('bitacora', function (Blueprint $table) {
            $table->id('bitacora_id');
            $table->unsignedInteger('usuario_id');
            $table->string('accion', 50)->nullable();
            $table->string('entidad', 50)->nullable();
            $table->text('descripcion')->nullable();
            $table->timestamp('fecha')->nullable();
            $table->string('ip', 50)->nullable();
            $table->foreign('usuario_id')->references('usuario_id')->on('usuario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacora');
    }
};
