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
        // 4. Comunidad
        Schema::create('comunidad', function (Blueprint $table) {
            $table->id('comunidad_id');
            $table->unsignedInteger('parroquia_id');
            $table->string('nombre', 255)->nullable();
            $table->integer('habitantes')->nullable();
            $table->integer('familias')->nullable();
            $table->integer('hombres')->nullable();
            $table->integer('mujeres')->nullable();
            $table->integer('ninos')->nullable();
            $table->boolean('usa_cisterna')->nullable();
            $table->boolean('agua_potable')->nullable();
            $table->boolean('zonas_silencio')->nullable();
            $table->boolean('tanques_grandes')->nullable();
            $table->string('sector', 255)->nullable();
            $table->foreign('parroquia_id')->references('parroquia_id')->on('parroquia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comunidad');
    }
};
