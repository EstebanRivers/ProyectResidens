<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgresoCurso extends Model
{
    use HasFactory;

    protected $table = 'progreso_cursos';

    protected $fillable = [
        'curso_id',
        'estudiante_id',
        'contenido_id',
        'actividad_id',
        'tipo',
        'completado',
        'tiempo_dedicado',
        'fecha_inicio',
        'fecha_completado'
    ];

    protected $casts = [
        'completado' => 'boolean',
        'fecha_inicio' => 'datetime',
        'fecha_completado' => 'datetime'
    ];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    public function contenido(): BelongsTo
    {
        return $this->belongsTo(Contenido::class);
    }

    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividad::class);
    }
}