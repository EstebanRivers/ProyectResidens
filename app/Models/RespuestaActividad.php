<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RespuestaActividad extends Model
{
    use HasFactory;

    protected $table = 'respuesta_actividades';

    protected $fillable = [
        'estudiante_id',
        'actividad_id',
        'respuesta',
        'puntos_obtenidos',
        'es_correcta',
        'completada',
        'fecha_completado'
    ];

    protected $casts = [
        'respuesta' => 'array',
        'es_correcta' => 'boolean',
        'completada' => 'boolean',
        'puntos_obtenidos' => 'integer',
        'fecha_completado' => 'datetime'
    ];

    // Relación con Estudiante
    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    // Relación con Actividad
    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividad::class);
    }

    // Scopes
    public function scopeCorrectas($query)
    {
        return $query->where('es_correcta', true);
    }

    public function scopeIncorrectas($query)
    {
        return $query->where('es_correcta', false);
    }

    public function scopeCompletadas($query)
    {
        return $query->where('completada', true);
    }

    // Accessor para porcentaje de puntos
    public function getPorcentajePuntosAttribute()
    {
        $puntosMaximos = $this->actividad->puntos;
        return $puntosMaximos > 0 ? round(($this->puntos_obtenidos / $puntosMaximos) * 100, 1) : 0;
    }
}