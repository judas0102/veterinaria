<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Policies\RolePolicy;
use App\Policies\PermissionPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Indica qué políticas aplicar a cada modelo.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Role::class       => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
    ];

    /**
     * Registra cualquier servicio de la aplicación.
     */
    public function register(): void
    {
        //
    }

    /**
     * Inicia los servicios de autenticación y autorización.
     */
    public function boot(): void
    {
        // Registra las políticas definidas en $policies
        $this->registerPolicies();

        // Aquí puedes colocar más lógica de Gate o de autenticación si lo deseas
    }
}
