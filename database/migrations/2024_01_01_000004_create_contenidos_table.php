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
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->enum('tipo', ['video', 'texto', 'pdf', 'imagen', 'audio'])->default('texto');
            $table->text('contenido')->nullable(); // Para texto
            $table->string('archivo')->nullable(); // Para archivos
            $table->string('url')->nullable(); // Para URLs externas
            $table->integer('duracion_minutos')->default(0);
            $table->integer('orden')->default(0);
            $table->foreignId('curso_id')->constrained()->onDelete('cascade');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contenidos');
    }
};