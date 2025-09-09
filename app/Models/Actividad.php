<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;

    protected $table = 'actividades';

    protected $fillable = [
        'titulo',
        'descripcion',
        'tipo',
        'contenido',
        'puntos_maximos',
        'tiempo_limite',
        'intentos_maximos',
        'curso_id'
    ];

    protected $casts = [
        'puntos_maximos' => 'integer',
        'tiempo_limite' => 'integer',
        'intentos_maximos' => 'integer',
    ];

    // Relación con Curso
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    // Relación con Preguntas
    public function preguntas()
    {
        return $this->hasMany(Pregunta::class);
    }

    // Relación con Respuestas de Actividad
    public function respuestas()
    {
        return $this->hasMany(RespuestaActividad::class);
    }

    // Obtener respuesta de un estudiante específico
    public function respuestaEstudiante($estudianteId)
    {
        return $this->respuestas()
            ->where('estudiante_id', $estudianteId)
            ->first();
    }

    // Verificar si un estudiante ya respondió
    public function yaRespondidaPor($estudianteId)
    {
        return $this->respuestas()
            ->where('estudiante_id', $estudianteId)
            ->exists();
    }

    // Calcular puntuación de un estudiante
    public function puntuacionEstudiante($estudianteId)
    {
        $respuesta = $this->respuestaEstudiante($estudianteId);
        return $respuesta ? $respuesta->puntuacion : 0;
    }
}