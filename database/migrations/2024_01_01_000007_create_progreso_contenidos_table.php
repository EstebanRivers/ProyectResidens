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
            $table->foreignId('contenido_id')->constrained()->onDelete('cascade');
            $table->foreignId('estudiante_id')->constrained('users')->onDelete('cascade');
            $table->boolean('completado')->default(false);
            $table->integer('tiempo_dedicado')->default(0); // En segundos
            $table->decimal('porcentaje_visto', 5, 2)->default(0);
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_completado')->nullable();
            $table->timestamps();
            
            $table->unique(['contenido_id', 'estudiante_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progreso_contenidos');
    }
};