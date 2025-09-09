<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contenido extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'tipo',
        'contenido',
        'url_video',
        'archivo',
        'orden',
        'curso_id'
    ];

    protected $casts = [
        'orden' => 'integer',
    ];

    // Relación con Curso
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    // Relación con ProgresoCurso
    public function progresos()
    {
        return $this->hasMany(ProgresoCurso::class, 'contenido_id');
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