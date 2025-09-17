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
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->enum('tipo', ['opcion_multiple', 'verdadero_falso', 'respuesta_corta', 'ensayo'])->default('opcion_multiple');
            $table->json('pregunta'); // Pregunta y opciones en JSON
            $table->json('respuesta_correcta'); // Respuestas correctas en JSON
            $table->integer('puntos')->default(10);
            $table->integer('tiempo_limite')->nullable(); // En minutos
            $table->integer('orden')->default(0);
            $table->foreignId('curso_id')->constrained()->onDelete('cascade');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};