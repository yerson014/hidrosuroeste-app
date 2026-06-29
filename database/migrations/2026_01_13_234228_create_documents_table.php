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
        // 13. Documento
        Schema::create('documento', function (Blueprint $table) {
            $table->id('documento_id');
            $table->string('entidad', 50)->nullable();
            $table->string('url_archivo', 255)->nullable();
            $table->text('descripcion')->nullable();
            $table->unsignedInteger('usuario_id');
            $table->timestamp('fecha')->nullable();
            $table->foreign('usuario_id')->references('usuario_id')->on('usuario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documento');
    }
};
