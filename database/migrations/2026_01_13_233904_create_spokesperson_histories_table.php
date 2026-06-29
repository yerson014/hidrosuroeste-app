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
        // 9. Vocero Historial
        Schema::create('vocero_historial', function (Blueprint $table) {
            $table->id('vocero_historial_id');
            $table->unsignedInteger('vocero_id');
            $table->unsignedInteger('mesa_id');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->text('motivo_salida')->nullable();
            $table->unsignedInteger('usuario_id');
            $table->foreign('vocero_id')->references('vocero_id')->on('vocero');
            $table->foreign('mesa_id')->references('mesa_tecnica_id')->on('mesa_tecnica');
            $table->foreign('usuario_id')->references('usuario_id')->on('usuario'); // Asumiendo que la tabla de laravel se llama 'users' pero el id es 'usuario_id'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vocero_historial');
    }
};
