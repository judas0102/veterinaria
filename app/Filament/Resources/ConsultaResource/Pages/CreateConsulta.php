<?php

namespace App\Filament\Resources\ConsultaResource\Pages;

use App\Filament\Resources\ConsultaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateConsulta extends CreateRecord
{
    protected static string $resource = ConsultaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirige a la tabla
    }
}
