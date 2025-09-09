<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    use HasFactory;

    protected $fillable = [
        'pregunta',
        'tipo',
        'opciones',
        'respuesta_correcta',
        'puntos',
        'actividad_id'
    ];

    protected $casts = [
        'opciones' => 'array',
        'puntos' => 'integer',
    ];

    // Relación con Actividad
    public function actividad()
    {
        return $this->belongsTo(Actividad::class);
    }

    // Verificar si una respuesta es correcta
    public function esRespuestaCorrecta($respuesta)
    {
        return $this->respuesta_correcta === $respuesta;
    }
}