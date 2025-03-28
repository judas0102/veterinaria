<?php

use App\Models\Venta;
use function Pest\Laravel\get;

test('la ruta /ventas retorna 200', function () {
    $response = get('/ventas');
    $response->assertStatus(200);
});

test('se puede crear una venta', function () {
    $venta = Venta::factory()->create(['total' => 100]);
    expect($venta->id)->not->toBeNull();
});
