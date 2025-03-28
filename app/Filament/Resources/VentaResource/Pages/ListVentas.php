<?php

namespace App\Filament\Resources\VentaResource\Pages;

use App\Filament\Resources\VentaResource;
use App\Models\Venta;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListVentas extends ListRecords
{
    protected static string $resource = VentaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('reporte')
                ->label('Reporte PDF')
                ->icon('heroicon-o-document-chart-bar')
                ->action('generarPdf'),
        ];
    }

    public function generarPdf()
    {
        // Obtén todas las ventas. Puedes agregar filtros si lo deseas.
        $ventas = Venta::all();

        // Carga la vista Blade que usaremos para el PDF.
        $pdf = Pdf::loadView('pdf.ventas', compact('ventas'));

        // Retorna la descarga del PDF
        return response()->streamDownload(
            fn () => print($pdf->output()),
            'ventas.pdf'
        );
    }
}
