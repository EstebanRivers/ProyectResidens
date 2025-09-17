<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curso_estudiante', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained()->onDelete('cascade');
            $table->foreignId('estudiante_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('fecha_inscripcion')->useCurrent();
            $table->decimal('calificacion_final', 5, 2)->nullable();
            $table->enum('estado', ['inscrito', 'aprobado', 'reprobado', 'retirado'])->default('inscrito');
            $table->timestamps();
            
            $table->unique(['curso_id', 'estudiante_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curso_estudiante');
    }
};