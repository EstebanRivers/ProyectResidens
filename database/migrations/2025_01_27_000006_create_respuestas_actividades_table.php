<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respuestas_actividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actividad_id')->constrained()->onDelete('cascade');
            $table->foreignId('estudiante_id')->constrained('users')->onDelete('cascade');
            $table->json('respuesta'); // Respuesta del estudiante
            $table->boolean('es_correcta')->default(false);
            $table->integer('puntos_obtenidos')->default(0);
            $table->text('retroalimentacion')->nullable();
            $table->timestamp('fecha_respuesta')->useCurrent();
            $table->timestamps();
            
            $table->unique(['actividad_id', 'estudiante_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respuestas_actividades');
    }
};