<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;

    protected $table = 'actividades';

    protected $fillable = [
        'curso_id',
        'contenido_id',
        'titulo',
        'descripcion',
        'tipo',
        'pregunta',
        'opciones',
        'respuesta_correcta',
        'explicacion',
        'puntos',
        'orden',
        'activo'
    ];

    protected $casts = [
        'pregunta' => 'array',
        'opciones' => 'array',
        'respuesta_correcta' => 'array',
        'puntos' => 'integer',
        'orden' => 'integer',
        'activo' => 'boolean'
    ];

    // Relación con Curso
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    // Relación con Contenido
    public function contenido(): BelongsTo
    {
        return $this->belongsTo(Contenido::class);
    }

    // Relación con Respuestas de Actividad
    public function respuestas(): HasMany
    {
        return $this->hasMany(RespuestaActividad::class, 'actividad_id');
    }

    // Relación con progreso
    public function progreso(): HasMany
    {
        return $this->hasMany(ProgresoCurso::class, 'actividad_id');
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }

    public function scopeOrdenadas($query)
    {
        return $query->orderBy('orden');
    }

    // Accessor para icono según tipo
    public function getIconoTipoAttribute()
    {
        return match($this->tipo) {
            'opcion_multiple' => '☑️',
            'verdadero_falso' => '✅',
            'respuesta_corta' => '✏️',
            'ensayo' => '📝',
            default => '📝'
        };
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
        return $respuesta ? $respuesta->puntos_obtenidos : 0;
    }

    // Verificar si una respuesta es correcta
    public function verificarRespuesta($respuestaEstudiante)
    {
        $respuestasCorrectas = $this->respuesta_correcta;
        
        if ($this->tipo === 'opcion_multiple') {
            return in_array($respuestaEstudiante, $respuestasCorrectas);
        } elseif ($this->tipo === 'verdadero_falso') {
            return strtolower($respuestaEstudiante) === strtolower($respuestasCorrectas[0]);
        } elseif ($this->tipo === 'respuesta_corta') {
            return in_array(strtolower(trim($respuestaEstudiante)), 
                           array_map('strtolower', array_map('trim', $respuestasCorrectas)));
        }
        
        return false;
    }
}