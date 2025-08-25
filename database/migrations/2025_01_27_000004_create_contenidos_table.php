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
            $table->enum('tipo', ['video', 'texto', 'pdf', 'imagen', 'audio']);
            $table->string('archivo_url')->nullable(); // URL del archivo
            $table->text('contenido_texto')->nullable(); // Para contenido de texto directo
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->json('metadata')->nullable(); // Para información adicional como duración, tamaño, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contenidos');
    }
};