<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progreso_cursos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained()->onDelete('cascade');
            $table->foreignId('estudiante_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('contenido_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('actividad_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('tipo', ['contenido', 'actividad']);
            $table->boolean('completado')->default(false);
            $table->integer('tiempo_dedicado')->default(0); // en minutos
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_completado')->nullable();
            $table->timestamps();
            
            $table->unique(['curso_id', 'estudiante_id', 'contenido_id', 'actividad_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progreso_cursos');
    }
};