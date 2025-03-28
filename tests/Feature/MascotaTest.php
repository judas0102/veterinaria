<?php

use App\Models\Mascota;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('puede crear una mascota', function () {
    $mascota = Mascota::create([
        'nombre de la mascota' => 'Firulais',
        'nombre del cliente'   => 'Carlos Pérez',
        'especie'              => 'Perro',
        'sexo'                 => 'macho',
    ]);

    expect($mascota)->toBeInstanceOf(Mascota::class)
        ->and($mascota->nombre)->toBe('Firulais');
});
