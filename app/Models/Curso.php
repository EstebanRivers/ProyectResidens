<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Curso extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'codigo',
        'creditos',
        'maestro_id',
        'activo',
        'cupo_maximo',
        'periodo_academico'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'creditos' => 'integer',
        'cupo_maximo' => 'integer'
    ];

    public function maestro(): BelongsTo
    {
        return $this->belongsTo(User::class, 'maestro_id');
    }

    public function estudiantes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'curso_estudiante', 'curso_id', 'estudiante_id')
                    ->withPivot('fecha_inscripcion', 'calificacion_final', 'estado')
                    ->withTimestamps();
    }

    public function contenidos(): HasMany
    {
        return $this->hasMany(Contenido::class, 'curso_id');
    }

    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class);
    }

    public function progreso(): HasMany
    {
        return $this->hasMany(ProgresoCurso::class);
    }

    public function calificaciones(): HasMany
    {
        return $this->hasMany(Calificacion::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function getEstudiantesInscritos()
    {
        return $this->estudiantes()->count();
    }

    public function tieneCupoDisponible()
    {
        return $this->getEstudiantesInscritos() < $this->cupo_maximo;
    }
}