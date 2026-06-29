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
        // 10. Mesa Historial
        Schema::create('mesa_historial', function (Blueprint $table) {
            $table->id('mesa_historial_id');
            $table->unsignedInteger('mesa_id');
            $table->text('descripcion')->nullable();
            $table->timestamp('fecha')->nullable();
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
        Schema::dropIfExists('mesa_historial');
    }
};
