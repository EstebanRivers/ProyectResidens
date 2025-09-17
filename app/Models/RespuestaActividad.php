<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespuestaActividad extends Model
{
    use HasFactory;

    protected $table = 'respuesta_actividades';

    protected $fillable = [
        'actividad_id',
        'estudiante_id',
        'pregunta_id',
        'opcion_id',
        'es_correcta',
        'fecha_respuesta'
    ];

    protected $casts = [
        'es_correcta' => 'boolean',
        'fecha_respuesta' => 'datetime'
    ];

    public function actividad()
    {
        return $this->belongsTo(Actividad::class);
    }

    public function estudiante()
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class);
    }

    public function opcion()
    {
        return $this->belongsTo(OpcionPregunta::class, 'opcion_id');
    }
}