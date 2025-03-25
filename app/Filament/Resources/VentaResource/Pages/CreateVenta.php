<?php

namespace App\Filament\Resources\VentaResource\Pages;

use App\Filament\Resources\VentaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateVenta extends CreateRecord
{
    protected static string $resource = VentaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Si 'productos' no está definido, inicializamos un array vacío
        $productos = $data['productos'] ?? [];

        // Calculamos el total sumando los subtotales de los productos
        $data['total'] = collect($productos)->sum('subtotal');

        return $data;
    }
}
