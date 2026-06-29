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
        // 6. Centro Asociado
        Schema::create('centro_asociado', function (Blueprint $table) {
            $table->id('centro_asociado_id');
            $table->unsignedInteger('comunidad_id');
            $table->string('nombre', 255)->nullable();
            $table->string('tipo', 100)->nullable();
            $table->string('ubicacion', 255)->nullable();
            $table->foreign('comunidad_id')->references('comunidad_id')->on('comunidad');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centro_asociado');
    }
};
