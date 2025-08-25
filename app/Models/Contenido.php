<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'metadata' => 'array'
    ];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class);
    }

    public function progreso(): HasMany
    {
        return $this->hasMany(ProgresoCurso::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeOrdenados($query)
    {
        return $query->orderBy('orden');
    }

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
}