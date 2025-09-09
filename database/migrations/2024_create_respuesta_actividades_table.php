<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('respuesta_actividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actividad_id')->constrained('actividades')->onDelete('cascade');
            $table->foreignId('estudiante_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pregunta_id')->constrained('preguntas')->onDelete('cascade');
            $table->foreignId('opcion_id')->constrained('opcion_preguntas')->onDelete('cascade');
            $table->boolean('es_correcta')->default(false);
            $table->timestamp('fecha_respuesta')->nullable();
            $table->timestamps();

            // Índices para mejorar rendimiento
            $table->index(['actividad_id', 'estudiante_id']);
            $table->index(['pregunta_id', 'estudiante_id']);
            
            // Evitar respuestas duplicadas por pregunta
            $table->unique(['pregunta_id', 'estudiante_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('respuesta_actividades');
    }
};