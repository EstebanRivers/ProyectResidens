<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained()->onDelete('cascade');
            $table->foreignId('contenido_id')->nullable()->constrained()->onDelete('set null');
            $table->string('titulo');
            $table->text('descripcion');
            $table->enum('tipo', ['opcion_multiple', 'verdadero_falso', 'respuesta_corta', 'ensayo']);
            $table->json('pregunta'); // Estructura de la pregunta
            $table->json('opciones')->nullable(); // Para preguntas de opción múltiple
            $table->json('respuesta_correcta'); // Respuesta(s) correcta(s)
            $table->text('explicacion')->nullable(); // Explicación de la respuesta
            $table->integer('puntos')->default(10);
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};