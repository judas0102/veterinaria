<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Permission;

class PermissionPolicy
{
    public function viewAny(User $user): bool
    {
        // Solo un usuario con rol 'administrador' puede ver Permisos
        return $user->hasRole('administrador');
    }
}
