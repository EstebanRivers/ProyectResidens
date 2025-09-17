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
            $table->foreignId('actividad_id')->constrained()->onDelete('cascade');
            $table->foreignId('estudiante_id')->constrained('users')->onDelete('cascade');
            $table->json('respuesta'); // Respuesta del estudiante en JSON
            $table->integer('puntos_obtenidos')->default(0);
            $table->integer('puntos_totales')->default(0);
            $table->boolean('es_correcta')->default(false);
            $table->timestamp('fecha_respuesta')->useCurrent();
            $table->integer('tiempo_empleado')->nullable(); // En segundos
            $table->timestamps();
            
            $table->unique(['actividad_id', 'estudiante_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respuesta_actividades');
    }
};