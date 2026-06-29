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
        // 8. Vocero
        Schema::create('vocero', function (Blueprint $table) {
            $table->id('vocero_id');
            $table->string('nombre', 255)->nullable();
            $table->string('apellido', 255)->nullable();
            $table->string('cedula', 20)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->string('genero', 50)->nullable();
            $table->string('estado', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vocero');
    }
};
