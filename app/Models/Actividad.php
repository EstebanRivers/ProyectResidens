<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'activo' => 'boolean'
    ];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function contenido(): BelongsTo
    {
        return $this->belongsTo(Contenido::class);
    }

    public function respuestas(): HasMany
    {
        return $this->hasMany(RespuestaActividad::class);
    }

    public function progreso(): HasMany
    {
        return $this->hasMany(ProgresoCurso::class);
    }

    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }

    public function scopeOrdenadas($query)
    {
        return $query->orderBy('orden');
    }

    public function getIconoTipoAttribute()
    {
        return match($this->tipo) {
            'opcion_multiple' => '☑️',
            'verdadero_falso' => '✅',
            'respuesta_corta' => '✏️',
            'ensayo' => '📝',
            default => '❓'
        };
    }
}