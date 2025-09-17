<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progreso_contenidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('contenido_id')->constrained('contenidos')->onDelete('cascade');
            $table->boolean('completado')->default(false);
            $table->integer('tiempo_dedicado')->default(0); // en minutos
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_completado')->nullable();
            $table->timestamps();

            // Evitar duplicados
            $table->unique(['user_id', 'contenido_id']);
            $table->index(['user_id', 'completado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progreso_contenidos');
    }
};