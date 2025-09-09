<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contenido extends Model
{
    use HasFactory;

    protected $fillable = [
        'curso_id',
        'titulo',
        'descripcion',
        'tipo',
        'archivo_url',
        'contenido_texto',
        'orden',
        'activo',
        'metadata'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'orden' => 'integer',
        'metadata' => 'array'
    ];

    // Relación con Curso
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    // Relación con ProgresoCurso
    public function progresos(): HasMany
    {
        return $this->hasMany(ProgresoCurso::class, 'contenido_id');
    }

    // Relación con actividades
    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class);
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeOrdenados($query)
    {
        return $query->orderBy('orden');
    }

    // Accessor para icono según tipo
    public function getIconoTipoAttribute()
    {
        return match($this->tipo) {
            'video' => '🎥',
            'texto' => '📄',
            'pdf' => '📋',
            'imagen' => '🖼️',
            'audio' => '🎵',
            default => '📄'
        };
    }
    // Verificar si un estudiante completó este contenido
    public function completadoPorEstudiante($estudianteId)
    {
        return $this->progresos()
            ->where('estudiante_id', $estudianteId)
            ->where('completado', true)
            ->exists();
    }

    // Obtener progreso de un estudiante específico
    public function progresoEstudiante($estudianteId)
    {
        return $this->progresos()
            ->where('estudiante_id', $estudianteId)
            ->first();
    }

    // Marcar como completado para un estudiante
    public function marcarCompletado($estudianteId)
    {
        return $this->progresos()->updateOrCreate(
            [
                'estudiante_id' => $estudianteId,
                'contenido_id' => $this->id,
                'curso_id' => $this->curso_id
            ],
            [
                'completado' => true,
                'fecha_completado' => now(),
                'tiempo_dedicado' => $this->progresos()
                    ->where('estudiante_id', $estudianteId)
                    ->value('tiempo_dedicado') ?? 0
            ]
        );
    }
}