<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respuesta_actividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('actividad_id')->constrained()->onDelete('cascade');
            $table->json('respuesta'); // Respuesta del estudiante
            $table->integer('puntos_obtenidos')->default(0);
            $table->boolean('es_correcta')->default(false);
            $table->boolean('completada')->default(false);
            $table->timestamp('fecha_completado')->nullable();
            $table->timestamps();
            
            $table->unique(['estudiante_id', 'actividad_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respuesta_actividades');
    }
};