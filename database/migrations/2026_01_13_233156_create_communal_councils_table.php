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
        // 5. Consejo Comunal
        Schema::create('consejo_comunal', function (Blueprint $table) {
            $table->id('consejo_comunal_id');
            $table->unsignedInteger('comunidad_id');
            $table->string('nombre', 255)->nullable();
            $table->string('lider_nombre', 255)->nullable();
            $table->string('lider_telefono', 20)->nullable();
            $table->foreign('comunidad_id')->references('comunidad_id')->on('comunidad');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consejo_comunal');
    }
};
