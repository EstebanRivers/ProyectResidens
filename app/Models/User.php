<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'matricula', 'telefono', 'direccion'

    ];

    // Relaciones
    public function cursosComoMaestro(): HasMany
    {
        return $this->hasMany(Curso::class, 'maestro_id');
    }

    public function cursosComoEstudiante(): BelongsToMany
    {
        return $this->belongsToMany(Curso::class, 'curso_estudiante', 'estudiante_id', 'curso_id')
                    ->withPivot('fecha_inscripcion', 'calificacion_final', 'estado')
                    ->withTimestamps();
    }

    public function calificaciones(): HasMany
    {
        return $this->hasMany(Calificacion::class, 'estudiante_id');
    }

    // Método para verificar si es administrador
    public function isAdmin()
    {
        return $this->role === 'administrador';
    }

    // Método para verificar si es maestro
    public function isMaestro()
    {
        return $this->role === 'maestro';
    }

    // Método para verificar si es alumno
    public function isAlumno()
    {
        return $this->role === 'alumno';
    }

    // Scopes
    public function scopeAdministradores($query)
    {
        return $query->where('role', 'administrador');
    }

    public function scopeMaestros($query)
    {
        return $query->where('role', 'maestro');
    }

    public function scopeAlumnos($query)
    {
        return $query->where('role', 'alumno');
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
