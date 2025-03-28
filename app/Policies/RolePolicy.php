<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    /**
     * Controla si el usuario puede ver el listado de roles (viewAny).
     * Filament usa esto para mostrar u ocultar el resource en el menú.
     */
    public function viewAny(User $user): bool
    {
        // Solo un usuario con rol 'administrador' puede ver Roles
        return $user->hasRole('administrador');
    }

    // Opcionalmente, podrías definir view, create, update, delete, etc.
    // public function view(User $user, Role $role): bool { ... }
    // ...
}

