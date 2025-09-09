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
        'titulo',
        'descripcion',
        'tipo',
        'pregunta',
        'opciones',
        'respuesta_correcta',
        'puntos',
        'activo',
        'orden'
    ];

    protected $casts = [
        'opciones' => 'array',
        'respuesta_correcta' => 'array',
        'activo' => 'boolean',
        'puntos' => 'integer',
        'orden' => 'integer'
    ];

    // Relación con curso
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    // Relación con respuestas
    public function respuestas(): HasMany
    {
        return $this->hasMany(RespuestaActividad::class);
    }

    // Scope para actividades activas
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    // Verificar si el usuario ya respondió
    public function yaRespondidaPor($userId)
    {
        return $this->respuestas()->where('user_id', $userId)->exists();
    }

    // Obtener respuesta del usuario
    public function respuestaDelUsuario($userId)
    {
        return $this->respuestas()->where('user_id', $userId)->first();
    }

    // Verificar respuesta correcta
    public function verificarRespuesta($respuestaUsuario)
    {
        if (!is_array($respuestaUsuario)) {
            $respuestaUsuario = [$respuestaUsuario];
        }

        $correctas = $this->respuesta_correcta ?? [];
        
        if (empty($correctas)) {
            return false;
        }

        // Para respuestas múltiples, verificar que coincidan exactamente
        if (count($respuestaUsuario) === count($correctas)) {
            return empty(array_diff($respuestaUsuario, $correctas));
        }

        return false;
    }

    // Calcular puntuación
    public function calcularPuntuacion($respuestaUsuario)
    {
        return $this->verificarRespuesta($respuestaUsuario) ? $this->puntos : 0;
    }
}