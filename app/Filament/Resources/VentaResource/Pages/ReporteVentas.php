<?php

namespace App\Filament\Resources\VentaResource\Pages;

use App\Filament\Resources\VentaResource;
use Filament\Resources\Pages\Page;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReporteVentas extends Page
{
    protected static string $resource = VentaResource::class;
    protected static string $view = 'filament.resources.venta-resource.pages.reporte-ventas';

    // Habilitar navegación en el menú
    protected static bool $shouldRegisterNavigation = true;
    protected static ?string $navigationLabel = 'Reporte PDF de Ventas';
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public $from;
    public $to;

    public function mount(): void
    {
        $this->from = Carbon::today()->toDateString();
        $this->to   = Carbon::now()->toDateString();
    }

    public function generarPdf()
    {
        $ventas = \App\Models\Venta::query()
            ->when($this->from, fn ($q) => $q->where('created_at', '>=', $this->from))
            ->when($this->to, fn ($q) => $q->where('created_at', '<=', $this->to . ' 23:59:59'))
            ->get();

        $pdf = Pdf::loadView('filament.resources.venta-resource.pages.reporte-ventas-pdf', [
            'ventas' => $ventas,
            'from'   => $this->from,
            'to'     => $this->to,
        ]);

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'reporte_ventas.pdf'
        );
    }
}
