<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('codigo')->unique();
            $table->integer('creditos')->default(3);
            $table->foreignId('maestro_id')->constrained('users')->onDelete('cascade');
            $table->boolean('activo')->default(true);
            $table->integer('cupo_maximo')->default(30);
            $table->string('periodo_academico');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};