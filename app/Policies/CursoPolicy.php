<?php

namespace App\Policies;

use App\Models\Curso;
use App\Models\User;

class CursoPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Todos pueden ver la lista de cursos
    }

    public function view(User $user, Curso $curso): bool
    {
        // Administradores y maestros pueden ver cualquier curso
        if ($user->isAdmin() || $user->isMaestro()) {
            return true;
        }
        
        // Alumnos solo pueden ver cursos en los que están inscritos
        if ($user->isAlumno()) {
            return $user->cursosComoEstudiante()->where('curso_id', $curso->id)->exists();
        }
        
        return false;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isMaestro();
    }

    public function update(User $user, Curso $curso): bool
    {
        // Administradores pueden editar cualquier curso
        if ($user->isAdmin()) {
            return true;
        }
        
        // Maestros solo pueden editar sus propios cursos
        if ($user->isMaestro()) {
            return $curso->maestro_id === $user->id;
        }
        
        return false;
    }

    public function delete(User $user, Curso $curso): bool
    {
        // Solo administradores pueden eliminar cursos
        return $user->isAdmin();
    }
}