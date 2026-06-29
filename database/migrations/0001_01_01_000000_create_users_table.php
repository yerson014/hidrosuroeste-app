<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Esta tabla es la base para las relaciones de auditoría (usuario_id).
     */
    public function up(): void
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->id('usuario_id'); // SERIAL PRIMARY KEY compatible con tus FK
            $table->string('nombre', 100);
            $table->string('apellido', 255);
            $table->string('cedula', 50)->unique();
            $table->string('correo', 150)->unique(); // Corresponde a 'email' en Laravel
            $table->string('password'); // Corresponde a 'contraseña'
            $table->string('rol', 50)->nullable();
            $table->date('fecha_nacimiento');
            
            // Campos de auditoría nativos de Laravel
            $table->rememberToken();
            $table->timestamps(); // Crea 'created_at' y 'updated_at'
        });

        // Tabla necesaria para la funcionalidad de recuperación de contraseña de Laravel
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Tabla opcional para sesiones activas si usas el driver 'database'
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};