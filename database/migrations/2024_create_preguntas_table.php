<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('preguntas', function (Blueprint $table) {
            $table->id();
            $table->text('pregunta');
            $table->enum('tipo', ['multiple_choice', 'true_false', 'short_answer'])->default('multiple_choice');
            $table->json('opciones')->nullable(); // Para opciones múltiples
            $table->string('respuesta_correcta');
            $table->integer('puntos')->default(1);
            $table->foreignId('actividad_id')->constrained('actividades')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('preguntas');
    }
};