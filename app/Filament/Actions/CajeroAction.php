<?php

namespace App\Filament\Actions;

use App\Models\Venta;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithModal;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;


class CajeroAction extends Action
{
    use InteractsWithModal;

    /**
     * Si quieres asignarle un nombre específico al action
     * (por defecto, será "cajero-action" si no pones nada).
     */
    // public static function getDefaultName(): ?string
    // {
    //     return 'cajero';
    // }

    /**
     * Configuración de la acción.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Cajero')
            ->modalHeading('Realizar Pago')
            ->modalWidth('md')
            ->form([
                TextInput::make('monto_pagado')
                    ->label('Monto Pagado')
                    ->numeric()
                    ->required(),
            ])
            ->action(function (Venta $record, array $data): void {
                // Obtenemos total de la venta y calculamos el cambio
                $total = $record->total;
                $montoPagado = $data['monto_pagado'];
                $cambio = $montoPagado - $total;

                // Marcamos la venta como pagada
                $record->update(['estado' => 'pagada']);

                // Mostramos notificación de Filament
                Notification::make()
                    ->title('Pago realizado')
                    ->body("Cambio: $".number_format($cambio, 2))
                    ->success()
                    ->send();
            });
    }
}
