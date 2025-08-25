<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RespuestaActividad extends Model
{
    use HasFactory;

    protected $table = 'respuestas_actividades';

    protected $fillable = [
        'actividad_id',
        'estudiante_id',
        'respuesta',
        'es_correcta',
        'puntos_obtenidos',
        'retroalimentacion',
        'fecha_respuesta'
    ];

    protected $casts = [
        'respuesta' => 'array',
        'es_correcta' => 'boolean',
        'fecha_respuesta' => 'datetime'
    ];

    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividad::class);
    }

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }
}