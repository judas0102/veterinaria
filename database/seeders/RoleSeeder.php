<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Crear roles
        $admin = Role::create(['name' => 'administrador']);
        $usuario = Role::create(['name' => 'usuario']); // Veterinario

        // Crear permisos
        Permission::create(['name' => 'ver productos']);
        Permission::create(['name' => 'crear productos']);
        Permission::create(['name' => 'editar productos']);
        Permission::create(['name' => 'eliminar productos']);

        // Asignar permisos al Administrador (puede hacer todo)
        $admin->givePermissionTo(Permission::all());

        // Asignar permisos al Usuario (solo puede ver productos)
        $usuario->givePermissionTo(['ver productos']);
    }
}
