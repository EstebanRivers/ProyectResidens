<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contenido extends Model
{
    use HasFactory;

    protected $table = 'contenidos';

    protected $fillable = [
        'curso_id',
        'titulo',
        'descripcion',
        'tipo',
        'contenido',
        'archivo_url',
        'duracion',
        'activo',
        'orden'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'duracion' => 'integer',
        'orden' => 'integer'
    ];

    // Relación con curso
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    // Relación con progreso de contenidos
    public function progresos(): HasMany
    {
        return $this->hasMany(ProgresoContenido::class);
    }

    // Scope para contenidos activos
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeOrdenados($query)
    {
        return $query->orderBy('orden');
    }

    // Verificar si está completado por un usuario
    public function completadoPor($userId)
    {
        return $this->progresos()
            ->where('user_id', $userId)
            ->where('completado', true)
            ->exists();
    }

    // Obtener progreso del usuario
    public function progresoDelUsuario($userId)
    {
        return $this->progresos()
            ->where('user_id', $userId)
            ->first();
    }

    // Marcar como completado
    public function marcarCompletado($userId)
    {
        return $this->progresos()->updateOrCreate(
            ['user_id' => $userId],
            [
                'completado' => true,
                'fecha_completado' => now(),
                'tiempo_dedicado' => $this->duracion ?? 0
            ]
        );
    }
}