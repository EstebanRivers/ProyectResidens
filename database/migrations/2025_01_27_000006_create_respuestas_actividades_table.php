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
<<<<<<< HEAD:database/migrations/2025_01_27_000006_create_respuestas_actividades_table.php
            $table->foreignId('actividad_id')->constrained('actividades')->onDelete('cascade');
            $table->json('respuesta'); // La respuesta del estudiante en formato JSON
=======
            $table->foreignId('actividad_id')->constrained()->onDelete('cascade');
            $table->json('respuesta'); // Respuesta del estudiante
>>>>>>> f09a5bb7387f279c0b992fdce36deb124e1e9815:database/migrations/2024_01_01_000006_create_respuesta_actividades_table.php
            $table->integer('puntos_obtenidos')->default(0);
            $table->boolean('es_correcta')->default(false);
            $table->boolean('completada')->default(false);
            $table->timestamp('fecha_completado')->nullable();
            $table->timestamps();

            // Índices para mejorar rendimiento
            $table->index(['actividad_id', 'estudiante_id']);
            
<<<<<<< HEAD:database/migrations/2025_01_27_000006_create_respuestas_actividades_table.php
            // Evitar respuestas duplicadas por actividad
            $table->unique(['actividad_id', 'estudiante_id']);
=======
            $table->unique(['estudiante_id', 'actividad_id']);
>>>>>>> f09a5bb7387f279c0b992fdce36deb124e1e9815:database/migrations/2024_01_01_000006_create_respuesta_actividades_table.php
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respuesta_actividades');
    }
};