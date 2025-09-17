<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained()->onDelete('cascade');
            $table->foreignId('estudiante_id')->constrained('users')->onDelete('cascade');
            $table->decimal('calificacion', 5, 2)->default(0);
            $table->integer('puntos_obtenidos')->default(0);
            $table->integer('puntos_totales')->default(0);
            $table->decimal('porcentaje', 5, 2)->default(0);
            $table->timestamp('fecha_calificacion')->useCurrent();
            $table->timestamps();
            
            $table->unique(['curso_id', 'estudiante_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calificaciones');
    }
};