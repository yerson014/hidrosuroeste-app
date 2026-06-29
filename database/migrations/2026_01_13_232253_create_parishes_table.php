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
        // 2. Parroquia
        Schema::create('parroquia', function (Blueprint $table) {
            $table->id('parroquia_id');
            $table->string('nombre', 255)->nullable();
            $table->unsignedInteger('municipio_id');
            // Referencia manual al ID personalizado
            $table->foreign('municipio_id')->references('municipio_id')->on('municipio')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parroquia');
    }
};
