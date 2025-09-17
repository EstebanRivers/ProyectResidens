<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contenidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained()->onDelete('cascade');
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->enum('tipo', ['video', 'texto', 'pdf', 'imagen', 'audio'])->default('texto');
            $table->text('contenido_texto')->nullable(); // Para contenido de texto directo
            $table->string('archivo_url')->nullable(); // URL del archivo
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->integer('duracion')->nullable(); // duración en minutos
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contenidos');
    }
};