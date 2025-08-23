<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Calificacion extends Model
{
    use HasFactory;

    protected $table = 'calificaciones';

    protected $fillable = [
        'estudiante_id',
        'curso_id',
        'tipo_evaluacion',
        'calificacion',
        'fecha_evaluacion',
        'observaciones'
    ];

    protected $casts = [
        'calificacion' => 'decimal:2',
        'fecha_evaluacion' => 'date'
    ];

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function getCalificacionLetraAttribute()
    {
        if ($this->calificacion >= 90) return 'A';
        if ($this->calificacion >= 80) return 'B';
        if ($this->calificacion >= 70) return 'C';
        if ($this->calificacion >= 60) return 'D';
        return 'F';
    }

    public function scopePorEstudiante($query, $estudianteId)
    {
        return $query->where('estudiante_id', $estudianteId);
    }

    public function scopePorCurso($query, $cursoId)
    {
        return $query->where('curso_id', $cursoId);
    }
}