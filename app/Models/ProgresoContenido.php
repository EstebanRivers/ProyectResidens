<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgresoContenido extends Model
{
    use HasFactory;

    protected $table = 'progreso_contenidos';

    protected $fillable = [
        'user_id',
        'contenido_id',
        'completado',
        'tiempo_dedicado',
        'fecha_inicio',
        'fecha_completado'
    ];

    protected $casts = [
        'completado' => 'boolean',
        'tiempo_dedicado' => 'integer',
        'fecha_inicio' => 'datetime',
        'fecha_completado' => 'datetime'
    ];

    // Relación con usuario
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relación con contenido
    public function contenido(): BelongsTo
    {
        return $this->belongsTo(Contenido::class);
    }

    // Scopes
    public function scopeCompletados($query)
    {
        return $query->where('completado', true);
    }

    public function scopePendientes($query)
    {
        return $query->where('completado', false);
    }
}