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
        // 3. Comuna
        Schema::create('comuna', function (Blueprint $table) {
            $table->id('comuna_id');
            $table->string('nombre', 255)->nullable();
            $table->unsignedInteger('parroquia_id');
            
            // Nuevo campo comunidad_id (Como entero, nullable y apuntando a comunidad con set null)
            $table->integer('comunidad_id')->nullable(); 

            // Llaves foráneas
            $table->foreign('parroquia_id')->references('parroquia_id')->on('parroquia')->onDelete('cascade');
            $table->foreign('comunidad_id')->references('comunidad_id')->on('comunidad')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comuna');
    }
};